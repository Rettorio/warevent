<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\KatDokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class DokumenController extends Controller
{
    private static $port = [
        "sm" => 25,
        "md" => 50,
        "xl" => 100
    ];

    public function __construct()
    {
        $this->middleware("auth:api");
    }

    public function index(Request $request)
    {

        $viewPort = $request->exists("content") && array_key_exists($request->content, self::$port) ? self::$port[$request->content] : self::$port['md'];
        $dokumen = Dokumen::with('kategori')->cursorPaginate($viewPort);
        return response()->json(["message" => "Berhasil mengambil data dokumen.", "data" => $dokumen]);
    }

    private function noSeriGenerator($idKat, $num = null): string
    {
        $kode = $this->kodeSeriLookup($idKat);
        $rInt = $num ?? rand(10, 999);
        $rInt = str_pad($rInt, 4, '0', STR_PAD_LEFT);
        $rInt .= is_null($num) ? substr(time(), -4) : '';
        return sprintf("%s-%s", $kode, $rInt);
    }

    private function kodeSeriLookup($id)
    {
        $kategori = KatDokumen::select("kode_seri")->find($id);
        return $kategori->kode_seri;
    }

    public function create(Request $request)
    {
        $this->validate($request, Dokumen::$createRules);

        $dataWoFile = $request->except("pdf");
        $noSeri = $this->noSeriGenerator($dataWoFile['kategori_id']);

        if (!$request->file('pdf')->isValid()) {
            return response()->json(["message" => "gagal menambahkan dokumen."], 500);
        }
        $docs = $request->file('pdf');
        $docName = $docs->getClientOriginalName();
        if (Storage::disk("public")->exists("docs/" . $docName)) {
            $docName = uniqid() . "." . $docs->getClientOriginalExtension();
        }
        $path = $docs->storeAs("docs", $docName, "public");
        $dataWoFile['lokasi_file'] = env('APP_URL') . Storage::url($path);
        $dataWoFile['input_dari'] = $this->guard()->id();
        $dataWoFile['no_seri'] = $noSeri;
        $dokumen = Dokumen::create($dataWoFile);

        return response()->json(["message" => "berhasil menambah dokumen", "data" => $dokumen]);
    }

    public function update($id, Request $request)
    {
        $dokumen = Dokumen::find($id);
        if (is_null($dokumen)) {
            return response()->json(["message" => "tidak dapat menemukan dokumen."], 404);
        }
        $this->validate($request, Dokumen::$updateRules);
        $dataWoFile = $request->except(['pdf', 'no_seri']);
        if ($request->exists('pdf') && $file = $request->file('pdf')) {
            $fileName = $file->getClientOriginalName();
            if (Storage::disk('public')->exists('docs/' . $fileName)) {
                $fileName = uniqid() . "." . $file->getClientOriginalExtension();
            }
            $path = $file->storeAs("docs", $fileName, "public");
            $dataWoFile['lokasi_file'] = $path;
        }
        if ($request->exists('no_seri') || $request->exists('kategori_id')) {
            $noSeri = $this->noSeriGenerator($request->kategori_id ?? $dokumen->kategori_id, $request->no_seri);
            $dataWoFile['no_seri'] = $noSeri;
        }
        // dd($dataWoFile);
        $dokumen->update($dataWoFile);
        return response()->json(["message" => "berhasil mengupdate dokumen", "data" => $dokumen]);
    }

    public function filter(Request $request)
    {
        $this->validate($request, [
            "rilisDari" => "date",
            "rilisHingga" => "date",
            "content" => Rule::in(array_keys(self::$port)),
        ]);

        $docs = Dokumen::with('kategori');
        if ($request->exists('rilisDari') && $request->exists('rilisHingga')) {
            $docs = $docs->whereBetween("tanggalPembuatan", [$request->rilisDari, $request->rilisHingga]);
        } else if ($request->exists("rilisDari")) {
            $docs = $docs->where("tanggalPembuatan", ">=", $request->rilisDari);
        } else if ($request->exists("rilisHingga")) {
            $docs = $docs->where("tanggalPembuatan", "<=", $request->rilisHingga);
        }

        //jika ada filter dari tanggal rilis dokumen ubah order
        if ($request->exists('rilisDari') || $request->exists('rilisHingga')) {
            $docs = $docs->orderBy('tanggalPembuatan', 'asc');
        }

        $viewPort = $request->exists("content") && array_key_exists($request->content, self::$port) ? self::$port[$request->content] : self::$port['md'];
        $response = [
            "message" => "berhasil memfilter dokumen.",
            "filter" => $request->only(["rilisDari", "rilisHingga", "content"]),
            "data" => $docs->cursorPaginate($viewPort),
        ];
        return response()->json($response);
    }

    public function show($id)
    {
        $data = Dokumen::find($id);
        $message = "Berhasil mengambil dokumen.";
        $code = 200;
        if (is_null($data)) {
            $message = "Dokumen tidak ditemukan.";
            $code = 404;
        }

        return response()->json(compact('message', 'data'), $code);
    }

    public function delete()
    {
    }
}