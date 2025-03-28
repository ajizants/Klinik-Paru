<?php
namespace App\Http\Controllers;

use App\Models\IGDTransModel;
use App\Models\KominfoModel;
use App\Models\LaboratoriumHasilModel;
use App\Models\PegawaiModel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EkinController extends Controller
{
    public function index()
    {
        $title   = 'E-Kinerja';
        $pegawai = PegawaiModel::with('biodata')
            ->whereNot('kd_jab', '22')
            ->get();

        $tablePegawai = $this->createTablePegawai($pegawai);

        return view('Laporan.Ekin.main', compact('pegawai', 'tablePegawai'))->with('title', $title);
    }

    private function createTablePegawai($pegawai)
    {
        $table = '<table class="table table-bordered table-hover dataTable dtr-inline" cellspacing="0" id="pegawaiTable">';
        $table .= '<thead class="bg bg-info table-bordered border-dark">
                        <tr>
                            <th>Aksi</th>
                            <th>No</th>
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
            <td>' . ($index + 1) . '</td>
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

    private function poinKominfo(Request $request)
    {
        $params = $request->only(['tanggal_awal', 'tanggal_akhir']);

        $nip         = $request->input('nip');
        $nama        = $request->input('nama'); // Bisa berupa sebagian dari nama
        $model       = new KominfoModel();
        $data        = $model->poinRequest($params);
        $poinKominfo = [];
        if (empty($data['response']['data'])) {
            return $poinKominfo;
        }

        // Filter data yang bukan "Ruang Poli" dan admin_nama mengandung $nama
        $filteredData = collect($data['response']['data'])->filter(function ($item) use ($nama) {
            return $item['ruang_nama'] !== 'Ruang Poli' && stripos($item['admin_nama'], $nama) !== false;
        });
        foreach ($filteredData as $item) {
            $key               = strtolower(str_replace([' ', '(', ')'], '', $item['ruang_nama'])); // Buat key unik
            $poinKominfo[$key] = $item['jumlah'];
        }
        return $poinKominfo;
    }

    private function poinIGD(Request $request)
    {
        $tglAwal  = $request->input('tanggal_awal');
        $tglAkhir = $request->input('tanggal_akhir');
        $nip      = $request->input('nip');
        // dd($nip);
        $nama  = $request->input('nama');
        $model = new IGDTransModel();
        $data  = json_decode(json_encode($model->cariPoin($tglAwal, $tglAkhir)), true);

        //filter data berdasarkan nip
        $filteredData = collect($data)->filter(function ($item) use ($nip) {
            return $item['nip'] === $nip;
        });

        $poinIgd = [];
        foreach ($filteredData as $item) {
            $key           = strtolower(str_replace([' ', '(', ')'], '', $item['tindakan'])); // Buat key unik
            $poinIgd[$key] = $item['jml'];
        }
        return $poinIgd;
    }
    private function poinInputHiv(Request $request)
    {
        $tglAwal  = $request->input('tanggal_awal');
        $tglAkhir = $request->input('tanggal_akhir');
        $data     = LaboratoriumHasilModel::where('created_at', '>=', $tglAwal)
            ->where('created_at', '<=', $tglAkhir)
            ->whereIn('idLayanan', [124, 125, 129])
            ->count();

        return $data;
    }

    public function show(Request $request)
    {
        $params = [
            'tanggal_awal'  => $request->input('tanggal_awal'),
            'tanggal_akhir' => $request->input('tanggal_akhir'),
            'nip'           => $request->input('nip'),
            'nama'          => $request->input('nama'),
        ];
        $tglAkhir = Carbon::parse($request->input('tanggal_akhir'))
            ->locale('id') // Atur lokal ke Indonesia
            ->translatedFormat('d F Y');

        $tgl = Carbon::parse($request->input('tanggal_akhir'));

        $poinIgd     = $this->poinIGD(new Request($params));
        $poinKominfo = $this->poinKominfo(new Request($params));
        if ($request->input('nip') == '199806222022031007') {
            $inputPitc = $this->poinInputHiv(new Request($params));
        } else {
            $inputPitc = 0;
        }

        $pegawai = PegawaiModel::with('biodata', 'jabatan')->where('nip', $request->input('nip'))->first();
        $biodata = [
            'nip'     => $pegawai->nip,
            'nama'    => $pegawai->gelar_d . ' ' . $pegawai->biodata->nama . ', ' . $pegawai->gelar_b,
            'jabatan' => $pegawai->jabatan->nm_jabatan ?? "-",
            'pangkat' => $pegawai->jabatan->pangkat_gol ?? "-",
        ];

        return view('Laporan.Ekin.show', compact('poinIgd', 'poinKominfo', 'inputPitc', 'biodata', 'tglAkhir', 'tgl'))->with('title', 'E-Kinerja');
        return [
            'inputPitc'   => $inputPitc,
            'poinIgd'     => $poinIgd,
            'poinKominfo' => $poinKominfo,
            'biodata'     => $biodata,
            'pegawai'     => $pegawai,
        ];

    }

    public function store(Request $request)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
