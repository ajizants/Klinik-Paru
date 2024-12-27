<?php

namespace App\Http\Controllers;

use App\Models\KasirTransModel;
use App\Models\KominfoModel;
use App\Models\KunjunganModel;
use App\Models\KunjunganWaktuSelesai;
use App\Models\PasienModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AntrianController extends Controller
{

    public function antrianIGD(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $data = KunjunganModel::with(['poli', 'biodata', 'tindakan', 'kelompok', 'petugas.pegawai.biodata'])
            ->whereHas('poli', function ($query) {
                $query->where(function ($q) {
                    $q->where('oksigenasi', '<>', '')
                        ->where('oksigenasi', 'NOT LIKE', '%-%')
                        ->orWhere('nebulizer', '<>', '')
                        ->where('nebulizer', 'NOT LIKE', '%-%')
                        ->orWhere('ekg', '<>', '')
                        ->where('ekg', 'NOT LIKE', '%-%')
                        ->orWhere('mantoux', '<>', '')
                        ->where('mantoux', 'NOT LIKE', '%-%')
                        ->orWhere('spirometri', '<>', '')
                        ->where('spirometri', 'NOT LIKE', '%-%')
                        ->orWhere('injeksi', '<>', '')
                        ->where('injeksi', 'NOT LIKE', '%-%')
                        ->orWhere('infus', '<>', '')
                        ->where('infus', 'NOT LIKE', '%-%');
                });
            })
            ->whereDate('tgltrans', $date)
            ->get();

        $formattedData = [];
        foreach ($data as $transaksi) {

            if (count($transaksi["tindakan"]) === 0) {
                $status = "belum";
            } else {
                $status = "sudah";
            }

            // $transaksi["status"] = $status;

            $formattedData[] = [
                "notrans" => $transaksi["notrans"] ?? null,
                "norm" => $transaksi["norm"] ?? null,
                "nourut" => $transaksi["nourut"] ?? null,
                "noasuransi" => $transaksi["noasuransi"] ?? null,
                "biaya" => $transaksi["kelompok"]["biaya"] ?? null,
                "noktp" => $transaksi["biodata"]["noktp"] ?? null,
                "namapasien" => $transaksi["biodata"]["nama"] ?? null,
                "alamatpasien" => $transaksi["biodata"]["alamat"] ?? null,
                "rtrwpasien" => $transaksi["biodata"]["rtrw"] ?? null,
                "kelaminpasien" => $transaksi["biodata"]["jeniskel"] ?? null,
                "tmptlahir" => $transaksi["biodata"]["tmptlahir"] ?? null,
                "tgllahir" => $transaksi["biodata"]["tgllahir"] ?? null,
                "umurpasien" => $transaksi["biodata"]["umur"] ?? null,
                "nohppasien" => $transaksi["biodata"]["nohp"] ?? null,
                "statKawinpasien" => $transaksi["biodata"]["statKawin"] ?? null,
                "kelompok" => $transaksi["biodata"]["kelompok"] ?? null,
                "provinsi" => $transaksi["biodata"]["provinsi"] ?? null,
                "kabupaten" => $transaksi["biodata"]["kabupaten"] ?? null,
                "kecamatan" => $transaksi["biodata"]["kecamatan"] ?? null,
                "kelurahan" => $transaksi["biodata"]["kelurahan"] ?? null,
                "rtrw" => $transaksi["biodata"]["rtrw"] ?? null,
                "agama" => $transaksi["biodata"]["agama"] ?? null,
                "pendidikan" => $transaksi["biodata"]["pendidikan"] ?? null,

                "tgltrans" => $transaksi["poli"]["tgltrans"] ?? null,
                "rontgen" => $transaksi["poli"]["rontgen"] ?? null,
                "konsul" => $transaksi["poli"]["konsul"] ?? null,
                "tcm" => $transaksi["poli"]["tcm"] ?? null,
                "bta" => $transaksi["poli"]["bta"] ?? null,
                "hematologi" => $transaksi["poli"]["hematologi"] ?? null,
                "kimiaDarah" => $transaksi["poli"]["kimiaDarah"] ?? null,
                "imunoSerologi" => $transaksi["poli"]["imunoSerologi"] ?? null,
                "mantoux" => $transaksi["poli"]["mantoux"] ?? null,
                "ekg" => $transaksi["poli"]["ekg"] ?? null,
                "mikroCo" => $transaksi["poli"]["mikroCo"] ?? null,
                "spirometri" => $transaksi["poli"]["spirometri"] ?? null,
                "spo2" => $transaksi["poli"]["spo2"] ?? null,
                "diagnosa1" => $transaksi["poli"]["diagnosa1"] ?? null,
                "diagnosa2" => $transaksi["poli"]["diagnosa2"] ?? null,
                "diagnosa3" => $transaksi["poli"]["diagnosa3"] ?? null,
                "nebulizer" => $transaksi["poli"]["nebulizer"] ?? null,
                "infus" => $transaksi["poli"]["infus"] ?? null,
                "oksigenasi" => $transaksi["poli"]["oksigenasi"] ?? null,
                "injeksi" => $transaksi["poli"]["injeksi"] ?? null,
                "terapi" => $transaksi["poli"]["terapi"] ?? null,

                "status" => $status,

                "idtindakan" => $transaksi["tindakan"]["id"] ?? null,
                "kdTind" => $transaksi["tindakan"]["kdTind"] ?? null,
                "petugastindakan" => $transaksi["tindakan"]["petugas"] ?? null,
                "doktertindakan" => $transaksi["tindakan"]["dokter"] ?? null,
                "created_at" => $transaksi["tindakan"]["created_at"] ?? null,
                "updated_at" => $transaksi["tindakan"]["updated_at"] ?? null,
                "nip" => $transaksi["petugas"]["pegawai"]["nip"] ?? null,
                "dokterpoli" => ($transaksi["petugas"]["pegawai"]["gelar_d"] ?? null) . ' ' . ($transaksi["petugas"]["pegawai"]["biodata"]["nama"] ?? null) . ' ' . ($transaksi["petugas"]["pegawai"]["gelar_b"] ?? null),
                "jabatan" => $transaksi["petugas"]["pegawai"]["nm_jabatan"] ?? null,
            ];
        }

        return response()->json($formattedData, 200, [], JSON_PRETTY_PRINT);
    }
    public function antrianLaboratorium(Request $request)
    {
        $norm = $request->input("norm");
        $date = $request->input('date', now()->toDateString());
        // dd($date);
        $data = KunjunganModel::with(['poli', 'biodata', 'lab', 'kelompok', 'petugas.pegawai.biodata'])
            ->whereDate('tgltrans', $date)
            ->where('norm', 'LIKE', '%' . $norm . '%')
            ->whereHas('poli', function ($query) {
                $query->where(function ($q) {
                    $q->where('tcm', 1)
                    // ->where('tcm', 'NOT LIKE', '%-%')
                        ->orWhere('bta', 1)
                    // ->where('bta', 'NOT LIKE', '%-%')
                        ->orWhere('hematologi', 1)
                    // ->where('hematologi', 'NOT LIKE', '%-%')
                        ->orWhere('kimiaDarah', 1)
                    // ->where('kimiaDarah', 'NOT LIKE', '%-%')
                        ->orWhere('imunoSerologi', 1);
                    // ->where('imunoSerologi', 'NOT LIKE', '%-%');
                });
            })
            ->orWhere('ktujuan', 5)
            ->get();

        $formattedData = [];
        // dd($data);
        foreach ($data as $transaksi) {

            if (count($transaksi["lab"]) === 0) {
                $status = "belum";
            } else {
                $status = "sudah";
            }
            // dd($status);
            if ($transaksi["umurthn"] <= 15) {
                $pang = "anak";
            } elseif ($transaksi["umurthn"] >= 16 && $transaksi["umurthn"] <= 30) {
                if ($transaksi["biodata"]["jeniskel"] == "Laki-Laki") {
                    $pang = "saudara";
                } else {
                    $pang = "nona";
                }
            } elseif ($transaksi["umurthn"] >= 31) {
                if ($transaksi["biodata"]["jeniskel"] == "Laki-Laki") {
                    $pang = "bapak";
                } else {
                    $pang = "ibu";
                }
            }

            $transaksi["status"] = $status;

            $formattedData[] = [
                "notrans" => $transaksi["notrans"] ?? null,
                "norm" => $transaksi["norm"] ?? null,
                "nourut" => $transaksi["nourut"] ?? null,
                "noasuransi" => $transaksi["noasuransi"] ?? null,
                "ktujuan" => $transaksi["ktujuan"] ?? null,
                "tgltrans" => $transaksi["tgltrans"] ?? null,
                "noktp" => $transaksi["biodata"]["noktp"] ?? null,
                "namapasien" => $transaksi["biodata"]["nama"] ?? null,
                "alamatpasien" => $transaksi["biodata"]["alamat"] ?? null,
                "rtrwpasien" => $transaksi["biodata"]["rtrw"] ?? null,
                "kelaminpasien" => $transaksi["biodata"]["jeniskel"] ?? null,
                "kelompok" => $transaksi["biodata"]["kelompok"] ?? null,
                "provinsi" => $transaksi["biodata"]["provinsi"] ?? null,
                "kabupaten" => $transaksi["biodata"]["kabupaten"] ?? null,
                "kecamatan" => $transaksi["biodata"]["kecamatan"] ?? null,
                "kelurahan" => $transaksi["biodata"]["kelurahan"] ?? null,
                "rtrw" => $transaksi["biodata"]["rtrw"] ?? null,
                "agama" => $transaksi["biodata"]["agama"] ?? null,
                "pendidikan" => $transaksi["biodata"]["pendidikan"] ?? null,

                "rontgen" => $transaksi["poli"]["rontgen"] ?? null,
                "konsul" => $transaksi["poli"]["konsul"] ?? null,
                "tcm" => $transaksi["poli"]["tcm"] ?? null,
                "bta" => $transaksi["poli"]["bta"] ?? null,
                "hematologi" => $transaksi["poli"]["hematologi"] ?? null,
                "kimiaDarah" => $transaksi["poli"]["kimiaDarah"] ?? null,
                "imunoSerologi" => $transaksi["poli"]["imunoSerologi"] ?? null,
                "terapi" => $transaksi["poli"]["terapi"] ?? null,

                "status" => $status,
                "pang" => $pang,

                "created_at" => $transaksi["tindakan"]["created_at"] ?? null,
                "updated_at" => $transaksi["tindakan"]["updated_at"] ?? null,
                "nip" => $transaksi["petugas"]["pegawai"]["nip"] ?? null,
                "dokterpoli" => ($transaksi["petugas"]["pegawai"]["gelar_d"] ?? null) . ' ' . ($transaksi["petugas"]["pegawai"]["biodata"]["nama"] ?? null) . ' ' . ($transaksi["petugas"]["pegawai"]["gelar_b"] ?? null),
                "jabatan" => $transaksi["petugas"]["pegawai"]["nm_jabatan"] ?? null,
            ];
        }

        return response()->json($formattedData, 200, [], JSON_PRETTY_PRINT);
        // return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
    // public function antrianFarmasi(Request $request)
    // {
    //     $tgl = $request->input('tgl') ?? now()->toDateString();
    //     // dd($tgl);
    //     $model = new KominfoModel();
    //     $loginResponse = $model->login(197609262011012003, env('PASSWORD_KOMINFO', ''));
    //     $cookie = $loginResponse['cookies'][0] ?? null;
    //     if (!$cookie) {
    //         return response()->json(['message' => 'Login gagal'], 401);
    //     }
    //     $daftarTunggu = $model->getTungguFaramsi($tgl, $cookie);

    //     $lists = $daftarTunggu['data'];

    //     foreach ($lists as &$list) {
    //         $norm = $list['pasien_no_rm'];
    //         $tanggal = $list['tanggal'];
    //         $kasir = KasirTransModel::where('norm', $norm)
    //             ->whereDate('created_at', $tanggal)->first();
    //         $list['status_kasir'] = !$kasir ? 'Tidak Ada Transaksi' : 'Sudah Selesai';
    //     }
    //     // unset($list);

    //     if ($lists === []) {
    //         return response()->json(['message' => 'Tidak Ada Antrian di tanggal ' . $tgl], 404);
    //     }

    //     Log::info('Final List:', $lists); // Debugging log

    //     return response()->json($lists, 200, [], JSON_PRETTY_PRINT);
    // }

    public function antrianFarmasi(Request $request)
    {
        $tgl = $request->input('tgl') ?? now()->toDateString();
        // dd($tgl);
        $model = new KominfoModel();
        $loginResponse = $model->login(197609262011012003, env('PASSWORD_KOMINFO', ''));

        $cookie = $loginResponse['cookies'][0] ?? null;
        if (!$cookie) {
            return response()->json(['message' => 'Login gagal'], 401);
        }
        $daftarTunggu = $model->getTungguFaramsi($tgl, $cookie);

        // dd($daftarTunggu);

        $lists = $daftarTunggu['data'];
        foreach ($lists as &$list) {
            $norm = $list['pasien_no_rm'];
            $tanggal = $list['tanggal'];
            $kasir = KasirTransModel::where('norm', $norm)
                ->whereDate('created_at', $tanggal)->first();
            $list['status_kasir'] = !$kasir ? 'Tidak Ada Transaksi' : 'Sudah Selesai';
            $pulang = KunjunganWaktuSelesai::where('notrans', $list['no_reg'])->first();
            // dd($pulang);
            $list['status_pulang'] = !$pulang || !$pulang['waktu_selesai_farmasi'] ? 'Belum Pulang' : 'Sudah Pulang';
        }
        // dd($lists);

        // Sort by created_at_log from oldest to newest
        usort($lists, function ($a, $b) {
            $dateA = strtotime($a['created_at_log']);
            $dateB = strtotime($b['created_at_log']);
            return $dateA - $dateB; // Ascending order (oldest first)
        });

        if ($lists === []) {
            return response()->json(['message' => 'Tidak Ada Antrian di tanggal ' . $tgl], 404);
        }

        Log::info('Final List:', $lists); // Debugging log

        return response()->json($lists, 200, [], JSON_PRETTY_PRINT);
    }

    public function antrianKasir(Request $request)
    {
        $date = $request->input('date', Carbon::now()->toDateString());

        $data = KunjunganModel::with(['poli', 'biodata', 'tindakan', 'farmasi', 'kelompok', 'petugas.pegawai'])
            ->whereDate('tgltrans', $date)
            ->whereHas('poli', function ($query) {
                $query->whereNotNull('notrans');
            })
            ->get();

        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
    public function all(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $ruang = $request->input('ruang');
        $data = KunjunganModel::with(['poli', 'tujuan', 'biodata', 'lab', 'tindakan', 'kelompok', 'petugas.pegawai.biodata'])
            ->whereDate('tgltrans', $date)
            ->where('ktujuan', '<>', $ruang)
            ->get();

        $formattedData = [];
        foreach ($data as $transaksi) {
            if (isset($transaksi["kunj"]) && $transaksi["kunj"] == 'B') {
                $status = "BARU"; // Set $status to "Baru" if $transaksi["kunj"] is equal to 'B'
            } else {
                $status = "LAMA"; // Set $status to "Lama" for all other cases
            }

            if (count($transaksi["lab"]) === 0) {
                $lab = "belum";
            } else {
                $lab = "sudah";
            }

            $transaksi["kunjungan"] = $status;

            $formattedData[] = [
                "notrans" => $transaksi["notrans"] ?? null,
                "norm" => $transaksi["norm"] ?? null,
                "nourut" => $transaksi["nourut"] ?? null,
                "noasuransi" => $transaksi["noasuransi"] ?? null,
                "kunj" => $transaksi["kunj"] ?? null,
                "kunjungan" => $status,
                "biaya" => $transaksi["kelompok"]["biaya"] ?? null,
                "layanan" => $transaksi["kelompok"]["kelompok"] ?? null,
                "noktp" => $transaksi["biodata"]["noktp"] ?? null,
                "namapasien" => $transaksi["biodata"]["nama"] ?? null,
                "alamatpasien" => $transaksi["biodata"]["alamat"] ?? null,
                "rtrwpasien" => $transaksi["biodata"]["rtrw"] ?? null,
                "kelaminpasien" => $transaksi["biodata"]["jeniskel"] ?? null,
                "tmptlahir" => $transaksi["biodata"]["tmptlahir"] ?? null,
                "tgllahir" => $transaksi["biodata"]["tgllahir"] ?? null,
                "umurpasien" => $transaksi["biodata"]["umur"] ?? null,
                "nohppasien" => $transaksi["biodata"]["nohp"] ?? null,
                "statKawinpasien" => $transaksi["biodata"]["statKawin"] ?? null,
                "provinsi" => $transaksi["biodata"]["provinsi"] ?? null,
                "kabupaten" => $transaksi["biodata"]["kabupaten"] ?? null,
                "kecamatan" => $transaksi["biodata"]["kecamatan"] ?? null,
                "kelurahan" => $transaksi["biodata"]["kelurahan"] ?? null,
                "rtrw" => $transaksi["biodata"]["rtrw"] ?? null,
                "agama" => $transaksi["biodata"]["agama"] ?? null,
                "pendidikan" => $transaksi["biodata"]["pendidikan"] ?? null,
                "lokasi" => $transaksi["tujuan"]["tujuan"] ?? null,

                "tgltrans" => $transaksi["poli"]["tgltrans"] ?? null,
                "rontgen" => $transaksi["poli"]["rontgen"] ?? null,
                "konsul" => $transaksi["poli"]["konsul"] ?? null,
                "tcm" => $transaksi["poli"]["tcm"] ?? null,
                "bta" => $transaksi["poli"]["bta"] ?? null,
                "hematologi" => $transaksi["poli"]["hematologi"] ?? null,
                "kimiaDarah" => $transaksi["poli"]["kimiaDarah"] ?? null,
                "imunoSerologi" => $transaksi["poli"]["imunoSerologi"] ?? null,
                "mantoux" => $transaksi["poli"]["mantoux"] ?? null,
                "ekg" => $transaksi["poli"]["ekg"] ?? null,
                "mikroCo" => $transaksi["poli"]["mikroCo"] ?? null,
                "spirometri" => $transaksi["poli"]["spirometri"] ?? null,
                "spo2" => $transaksi["poli"]["spo2"] ?? null,
                "diagnosa1" => $transaksi["poli"]["diagnosa1"] ?? null,
                "diagnosa2" => $transaksi["poli"]["diagnosa2"] ?? null,
                "diagnosa3" => $transaksi["poli"]["diagnosa3"] ?? null,
                "nebulizer" => $transaksi["poli"]["nebulizer"] ?? null,
                "infus" => $transaksi["poli"]["infus"] ?? null,
                "oksigenasi" => $transaksi["poli"]["oksigenasi"] ?? null,
                "injeksi" => $transaksi["poli"]["injeksi"] ?? null,
                "terapi" => $transaksi["poli"]["terapi"] ?? null,
                "laborat" => $lab,

                "idtindakan" => $transaksi["tindakan"]["id"] ?? null,
                "kdTind" => $transaksi["tindakan"]["kdTind"] ?? null,
                "petugastindakan" => $transaksi["tindakan"]["petugas"] ?? null,
                "doktertindakan" => $transaksi["tindakan"]["dokter"] ?? null,
                "created_at" => $transaksi["tindakan"]["created_at"] ?? null,
                "updated_at" => $transaksi["tindakan"]["updated_at"] ?? null,
                "nip" => $transaksi["petugas"]["pegawai"]["nip"] ?? null,
                "dokterpoli" => ($transaksi["petugas"]["pegawai"]["gelar_d"] ?? null) . ' ' . ($transaksi["petugas"]["pegawai"]["biodata"]["nama"] ?? null) . ' ' . ($transaksi["petugas"]["pegawai"]["gelar_b"] ?? null),
                "jabatan" => $transaksi["petugas"]["pegawai"]["nm_jabatan"] ?? null,
            ];
        }

        return response()->json($formattedData, 200, [], JSON_PRETTY_PRINT);
        // return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
    public function cariTgl(Request $request)
    {
        $date = $request->input('date', now()->toDateString());

        $data = KunjunganModel::with(['poli', 'biodata', 'tindakan', 'kelompok', 'petugas.pegawai.biodata'])
            ->whereHas('poli', function ($query) {
                $query->where(function ($q) {
                    $q->where('oksigenasi', '<>', '')
                        ->where('oksigenasi', 'NOT LIKE', '%-%')
                        ->orWhere('nebulizer', '<>', '')
                        ->where('nebulizer', 'NOT LIKE', '%-%')
                        ->orWhere('ekg', '<>', '')
                        ->where('ekg', 'NOT LIKE', '%-%')
                        ->orWhere('spirometri', '<>', '')
                        ->where('spirometri', 'NOT LIKE', '%-%')
                        ->orWhere('injeksi', '<>', '')
                        ->where('injeksi', 'NOT LIKE', '%-%')
                        ->orWhere('infus', '<>', '')
                        ->where('infus', 'NOT LIKE', '%-%');
                });
            })
            ->whereDate('tgltrans', $date)
            ->get();

        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
    public function cariRM(Request $request)
    {
        $norm = $request->input('norm');
        $date = $request->input('date', now()->toDateString());

        $data = KunjunganModel::with(['poli', 'biodata', 'kelompok', 'petugas.pegawai.biodata'])
            ->where('t_kunjungan.norm', 'LIKE', '%' . $norm . '%')
            ->whereDate('tgltrans', $date)
            ->get();

        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
    public function cariRMObat(Request $request)
    {
        $norm = $request->input('norm');
        $date = $request->input('date', now()->toDateString());

        $data = PasienModel::where('norm', 'LIKE', '%' . $norm . '%')
            ->first();

        // $res = array_values($data);
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
        // return response()->json($res, 200, [], JSON_PRETTY_PRINT);
    }

    public function selesaiRM(Request $request)
    {
        $norm = $request->input('norm');
        $notrans = $request->input('notrans');
        $nosep = $request->input('nosep');

        try {
            DB::beginTransaction();

            // Cari entri dengan notrans yang diberikan
            $data = KunjunganWaktuSelesai::where('notrans', $notrans)->first();

            if ($data) {
                // Jika entri sudah ada, perbarui kolom updated_at
                $data->waktu_selesai_rm = now();
                $data->no_sep = $nosep;
            } else {
                // Jika entri belum ada, buat entri baru
                $data = new KunjunganWaktuSelesai;
                $data->norm = $norm;
                $data->notrans = $notrans;
                $data->no_sep = $nosep;
                $data->waktu_selesai_rm = now();
            }

            $data->save();

            $now = date('Y-m-d H:i:s');

            $msg = "Pendaftaran Pasien No. RM: " . $norm . " Selesai Tanggal: " . $now;

            DB::commit();

            return response()->json([
                'message' => $msg,
            ], 200);
        } catch (\Exception $e) {
            DB::rollback(); // Rollback transaksi jika terjadi kesalahan
            Log::error('Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat CheckOut data: ' . $e->getMessage()], 500);
        }
    }
    public function selesaiIGD(Request $request)
    {
        $norm = $request->input('norm');
        $notrans = $request->input('notrans');

        try {
            DB::beginTransaction();

            // Cari entri dengan notrans yang diberikan
            $data = KunjunganWaktuSelesai::where('notrans', $notrans)->first();

            if ($data) {
                // Jika entri sudah ada, perbarui kolom updated_at
                $data->waktu_selesai_igd = now();
            } else {
                // Jika entri belum ada, buat entri baru
                $data = new KunjunganWaktuSelesai;
                $data->norm = $norm;
                $data->notrans = $notrans;
                $data->waktu_selesai_igd = now();
            }

            $data->save();

            $now = date('Y-m-d H:i:s');

            $msg = "Pasien No. RM: " . $norm . " Selesai Tindakan Tanggal: " . $now;

            DB::commit();

            return response()->json([
                'message' => $msg,
            ], 200);
        } catch (\Exception $e) {
            DB::rollback(); // Rollback transaksi jika terjadi kesalahan
            Log::error('Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat checkOut data: ' . $e->getMessage()], 500);
        }
    }

}
