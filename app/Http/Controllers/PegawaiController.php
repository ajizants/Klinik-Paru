<?php

namespace App\Http\Controllers;

use App\Models\JabatanModel;
use App\Models\PegawaiModel;
use App\Models\Vpegawai;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pegawai = PegawaiModel::with(['biodata', 'jabatan'])
            ->get();

        $data = [];
        foreach ($pegawai as $peg) {
            $data[] = array_map('strval', [
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

        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }

    public function dokter(Request $request)
    {
        $nip = $request->nip;
        $kdjab = [1, 7, 8];

        $dokter = PegawaiModel::with(['biodata', 'jabatan'])
            ->whereIn('kd_jab', $kdjab)->get();

        $data = [];
        foreach ($dokter as $peg) {

            $data[] = array_map('strval', [
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
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }

    public function perawat()
    {
        $kdjab = [10, 15];

        $perawat = PegawaiModel::on('mysql')->whereIn('kd_jab', $kdjab)->get();

        $data = [];
        foreach ($perawat as $peg) {
            $data[] = array_map('strval', [
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
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
    public function radiografer()
    {
        $kdjab = [12];

        $radiografer = PegawaiModel::on('mysql')->whereIn('kd_jab', $kdjab)->get();

        $data = [];
        foreach ($radiografer as $peg) {
            $data[] = array_map('strval', [
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
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
    public function apoteker()
    {
        $kdjab = [9];

        $apoteker = PegawaiModel::on('mysql')->whereIn('kd_jab', $kdjab)->get();

        $data = [];
        foreach ($apoteker as $peg) {
            $data[] = array_map('strval', [
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
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
    public function analis()
    {
        $kdjab = [11];

        $apoteker = PegawaiModel::on('mysql')->whereIn('kd_jab', $kdjab)->get();

        $data = [];
        foreach ($apoteker as $peg) {
            $data[] = array_map('strval', [
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
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function vpegawai()
    {
        $v = Vpegawai::all();
        return response()->json($v, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $nip)
    {
        $pegawai = PegawaiModel::with(['biodata', 'jabatan'])->where('nip', $nip)->first();
        $jabatan = JabatanModel::all();

        if (!$pegawai) {
            return response()->json(['message' => 'Pegawai tidak ditemukan'], 404);
        }

        return response($this->createFormPegawai($pegawai, $jabatan), 200, [], JSON_PRETTY_PRINT);
    }

    private function createFormPegawai($pegawai, $jabatan)
    {
        $kd_jab = $pegawai->kd_jab;
        $form = '<div class="container mt-4">';
        $form .= '<div class="card">';
        $form .= '<div class="card-header bg-primary text-white">Form Pegawai</div>';
        $form .= '<div class="card-body">';
        $form .= '<form id="pegawaiForm">
        <div class="row">';

        // NIP (readonly)
        $form .= '<div class="form-group col-auto">
                <label for="nip">NIP</label>
                <input type="text" class="form-control form-control-sm" id="nip" name="nip" value="' . htmlspecialchars($pegawai->nip, ENT_QUOTES, 'UTF-8') . '" readonly>
              </div>';

        // Nama
        $form .= '<div class="form-group col-auto">
                <label for="nama">Nama</label>
                <input type="text" class="form-control form-control-sm" id="nama" name="nama" value="' . htmlspecialchars($pegawai->biodata->nama, ENT_QUOTES, 'UTF-8') . '" >
              </div>';

        // Jenis Kelamin
        $form .= '<div class="form-group col-auto">
                <label for="jeniskel">Jenis Kelamin</label>
                <select class="form-control form-control-sm" id="jeniskel" name="jeniskel">
                    <option value="P"' . ($pegawai->biodata->jeniskel == 'P' ? ' selected' : '') . '>Perempuan</option>
                    <option value="L"' . ($pegawai->biodata->jeniskel == 'L' ? ' selected' : '') . '>Laki-laki</option>
                </select>
              </div>';

        // Tempat Lahir
        $form .= '<div class="form-group col-auto">
                <label for="tempat_lahir">Tempat Lahir</label>
                <input type="text" class="form-control form-control-sm" id="tempat_lahir" name="tempat_lahir" value="' . htmlspecialchars($pegawai->biodata->tempat_lahir, ENT_QUOTES, 'UTF-8') . '" >
              </div>';

        // Tanggal Lahir
        $form .= '<div class="form-group col-auto">
                <label for="tgl_lahir">Tanggal Lahir</label>
                <input type="date" class="form-control form-control-sm" id="tgl_lahir" name="tgl_lahir" value="' . $pegawai->biodata->tgl_lahir . '" >
              </div>';

        // Alamat
        $form .= '<div class="form-group col-auto">
                <label for="alamat">Alamat</label>
                <input type="text" class="form-control form-control-sm" id="alamat" name="alamat"  value="' . htmlspecialchars($pegawai->biodata->alamat, ENT_QUOTES, 'UTF-8') . '"/>
              </div>';

        // Status Kawin
        $form .= '<div class="form-group col-auto">
                <label for="status_kawin">Status Kawin</label>
                <select class="form-control form-control-sm" id="status_kawin" name="status_kawin">
                    <option value="KAWIN"' . ($pegawai->biodata->status_kawin == 'KAWIN' ? ' selected' : '') . '>KWIN</option>
                    <option value="BELUM KAWIN"' . ($pegawai->biodata->status_kawin == 'BELUM KAWIN' ? ' selected' : '') . '>BELUM KAWIN</option>
                    <option value="CERAI HIDUP"' . ($pegawai->biodata->status_kawin == 'CERAI HIDUP' ? ' selected' : '') . '>CERAI HIDUP</option>
                    <option value="CERAI MATI"' . ($pegawai->biodata->status_kawin == 'CERAI MATI' ? ' selected' : '') . '>CERAI MATI</option>

                </select>
              </div>';
        // Gelar Depan
        $form .= '<div class="form-group col-auto">
                <label for="gelar_d">Gelar Depan</label>
                <input type="text" class="form-control form-control-sm" id="gelar_d" name="gelar_d" value="' . htmlspecialchars($pegawai->gelar_d, ENT_QUOTES, 'UTF-8') . '" >
              </div>';

        // Gelar Belakang
        $form .= '<div class="form-group col-auto">
                <label for="gelar_b">Gelar Belakang</label>
                <input type="text" class="form-control form-control-sm" id="gelar_b" name="gelar_b" value="' . htmlspecialchars($pegawai->gelar_b, ENT_QUOTES, 'UTF-8') . '" >
              </div>';
        //Pangkat
        $form .= '<div class="form-group col-auto">
              <label for="pangkat">Pangkat</label>
              <input type="text" class="form-control form-control-sm" id="pangkat" name="pangkat" value="' . htmlspecialchars($pegawai->pangkat_gol, ENT_QUOTES, 'UTF-8') . '" >
            </div>';

        // Jabatan (readonly)
        $form .= '<div class="form-group col-auto">
        <label for="kd_jab">Jabatan</label>
        <select class="form-control form-control-sm select2" id="kd_jab" name="kd_jab">';

        foreach ($jabatan as $item) {
            $selected = ($item->kd_jab == $kd_jab) ? 'selected' : '';
            $form .= '<option value="' . htmlspecialchars($item->kd_jab, ENT_QUOTES, 'UTF-8') . '" ' . $selected . '>' . htmlspecialchars($item->nm_jabatan, ENT_QUOTES, 'UTF-8') . '</option>';
        }

        $form .= '</select>
      </div>';

        // Tombol Simpan
        $form .= '<div class="col-auto d-flex align-items-center mt-2">
        <a type="button" onclick="updatePegawai(' . $pegawai->nip . ')" class="btn btn-success">Update</a>
        </div>';

        $form .= '</div>'; // End row
        $form .= '</form>';
        $form .= '</div>'; // End card-body
        $form .= '</div>'; // End card
        $form .= '</div>'; // End container

        return $form;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $nip)
    {

        // Cari pegawai berdasarkan ID

        $pegawai = PegawaiModel::with('biodata')
            ->where('nip', $nip)
            ->first();

        if (!$pegawai) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pegawai tidak ditemukan',
            ], 404);
        }

        // Update data pegawai
        $pegawai->kd_jab = $request->kd_jab;
        $pegawai->gelar_d = $request->gelar_d;
        $pegawai->gelar_b = $request->gelar_b;
        $pegawai->pangkat_gol = $request->pangkat;
        $pegawai->stat_pns = $request->stat_pns;
        $pegawai->save();

        // Update data biodata pegawai
        $pegawai->biodata->update([
            'nama' => $request->nama,
            'jeniskel' => $request->jeniskel,
            'tempat_lahir' => $request->tempat_lahir,
            'tgl_lahir' => $request->tgl_lahir,
            'alamat' => $request->alamat,
            'status_kawin' => $request->status_kawin,
        ]);

        $listData = $this->dataPegawai();

        // Response sukses
        return response()->json([
            'status' => 'success',
            'message' => 'Data pegawai berhasil diperbarui',
            'data' => $listData,
        ], 200);
    }

    private function dataPegawai()
    {
        $title = 'E-Kinerja';
        $pegawai = PegawaiModel::with('biodata')
            ->whereNot('kd_jab', '22')
            ->get();

        $tablePegawai = $this->createTablePegawai($pegawai);

        return $tablePegawai;
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
                <a type="button" class="btn btn-primary"
                   onclick="lihat(\'' . $data->nip . '\', \'' . addslashes($data->biodata->nama) . '\')">
                   Lihat
                </a>
                <a type="button" class="btn btn-success"
                   onclick="cetak(\'' . $data->nip . '\', \'' . addslashes($data->biodata->nama) . '\')">
                   Cetak
                </a>
            </td>
            <td>' . ($index + 1) . '</td>
            <td>' . $data->gelar_d . ' ' . htmlspecialchars($data->biodata->nama, ENT_QUOTES, 'UTF-8') . ' ' . $data->gelar_b . '</td>
            <td>' . $data->nip . '</td>
            <td>' . $jabatan . '</td>
            <td>' . $data->stat_pns . '</td>
        </tr>';

        }

        $table .= '</tbody></table>';

        return $table;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
