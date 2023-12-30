<?php

namespace App\Http\Controllers;

use App\Models\KunjunganModel;
use App\Models\FarmasiModel;
use App\Models\GudangFarmasiInStokModel;
use App\Models\GudangFarmasiModel;
use App\Models\TransaksiBMHPModel;
use App\Models\LogGudangFarmasiModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FarmasiController extends Controller
{
    //fungsi Farmasi
    public function index(Request $request)
    {
        $date = $request->input('date', Carbon::now()->toDateString());

        $data = KunjunganModel::with(['biodata', 'kelompok', 'poli', 'tindakan', 'farmasi',  'petugas.pegawai'])
            ->whereDate('tgltrans', $date)
            ->whereHas('poli', function ($query) {
                $query->whereNotNull('notrans');
            })
            ->get();
        $formattedData = [];
        foreach ($data as $transaksi) {

            if (isset($transaksi["farmasi"]) && isset($transaksi["farmasi"]["idAptk"]) && $transaksi["farmasi"]["idAptk"] !== null) {
                $status = "sudah";
            } else {
                $status = "belum";
            }

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
                "notrans" => $transaksi["notrans"] ?? "null",
                "norm" => $transaksi["norm"] ?? "null",
                "nourut" => $transaksi["nourut"] ?? "null",
                "noasuransi" => $transaksi["noasuransi"] ?? "null",
                "layanan" => $transaksi["kelompok"]["kelompok"] ?? "null",
                "biaya" => $transaksi["kelompok"]["biaya"] ?? "null",
                "noktp" => $transaksi["biodata"]["noktp"] ?? "null",
                "pang" => $pang ?? "null",
                "namapasien" => $transaksi["biodata"]["nama"] ?? "null",
                "alamatpasien" => $transaksi["biodata"]["alamat"] ?? "null",
                "rtrwpasien" => $transaksi["biodata"]["rtrw"] ?? "null",
                "kelaminpasien" => $transaksi["biodata"]["jeniskel"] ?? "null",
                "tgllahir" => $transaksi["biodata"]["tgllahir"] ?? "null",
                "umurpasien" => $transaksi["biodata"]["umur"] ?? "null",
                "nohppasien" => $transaksi["biodata"]["nohp"] ?? "null",
                "provinsi" => $transaksi["biodata"]["provinsi"] ?? "null",
                "kabupaten" => $transaksi["biodata"]["kabupaten"] ?? "null",
                "kecamatan" => $transaksi["biodata"]["kecamatan"] ?? "null",
                "kelurahan" => $transaksi["biodata"]["kelurahan"] ?? "null",
                "rtrw" => $transaksi["biodata"]["rtrw"] ?? "null",
                "agama" => $transaksi["biodata"]["agama"] ?? "null",
                "pendidikan" => $transaksi["biodata"]["pendidikan"] ?? "null",

                "status" => $status,

                "tgltrans" => $transaksi["poli"]["tgltrans"] ?? "null",
                "rontgen" => $transaksi["poli"]["rontgen"] ?? "null",
                "konsul" => $transaksi["poli"]["konsul"] ?? "null",
                "tcm" => $transaksi["poli"]["tcm"] ?? "null",
                "bta" => $transaksi["poli"]["bta"] ?? "null",
                "hematologi" => $transaksi["poli"]["hematologi"] ?? "null",
                "kimiaDarah" => $transaksi["poli"]["kimiaDarah"] ?? "null",
                "imunoSerologi" => $transaksi["poli"]["imunoSerologi"] ?? "null",
                "mantoux" => $transaksi["poli"]["mantoux"] ?? "null",
                "ekg" => $transaksi["poli"]["ekg"] ?? "null",
                "mikroCo" => $transaksi["poli"]["mikroCo"] ?? "null",
                "spirometri" => $transaksi["poli"]["spirometri"] ?? "null",
                "spo2" => $transaksi["poli"]["spo2"] ?? "null",
                "diagnosa1" => $transaksi["poli"]["diagnosa1"] ?? "null",
                "diagnosa2" => $transaksi["poli"]["diagnosa2"] ?? "null",
                "diagnosa3" => $transaksi["poli"]["diagnosa3"] ?? "null",
                "nebulizer" => $transaksi["poli"]["nebulizer"] ?? "null",
                "infus" => $transaksi["poli"]["infus"] ?? "null",
                "oksigenasi" => $transaksi["poli"]["oksigenasi"] ?? "null",
                "injeksi" => $transaksi["poli"]["injeksi"] ?? "null",
                "terapi" => $transaksi["poli"]["terapi"] ?? "null",
                "dokterpoli" => ($transaksi["petugas"]["pegawai"]["gelar_d"] ?? "null") . ' ' . ($transaksi["petugas"]["pegawai"]["nama"] ?? "null") . ' ' . ($transaksi["petugas"]["pegawai"]["gelar_b"] ?? "null"),
                "kddokter" => $transaksi["petugas"]["pegawai"]["nip"],
                "idtindakan" => $transaksi["tindakan"]["id"] ?? "null",
                "kdTind" => $transaksi["tindakan"]["kdTind"] ?? "null",
                "petugastindakan" => $transaksi["tindakan"]["petugas"] ?? "null",
                "doktertindakan" => $transaksi["tindakan"]["dokter"] ?? "null",
                "jabatan" => $transaksi["petugas"]["pegawai"]["nm_jabatan"] ?? "null",
                "farmasi" => $transaksi["farmasi"] ?? "null",

            ];
        }

        return response()->json($formattedData, 200, [], JSON_PRETTY_PRINT);
        // return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
    public function riwayatFarmasi(Request $request)
    {
        $norm = $request->input('norm');

        $daftariwayat = KunjunganModel::with([
            'riwayatFarmasi', 'riwayatFarmasi.obat',  'riwayatTindakan.transbmhp.bmhp'
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

        $datatransaksi = FarmasiModel::with(['obat',  'petugasPegawai', 'dokterPegawai'])
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
        $data = TransaksiBMHPModel::with(['bmhp', 'tindakan', 'tindakan.petugasPegawai', 'tindakan.dokterPegawai',])
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
                // "dokter" => $item->dokterPegawai->gelar_d . ' ' . $item->dokterPegawai->nama . ' ' . $item->dokterPegawai->gelar_b,
                // "petugas" => $item->tindakan->petugasPegawai->gelar_d . ' ' . $item->tindakan->petugasPegawai->nama . ' ' . $item->tindakan->petugasPegawai->gelar_b,
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
