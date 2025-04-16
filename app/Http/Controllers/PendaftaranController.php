<?php

namespace App\Http\Controllers;

use App\Models\KominfoModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{

    public function label($norm)
    {
        $model = new KominfoModel();
        $pasien = $model->pasienRequest($norm);
        // return $pasien;

        if (!isset($pasien)) {
            abort(404, 'Data pasien tidak ditemukan.');
        }
        $umur = date_diff(date_create($pasien['pasien_tgl_lahir']), date_create('today'))->y;

        $title = $this->generateTitle($umur, $pasien['jenis_kelamin_nama'], $pasien['status_kawin_nama']);
        $alamat = ucwords(strtolower(
            $pasien['kelurahan_nama'] . ', ' .
            $pasien['pasien_rt'] . '/' .
            $pasien['pasien_rw'] . ', ' .
            $pasien['kabupaten_nama']
        ));

        $dataArray = [
            'pasien' => array_fill(0, 28, [ // isi array 28 elemen dengan data yang sama
                'norm' => $pasien['pasien_no_rm'],
                'jkel' => $pasien['jenis_kelamin_nama'],
                'tgllahir' => $pasien['pasien_tgl_lahir'],
                'umur' => $umur,
                'sKwn' => $pasien['status_kawin_nama'],
                'sebutan' => $title,
                'nama' => $pasien['pasien_nama'],
                'alamat' => $alamat,
            ]),
        ];

        // return view('Pendaftaran.Cetak.label', $dataArray);

        $pdf = Pdf::loadView('Pendaftaran.Cetak.label', $dataArray)
            ->setPaper([0, 0, 600, 500]) // 211mm x 135mm in points
            ->setOptions([
                'defaultPaperSize' => 'custom',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'dpi' => 96,
                'defaultFont' => 'sans-serif',
            ]);

        return $pdf->stream('label.pdf'); // bisa juga ->download()
    }
    public function biodata($norm)
    {
        $model = new KominfoModel();
        $pasien = $model->pasienRequest($norm);
        // return $pasien;

        if (!isset($pasien)) {
            abort(404, 'Data pasien tidak ditemukan.');
        }
        $umur = date_diff(date_create($pasien['pasien_tgl_lahir']), date_create('today'))->y;

        $title = $this->generateTitle($umur, $pasien['jenis_kelamin_nama'], $pasien['status_kawin_nama']);
        $alamat = ucwords(strtolower(
            $pasien['kelurahan_nama'] . ', ' .
            $pasien['pasien_rt'] . '/' .
            $pasien['pasien_rw'] . ', ' .
            $pasien['kabupaten_nama']
        ));

        $dataArray = [
            'norm' => $pasien['pasien_no_rm'],
            'jkel' => $pasien['jenis_kelamin_nama'],
            'tgllahir' => $pasien['pasien_tgl_lahir'],
            'umur' => $umur,
            'sKwn' => $pasien['status_kawin_nama'],
            'sebutan' => $title,
            'nama' => $pasien['pasien_nama'],
            'alamat' => $alamat,
        ];

        // return view('Pendaftaran.Cetak.biodata', $pasien, compact('pasien'));

        $pdf = Pdf::loadView('Pendaftaran.Cetak.biodata', $pasien);
        // ->setPaper([0, 0, 600, 500]) // 211mm x 135mm in points
        // ->setOptions([
        //     'defaultPaperSize' => 'custom',
        //     'isHtml5ParserEnabled' => true,
        //     'isRemoteEnabled' => true,
        //     'dpi' => 96,
        //     'defaultFont' => 'sans-serif',
        // ]);

        return $pdf->stream('label.pdf'); // bisa juga ->download()
    }

    public function generateTitle($umur, $jkel, $sKwn)
    {
        $jkel = strtoupper(substr($jkel, 0, 1)); // L atau P
        $sKwn = strtolower($sKwn); // misal: "Belum Kawin", "Kawin", dll

        if ($umur < 17) {
            return 'An';
        }

        if ($jkel === 'L') {
            return (strpos($sKwn, 'kawin') !== false) ? 'Tn' : 'Sdr';
        } elseif ($jkel === 'P') {
            return (strpos($sKwn, 'kawin') !== false) ? 'Ny' : 'Nn';
        }

        return 'Sdr'; // fallback
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
