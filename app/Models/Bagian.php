<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bagian extends Model
{
    protected $table = "bagian";

    public function barang(): HasMany
    {
        return $this->hasMany(Barang::class);
    }
}
