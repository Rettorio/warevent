<?php

namespace App\Models;

use App\Enums\kategoriBagian;
use App\Enums\kategoriBarang;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Barang extends Model
{
    protected $table = "barang";
    protected $fillable = [
        'nama',
        'keterangan',
        'gambar',
        'kategori_id',
        'padaBagian',
        'padaPegawai',
        'inputDari',
    ];

    static public function rules()
    {
        return [
            "nama" => 'required|string',
            "keterangan" => 'required|string',
            "gambar" => 'required|image',
            'kategori_id' => ['required', Rule::enum(kategoriBarang::class)],
            'padaBagian' => Rule::enum(kategoriBagian::class),
            'padaPegawai' => 'exists:pegawai,id',
        ];
    }
}
