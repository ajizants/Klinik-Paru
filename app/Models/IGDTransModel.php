<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class IGDTransModel extends Model
{
    use HasFactory;
    protected $connection = 'mysql';

    protected $table = 't_kunjungan_tindakan';
    protected $primaryKey = 'id';

    public function tindakan()
    {
        return $this->belongsTo(TindakanModel::class, 'kdTind', 'kdTindakan');
    }
    public function transbmhp()
    {
        return $this->hasMany(TransaksiBMHPModel::class, 'idTind');
    }

    public function petugas()
    {
        return $this->belongsTo(PegawaiModel::class, 'petugas', 'nip');
    }

    public function dokter()
    {
        return $this->belongsTo(PegawaiModel::class, 'dokter', 'nip');
    }
    public function kunjungan()
    {
        return $this->belongsTo(KunjunganWaktuSelesai::class, 'notrans', 'notrans');
    }

    protected $fillable = [
        'norm',
        'notrans',
        'jaminan',
        'kdtind',
        'petugas',
        'dokter',
        'created_at',
        'updated_at',
    ];

    public function cariPoinTotal($request)
    {
        $mulaiTgl = $request->input('mulaiTgl');
        $selesaiTgl = $request->input('selesaiTgl');

        $query = DB::table(DB::raw('(
            SELECT COUNT(t_kunjungan_tindakan.notrans) AS jml,
                   peg_m_biodata.nip,
                   peg_m_biodata.nama,
                   "Dokter" AS sts
            FROM t_kunjungan_tindakan
            INNER JOIN peg_m_biodata ON t_kunjungan_tindakan.dokter = peg_m_biodata.nip
            WHERE DATE_FORMAT(t_kunjungan_tindakan.created_at, "%Y-%m-%d") BETWEEN ? AND ?
            GROUP BY peg_m_biodata.nip, peg_m_biodata.nama

            UNION

            SELECT COUNT(t_kunjungan_tindakan.notrans) AS jml,
                   peg_m_biodata.nip,
                   peg_m_biodata.nama,
                   "Petugas" AS sts
            FROM t_kunjungan_tindakan
            INNER JOIN peg_m_biodata ON t_kunjungan_tindakan.petugas = peg_m_biodata.nip
            WHERE DATE_FORMAT(t_kunjungan_tindakan.created_at, "%Y-%m-%d") BETWEEN ? AND ?
            GROUP BY peg_m_biodata.nip, peg_m_biodata.nama
        ) as subquery'))
            ->setBindings([$mulaiTgl, $selesaiTgl, $mulaiTgl, $selesaiTgl])
            ->get();

        return response()->json($query, 200, [], JSON_PRETTY_PRINT);
    }
    public function cariPoin($mulaiTgl, $selesaiTgl)
    {
        $query = DB::table('t_kunjungan_tindakan')
            ->select(
                DB::raw('COUNT(t_kunjungan_tindakan.notrans) AS jml'),
                'peg_m_biodata.nip',
                'peg_m_biodata.nama',
                'm_tindakan.nmTindakan AS tindakan',
                DB::raw('"Dokter" AS sts')
            )
            ->join('peg_m_biodata', 't_kunjungan_tindakan.dokter', '=', 'peg_m_biodata.nip')
            ->join('m_tindakan', 't_kunjungan_tindakan.kdTind', '=', 'm_tindakan.kdTindakan')
            ->whereBetween(DB::raw('DATE_FORMAT(t_kunjungan_tindakan.created_at, "%Y-%m-%d")'), [$mulaiTgl, $selesaiTgl])
            ->groupBy('peg_m_biodata.nip', 'peg_m_biodata.nama', 'm_tindakan.kdTindakan', 'm_tindakan.nmTindakan')

            ->union(

                DB::table('t_kunjungan_tindakan')
                    ->select(
                        DB::raw('COUNT(t_kunjungan_tindakan.notrans) AS jml'),
                        'peg_m_biodata.nip',
                        'peg_m_biodata.nama',
                        'm_tindakan.nmTindakan AS tindakan',
                        DB::raw('"Petugas" AS sts')
                    )
                    ->join('peg_m_biodata', 't_kunjungan_tindakan.petugas', '=', 'peg_m_biodata.nip')
                    ->join('m_tindakan', 't_kunjungan_tindakan.kdTind', '=', 'm_tindakan.kdTindakan')
                    ->whereBetween(DB::raw('DATE_FORMAT(t_kunjungan_tindakan.created_at, "%Y-%m-%d")'), [$mulaiTgl, $selesaiTgl])
                    ->groupBy('peg_m_biodata.nip', 'peg_m_biodata.nama', 'm_tindakan.kdTindakan', 'm_tindakan.nmTindakan')

            )
            ->get();

        return $query;
    }
}
