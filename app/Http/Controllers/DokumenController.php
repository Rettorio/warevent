<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\KatDokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DokumenController extends Controller
{
    public function index(Request $request)
    {
        $port = [
            "sm" => 25,
            "md" => 50,
            "xl" => 100
        ];
        $viewPort = $request->exists("content") && array_key_exists($request->content, $port) ? $port[$request->content] : $port['md'];
        $dokumen = Dokumen::with('kategori')->cursorPaginate($viewPort);
        return response()->json(["message" => "Berhasil mengambil data dokumen.", "data" => $dokumen]);
    }

    private function noSeriGenerator($kode): string
    {
        $rInt = rand(10, 999);
        $rInt = str_pad($rInt, 4, '0', STR_PAD_LEFT);
        return sprintf("%s-%d%s", $kode, $rInt, substr(time(), -4));
    }

    public function create(Request $request)
    {
        $this->validate($request, Dokumen::$createRules);

        $dataWoFile = $request->except("pdf");
        $kategori = KatDokumen::select("kode_seri")->find($dataWoFile['kategori_id']);
        $noSeri = $this->noSeriGenerator($kategori->kode_seri);

        if (!$request->file('pdf')->isValid()) {
            return response()->json(["message" => "gagal menambahkan dokumen."], 500);
        }
        $docs = $request->file('pdf');
        $docName = $docs->getClientOriginalName();
        if (Storage::disk("public")->exists("docs/" . $docName)) {
            $docName = uniqid() . $docs->getClientOriginalExtension();
        }
        $path = $docs->storeAs("docs", $docName, "public");
        $dataWoFile['lokasi_file'] = env('APP_URL') . Storage::url($path);
        $dataWoFile['input_dari'] = $this->guard()->id();
        $dataWoFile['no_seri'] = $noSeri;
        $dokumen = Dokumen::create($dataWoFile);

        return response()->json(["message" => "berhasil menambah dokumen", "data" => $dokumen]);
    }

    public function update()
    {
    }

    public function filter()
    {
    }

    public function show()
    {
    }

    public function delete()
    {
    }
}