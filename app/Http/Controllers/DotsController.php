<?php

namespace App\Http\Controllers;

use App\Models\DiagnosaModel;
use App\Models\DotsBlnModel;
use App\Models\DotsModel;
use App\Models\DotsObatModel;
use App\Models\DotsTransModel;
use App\Models\KominfoModel;
use App\Models\PegawaiModel;
use Carbon\Carbon;
use Exception;
use function PHPUnit\Framework\isNull;
use Illuminate\Http\Request;

class DotsController extends Controller
{
    // public function Ptb(Request $request)
    // {
    //     $norm = $request->input('norm');
    //     $ptbData = [];
    //     $kominfo = new KominfoModel();

    //     if ($norm) {
    //         $Ptb = DotsModel::with('dokter.biodata')->where('norm', $norm)->first();

    //         if (!$Ptb) {
    //             $pasien = $kominfo->pasienRequest($norm);
    //             $tanggal = $request->input('tanggal', Carbon::now()->toDateString());
    //             $params = [
    //                 'tanggal_awal' => $tanggal,
    //                 'tanggal_akhir' => $tanggal,
    //                 'no_rm' => $norm,
    //             ];
    //             $cppt = $kominfo->cpptRequest($params);

    //             if (!empty($cppt) && isset($cppt['response']['data'])) {
    //                 // Mengambil data diagnostik dari semua entri dalam data
    //                 $diagnoses = array_column($cppt['response']['data'], 'diagnosa');

    //                 // Menggabungkan semua diagnosa menjadi satu array (jika ada beberapa entri)
    //                 $allDiagnoses = array_merge(...$diagnoses);

    //                 // Mengambil kode diagnosa jika ada
    //                 $kode_diagnosa = array_column($allDiagnoses, 'kode_diagnosa');
    //             } else {
    //                 $kode_diagnosa = '';
    //             }

    //             // dd($kode_diagnosa);

    //             $pendaftaran = $kominfo->pendaftaranRequest($params);

    //             $filteredData = array_map(function ($d) {
    //                 $doctorNipMap = [
    //                     'dr. Cempaka Nova Intani, Sp.P, FISR., MM.' => '198311142011012002',
    //                     'dr. AGIL DANANJAYA, Sp.P' => '9',
    //                     'dr. FILLY ULFA KUSUMAWARDANI' => '198907252019022004',
    //                     'dr. SIGIT DWIYANTO' => '198903142022031005',
    //                 ];

    //                 $dokter_nama = $d['dokter_nama'];
    //                 $d['nip_dokter'] = $doctorNipMap[$dokter_nama] ?? 'Unknown';

    //                 return $d;
    //             }, $pendaftaran);

    //             $ptbData[] = [
    //                 'pendaftaran' => $filteredData,
    //                 'pasien' => $pasien,
    //                 'diagnosa' => $kode_diagnosa,
    //             ];

    //             $res = [
    //                 'exist' => false,
    //                 'metadata' => [
    //                     'code' => 204,
    //                     'message' => 'Belum Terdaftar Sebagai Pasien TBC...!!',
    //                 ],
    //                 'data' => $ptbData,
    //             ];
    //             return response()->json($res, 200, [], JSON_PRETTY_PRINT);
    //         } else {

    //             $kdDiag = $Ptb['kdDx'];
    //             $no_rm = $Ptb['norm'];

    //             $dx = DiagnosaModel::where('kdDiag', $kdDiag)->get();
    //             $pasien = $kominfo->pasienRequest($no_rm);
    //             $tanggal = $request->input('tanggal_awal');
    //             $params = [
    //                 'tanggal' => $tanggal,
    //                 'no_rm' => $no_rm,
    //             ];
    //             $pendaftaran = $kominfo->waktuLayananRequest($params);
    //             // dd($pendaftaran);

