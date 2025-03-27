<?php
namespace App\Http\Controllers;

use App\Models\IGDTransModel;
use App\Models\KominfoModel;
use App\Models\PegawaiModel;
use Illuminate\Http\Request;

class EkinController extends Controller
{
    public function index()
    {
        $title = 'E-Kinerja';
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
                            <th>Status Pegawai</th>
                        </tr>
                    </thead>';
        $table .= '<tbody>';

        foreach ($pegawai as $index => $data) {
            $jabatan = isset($data->jabatan->nm_jabatan) ? $data->jabatan->nm_jabatan : '-';
            $atribut = '
                item-nip="' . $data->nip . '"
                item-nama="' . $data->nama . '"
                item-stat_pns="' . $data->stat_pns . '"
                item-jabatan="' . $jabatan . '"
            ';

            $table .= '<tr>
                        <td>
                            <a type="button" class="btn btn-warning" ' . $atribut . ' onclick="edit(' . $data->nip . ')">Edit</a>
                            <a type="button" class="btn btn-primary" onclick="lihat(' . $data->nip . ')">Lihat</a>
                            <a type="button" class="btn btn-success" onclick="cetak(' . $data->nip . ')">Cetak</a>
                        </td>
                        <td>' . ($index + 1) . '</td>
                        <td>' . $data->gelar_d . ' ' . $data->biodata->nama . ' ' . $data->gelar_b . '</td>
                        <td>' . $data->nip . '</td>
                        <td>' . $jabatan . '</td>
                        <td>' . $data->stat_pns . '</td>
                    </tr>';
        }

        $table .= '</tbody></table>';

        return $table;
    }

    private function poinKominfo(Request $request)
    {
        $params = $request->only(['tanggal_awal', 'tanggal_akhir']);
        $model = new KominfoModel();
        $data = $model->poinRequest($params);

        // Filter data yang bukan "Ruang Poli"
        $filteredData = collect($data['response']['data'])->reject(function ($item) {
            return $item['ruang_nama'] === 'Ruang Poli';
        });
        return $filteredData->values();

    }

    private function poinIGD(Request $request)
    {
        $tglAwal = $request->input('tanggal_awal');
        $tglAkhir = $request->input('tanggal_akhir');
        $model = new IGDTransModel();
        return $model->cariPoin($tglAwal, $tglAkhir);
    }

    public function show(Request $request)
    {
        $params = [
            'tanggal_awal' => $request->input('tanggal_awal'),
            'tanggal_akhir' => $request->input('tanggal_akhir'),
        ];

        $poinIgd = $this->poinIGD(new Request($params));
        $poinKominfo = $this->poinKominfo(new Request($params));

        return view('Laporan.Ekin.show', compact('poinIgd', 'poinKominfo'));
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
