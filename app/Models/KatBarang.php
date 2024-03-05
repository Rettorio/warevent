<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KatBarang extends Model
{
    protected $table = "kategori_barang";
    protected $fillable = [
        'kategori',
    ];


    public function barang(): HasMany
    {
        return $this->hasMany(Barang::class);
    }
}
