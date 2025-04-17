<?php
namespace App\Http\Controllers;

use App\Models\DataPasienModel;
use App\Models\KominfoModel;
use App\Models\KunjunganModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

        return view('Pendaftaran.Cetak.label', $dataArray);

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

        return view('Pendaftaran.Cetak.biodata', $pasien, compact('pasien'));

        $pdf = Pdf::loadView('Pendaftaran.Cetak.biodata', $pasien);

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

    public function daftar(Request $request)
    {
        $norm = str_pad($request->input('norm'), 6, '0', STR_PAD_LEFT);
        $pekerjaan = $request->input('pekerjaan');
        $ibukandung = $request->input('ibu_kandung');
        $notrans = $request->input('notrans');
        $nourut = (int) ltrim($request->input('no_urut'), '0');
        // dd($nourut);

        $model = new KominfoModel();
        $pasienKominfo = $model->pasienRequest($norm);
        $pasienLocal = DataPasienModel::where('norm', $norm)->first();

        $pasienFromLocal = $pasienLocal ? [
            'norm' => str_pad($pasienLocal->norm, 6, '0', STR_PAD_LEFT),
            'rmlama' => $pasienLocal->rmlama,
            'tgldaftar' => $pasienLocal->tgldaftar,
            'jamdaftar' => $pasienLocal->jamdaftar,
            'kkelompok' => $pasienLocal->kkelompok,
            'noasuransi' => $pasienLocal->noasuransi,
            'noktp' => $pasienLocal->noktp,
            'nama' => $pasienLocal->nama,
            'alamat' => $pasienLocal->alamat,
            'kprovinsi' => $pasienLocal->kprovinsi,
            'kkabupaten' => $pasienLocal->kkabupaten,
            'kkecamatan' => $pasienLocal->kkecamatan,
            'kkelurahan' => $pasienLocal->kkelurahan,
            'rtrw' => $pasienLocal->rtrw,
            'jeniskel' => $pasienLocal->jeniskel,
            'tmptlahir' => $pasienLocal->tmptlahir,
            'tgllahir' => $pasienLocal->tgllahir,
            'kdAgama' => intval($pasienLocal->kdAgama),
            'kdPendidikan' => intval($pasienLocal->kdPendidikan),
            'nohp' => $pasienLocal->nohp,
            'statKawin' => $pasienLocal->statKawin,
            'pjwb' => $pasienLocal->pjwb,
            'goldarah' => $pasienLocal->goldarah,
            'jctkkartu' => $pasienLocal->jctkkartu,
        ] : [];

        $inisial = Str::upper(substr($pasienKominfo['pasien_nama'], 0, 1));

        $pasien = [
            'norm' => $norm,
            'rmlama' => $inisial . '.' . str_pad($pasienKominfo['pasien_no_rm'], 9, '0', STR_PAD_LEFT),
            'tgldaftar' => Carbon::parse($pasienKominfo['created_at'])->format('Y-m-d'),
            'jamdaftar' => Carbon::parse($pasienKominfo['created_at'])->format('H:i:s'),
            'kkelompok' => intval($pasienKominfo['penjamin_id']),
            'noasuransi' => $pasienKominfo['penjamin_nomor'] ?? '',
            'noktp' => $pasienKominfo['pasien_nik'],
            'nama' => $pasienKominfo['pasien_nama'],
            'alamat' => $pasienKominfo['pasien_domisili'],
            'kprovinsi' => $pasienKominfo['provinsi_id'],
            'kkabupaten' => $pasienKominfo['kabupaten_id'],
            'kkecamatan' => $pasienKominfo['kecamatan_id'],
            'kkelurahan' => $pasienKominfo['kelurahan_id'],
            'rtrw' => $pasienKominfo['pasien_rt'] . '/' . $pasienKominfo['pasien_rw'],
            'jeniskel' => $pasienKominfo['jenis_kelamin_nama'] === 'L' ? 'L' : 'P',
            'tmptlahir' => $pasienKominfo['pasien_tempat_lahir'],
            'tgllahir' => $pasienKominfo['pasien_tgl_lahir'],
            'kdAgama' => intval($pasienKominfo['rs_paru_agama_id']),
            'kdPendidikan' => intval($pasienKominfo['rs_paru_pendidikan_id']),
            'nohp' => $pasienKominfo['pasien_no_hp'],
            'statKawin' => $pasienKominfo['rs_paru_status_kawin'],
            'pekerjaan' => $pekerjaan,
            'pjwb' => $pasienKominfo['pasien_penanggung_jawab_nama'],
            'ibuKandung' => $ibukandung,
            'jctkkartu' => 0,
            'goldarah' => $pasienKominfo['goldar_nama'] === 'TIDAK DIKETAHUI' ? '' : $pasienKominfo['goldar_nama'] ?? '',
            'kunj' => 'B',
        ];

        $jenisKunjungan = $pasienLocal ? 'L' : 'B';
        $umurthn = Carbon::parse($pasien['tgllahir'])->age;
        $umurbln = Carbon::now()->diffInMonths(Carbon::parse($pasien['tgllahir']));
        $umurhr = Carbon::now()->diffInDays(Carbon::parse($pasien['tgllahir']));

        $dataPendaftaran = [
            'nourut' => $nourut,
            'notrans' => $notrans,
            'ibuKandung' => $ibukandung,
            'pekerjaan' => $pekerjaan,
            'jenisKunjungan' => $jenisKunjungan,
            'pasien' => $pasien,
            'umurthn' => $umurthn,
            'umurbln' => $umurbln,
            'umurhr' => $umurhr,
        ];

        if ($pasienLocal && $pasienFromLocal == array_intersect_key($pasien, $pasienFromLocal)) {
            // return response()->json([
            //     'status' => 'apa',
            //     'message' => 'Data pasien sama.',
            //     'pasienKominfo' => $pasien,
            //     'fromLocal' => $pasienFromLocal,
            //     'local' => $pasienLocal,
            //     'kominfo' => $pasienKominfo,
            // ]);
            $this->storeKunjungan($dataPendaftaran);
            return response()->json(['message' => 'Pasien Berhasil Di daftarkan.'], 200, [], JSON_PRETTY_PRINT);
        }

        $beda = array_diff_assoc($pasien, $pasienFromLocal);

        if (!isset($beda['jamdaftar'], $beda['rmlama'], $beda['ibuKandung'], $beda['pekerjaan'])) {
            $storePasien = DataPasienModel::updateOrCreate(['norm' => $norm], $pasien);
            if ($storePasien) {
                $this->storeKunjungan($dataPendaftaran);
            }
            return response()->json(['message' => 'Pasien Berhasil Di daftarkan.'], 200, [], JSON_PRETTY_PRINT);

            // return response()->json([
            //     'status' => 'sama',
            //     'message' => 'Data pasien diperbarui.',
            //     'perbedaan' => $beda,
            //     'dataPendaftaran' => $dataPendaftaran,
            //     'pasienKominfo' => $pasien,
            //     'fromLocal' => $pasienFromLocal,
            //     'local' => $pasienLocal,
            //     'kominfo' => $pasienKominfo,
            // ]);
        }

        return response()->json([
            'status' => 'beda',
            'message' => 'Data pasien berbeda.',
            'perbedaan' => $beda,
            'pasienKominfo' => $pasien,
            'fromLocal' => $pasienFromLocal,
            'local' => $pasienLocal,
            'kominfo' => $pasienKominfo,
        ]);
    }

    public function storeKunjungan($data)
    {
        $pasien = $data['pasien'];

        // Cari data kunjungan berdasarkan 'notrans'
        $kunjungan = KunjunganModel::where('notrans', $data['notrans'])->first();

        // Jika data kunjungan ditemukan, update hanya kolom yang diperlukan
        if ($kunjungan) {
            // Hanya update jika 'notrans' cocok
            $kunjungan->update([
                'norm' => $pasien['norm'],
                'rmlama' => $pasien['rmlama'],
                'nourut' => $data['nourut'],
                'tgltrans' => $pasien['tgldaftar'],
                'kunj' => $data['jenisKunjungan'],
                'jeniskel' => $pasien['jeniskel'],
                'kkelompok' => $pasien['kkelompok'],
                'noasuransi' => $pasien['noasuransi'],
                'ktujan' => 1,
                'kkabupaten' => $pasien['kkabupaten'],
                'umurthn' => $data['umurthn'],
                'umurbln' => $data['umurbln'],
                'umurhr' => $data['umurhr'],
                'biaaya' => 0,
                'loket' => 1,
                'selesai' => 0,
            ]);
        } else {
            // Jika data belum ada, buat baru
            KunjunganModel::create([
                'notrans' => $data['notrans'],
                'norm' => $pasien['norm'],
                'rmlama' => $pasien['rmlama'],
                'nourut' => $data['nourut'],
                'tgltrans' => $pasien['tgldaftar'],
                'kunj' => $data['jenisKunjungan'],
                'jeniskel' => $pasien['jeniskel'],
                'kkelompok' => $pasien['kkelompok'],
                'noasuransi' => $pasien['noasuransi'],
                'ktujan' => 1,
                'kkabupaten' => $pasien['kkabupaten'],
                'umurthn' => $data['umurthn'],
                'umurbln' => $data['umurbln'],
                'umurhr' => $data['umurhr'],
                'biaaya' => 0,
                'loket' => 1,
                'selesai' => 0,
            ]);
        }
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