    //             $filteredData = array_map(function ($d) {
    //                 $doctorNipMap = [
    //                     'dr. Cempaka Nova Intani, Sp.P, FISR., MM.' => '198311142011012002',
    //                     'dr. AGIL DANANJAYA, Sp.P' => '9',
    //                     'dr. FILLY ULFA KUSUMAWARDANI' => '198907252019022004',
    //                     'dr. SIGIT DWIYANTO' => '198903142022031005',
    //                 ];

    //                 $dokter_nama = $d['dokter_nama'];
    //                 $d['nip_dokter'] = $doctorNipMap[$dokter_nama] ?? 'Unknown';

    //                 return $d;
    //             }, $pendaftaran);

    //             switch ($Ptb['statusPengobatan']) {
    //                 case "1":
    //                     $Ptb['status'] = "Pengobatan Pertama";
    //                     break;
    //                 case "2":
    //                     $Ptb['status'] = "Pengobatan Kedua";
    //                     break;
    //                 case "3":
    //                     $Ptb['status'] = "Pengobatan Ketiga";
    //                     break;
    //                 case "4":
    //                     $Ptb['status'] = "Pengobatan Keempat";
    //                     break;
    //                 default:
    //                     $Ptb['status'] = "Tidak Diketahui";
    //                     break;
    //             }

    //             if ($Ptb['hasilBerobat'] == null) {
    //                 $Ptb['statusPengobatan'] = "Belum Ada Pengobatan";
    //             } else {
    //                 $status = DotsBlnModel::where('id', $Ptb['hasilBerobat'])->first();
    //                 $Ptb['statusPengobatan'] = $status['nmBlnKe'];
    //             }
    //             $ptbData[] = [
    //                 'pasien' => $pasien,
    //                 'ptb' => $Ptb,
    //                 'diagnosa' => $dx,
    //                 'pendaftaran' => $filteredData,
    //             ];

    //             $res = [
    //                 'exist' => true,
    //                 'metadata' => [
    //                     'code' => 200,
    //                     'message' => 'Pasien Ditemukan...!!',
    //                 ],
    //                 'data' => $ptbData,
    //             ];
    //             return response()->json($res, 200, [], JSON_PRETTY_PRINT);
    //         }
    //     } else {
    //         $Ptb = DotsModel::with('dokter.biodata', 'diagnosa')->get();
    //         $pasienTB = [];
    //         foreach ($Ptb as $d) {
    //             $kdDiag = $d['kdDx'];
    //             $no_rm = $d['norm'];

    //             $dx = DiagnosaModel::where('kdDiag', $kdDiag)->first();
    //             $d['diagnosa'] = $dx['diagnosa'];
    //             if ($d['hasilBerobat'] == null) {
    //                 $d['statusPengobatan'] = "Belum Ada Pengobatan";
    //             } else {
    //                 $status = DotsBlnModel::where('id', $d['hasilBerobat'])->first();
    //                 $d['statusPengobatan'] = $status['nmBlnKe'];
    //             }

    //             switch ($d['statusPengobatan']) {
    //                 case "1":
    //                     $d['status'] = "Pengobatan Pertama";
    //                     break;
    //                 case "2":
    //                     $d['status'] = "Pengobatan Kedua";
    //                     break;
    //                 case "3":
    //                     $d['status'] = "Pengobatan Ketiga";
    //                     break;
    //                 case "4":
    //                     $d['status'] = "Pengobatan Keempat";
    //                     break;
    //                 default:
    //                     $d['status'] = "Tidak Diketahui";
    //                     break;
    //             }
    //             $pasienTB[] = $d;
    //         }

    //         $res = [
    //             'exist' => true,
    //             'metadata' => [
    //                 'code' => 200,
    //                 'message' => 'Data Semua Pasien Ditemukan...!!',
    //             ],
    //             'data' => $pasienTB,
    //         ];
    //         return response()->json($res, 200, [], JSON_PRETTY_PRINT);
    //     }
    // }

