<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dokumen extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "dokumen";


    protected $fillable = [
        "judul",
        "tanggalPembuatan",
        "no_seri",
        "input_dari",
        "kategori_id"
    ];

    protected $hidden = [
        "created_at",
        "updated_at",
        "deleted_at"
    ];

    public function kategori(): HasOne
    {
        return $this->hasOne(KatDokumen::class, foreignKey: "kategori_id", localKey: "id");
    }
}