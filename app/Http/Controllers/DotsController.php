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
use Illuminate\Http\Request;

class DotsController extends Controller
{
    public function Ptb(Request $request)
    {
        $norm = $request->input('norm');
        $ptbData = [];
        $kominfo = new KominfoModel();

        if ($norm) {
            $Ptb = DotsModel::with('dokter.biodata')->where('norm', $norm)->first();

            if (!$Ptb) {
                $pasien = $kominfo->pasienRequest($norm);
                $tanggal = $request->input('tanggal', Carbon::now()->toDateString());
                $params = [
                    'tanggal_awal' => $tanggal,
                    'tanggal_akhir' => $tanggal,
                    'no_rm' => $norm,
                ];
                $cppt = $kominfo->cpptRequest($params);

                if (!empty($cppt) && isset($cppt['response']['data'])) {
                    // Mengambil data diagnostik dari semua entri dalam data
                    $diagnoses = array_column($cppt['response']['data'], 'diagnosa');

                    // Menggabungkan semua diagnosa menjadi satu array (jika ada beberapa entri)
                    $allDiagnoses = array_merge(...$diagnoses);

                    // Mengambil kode diagnosa jika ada
                    $kode_diagnosa = array_column($allDiagnoses, 'kode_diagnosa');
                } else {
                    $kode_diagnosa = '';
                }

                // dd($kode_diagnosa);

                $pendaftaran = $kominfo->pendaftaranRequest($params);

                $filteredData = array_map(function ($d) {
                    $doctorNipMap = [
                        'dr. Cempaka Nova Intani, Sp.P, FISR., MM.' => '198311142011012002',
                        'dr. AGIL DANANJAYA, Sp.P' => '9',
                        'dr. FILLY ULFA KUSUMAWARDANI' => '198907252019022004',
                        'dr. SIGIT DWIYANTO' => '198903142022031005',
                    ];

                    $dokter_nama = $d['dokter_nama'];
                    $d['nip_dokter'] = $doctorNipMap[$dokter_nama] ?? 'Unknown';

                    return $d;
                }, $pendaftaran);

                $ptbData[] = [
                    'pendaftaran' => $filteredData,
                    'pasien' => $pasien,
                    'diagnosa' => $kode_diagnosa,
                ];

                $res = [
                    'exist' => false,
                    'metadata' => [
                        'code' => 204,
                        'message' => 'Belum Terdaftar Sebagai Pasien TBC...!!',
                    ],
                    'data' => $ptbData,
                ];
                return response()->json($res, 200, [], JSON_PRETTY_PRINT);
            } else {

                $kdDiag = $Ptb['kdDx'];
                $no_rm = $Ptb['norm'];

                $dx = DiagnosaModel::where('kdDiag', $kdDiag)->get();
                $pasien = $kominfo->pasienRequest($no_rm);
                $tanggal = $request->input('tanggal_awal');
                $params = [
                    'tanggal' => $tanggal,
                    'no_rm' => $no_rm,
                ];
                $pendaftaran = $kominfo->waktuLayananRequest($params);
                // dd($pendaftaran);

                $filteredData = array_map(function ($d) {
                    $doctorNipMap = [
                        'dr. Cempaka Nova Intani, Sp.P, FISR., MM.' => '198311142011012002',
                        'dr. AGIL DANANJAYA, Sp.P' => '9',
                        'dr. FILLY ULFA KUSUMAWARDANI' => '198907252019022004',
                        'dr. SIGIT DWIYANTO' => '198903142022031005',
                    ];

                    $dokter_nama = $d['dokter_nama'];
                    $d['nip_dokter'] = $doctorNipMap[$dokter_nama] ?? 'Unknown';

                    return $d;
                }, $pendaftaran);
                // switch ($Ptb['hasilBerobat']) {
                //     case 95:
                //         $Ptb['statusPengobatan'] = "Pasien di Rujuk/Tidak OAT di KKPM";
                //         break;
                //     case 96:
                //         $Ptb['statusPengobatan'] = "Pasien di Rujuk/Tidak OAT di KKPM";
                //         break;
                //     case 97:
                //         $Ptb['statusPengobatan'] = "Pasien di Rujuk/Tidak OAT di KKPM";
                //         break;
                //     case 98:
                //         $Ptb['statusPengobatan'] = "Pasien di Rujuk/Tidak OAT di KKPM";
                //         break;
                //     case 99:
                //         $Ptb['statusPengobatan'] = "Pasien di Rujuk/Tidak OAT di KKPM";
                //         break;
                //     case null:
                //         $Ptb['statusPengobatan'] = "Belum Ada Pengobatan";
                //         break;
                //     default:
                //         $status = DotsBlnModel::where('id', $Ptb['hasilBerobat'])->first();
                //         $Ptb['statusPengobatan'] = $status['nmBlnKe'];
                //         break;
                // }

                if ($Ptb['hasilBerobat'] == null) {
                    $Ptb['statusPengobatan'] = "Belum Ada Pengobatan";
                } else {
                    $status = DotsBlnModel::where('id', $Ptb['hasilBerobat'])->first();
                    $Ptb['statusPengobatan'] = $status['nmBlnKe'];
                }
                $ptbData[] = [
                    'pasien' => $pasien,
                    'ptb' => $Ptb,
                    'diagnosa' => $dx,
                    'pendaftaran' => $filteredData,
                ];

                $res = [
                    'exist' => true,
                    'metadata' => [
                        'code' => 200,
                        'message' => 'Pasien Ditemukan...!!',
                    ],
                    'data' => $ptbData,
                ];
                return response()->json($res, 200, [], JSON_PRETTY_PRINT);
            }
        } else {
            $Ptb = DotsModel::with('dokter.biodata', 'diagnosa')->get();
            $pasienTB = [];
            foreach ($Ptb as $d) {
                $kdDiag = $d['kdDx'];
                $no_rm = $d['norm'];

                $dx = DiagnosaModel::where('kdDiag', $kdDiag)->first();
                $d['diagnosa'] = $dx['diagnosa'];
                if ($d['hasilBerobat'] == null) {
                    $d['statusPengobatan'] = "Belum Ada Pengobatan";
                } else {
                    $status = DotsBlnModel::where('id', $d['hasilBerobat'])->first();
                    $d['statusPengobatan'] = $status['nmBlnKe'];
                }
                $pasienTB[] = $d;
            }

            $res = [
                'exist' => true,
                'metadata' => [
                    'code' => 200,
                    'message' => 'Data Semua Pasien Ditemukan...!!',
                ],
                'data' => $pasienTB,
            ];
            return response()->json($res, 200, [], JSON_PRETTY_PRINT);
        }
    }

