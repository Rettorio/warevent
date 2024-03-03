<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("kategori_barang", function (Blueprint $table) {
            $table->id();
            $table->string("kategori");
        });

        Schema::create("barang", function (Blueprint $table) {
            $table->id();
            $table->string("nama", 255);
            $table->text("keterangan");
            $table->string("gambar", 255);
            $table->foreignId("kategori_id")->constrained(table: "kategori_barang")->cascadeOnUpdate();
            $table->foreignId("padaBagian")->nullable()->constrained(table: "bagian")->cascadeOnUpdate();
            $table->foreignId("inputDari")->constrained(table: "User")->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};