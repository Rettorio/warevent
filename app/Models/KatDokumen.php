<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KatDokumen extends Model
{
    protected $table = "kategori_dokumen";

    protected $fillable = [
        "kategori"
    ];

    protected $timestamps = false;

    public function dokumen(): HasMany
    {
        return $this->hasMany(Dokumen::class);
    }
}