    public function Ptb(Request $request)
    {
        $norm = $request->input('norm');
        $tanggal = $request->input('tanggal', Carbon::now()->toDateString());
        $kominfo = new KominfoModel();
        $ptbData = [];

        // Jika `norm` tidak diisi, ambil semua data pasien
        if (!$norm) {
            $Ptb = DotsModel::with('dokter.biodata', 'diagnosa')->get()->map(function ($d) {
                $d['diagnosa'] = DiagnosaModel::where('kdDiag', $d['kdDx'])->value('diagnosa');
                $d['status'] = $this->getStatusPengobatan($d['statusPengobatan']);
                $d['hasilPengobatan'] = $d['hasilBerobat']
                ? DotsBlnModel::where('id', $d['hasilBerobat'])->value('nmBlnKe')
                : "Belum Ada Pengobatan";
                if (isNull($d['ket'])) {
                    $d['ket'] = '-';
                }
                return $d;
            });

            return $this->responseJson(true, 'Data Semua Pasien Ditemukan...!!', $Ptb);
        }

        // Cari pasien berdasarkan `norm`
        $Ptb = DotsModel::with('dokter.biodata')->where('norm', $norm)->first();

        if (!$Ptb) {
            // Jika pasien tidak ditemukan
            $pasien = $kominfo->pasienRequest($norm);
            $params = ['tanggal_awal' => $tanggal, 'tanggal_akhir' => $tanggal, 'no_rm' => $norm];

            // Ambil data diagnosa dari CPPT
            $cppt = $kominfo->cpptRequest($params);
            $kodeDiagnosa = !empty($cppt['response']['data'])
            ? array_column(array_merge(...array_column($cppt['response']['data'], 'diagnosa')), 'kode_diagnosa')
            : '';

            // Ambil data pendaftaran dan tambahkan NIP dokter
            $pendaftaran = $this->filterDokterNip($kominfo->pendaftaranRequest($params));

            $ptbData[] = [
                'pendaftaran' => $pendaftaran,
                'pasien' => $pasien,
                'diagnosa' => $kodeDiagnosa,
            ];

            return $this->responseJson(false, 'Belum Terdaftar Sebagai Pasien TBC...!!', $ptbData, 204);
        }

        // Jika pasien ditemukan
        $pasien = $kominfo->pasienRequest($Ptb['norm']);
        $params = ['tanggal' => $tanggal, 'no_rm' => $Ptb['norm']];
        $pendaftaran = $this->filterDokterNip($kominfo->waktuLayananRequest($params));

        if (isNull($Ptb->ket)) {
            $Ptb->ket = '-';
        }

        $Ptb['status'] = $this->getStatusPengobatan($Ptb['statusPengobatan']);
        $Ptb['statusPengobatan'] = $Ptb['hasilBerobat']
        ? DotsBlnModel::where('id', $Ptb['hasilBerobat'])->value('nmBlnKe')
        : "Belum Ada Pengobatan";

        $ptbData[] = [
            'pasien' => $pasien,
            'ptb' => $Ptb,
            'diagnosa' => DiagnosaModel::where('kdDiag', $Ptb['kdDx'])->get(),
            'pendaftaran' => $pendaftaran,
        ];

        return $this->responseJson(true, 'Pasien Ditemukan...!!', $ptbData);
    }

    private function getStatusPengobatan($status)
    {
        $statuses = [
            "1" => "Pengobatan Pertama",
            "2" => "Pengobatan Kedua",
            "3" => "Pengobatan Ketiga",
            "4" => "Pengobatan Keempat",
        ];
        return $statuses[$status] ?? "Tidak Diketahui";
    }

    private function filterDokterNip($data)
    {
        $doctorNipMap = [
            'dr. Cempaka Nova Intani, Sp.P, FISR., MM.' => '198311142011012002',
            'dr. AGIL DANANJAYA, Sp.P' => '9',
            'dr. FILLY ULFA KUSUMAWARDANI' => '198907252019022004',
            'dr. SIGIT DWIYANTO' => '198903142022031005',
        ];

        return array_map(function ($d) use ($doctorNipMap) {
            $d['nip_dokter'] = $doctorNipMap[$d['dokter_nama']] ?? 'Unknown';
            return $d;
        }, $data);
    }

