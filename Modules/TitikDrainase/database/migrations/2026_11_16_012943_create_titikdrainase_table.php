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
        Schema::create('titikdrainase', function (Blueprint $table) {
            $table->id();
            $table->string('nama_titik',100);
            $table->string('kecamatan', 200);
            $table->string('deskripsi',80);
            $table->integer('kedalaman_cm', 30);
            $table->timestamps('create_at');
            $table->timestamps('update_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('titikdrainase');
    }
};
