<?php

namespace App\Http\Controllers;

use App\Models\Barang;
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

    public function index()
    {
        $list = Barang::all();
        $message = "List barang berhasil diambil.";
        return response()->json(compact('message', 'list'));
    }

    public function create(Request $request)
    {
        $this->validate($request, Barang::rules());

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
            $path = $gambar->storeAs('barang-assets', $fileName, "public");
            $barang->gambar = env('APP_URL') . Storage::url($path);
        }
        $barang->save();

        return response()->json(["message" => "Berhasil menambahkan barang.", "data" => $barang], 201);
    }

    public function filter(string $kategori)
    {
    }

    public function show(Barang $barang)
    {
    }

    public function update(Request $request, Barang $barang)
    {
    }

    public function destroy(Barang $barang)
    {
    }
    //
}
