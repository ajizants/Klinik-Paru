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
        Schema::create('t_kunjungan_gizi', function (Blueprint $table) {
            $table->id();
            $table->string('norm');
            $table->string('notrans');
            $table->string('dokter');
            $table->string('ahli_gizi');
            $table->decimal('bb', 5, 2);
            $table->decimal('tb', 5, 2);
            $table->decimal('imt', 5, 2);
            $table->text('keluhan');
            $table->text('parameter');
            $table->text('dx_medis');
            $table->text('dx_gizi');
            $table->text('etiologi');
            $table->text('evaluasi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_kunjungan_gizi');
    }
};
