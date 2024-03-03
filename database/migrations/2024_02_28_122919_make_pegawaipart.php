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
        Schema::create("bagian", function (Blueprint $table) {
            $table->id();
            $table->string("namaBagian");
        });

        Schema::create("jabatan", function (Blueprint $table) {
            $table->id();
            $table->string("namaJabatan");
        });

        Schema::create("pegawai", function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained(table: 'User')->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId("jabatan_id")->nullable()
                ->constrained(table: 'jabatan')->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId("bagian_id")->constrained(table: 'bagian')->cascadeOnUpdate()
                ->cascadeOnDelete();
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