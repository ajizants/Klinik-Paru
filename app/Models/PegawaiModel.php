<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PegawaiModel extends Model
{
    protected $table = 'peg_t_pegawai';
    protected $primaryKey = 'nip'; // Set primary key menjadi 'nip'
    public $incrementing = false; // Karena 'nip' bukan auto-increment
    protected $keyType = 'string'; // Jika 'nip' adalah string

    protected $fillable = [
        'nip', 'kd_jab', 'kd_pend', 'kd_jurusan', 'gelar_d', 'gelar_b', 'stat_pns', 'tgl_masuk', 'sip', 'pangkat_gol',
    ];
    public function biodata()
    {
        return $this->hasOne(BiodataModel::class, 'nip', 'nip');
    }
    public function jabatan()
    {
        return $this->hasOne(JabatanModel::class, 'kd_jab', 'kd_jab');
    }
    public function karyawan()
    {
        return $this->with('biodata', 'jabatan')->get();
    }

    public function dataPegawai()
    {
        $title = 'E-Kinerja';
        $pegawai = $this->with('biodata')
            ->whereNot('kd_jab', '22')
            ->get()
            ->sortBy('kd_jab');

        $tablePegawai = $this->createTablePegawai($pegawai);

        return $tablePegawai;
    }

    private function createTablePegawai($pegawai)
    {
        $table = '<table class="table table-bordered table-hover dataTable dtr-inline" cellspacing="0" id="pegawaiTable">';
        $table .= '<thead class="bg bg-info table-bordered border-dark">
                        <tr>
                            <th>Aksi</th>
                            <th>Nama</th>
                            <th>NIP</th>
                            <th>Jabatan</th>
                            <th>Pangkat/Gol</th>
                            <th>Status Pegawai</th>
                        </tr>
                    </thead>';
        $table .= '<tbody>';

        foreach ($pegawai as $index => $data) {
            $jabatan = isset($data->jabatan->nm_jabatan) ? $data->jabatan->nm_jabatan : '-';
            $atribut = '
                item-nip="' . $data->nip . '"
                item-nama="' . $data->biodata->nama . '"
                item-stat_pns="' . $data->stat_pns . '"
                item-jabatan="' . $jabatan . '"
            ';
            $table .= '<tr>
            <td>
                <a type="button" class="btn btn-warning" ' . $atribut . '
                   onclick="edit(\'' . $data->nip . '\', \'' . addslashes($data->biodata->nama) . '\')">
                   Update Data Pegawai
                </a>

                <a type="button" class="btn btn-success"
                   onclick="cetak(\'' . $data->nip . '\', \'' . addslashes($data->biodata->nama) . '\')">
                   Cetak Data Kinerja
                </a>
            </td>
            <td>' . $data->gelar_d . ' ' . htmlspecialchars($data->biodata->nama, ENT_QUOTES, 'UTF-8') . ' ' . $data->gelar_b . '</td>
            <td>' . $data->nip . '</td>
            <td>' . $jabatan . '</td>
            <td>' . $data->pangkat_gol . '</td>
            <td>' . $data->stat_pns . '</td>
        </tr>';

        }

        $table .= '</tbody></table>';

        return $table;
    }

    public function olahPegawai($kdjab)
    {
        $nip = [4, 5, 9999];
        if ($kdjab == []) {
            $data = PegawaiModel::with(['biodata', 'jabatan'])
                ->whereNotIn('nip', $nip)->get();
        } else {

            $data = PegawaiModel::with(['biodata', 'jabatan'])->whereIn('kd_jab', $kdjab)
                ->whereNotIn('nip', $nip)->get();
        }

        $pegawai = [];
        foreach ($data as $peg) {
            $pegawai[] = array_map('strval', [
                "nip" => $peg["nip"] ?? null,
                "status" => $peg["stat_pns"] ?? null,
                "gelar_d" => $peg["gelar_d"] ?? null,
                "gelar_b" => $peg["gelar_b"] ?? null,
                "kd_jab" => $peg["kd_jab"] ?? null,
                "kd_pend" => $peg["kd_pend"] ?? null,
                "kd_jurusan" => $peg["kd_jurusan"] ?? null,
                "tgl_masuk" => $peg["tgl_masuk"] ?? null,
                "nama" => $peg["biodata"]["nama"] ?? null,
                "jeniskel" => $peg["biodata"]["jeniskel"] ?? null,
                "tempat_lahir" => $peg["biodata"]["tempat_lahir"] ?? null,
                "tgl_lahir" => $peg["biodata"]["tgl_lahir"] ?? null,
                "alamat" => $peg["biodata"]["alamat"] ?? null,
                "kd_prov" => $peg["biodata"]["kd_prov"] ?? null,
                "kd_kab" => $peg["biodata"]["kd_kab"] ?? null,
                "kd_kec" => $peg["biodata"]["kd_kec"] ?? null,
                "kd_kel" => $peg["biodata"]["kd_kel"] ?? null,
                "kdAgama" => $peg["biodata"]["kdAgama"] ?? null,
                "status_kawin" => $peg["biodata"]["status_kawin"] ?? null,
                "nm_jabatan" => $peg["jabatan"]["nm_jabatan"] ?? null,
            ]);
        }
        return $pegawai;
    }

}