    // public function kontrol()
    // {
    //     $tanggal = Carbon::now()->format('Y-m-d');

    //     $data = DotsTransModel::with('pasien')
    //         ->where('nxKontrol', $tanggal)
    //         ->get();

    //     return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    // }

    // public function telat()
    // {
    //     // Ambil semua data pasien dari DotsModel
    //     $Ptb = DotsModel::all();
    //     $pasien_telat = [];

    //     foreach ($Ptb as $d) {
    //         // Cari transaksi yang paling baru untuk pasien ini
    //         $Pkontrol = DotsTransModel::with('bln')
    //             ->where('norm', $d->norm)
    //             ->latest('created_at')
    //             ->first();

    //         // Jika ada transaksi yang memenuhi kriteria
    //         if ($Pkontrol) {
    //             $now = Carbon::now();
    //             $nxKontrolDate = Carbon::parse($Pkontrol->nxKontrol);
    //             $terakhir_kontrol = $Pkontrol->created_at;

    //             $kdBlnke = $Pkontrol->bln->id;
    //             $blnke = $Pkontrol->bln->nmBlnKe;
    //             $selisihHari = $terakhir_kontrol->diffInDays($now);
    //             $dataDokter = PegawaiModel::with('biodata')->where('nip', $Pkontrol->dokter)->first();
    //             $namaDokter = $dataDokter->gelar_d . " " . $dataDokter->biodata->nama . " " . $dataDokter->gelar_b;
    //             if ($d->hasilBerobat == 93 || $d->hasilBerobat == 94 || $d->hasilBerobat == 95 || $d->hasilBerobat == 96 || $d->hasilBerobat == 97 || $d->hasilBerobat == 98) {
    //                 // dd($d->hasilBerobat);
    //                 $ket = DotsBlnModel::where('id', $d->hasilBerobat)->first();
    //                 $d->status = $ket->nmBlnKe;

    //             } else {

    //                 if ($selisihHari > 30 || $d->hasilBerobat == 99) {
    //                     $d->status = 'DO';
    //                 } elseif ($selisihHari > 7) {
    //                     $d->status = 'Telat';
    //                 } else {
    //                     $d->status = 'Tepat Waktu';
    //                 }
    //             }

    //             $d->terakhir = $terakhir_kontrol->format('d-m-Y');
    //             $d->selisih = $selisihHari;
    //             $d->nxKontrol = $nxKontrolDate->format('d-m-Y');
    //             $d->blnKe = $blnke;
    //             $d->kdPengobatan = $kdBlnke;
    //             $d->namaDokter = $namaDokter;

    //             $pasien_telat[] = $d;
    //         }
    //     }

