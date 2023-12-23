<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AntrianModel extends Model
{
    use HasFactory;
    protected $connection = 'mysql';

    protected $table = 't_kunjungan';

    public function getDataByDate($date)
    {
        $data = DB::connection('mysql')
            ->table('t_kunjungan')
            ->join('t_poli', 't_kunjungan.notrans', '=', 't_poli.notrans')
            ->join('v_biodata', 't_kunjungan.norm', '=', 'v_biodata.norm')
            ->join('m_kelompok', 't_kunjungan.kkelompok', '=', 'm_kelompok.kkelompok')
            ->join('t_petugas', 't_kunjungan.notrans', '=', 't_petugas.notrans')
            ->join('v_pegawai', 't_petugas.p_dokter_poli', '=', 'v_pegawai.nip')
            ->select(
                't_kunjungan.notrans AS notrans',
                't_kunjungan.nourut AS nourut',
                't_kunjungan.norm AS norm',
                'v_biodata.nama AS nama',
                'm_kelompok.kelompok AS layanan',
                't_poli.tgltrans AS tgltrans',
                'v_biodata.kelurahan AS kelurahan',
                'v_biodata.rtrw AS rtrw',
                'v_biodata.kecamatan AS kecamatan',
                'v_biodata.kabupaten AS kabupaten',
                'v_biodata.provinsi AS provinsi',
                't_poli.ekg',
                't_poli.mikroCo',
                't_poli.spirometri',
                't_poli.spo2',
                't_poli.nebulizer',
                't_poli.infus',
                't_poli.oksigenasi',
                't_poli.injeksi',
                't_poli.terapi AS lain-lain',
                'v_pegawai.nama AS dokter'
            )
            ->whereDate('t_poli.tgltrans', $date)
            ->where(function ($query) {
                $query->where('t_poli.oksigenasi', '<>', '')
                    ->where('t_poli.oksigenasi', 'NOT LIKE', '%-%')
                    ->orWhere('t_poli.nebulizer', '<>', '')
                    ->where('t_poli.nebulizer', 'NOT LIKE', '%-%');
            })
            ->get();

        return $data;
    }
}
