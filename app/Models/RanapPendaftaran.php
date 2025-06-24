<?php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RanapPendaftaran extends Model
{
    use HasFactory;
    protected $table = 'ranap_pendaftaran';

    public function pasien()
    {
        return $this->belongsTo(PasienModel::class, 'norm', 'norm');
    }

    public function identitas()
    {
        return $this->belongsTo(PasienModel::class, 'norm', 'norm');
    }

    public function dokter()
    {
        return $this->belongsTo(Vpegawai::class, 'dpjp', 'nip');
    }

    public function kamar()
    {
        return $this->belongsTo(RanapRuangan::class, 'ruang', 'id');
    }

    public function petugas()
    {
        return $this->belongsTo(Vpegawai::class, 'admin', 'nip');
    }

    public function getPasienRanap($dpjp = null)
    {
        $query = RanapPendaftaran::whereNull('status_pulang')
            ->with('dokter', 'kamar', 'petugas');

        if ($dpjp !== null) {
            $query->where('dpjp', $dpjp);
        }

        $data = $query->get();
        // return $data;
        $model     = new KominfoModel();
        $allPasien = [];

        foreach ($data as $item) {
            $pasien = $model->pasienRequest($item->norm);
            if ($pasien) {
                $allPasien[$item->norm] = $pasien;
            } else {

            }
        }

        $data = $data->map(function ($item) use ($allPasien) {
            return [
                'id'               => $item->id,
                'norm'             => $item->norm,
                'jaminan'          => $item->jaminan,
                'notrans'          => $item->notrans,
                'pasien_no_rm'     => $item->norm,
                'pasien_nama'      => $allPasien[$item->norm]['pasien_nama'] ?? '-',
                'pasien_alamat'    => $allPasien[$item->norm]['pasien_alamat'] ?? '-',
                'pasien_tgl_lahir' => $allPasien[$item->norm]['pasien_tgl_lahir'] ?? '-',
                'pasien_jk'        => $allPasien[$item->norm]['jenis_kelamin_nama'] ?? '-',
                'tgl_masuk'        => $item->tgl_masuk,
                'ruang'            => $item->ruang,
                'dpjp'             => $item->dpjp,
                'dokter'           => $item->dokter->gelar_d . ' ' . $item->dokter->nama . ' ' . $item->dokter->gelar_b,
                'admin'            => $item->petugas->gelar_d . ' ' . $item->petugas->nama . ' ' . $item->petugas->gelar_b,
                'ruang'            => $item->kamar->nama_ruangan,
                'umur'             => Carbon::parse($allPasien[$item->norm]['pasien_tgl_lahir'])->age,
            ];
        });

        // dd($data);

        //buat table bootstrap
        $table = '<table class="table table-striped table-bordered table-hover pt-0 mt-0 fs-6 dataTable no-footer" id="tablePasienRanap">
            <thead>
                <tr>
                    <th>Aksi</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Tgl Masuk</th>
                    <th>Ruangan</th>
                    <th>Jaminan</th>
                    <th>Dokter</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($data as $item) {
            $table .= '<tr id="row-' . $item['id'] . '">
                <td>
                    <a class="mt-1 btn btn-warning" type="button"
                    data-notrans="' . $item['notrans'] . '"
                    data-norm="' . $item['norm'] . '"
                    data-jaminan="' . $item['jaminan'] . '"
                    data-nama="' . $item['pasien_nama'] . '"
                    data-alamat="' . $item['pasien_alamat'] . '"
                    data-ruang="' . $item['ruang'] . '"
                    data-jk="' . $item['pasien_jk'] . '"
                    data-umur="' . $item['umur'] . '"
                    data-dpjp="' . $item['dpjp'] . '"
                    data-tgllahir="' . Carbon::parse($item['pasien_tgl_lahir'])->format('d-m-Y') . '"
                    onclick="entryCppt(this,' . "'" . $item['notrans'] . "'" . ')" data-toggle="tooltip" data-placement="top" title="Entry CPPT Pasien"><i class="fas fa-edit"></i></a>
                </td>
                <td>' . $item['pasien_nama'] . ' <br> ( ' . $item['pasien_no_rm'] . ' )</td>
                <td>' . $item['pasien_alamat'] . '</td>
                <td>' . Carbon::parse($item['tgl_masuk'])->format('d-m-Y') . '</td>
                <td>' . $item['ruang'] . '</td>
                <td>' . $item['jaminan'] . '</td>
                <td>' . $item['dokter'] . '</td>
            </tr>';
        }

        $table .= '</tbody>
        </table>';

        return $table;
    }

    public function show($notrans)
    {
        $ranapPendaftaran = RanapPendaftaran::where('notrans', $notrans)->first();
        $norm             = $ranapPendaftaran->norm;

        // Ambil detail pasien dari KominfoModel
        $kominfo        = new KominfoModel();
        $pasien         = $kominfo->pasienRequest($norm);
        $pasien['umur'] = date_diff(date_create($pasien['pasien_tgl_lahir']), date_create('today'))->y;
        $ruanganPasien  = RanapRuangan::where('id', $ranapPendaftaran->ruang)->first()->toArray();
        // return $pasien;

        // Konversi model Eloquent ke array
        $ranapData = $ranapPendaftaran->toArray();

        // Merge data ranap dan pasien
        $dataGabungan = array_merge($ruanganPasien, $ranapData, $pasien ?? []);

        return $dataGabungan;
    }
}