    //     // Return atau lanjutkan proses sesuai kebutuhan
    //     // Misalnya, mengembalikan hasil dalam JSON response
    //     return response()->json([
    //         'metadata' => [
    //             'code' => 200,
    //             'message' => 'Data Pasien Ditemukan...!!',
    //         ],
    //         'data' => $pasien_telat,
    //     ], 200, [], JSON_PRETTY_PRINT);
    // }
    // public function telat()
    // {
    //     // Ambil semua data pasien dari DotsModel
    //     $Ptb = DotsModel::all();
    //     $pasien_telat = [];

    //     foreach ($Ptb as $d) {
    //         // Cari transaksi terakhir untuk pasien ini
    //         $Pkontrol = DotsTransModel::with('bln')
    //             ->where('norm', $d->norm)
    //             ->latest('created_at')
    //             ->first();

    //         // Jika ada transaksi yang ditemukan
    //         if ($Pkontrol) {
    //             $now = Carbon::now();
    //             $nxKontrolDate = Carbon::parse($Pkontrol->nxKontrol);
    //             $terakhir_kontrol = Carbon::parse($Pkontrol->created_at);

    //             $kdBlnke = $Pkontrol->bln->id ?? null; // Validasi null
    //             $blnke = $Pkontrol->bln->nmBlnKe ?? '-'; // Fallback jika null
    //             $selisihHari = $nxKontrolDate->diffInDays($now);

    //             // Mengambil data dokter
    //             $dataDokter = PegawaiModel::with('biodata')->where('nip', $Pkontrol->dokter)->first();
    //             $namaDokter = $dataDokter
    //             ? $dataDokter->gelar_d . " " . $dataDokter->biodata->nama . " " . $dataDokter->gelar_b
    //             : 'Tidak Diketahui';

    //             // Penentuan status berdasarkan hasil berobat dan nex kontrol
    //             if (in_array($d->hasilBerobat, [93, 94, 95, 96, 97, 98])) {
    //                 // Jika hasil berobat masuk ke dalam daftar status tertentu
    //                 $ket = DotsBlnModel::where('id', $d->hasilBerobat)->first();
    //                 $d->status = $ket->nmBlnKe ?? 'Status Tidak Diketahui';
    //             } else {
    //                 // Penentuan status berdasarkan nex kontrol
    //                 if ($nxKontrolDate->lt($now)) {
    //                     // Nex kontrol sudah lewat
    //                     $daysLate = $nxKontrolDate->diffInDays($now); // Hitung selisih hari dari nex kontrol

    //                     if ($daysLate > 30) {
    //                         $d->status = 'DO'; // Telat lebih dari 30 hari
    //                     } elseif ($daysLate > 7) {
    //                         $d->status = 'Telat'; // Telat lebih dari 7 hari
    //                     } else {
    //                         $d->status = 'Tepat Waktu'; // Nex kontrol baru saja lewat
    //                     }
    //                 } elseif ($nxKontrolDate->lte($now->addDays(7))) {
    //                     // Nex kontrol dalam waktu 7 hari
    //                     $d->status = 'Tepat Waktu';
    //                 } else {
    //                     // Nex kontrol lebih dari 7 hari lagi
    //                     $d->status = 'Belum Saatnya Kontrol';
    //                 }
    //             }

    //             // Format tanggal dan tambahan data
    //             $d->terakhir = $terakhir_kontrol->format('d-m-Y');
    //             $d->selisih = $selisihHari;
    //             $d->nxKontrol = $nxKontrolDate->format('d-m-Y');
    //             $d->blnKe = $blnke;
    //             $d->kdPengobatan = $kdBlnke;
    //             $d->namaDokter = $namaDokter;

    //             $pasien_telat[] = $d;
    //         }
    //     }

    //     // Kembalikan data sebagai JSON response
    //     return response()->json([
    //         'metadata' => [
    //             'code' => 200,
    //             'message' => 'Data Pasien Ditemukan...!!',
    //         ],
    //         'data' => $pasien_telat,
    //     ], 200, [], JSON_PRETTY_PRINT);
    // }

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

            // Simpan data ke dalam tabel
            $addPTB->save();

            // Respon sukses atau redirect ke halaman lain
            return response()->json(['message' => 'Data berhasil disimpan']);
        } else {
            // Handle case when $kdTind is null, misalnya kirim respon error
            return response()->json(['message' => 'kdBmhp tidak valid'], 400);
        }
    }

    public function updatePengobatanPasien(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');
        $sample = $request->input('sample');
        $ket = $request->input('ket');

        if ($id === null) {
            return response()->json(['message' => 'ID tidak valid'], 400);
        }

        $pasien = DotsModel::where('id', $id)->first();

        if ($pasien === null) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $pasien->hasilBerobat = $status;
        $pasien->sample = $sample;
        $pasien->ket = $ket;

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
