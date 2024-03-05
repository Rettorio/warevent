<?php

namespace App\Models;

use App\Enums\KategoriBagian;
use App\Enums\KategoriBarang;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\AsStringable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
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

    protected $hidden = [
        "padaBagian",
        "padaPegawai",
        "inputDari",
        "kategori_id",
    ];

    protected $appends = [
        'lokasi_bagian',
    ];

    static public function createdRules()
    {
        return [
            "nama" => 'required|string',
            "keterangan" => 'required|string',
            "gambar" => 'required|image',
            'kategori_id' => ['required', Rule::enum(KategoriBarang::class)],
            'padaBagian' => Rule::enum(KategoriBagian::class),
            'padaPegawai' => 'exists:pegawai,id',
        ];
    }

    static public function updateRules()
    {
        return [
            "nama" => 'string',
            "keterangan" => 'string',
            "gambar" => 'image',
            'kategori_id' => Rule::enum(KategoriBarang::class),
            'padaBagian' => Rule::enum(KategoriBagian::class),
            'padaPegawai' => 'exists:pegawai,id',
        ];
    }

    static public function withBaseRelation(): Builder
    {
        return Barang::with(["kategori" => function ($query) {
            $query->select("id", "kategori as nama");
        }, "milikPegawai" => function ($query) {
            $query->select("id", "pegawai.nama");
        }]);
    }

    public function kategori(): HasOne
    {
        return $this->hasOne(KatBarang::class, foreignKey: "id", localKey: "kategori_id");
    }

    public function milikPegawai(): HasOne
    {
        return $this->hasOne(Pegawai::class, foreignKey: "id", localKey: "padaPegawai");
    }

    public function getLokasiBagianAttribute()
    {
        $cases = array_column(KategoriBagian::cases(), 'name');
        return is_null($this->padaBagian) ? null : $cases[$this->padaBagian - 1];
    }
}