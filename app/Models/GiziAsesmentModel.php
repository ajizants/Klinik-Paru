<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiziAsesmentModel extends Model
{
    use HasFactory;

    // Tentukan koneksi database jika perlu
    protected $connection = 'mysql';

    // Nama tabel yang digunakan oleh model ini
    protected $table = 't_asesment_awal_gizi';

    // Kolom primary key tabel ini
    protected $primaryKey = 'id';

    // Indikasikan bahwa primary key bukan auto-increment
    public $incrementing = true;

    // Tipe data dari primary key
    protected $keyType = 'int';
    protected $casts = [
        'keluhan' => 'array',
    ];

    // Kolom-kolom yang dapat diisi massal
    protected $fillable = [
        'notrans', 'tgltrans', 'norm', 'nama', 'tglLahir', 'alamat', 'jk', 'layanan',
        'dokter', 'ahli_gizi', 'frek_makan', 'frek_selingan', 'makanan_selingan',
        'alergi_makanan', 'pantangan_makanan', 'makanan_pokok', 'lauk_hewani',
        'lauk_nabati', 'sayuran', 'buah', 'minuman', 'bb_awal', 'bbi', 'tb_awal',
        'lla', 'imt_awal', 'status_gizi', 'keluhan', 'td', 'nadi', 'rr', 'suhu', 'hasil_lab',
        'riwayat_diet_penyakit', 'catatan', 'dxMedis_awal', 'dxGizi_awal', 'etiologi_awal', 'diit',
        'perinsip_diit', 'energi', 'protein', 'lemak', 'karbohidrat',
    ];

    // Jika Anda tidak ingin menggunakan timestamps
    // public $timestamps = false;

    public function dxGizi()
    {
        return $this->belongsTo(GiziDxSubKelasModel::class, 'dxGizi', 'kode');
    }

    public function dxMedis()
    {
        return $this->belongsTo(DiagnosaModel::class, 'dxMedis', 'kode_dx');
    }
    public function kunjungan()
    {
        return $this->hasMany(GiziKunjungan::class, 'norm', 'norm');
    }
}
