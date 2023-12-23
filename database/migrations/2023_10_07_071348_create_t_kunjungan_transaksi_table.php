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
        Schema::create('t_kunjungan_tindakan', function (Blueprint $table) {
            $table->id();
            $table->string('norm');
            $table->string('notrans');
            $table->string('kdTind');
            $table->string('petugas');
            $table->string('dokter');
            $table->string('status');
            $table->timestamps(); // Ini akan menambahkan created_at dan updated_at secara otomatis
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_kunjungan_tindakan');
    }
};
