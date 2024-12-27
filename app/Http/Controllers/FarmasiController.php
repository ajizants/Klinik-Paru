<?php

namespace App\Http\Controllers;

use App\Models\FarmasiModel;
use App\Models\GudangFarmasiInStokModel;
use App\Models\GudangFarmasiModel;
use App\Models\IGDTransModel;
use App\Models\KominfoModel;
use App\Models\KunjunganModel;
use App\Models\KunjunganWaktuSelesai;
use App\Models\LogGudangFarmasiModel;
use App\Models\TransaksiBMHPModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FarmasiController extends Controller
{

    public function obats(Request $request)
    {
        $norm = $request->input('norm');
        $tanggal = $request->input('tanggal');
        return $this->cariObats($norm, $tanggal);
    }
    public function cetakObat($norm, $tanggal)
    {
        // Fetch the response data from the API
        $data = $this->cariObats($norm, $tanggal);

        // Decode the JSON response to an array if it's a JsonResponse
        $dataArray = json_decode($data->getContent(), true);

        // return $dataArray;
        // Extract 'obats' and 'tindakanList' from the decoded array
        $obats = $dataArray['obats'] ?? []; // Ensure it's set as an empty array if not available
        $tindakanList = $dataArray['tindakan'] ?? [];
        // return $tindakanList;
        $cppt = $dataArray['cppt'] ?? [];
        // return $cppt;
        // Pass the data to the view
        return view('Laporan.obat', compact('tindakanList', 'obats', 'cppt'))->with([
            'title' => "Obat Terpakai",
        ]);
    }

    private function cariObats($norm, $tanggal)
    {

        $params = [
            'tanggal_awal' => $tanggal,
            'tanggal_akhir' => $tanggal,
            'no_rm' => $norm ?? '',
        ];
        $model = new KominfoModel();
        $data = $model->cpptRequest($params);

        // Pastikan data CPPT tersedia
        $cppt = $data['response']['data'][0] ?? null;
        // Periksa resep obat dari CPPT
        $obats = [];
        $dObats = [];
        if (is_array($cppt) && isset($cppt['resep_obat'])) {
            $dObats = $cppt['resep_obat'];
        } elseif (is_object($cppt) && isset($cppt->resep_obat)) {
            $dObats = $cppt->resep_obat;
        }
        // return $dObats;
        foreach ($dObats as $obat) {
            $obats[] = [
                'no_resep' => $obat['no_resep'],
                'jumlah_puyer' => $obat['jumlah_puyer'],
                'signa' => $obat['signa_1'] . ' X ' . $obat['signa_2'] . ' ' . $obat['aturan_pakai'],
                'nmObat' => $obat['resep_obat_detail'][0]['nama_obat'],
                'jumlah' => $obat['resep_obat_detail'][0]['jumlah_obat'],
            ];
        }

        if (!$cppt || !isset($cppt['no_reg'])) {
            return response()->json(['error' => 'Data CPPT tidak ditemukan atau tidak valid'], 404);
        }

        $noReg = $cppt['no_reg'];
        // dd($noReg);

        // Fetch tindakan dari model
        $tindakans = IGDTransModel::with(['tindakan', 'transbmhp.bmhp'])
            ->where('notrans', $noReg)
        // ->whereDate('created_at', $tanggal)
            ->get();
        // return $tindakans;

        $tindakanList = [];
        foreach ($tindakans as $item) {
            $bmhps = [];
            foreach ($item->transbmhp as $trans) {
                $bmhps[] = [
                    'kdBmhp' => $trans->kdBmhp,
                    'qty' => $trans->jml,
                    'bmhp' => $trans->bmhp->nmObat ?? '',
                ];
            }

            $tindakanList[] = [
                'id' => $item->id,
                'notrans' => $item->notrans,
                'norm' => $item->norm,
                'kdTind' => $item->kdTind,
                'tindakan' => $item->tindakan->nmTindakan ?? '',
                'bmhps' => $bmhps,
            ];
        }

        return response()->json([
            'tindakan' => $tindakanList,
            'obats' => $obats,
            'cppt' => $cppt,
        ]);
    }

    public function panggil4(Request $request)
    {
        $log_id = $request->input('log_id');
        $norm = $request->input('norm');
        $notrans = $request->input('notrans');
        // session()->forget('cookie_farmasi');
        $cookie = session('cookie_farmasi'); // Retrieve the cookie from the session
        // dd($cookie);

        if (!$cookie) {
            $cookie = $this->loginAndStoreCookie();
            if (!$cookie) {
                return response()->json(['message' => 'Login gagal'], 401);
            }
        }
        // dd($cookie);

        $url = env('BASR_URL_KOMINFO', '') . '/loket_farmasi/panggil';

        try {
            $panggil = $this->sendRequest($url, $cookie, ['log_id' => $log_id]);
            // atasi jika eror
            if ($panggil->getStatusCode() !== 200) {
                return response()->json(['message' => 'Request gagal'], 500);
            }

            $antrian = $this->antrianFarmasi(now()->toDateString(), $norm, $cookie);
            $log_id = $antrian['log_id'] ?? null;
            if ($antrian === null || $antrian['log_id'] === null) {
                return response()->json(['message' => 'Tidak Ada Antrian di tanggal ' . now()->toDateString()], 404);
            }
            // dd($log_id);
            $pulangkan = $this->pulangkan($log_id, $cookie);
            // dd($pulangkan);
            if ($pulangkan->getStatusCode() !== 200) {
                return response()->json(['message' => 'Request gagal'], 500);
            }
            $waktu = $this->selesaiFarmasi($norm, $notrans);
            return response()->json(['message' => $waktu], 200);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            return response()->json([
                'message' => 'Request gagal',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function panggil(Request $request)
    {
        $log_id = $request->input('log_id');
        $cookie = session('cookie_farmasi'); // Retrieve the cookie from the session
        // dd($cookie);

        if (!$cookie) {
            $cookie = $this->loginAndStoreCookie();
            if (!$cookie) {
                return response()->json(['message' => 'Login gagal'], 401);
            }
        }

        // dd($cookie);

        $url = env('BASR_URL_KOMINFO', '') . '/loket_farmasi/panggil';

        try {
            $panggil = $this->sendRequest($url, $cookie, ['log_id' => $log_id]);
            // atasi jika eror
            if ($panggil->getStatusCode() !== 200) {
                return response()->json(['message' => 'Request gagal'], 500);
            }

            return response()->json($panggil, 200, [], JSON_PRETTY_PRINT);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            return response()->json([
                'message' => 'Request gagal',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function pulangkan(Request $request)
    {
        $cookie = session('cookie_farmasi'); // Retrieve the cookie from the session
        if (!$cookie) {
            $cookie = $this->loginAndStoreCookie();
            if (!$cookie) {
                return response()->json(['message' => 'Login gagal'], 401);
            }
        }
        // dd($cookie);
        $log_id = $request->input('log_id');
        $norm = $request->input('norm');
        $notrans = $request->input('notrans');
        $url = env('BASR_URL_KOMINFO', '') . '/loket_farmasi/selesai';

        try {
            $response = $this->sendRequest($url, $cookie, [
                'log_id' => $log_id,
                'ruang_id_selanjutnya' => 'Pulang',
            ]);

            if ($response->getStatusCode() !== 200) {
                return response()->json(['message' => 'Request gagal'], 500);
            }
            $waktu = $this->selesaiFarmasi($norm, $notrans);
            $resKominfo = json_decode($response->getBody(), true);
            $msg = [
                'message' => $waktu,
                'data' => $resKominfo,
            ];
            return response()->json($msg, 200, [], JSON_PRETTY_PRINT);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            return response()->json([
                'message' => 'Request gagal',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function selesaiFarmasi($norm, $notrans)
    {
        try {
            DB::beginTransaction();

            // Cari entri dengan notrans yang diberikan
            $data = KunjunganWaktuSelesai::where('notrans', $notrans)->first();

            if ($data) {
                // Jika entri sudah ada, perbarui kolom updated_at
                $data->waktu_selesai_farmasi = now();
            } else {
                // Jika entri belum ada, buat entri baru
                $data = new KunjunganWaktuSelesai;
                $data->norm = $norm;
                $data->notrans = $notrans;
                $data->waktu_selesai_farmasi = now();

            }

            $data->save();

            $now = date('Y-m-d H:i:s');

            $msg = "Pasien No RM: " . $norm . "Berhasil dipulangkan pukul: " . $now;

            DB::commit();

            return $msg;
        } catch (\Exception $e) {
            DB::rollback(); // Rollback transaksi jika terjadi kesalahan
            Log::error('Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat memulangkan pasien. Data: ' . $e->getMessage()], 500);
        }
    }

    // private function antrianFarmasi($tgl, $norm = null, $cookie = null)
    private function antrianFarmasi($tgl, $cookie = null)
    {
        $tgl = $tgl ?? now()->toDateString();
        $model = new KominfoModel();

        $daftarTunggu = $model->getTungguFaramsi($tgl, $cookie);
        $lists = $daftarTunggu['data'];

        if (empty($lists)) {
            return response()->json(['message' => 'Tidak Ada Antrian di tanggal ' . $tgl], 404);
        }

        return $lists;
        dd($lists);

        // $pasien = array_filter($lists, function ($list) use ($norm) {
        //     return $list['pasien_no_rm'] === $norm;
        // });

        // return array_values($pasien)[0] ?? null;
    }

    private function loginAndStoreCookie()
    {
        $model = new KominfoModel();
        $loginResponse = $model->login(197609262011012003, env('PASSWORD_KOMINFO', ''));

        $cookieFar = $loginResponse['cookies'][0] ?? null;
        if ($cookieFar) {
            session(['cookie_farmasi' => $cookieFar]);
        }

        return $cookieFar;
    }

    private function sendRequest($url, $cookie, $params)
    {
        $client = new \GuzzleHttp\Client();

        return $client->request('POST', $url, [
            'form_params' => $params,
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Cookie' => $cookie,
            ],
        ]);
    }

    public function riwayatFarmasi(Request $request)
    {
        $norm = $request->input('norm');

        $daftariwayat = KunjunganModel::with([
            'riwayatFarmasi', 'riwayatFarmasi.obat', 'riwayatTindakan.transbmhp.bmhp',
        ])
            ->where('norm', $norm)
            ->orderBy('tgltrans', 'desc')
            ->get();
        $res = [];
        foreach ($daftariwayat as $d) {
            $res[] = [
                "notrans" => $d["notrans"] ?? "null",
                "norm" => $d["norm"] ?? "null",
                "nourut" => $d["nourut"] ?? "null",
                "noasuransi" => $d["noasuransi"] ?? "null",
                "layanan" => $d["kelompok"]["kelompok"] ?? "null",
                "biaya" => $d["kelompok"]["biaya"] ?? "null",
                "noktp" => $d["biodata"]["noktp"] ?? "null",
                "namapasien" => $d["biodata"]["nama"] ?? "null",
                "alamatpasien" => $d["biodata"]["alamat"] ?? "null",
                "rtrwpasien" => $d["biodata"]["rtrw"] ?? "null",
                "kelaminpasien" => $d["biodata"]["jeniskel"] ?? "null",
                "tgllahir" => $d["biodata"]["tgllahir"] ?? "null",
            ];
        }
        if ($daftariwayat->isEmpty()) {
            // Handle the case where no records are found
            return response()->json(['error' => 'Patient not found'], 404);
        } else {
            return response()->json($res, 200, [], JSON_PRETTY_PRINT);
            // return response()->json($daftariwayat, 200, [], JSON_PRETTY_PRINT);
        }
    }

    public function datatransaksi(Request $request)
    {
        $notrans = $request->input('notrans');
        $norm = $request->input('norm');
        $tgl = $request->input('tgl');

        $datatransaksi = FarmasiModel::with(['obat', 'petugasPegawai', 'dokterPegawai'])
            ->where('notrans', 'LIKE', '%' . $notrans . '%')
            ->where('norm', 'LIKE', '%' . $norm . '%')
            ->whereDate('created_at', 'LIKE', '%' . $tgl . '%')
            ->get();

        // Ubah struktur respons JSON sesuai kebutuhan
        $formattedData = [];
        foreach ($datatransaksi as $transaksi) {
            $transaksi['tglTrans'] = $transaksi->created_at->format('d-m-Y');
            $formattedData[] = [
                'idAptk' => $transaksi->idAptk,
                'notrans' => $transaksi->notrans,
                'norm' => $transaksi->norm,
                'qty' => $transaksi->jumlah,
                'total' => $transaksi->total,
                'idObat' => $transaksi->product_id,
                'product_id' => $transaksi->obat->product_id,
                'nmObat' => $transaksi->obat->nmObat,
                'petugas' => $transaksi->petugasPegawai->gelar_d . ' ' . $transaksi->petugasPegawai->nama . ' ' . $transaksi->petugasPegawai->gelar_b,
                'dokter' => $transaksi->dokterPegawai->gelar_d . ' ' . $transaksi->dokterPegawai->nama . ' ' . $transaksi->dokterPegawai->gelar_b,
                'tglTrans' => $transaksi->tglTrans,
            ];
        }

        return response()->json($formattedData, 200, [], JSON_PRETTY_PRINT);
    }
    public function cariTotalBmhp(Request $request)
    {
        $notrans = $request->input('notrans');
        // dd($idTind);
        $data = TransaksiBMHPModel::with(['bmhp', 'tindakan'])
            ->where('notrans', 'LIKE', '%' . $notrans . '%')
            ->get();
        $res = [];
        foreach ($data as $item) {
            $item['norm'] = substr($item->notrans, 0, 6);
            $item['tglTrans'] = $item->created_at->format('d-m-Y');

            $res[] = [
                "id" => $item->id,
                "notrans" => $item->notrans,
                "norm" => $item->norm,
                "tgltrans" => $item->tgltrans,
                "tgl_lahir" => $item->tgl_lahir,
                "umur" => $item->umur,
                "gender" => $item->gender,
                "alamat" => $item->alamat,
                "nohp" => $item->nohp,
                "tindakan" => $item->tindakan->nmTindakan,
                "biaya" => $item->biaya,
                "total" => $item->total,
            ];
        }
        // return response()->json($res, 200, [], JSON_PRETTY_PRINT);
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
    public function simpanFarmasi(Request $request)
    {
        // Mengambil nilai dari input pengguna
        $notrans = $request->input('notrans');
        $norm = $request->input('norm');
        $idFarmasi = $request->input('idFarmasi');
        $idObat = $request->input('product_id');
        $qty = $request->input('qty');
        $total = $request->input('total');
        $petugas = $request->input('petugas');
        $dokter = $request->input('dokter');
        $created_at = Carbon::now()->toDateString();

        // Pastikan $kdTind memiliki nilai yang valid sebelum menyimpan data
        if ($idFarmasi !== null) {
            // Membuat instance dari model KunjunganTindakan
            $kunjunganFarmasi = new FarmasiModel();
            // Mengatur nilai-nilai kolom
            $kunjunganFarmasi->notrans = $notrans;
            $kunjunganFarmasi->norm = $norm;
            $kunjunganFarmasi->product_id = $idFarmasi;
            $kunjunganFarmasi->jumlah = $qty;
            $kunjunganFarmasi->total = $total;
            $kunjunganFarmasi->petugas = $petugas;
            $kunjunganFarmasi->dokter = $dokter;
            $kunjunganFarmasi->created_at = $created_at;
            // $kunjunganFarmasi->updated_at = $updated_at;

            // Simpan data ke dalam tabel
            $kunjunganFarmasi->save();

            // Memanggil fungsi updateKeluar untuk mengupdate stok keluar
            $this->updateStokFarmasi($idFarmasi, $idObat, $qty, $request->all());

            // Respon sukses atau redirect ke halaman lain
            return response()->json(['message' => 'Data berhasil disimpan']);
        } else {
            // Handle case when $kdObat is null, misalnya kirim respon error
            return response()->json(['message' => 'Obat tidak valid'], 400);
        }
    }

    private function updateStokFarmasi($idFarmasi, $idObat, $qty, $requestData)
    {
        // dd($idGudang);
        $updatKeluarFarmasi = GudangFarmasiModel::where('product_id', $idObat)->first();
        // dd($updatKeluarFarmasi);
        if ($updatKeluarFarmasi) {
            $updatKeluarFarmasi->update([
                'keluar' => $updatKeluarFarmasi->keluar + $qty,
                'sisa' => $this->calculateSisa($updatKeluarFarmasi->stokBaru, $updatKeluarFarmasi->masuk, $updatKeluarFarmasi->keluar + $qty),
            ]);
        } else {
            return response()->json(['message' => 'Obat tidak valid'], 400);
        }

        $updateKeluar = GudangFarmasiInStokModel::where('id', $idFarmasi)->first();
        // dd($updateKeluar);
        if ($updateKeluar) {
            $updateKeluar->update([
                'keluar' => $updateKeluar->keluar + $qty,
                'sisa' => $this->calculateSisa($updateKeluar->stokBaru, $updateKeluar->masuk, $updateKeluar->keluar + $qty),
            ]);
        } else {
            return response()->json(['message' => 'Obat tidak valid'], 400);
        }
    }
    private function calculateSisa($stokBaru, $masuk, $keluar)
    {
        return $stokBaru + $masuk - $keluar;
    }

    public function deleteFarmasi(Request $request)
    {
        $idAptk = $request->input('idAptk');

        $farmasi = FarmasiModel::find($idAptk);
        // dd($farmasi);
        if ($farmasi) {
            // Mengambil nilai jumlah yang akan dihapus dari transaksi farmasi
            $jumlahDihapus = $farmasi->jumlah;
            $product_id = $farmasi->product_id;
            $gudangFarmasiIn = GudangFarmasiInStokModel::where('id', $product_id)->first();
            $idGudang = $gudangFarmasiIn->product_id;
            // dd($idGudang);
            $gudangFarmasi = GudangFarmasiModel::where('product_id', $idGudang)->first();
            // dd($gudangFarmasi);
            //update stok farmasi
            if ($gudangFarmasi) {
                $gudangFarmasi->update([
                    'keluar' => $gudangFarmasi->keluar - $jumlahDihapus,
                    'sisa' => $this->calculateSisa($gudangFarmasi->stokBaru, $gudangFarmasi->masuk, $gudangFarmasi->keluar - $jumlahDihapus),
                ]);
            } else {
                return response()->json(['message' => 'Obat tidak valid'], 400);
            }
            //update farmasi in stok model
            if ($gudangFarmasiIn) {
                $gudangFarmasiIn->update([
                    'keluar' => intval($gudangFarmasiIn->keluar) - intval($jumlahDihapus),

                    'sisa' => $this->calculateSisa($gudangFarmasiIn->stokBaru, $gudangFarmasiIn->masuk, intval($gudangFarmasiIn->keluar) - intval($jumlahDihapus)),
                ]);
            } else {
                return response()->json(['message' => 'Obat tidak valid'], 400);
            }

            $farmasi->delete();

            // Respon sukses
            return response()->json(['message' => 'Data transaksi obat berhasil dihapus']);
        } else {
            // Handle case when $kdTind is null, misalnya kirim respon error
            return response()->json(['message' => 'Transaksi apotik dengan iID tersebut tidak ada'], 400);
        }
    }
    public function editFarmasi(Request $request)
    {
        $idAptk = $request->input('idAptk');

        $notrans = $request->input('notrans');
        $norm = $request->input('norm');
        $idFarmasi = $request->input('idFarmasi');
        $idObat = $request->input('product_id');
        $qty = $request->input('qty');
        $total = $request->input('total');
        $petugas = $request->input('petugas');
        $dokter = $request->input('dokter');
        $updated_at = Carbon::now()->toDateString();

        $farmasi = FarmasiModel::find($idAptk);
        // dd($farmasi);
        if ($farmasi) {
            // Mengambil nilai jumlah yang akan dihapus dari transaksi farmasi
            $qtyup = $farmasi->jumlah;
            $qtyupdate = intval($qtyup) - intval($qty);
            // dd($qtyupdate);
            $farmasi->update(['jumlah' => $qty, 'total' => $total]);
            $product_id = $farmasi->product_id;

            $gudangFarmasiIn = GudangFarmasiInStokModel::where('id', $product_id)->first();
            $idGudang = $gudangFarmasiIn->product_id;
            $gudangFarmasi = GudangFarmasiModel::where('product_id', $idGudang)->first();

            //update stok farmasi
            if ($gudangFarmasi) {
                $gudangFarmasi->update([
                    'keluar' => $gudangFarmasi->keluar - $qtyupdate,
                    'sisa' => $this->calculateSisa($gudangFarmasi->stokBaru, $gudangFarmasi->masuk, $gudangFarmasi->keluar - $qtyupdate),
                ]);
            } else {
                return response()->json(['message' => 'Obat tidak valid'], 400);
            }

            //update farmasi in stok model
            if ($gudangFarmasiIn) {
                $gudangFarmasiIn->update([
                    'keluar' => intval($gudangFarmasiIn->keluar) - intval($qtyupdate),

                    'sisa' => $this->calculateSisa($gudangFarmasiIn->stokBaru, $gudangFarmasiIn->masuk, intval($gudangFarmasiIn->keluar) - intval($qtyupdate)),
                ]);
            } else {
                return response()->json(['message' => 'Obat tidak valid'], 400);
            }

            // Respon sukses
            return response()->json(['message' => 'Data transaksi obat berhasil diupdate']);
        } else {
            // Handle case when $kdTind is null, misalnya kirim respon error
            return response()->json(['message' => 'Transaksi apotik dengan iID tersebut tidak ada'], 400);
        }
    }

    public function updateStokAwal()
    {
        // Mengambil semua data dari GudangFarmasiModel
        $gudangData = GudangFarmasiModel::all();

        // Mulai transaksi database untuk memastikan keamanan data
        DB::beginTransaction();

        try {
            // Iterasi melalui setiap baris data dan memperbarui stok_awal dan mengosongkan masuk, keluar, stok_akhir
            foreach ($gudangData as $gudangRow) {
                $gudangRow->update([
                    'stok_awal' => $gudangRow->stok_akhir,
                    'masuk' => 0,
                    'keluar' => 0,
                    'stok_akhir' => null,
                ]);
            }

            // Commit transaksi jika berhasil
            DB::commit();

            return response()->json(['message' => 'Data stok berhasil diperbarui'], 200);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            return response()->json(['message' => 'Terjadi kesalahan saat memperbarui data stok'], 500);
        }
    }

    public function stokOpnameFarmasi()
    {
        // Mengambil semua data dari GudangFarmasiModel
        $gudangData = GudangFarmasiModel::on('mysql')->get();

        // Mulai transaksi database untuk memastikan keamanan data
        DB::beginTransaction();

        try {
            // Mengubah data ke dalam bentuk array
            $dataToInsert = $gudangData->map(function ($row) {
                return [
                    'product_id' => $row->product_id,
                    'idObat' => $row->idObat,
                    'nmObat' => $row->nmObat,
                    'jenis' => $row->jenis,
                    'pabrikan' => $row->pabrikan,
                    'sediaan' => $row->sediaan,
                    'sumber' => $row->sumber,
                    'supplier' => $row->supplier,
                    'tglPembelian' => $row->tglPembelian,
                    'stok_awal' => $row->stok_awal,
                    'masuk' => $row->masuk,
                    'keluar' => $row->keluar,
                    'stok_akhir' => $row->stok_akhir,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

            // Menjalankan operasi bulk insert
            LogGudangFarmasiModel::insert($dataToInsert);

            // Opsional: Hapus data dari GudangFarmasiModel setelah pemindahan
            // GudangFarmasiModel::truncate();

            // Commit transaksi jika berhasil
            DB::commit();

            return response()->json(['message' => 'Data berhasil dipindahkan ke LogStokFarmasiModel'], 200);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            return response()->json(['message' => 'Terjadi kesalahan saat memindahkan data'], 500);
        }
    }
}
