<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('titik_drainase_id')
                ->constrained('titikdrainase')
                ->restrictOnDelete();

            $table->text('deskripsi');
            $table->string('foto')->nullable();
            $table->integer('tinggi_air_cm');
            $table->string('status_kondisi');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};