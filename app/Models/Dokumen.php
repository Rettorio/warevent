<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rules\File;

class Dokumen extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "dokumen";


    protected $fillable = [
        "judul",
        "tanggalPembuatan",
        "no_seri",
        "lokasi_file",
        "input_dari",
        "kategori_id"
    ];

    public static $createRules = [
        "judul" => ["required", "string"],
        "tanggalPembuatan" => ["required", "date"],
        "no_seri" => ["alpha_num:ascii"],
        "pdf" => ["required", 'mimes:pdf', 'max:3072'],
        "kategori_id" => ["required", "exists:kategori_dokumen,id"]
    ];

    public static $updateRules = [
        "judul" => ["string"],
        "tanggalPembuatan" => ["date"],
        "no_seri" => ["alpha_num:ascii"],
        "pdf" => ['mimes:pdf', 'max:3072'],
        "kategori_id" => ["exists:kategori_dokumen,id"]
    ];

    protected $hidden = [
        "created_at",
        "updated_at",
        "deleted_at"
    ];

    public function kategori(): HasOne
    {
        return $this->hasOne(KatDokumen::class, foreignKey: "id", localKey: "kategori_id");
    }
}