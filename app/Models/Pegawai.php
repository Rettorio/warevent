<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = "pegawai";

    public function barang()
    {
        return $this->hasMany(Barang::class);
    }
}