    private function responseJson($exist, $message, $data, $code = 200)
    {
        return response()->json([
            'exist' => $exist,
            'metadata' => [
                'code' => $code,
                'message' => $message,
            ],
            'data' => $data,
        ], $code, [], JSON_PRETTY_PRINT);
    }

    public function telat()
    {
        // Ambil semua data pasien dari DotsModel
        $Ptb = DotsModel::all();
        $pasien_telat = [];

        foreach ($Ptb as $d) {
            // Ambil transaksi terakhir untuk pasien ini
            $Pkontrol = DotsTransModel::with('bln')
                ->where('norm', $d->norm)
                ->latest('created_at')
                ->first();

            // Pastikan $Pkontrol tidak null sebelum akses atribut
            if ($Pkontrol) {
                $hasilBerobat = $d->hasilBerobat; // Ambil hasil berobat dari Ptb (DotsModel)
                $now = Carbon::now();
                $nxKontrolDate = Carbon::parse($Pkontrol->nxKontrol);
                $terakhir_kontrol = Carbon::parse($Pkontrol->created_at);

                $kdBlnke = $Pkontrol->bln->id ?? null; // Validasi null
                $blnke = $Pkontrol->bln->nmBlnKe ?? '-'; // Fallback jika null
                $selisihHari = $nxKontrolDate->diffInDays($now, false); // Hitung selisih hari (bisa negatif jika sudah lewat)

                // Mengambil data dokter
                $dataDokter = PegawaiModel::with('biodata')->where('nip', $Pkontrol->dokter)->first();
                $namaDokter = $dataDokter
                ? $dataDokter->gelar_d . " " . $dataDokter->biodata->nama . " " . $dataDokter->gelar_b
                : 'Tidak Diketahui';

                // Tentukan status pasien berdasarkan hasilBerobat dan selisih hari
                if (in_array($hasilBerobat, ["93", "94", "95", "96", "97", "98"])) {
                    $status = 'Tidak Diketahui';
                } elseif (abs($selisihHari) > 30) {
                    $status = 'DO';
                } elseif ($selisihHari > 0 && abs($selisihHari) <= 30) {
                    $status = 'Telat';
                } elseif ($selisihHari >= -7 && $selisihHari <= 0) {
                    $status = 'Tepat Waktu';
                } elseif ($selisihHari < -7) {
                    $status = 'Belum Saatnya';
                } else {
                    $status = 'Tidak Diketahui';
                }
                if (isNull($d->ket)) {
                    $d->ket = '-';
                }

                // Format tanggal dan tambahan data
                $d->terakhir = $terakhir_kontrol->format('d-m-Y');
                $d->selisih = $selisihHari;
                $d->nxKontrol = $nxKontrolDate->format('d-m-Y');
                $d->blnKe = $blnke;
                $d->kdPengobatan = $kdBlnke;
                $d->namaDokter = $namaDokter;
                $d->status = $status;

                $pasien_telat[] = $d;
            }
        }

        // Kembalikan data sebagai JSON response
        return response()->json([
            'metadata' => [
                'code' => 200,
                'message' => 'Data Pasien Ditemukan...!!',
            ],
            'data' => $pasien_telat,
        ], 200, [], JSON_PRETTY_PRINT);
    }

