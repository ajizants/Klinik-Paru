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
        Schema::create('t_asesment_awal_gizi', function (Blueprint $table) {
            $table->id();
            $table->string('notrans');
            $table->date('tgltrans');
            $table->string('norm');
            $table->string('nama');
            $table->date('tglLahir');
            $table->text('alamat');
            $table->string('layanan');
            $table->string('dokter');
            $table->string('ahli_gizi');
            $table->string('frek_makan');
            $table->string('frek_selingan')->nullable();
            $table->text('makanan_selingan')->nullable();
            $table->text('alergi_makanan')->nullable();
            $table->text('pantangan_makanan')->nullable();
            $table->text('makanan_pokok')->nullable();
            $table->text('lauk_hewani')->nullable();
            $table->text('lauk_nabati')->nullable();
            $table->text('sayuran')->nullable();
            $table->text('buah')->nullable();
            $table->text('minuman')->nullable();
            $table->decimal('bb_awal', 5, 2);
            $table->decimal('bbi', 5, 2)->nullable();
            $table->decimal('tb_awal', 5, 2);
            $table->decimal('lla', 5, 2)->nullable();
            $table->decimal('imt_awal', 5, 2);
            $table->string('status_gizi');
            $table->text('keluhan')->nullable(); // Jika ini adalah array, Anda bisa menyimpannya sebagai JSON
            $table->string('td')->nullable();
            $table->string('nadi')->nullable();
            $table->string('rr')->nullable();
            $table->string('suhu')->nullable();
            $table->text('hasil_lab')->nullable();
            $table->text('riwayat_diet_penyakit')->nullable();
            $table->text('catatan')->nullable();
            $table->text('dx_medis')->nullable();
            $table->text('dx_gizi');
            $table->text('etiologi');
            $table->text('diit');
            $table->text('perinsip_diit');
            $table->decimal('energi', 8, 2)->nullable();
            $table->decimal('protein', 8, 2)->nullable();
            $table->decimal('lemak', 8, 2)->nullable();
            $table->decimal('karbohidrat', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_asesment_awal_gizi');
    }
};
