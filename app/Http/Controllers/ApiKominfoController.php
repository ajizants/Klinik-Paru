<?php
namespace App\Http\Controllers;

use App\Models\ApiKominfo;
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

}