    public function obatDots()
    {
        $obat = DotsObatModel::on('mysql')
            ->get();
        return response()->json($obat, 200, [], JSON_PRETTY_PRINT);
    }
    public function kunjunganDots(Request $request)
    {
        $norm = $request->input('norm');
        $data = DotsTransModel::with('pasien', 'petugas.biodata', 'dokter.biodata', 'bln')
            ->where('norm', $norm)
            ->get();
        foreach ($data as $d) {
            $bta = $d->bta;
            if ($bta == null || $bta == '') {
                $d['bta'] = 'Tidak Diketahui/Tidak Cek';
            }
        }

        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
    public function blnKeDots()
    {
        $obat = DotsBlnModel::on('mysql')
            ->get();
        return response()->json($obat, 200, [], JSON_PRETTY_PRINT);
    }

    public function simpanKunjungan(Request $request)
    {
        // Ambil data dari permintaan Ajax
        $norm = $request->input('norm');
        $notrans = $request->input('notrans');
        $tgltrans = $request->input('tgltrans');
        $bta = $request->input('bta');
        $bb = $request->input('bb');
        $blnKe = $request->input('blnKe');
        $nxKontrol = $request->input('nxKontrol');
        $terapi = $request->input('terapi');
        $petugas = $request->input('petugas');
        $dokter = $request->input('dokter');

        if ($norm !== null) {
            // Cek apakah data sudah ada berdasarkan notrans
            $existingData = DotsTransModel::where('norm', $norm)->where('created_at', $tgltrans)->first();

            if ($existingData) {
                // Jika data ditemukan, lakukan update
                $existingData->created_at = $tgltrans;
                $existingData->bta = $bta;
                $existingData->blnKe = $blnKe;
                $existingData->bb = $bb;
                $existingData->nxKontrol = $nxKontrol;
                $existingData->terapi = $terapi;
                $existingData->petugas = $petugas;
                $existingData->dokter = $dokter;

                // Simpan perubahan
                $existingData->save();

                $msg = "Data kunjungan dengan NoTrans: $notrans berhasil diupdate.";
            } else {
                // Jika data tidak ditemukan, lakukan insert
                $addPTB = new DotsTransModel();
                $addPTB->norm = $norm;
                $addPTB->notrans = $notrans;
                $addPTB->created_at = $tgltrans;
                $addPTB->bta = $bta;
                $addPTB->blnKe = $blnKe;
                $addPTB->bb = $bb;
                $addPTB->nxKontrol = $nxKontrol;
                $addPTB->terapi = $terapi;
                $addPTB->petugas = $petugas;
                $addPTB->dokter = $dokter;

                // Simpan data baru
                $addPTB->save();

                $msg = "Kunjungan pasien TBC berhasil disimpan.";
            }

            // Update data pada tabel DotsModel jika ditemukan
            $msgUpdate = "";
            $update = DotsModel::where('norm', $norm)->first();
            if ($update) {
                $update->hasilBerobat = $blnKe;
                $update->save();
                $msgUpdate = " Status Pengobatan berhasil diupdate.";
            }

            // Gabungkan pesan respon
            $res = $msg . $msgUpdate;

            // Respon sukses
            return response()->json(['message' => $res]);
        } else {
            // Jika $norm null, kirim respon error
            return response()->json(['message' => 'Kode tidak valid'], 400);
        }

    }

    public function addPasienTb(Request $request)
    {
        // Ambil data dari permintaan Ajax
        $norm = $request->input('norm');
        $nik = $request->input('nik');
        $hp = $request->input('hp');
        $nama = $request->input('nama');
        $alamat = $request->input('alamat');
        $tcm = $request->input('tcm');
        $sample = $request->input('sample');
        $dx = $request->input('dx');
        $mulai = $request->input('mulai');
        $bb = $request->input('bb');
        $terapi = $request->input('terapi');
        $hasilBerobat = $request->input('hasilBerobat');

        $hiv = $request->input('hiv');
        $dm = $request->input('dm');
        $ket = $request->input('ket');
        $status = $request->input('status');
        $petugas = $request->input('petugas');
        $dokter = $request->input('dokter');

        if ($norm !== null) {
            // Membuat instance dari model KunjunganTindakan
            $addPTB = new DotsModel();
            // Mengatur nilai-nilai kolom
            $addPTB->norm = $norm;
            $addPTB->nama = $nama;
            $addPTB->nik = $nik;
            $addPTB->alamat = $alamat;
            $addPTB->noHp = $hp;
            $addPTB->tcm = $tcm;
            $addPTB->sample = $sample;
            $addPTB->kdDx = $dx;
            $addPTB->tglMulai = $mulai;
            $addPTB->bb = $bb;
            $addPTB->obat = $terapi;
            $addPTB->hiv = $hiv;
            $addPTB->dm = $dm;
            $addPTB->ket = $ket;
            $addPTB->petugas = $petugas;
            $addPTB->dokter = $dokter;
            $addPTB->hasilBerobat = $hasilBerobat;
            $addPTB->statusPengobatan = $status;

            // Simpan data ke dalam tabel
            $addPTB->save();

            // Respon sukses atau redirect ke halaman lain
            return response()->json(['message' => 'Data berhasil disimpan']);
        } else {
            // Handle case when $kdTind is null, misalnya kirim respon error
            return response()->json(['message' => 'kdBmhp tidak valid'], 400);
        }
    }

    public function updatePasienTB(Request $request)
    {
        // dd($request->all());
        $id = $request->input('id');

        if ($id === null) {
            return response()->json(['message' => 'ID tidak valid'], 400);
        }

        $pasien = DotsModel::where('id', $id)->first();

        if ($pasien === null) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $pasien->norm = $request->input('norm');
        $pasien->nama = $request->input('nama');
        $pasien->nik = $request->input('nik');
        $pasien->alamat = $request->input('alamat');
        $pasien->noHp = $request->input('noHp');
        $pasien->tcm = $request->input('tcm');
        $pasien->sample = $request->input('sample');
        $pasien->kdDx = $request->input('kdDx');
        $pasien->tglMulai = $request->input('tglMulai');
        $pasien->bb = $request->input('bb');
        $pasien->obat = $request->input('obat');
        $pasien->hiv = $request->input('hiv');
        $pasien->dm = $request->input('dm');
        $pasien->ket = $request->input('ket');
        $pasien->petugas = $request->input('petugas');
        $pasien->dokter = $request->input('dokter');
        $pasien->hasilBerobat = $request->input('hasilBerobat');
        $pasien->statusPengobatan = $request->input('status');

        try {
            $pasien->save();
        } catch (Exception $e) {
            return response()->json(['message' => 'Gagal mengupdate data'], 500);
        }

        return response()->json(['message' => 'Data berhasil diupdate']);
    }

    // public function poinPetugas(Request $request)
    // {
    //     $mulaiTgl = $request->input('tglAwal', now()->toDateString());
    //     $selesaiTgl = $request->input('tglAkhir', now()->toDateString());
    //     $data = DotsTransModel::with('petugas.biodata')
    //         ->whereBetween('created_at', [$mulaiTgl, $selesaiTgl])
    //         ->get();
    //     return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    // }

    public function poinPetugas(Request $request)
    {
        $mulaiTgl = $request->input('tglAwal', now()->toDateString());
        $selesaiTgl = $request->input('tglAkhir', now()->toDateString());

        // Ambil data
        $kunjungan = DotsTransModel::whereBetween('created_at', [$mulaiTgl, $selesaiTgl])
            ->get();

        $data = [];
        foreach ($kunjungan as $d) {
            $pegawai = PegawaiModel::with('biodata')->where('nip', $d->petugas)->first();
            $data[] = [
                "id" => $d->id,
                "norm" => $d->norm,
                "notrans" => $d->notrans,
                'nip' => $d->petugas,
                'nama' => $pegawai->biodata->nama ?? 'Tidak Diketahui',
            ];
        }

        // Hitung jumlah kemunculan per NIP
        $poin = collect($data)->groupBy('nip')->map(function ($group, $nip) {
            return [
                'nip' => $nip,
                'nama' => $group->first()['nama'], // Nama pertama dari grup
                'jumlah' => $group->count(), // Hitung jumlah kemunculan
            ];
        })->values(); // Reset indeks array

        return response()->json($poin, 200, [], JSON_PRETTY_PRINT);
    }

}
