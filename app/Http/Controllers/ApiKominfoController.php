<?php
namespace App\Http\Controllers;

use App\Models\ApiKominfo;
use App\Models\KominfoModel;
use Illuminate\Http\Request;

class ApiKominfoController extends Controller
{

    public function data_rencana_kontrol(Request $request)
    {
        $model = new ApiKominfo();
        $data  = $model->data_pasien_kontrol($request->all());

        // Cek jika data kosong atau tidak valid
        if (! $data || count($data) == 0) {
            return response()->json([
                'html' => '<p class="text-center text-danger">Tidak ada data tersedia</p>',
                'data' => [],
            ]);
        }

        $html = '<table class="table table-bordered table-hover dataTable dtr-inline" id="rencanaKontrolTable">
            <thead class="bg bg-info">
                <tr>
                    <th>No</th>
                    <th>Kontrol Selanjutnya</th>
                    <th>Jaminan</th>
                    <th>No RM</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>No HP Pasien</th>
                    <th>Penanggung Jawab</th>
                    <th>No Hp Penanggung Jawab</th>
                    <th>Dokter</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($data as $index => $d) {
            $alamatFull = ($d['kelurahan_nama'] ?? '') . ', ' .
                ($d['pasien_rt'] ?? '') . '/' . ($d['pasien_rw'] ?? '') . ', ' .
                ($d['kecamatan_nama'] ?? '') . ', ' . ($d['kabupaten_nama'] ?? '');

            $html .= "<tr>
                <td>" . ($index + 1) . "</td>
                <td>" . ($d['tanggal_kontrol_selanjutnya'] ?? '-') . "</td>
                <td>" . ($d['penjamin_nama'] ?? '-') . "</td>
                <td>" . ($d['pasien_no_rm'] ?? '-') . "</td>
                <td>" . ($d['pasien_nama'] ?? '-') . "</td>
                <td>" . ($alamatFull ?: '-') . "</td>
                <td>" . ($d['pasien_no_hp'] ?? '-') . "</td>
                <td>" . ($d['pasien_penanggung_jawab_nama'] ?? '-') . "</td>
                <td>" . ($d['pasien_penanggung_jawab_no_hp'] ?? '-') . "</td>
                <td>" . ($d['dokter_nama'] ?? '-') . "</td>
            </tr>";
        }

        $html .= '</tbody> </table>';

        return response()->json([
            'html' => $html,
            'data' => $data,
        ]);
    }

    public function poliDokter()
    {
        $model        = new KominfoModel();
        $res          = $model->getAkssLoket();
        $data         = $res['data'];
        $jadwalDokter = array_filter($data, function ($item) {
            return stripos($item['admin_nama'], 'dr. ') !== false;
        });
        $jadwalDokter = array_values($jadwalDokter);
        return $jadwalDokter;

        $html = '<table id="jadwal_ruang_dokter" class="table table-bordered table-striped">';
        $html .= '<thead class="bg bg-orange table-bordered">
                     <tr>
                        <th>NO</th>
                        <th>Nama Dokter</th>
                        <th>Jadwal</th>
                    </tr>
                </thead>';
        $html .= '<tbody>';

        foreach ($jadwalDokter as $index => $item) {
            $html .= '<tr>';
            $html .= '<td>' . ($index + 1) . '</td>';
            $html .= '<td>' . $item['admin_nama'] . '</td>';
            $html .= '<td>' . $item['loket_nama'] . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';

        return response()->json(['html' => $html]);
    }

    public function getDataSEP(Request $request)
    {
        $model  = new KominfoModel();
        $params = [
            'tanggal_awal'  => $request->input('tanggal_awal'),
            'tanggal_akhir' => $request->input('tanggal_akhir'),
        ];
        // dd($params);
        $data = $model->getDataSEP($params);
        return response()->json($data);
    }
    public function getDetailSEP(Request $request)
    {
        $model  = new KominfoModel();
        $no_sep = $request->input('no_sep');
        // dd($params);
        $data = $model->getDetailSEP($no_sep);
        return response()->json($data);
    }

}
