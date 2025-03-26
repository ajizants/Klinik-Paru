<?php
namespace App\Http\Controllers;

use App\Models\PegawaiModel;
use Illuminate\Http\Request;

class EkinController extends Controller
{

    public function index()
    {
        $title   = 'E-Kinerja';
        $pegawai = PegawaiModel::with('biodata')->get();
        // return $pegawai;
        $tablePegawai = $this->createTablePegawai($pegawai);
        // return $tablePegawai;
        return view('Laporan.Ekin.main', compact('pegawai', 'tablePegawai'))->with('title', $title);
    }
    private function createTablePegawai($pegawai)
    {
        $table = ' <table class="table table-bordered table-hover dataTable dtr-inline"  cellspacing="0" id="pegawaiTable">';
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
            $atribut = '
                        item-nip="' . $data->nip . '"
                        item-nama="' . $data->nama . '"
                        item-stat_pns="' . $data->stat_pns . '"
                        item-jabatan="' . $data->jabatan->nm_jabatan . '"
                        ';
            $table .= '<tr>
                            <td><a type="button" class="btn btn-warning"' . $atribut . ' onclick="edit(' . $data->nip . ')">Edit</a>
                                <a type="button" class="btn btn-primary" onclick="lihat(' . $data->nip . ')">Lihat</a>
                                <a type="button" class="btn btn-success" onclick="cetak(' . $data->nip . ')">Cetak</a>
                            </td>
                            <td>' . ($index + 1) . '</td>
                            <td>' . $data->gelar_d . ' ' . $data->biodata->nama . ' ' . $data->gelar_b . '</td>
                            <td>' . $data->nip . '</td>
                            <td>' . $data->jabatan->nm_jabatan . '</td>
                            <td>' . $data->stat_pns . '</td>
                        </tr>';
        }

        $table .= '</tbody></table>';

        return $table;
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
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
