<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
        $this->middleware("auth:api");
    }

    public function test()
    {
        $barang = Barang::with(["kategori" => function ($query) {
            $query->select("id", "kategori as nama");
        }, "milikPegawai"])->first();
        // $barang->lokasi_bagian = $barang->padaBagian;
        // dd($barang);
        return response()->json(["message" => "Barang", "data" => $barang]);
    }

    public function index()
    {
        $list = Barang::withBaseRelation()->get();
        $message = "List barang berhasil diambil.";
        return response()->json(compact('message', 'list'));
    }

    public function create(Request $request)
    {
        $this->validate($request, Barang::createdRules());

        $nonObjData = $request->except(["gambar", "padaBagian", "padaPegawai"]);
        $barang = new Barang;
        $barang->nama = $nonObjData['nama'];
        $barang->keterangan = $nonObjData['keterangan'];
        $barang->kategori_id = $nonObjData['kategori_id'];
        $barang->inputDari = $this->guard()->id();

        if ($request->exists("padaBagian")) {
            $barang->padaBagian = $request->input("padaBagian");
        }
        if ($request->exists("padaPegawai")) {
            $barang->padaPegawai = $request->input("padaPegawai");
        }

        $gambar = $request->file("gambar");
        if ($gambar->isValid()) {
            $fileName = $gambar->getClientOriginalName();
            //if filename exist before generate strong randomly name for the file
            if (Storage::disk("public")->exists("barang-assets/" . $fileName)) {
                $fileName = uniqid() . $gambar->getClientOriginalExtension();
            }
            $path = $gambar->storeAs('barang-assets', $fileName, "public");
            $barang->gambar = env('APP_URL') . Storage::url($path);
        }
        $barang->save();

        return response()->json(["message" => "Berhasil menambahkan barang.", "data" => $barang], 201);
    }

    public function filter(string $kategori)
    {
    }

    public function show($id)
    {
        $barang = Barang::withBaseRelation()->find($id);
        if (is_null($barang)) {
            return response()->json(["message" => "Tidak dapat menemukan  barang"], 404);
        }
        $message = "Berhasil mengambil data barang.";
        return response()->json(compact('message', 'barang'));
    }

    public function update($id, Request $request)
    {
        $this->validate($request, Barang::updateRules());
        $barang = Barang::withBaseRelation()->find($id);
        $data = $request->except("gambar");
        if ($request->exists("gambar") && $gambar = $request->file('gambar')) {
            $fileName = $gambar->getClientOriginalName();
            //if filename exist generate strong randomly name for the file
            if (Storage::disk("public")->exists("barang-assets/" . $fileName)) {
                $fileName = uniqid() . $gambar->getClientOriginalExtension();
            }
            // Storage::disk("public")->delete($barang->gambar)
            $data['gambar'] = $gambar->storeAs('barang-assets', $fileName, "public");
        }
        $barang->update($data);
        return response()->json(["message" => "berhasil update barang.", "barang" => $barang]);
    }

    public function destroy(Barang $barang)
    {
    }
    //
}