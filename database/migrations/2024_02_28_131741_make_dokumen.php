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
        Schema::create("kategori_dokumen", function (Blueprint $table) {
            $table->id();
            $table->string("kategori");
        });

        Schema::create("dokumen", function (Blueprint $table) {
            $table->id();
            $table->text("judul");
            $table->string("lokasi")->nullable(false);
            $table->date("tanggalPembuatan");
            $table->string("no_seri", 255);
            $table->foreignId("input_dari")->constrained(table: "User")->cascadeOnUpdate();
            $table->foreignId("kategori_id")->constrained(table: "kategori_dokumen")->cascadeOnUpdate();
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