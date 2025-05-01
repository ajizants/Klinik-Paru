<?php
namespace App\Http\Controllers;

use App\Models\BMHPIGDInStokModel;
use App\Models\BMHPModel;
use App\Models\FarmasiModel;
use App\Models\GudangFarmasiInStokModel;
use App\Models\GudangFarmasiModel;
use App\Models\GudangObatInStokModel;
use App\Models\GudangObatModel;
use App\Models\ObatModel;
use App\Models\PabrikanModel;
use App\Models\SupplierModel;
use Illuminate\Http\Request;

class GudangFarmasiController extends Controller
{

    public function updateMasuk(Request $request, $product_id)
    {
        // Mengambil data GudangFarmasiModel berdasarkan product_id
        $gudangFarmasi = GudangFarmasiModel::find($product_id);

        // Mengambil nilai lama kolom keluar
        $nilaiLamaMasuk = $gudangFarmasi->masuk;

        // Menambahkan nilai baru kolom keluar dari request
        $nilaiBaruMasuk = $request->input('stok');

        // Menghitung stok akhir
        $stokAkhir = $gudangFarmasi->stok_awal + $nilaiLamaMasuk + $nilaiBaruMasuk - $gudangFarmasi->keluar;

        // Update kolom keluar dan stok_akhir
        $gudangFarmasi->masuk      = $nilaiLamaMasuk + $nilaiBaruMasuk;
        $gudangFarmasi->stok_akhir = $stokAkhir;

        // Simpan perubahan ke dalam database
        $gudangFarmasi->save();

        // Mengembalikan response
        return response()->json(['message' => 'Data berhasil diupdate']);
    }

    public function updateKeluar(Request $request, $product_id)
    {
        // Mengambil data GudangFarmasiModel berdasarkan product_id
        $gudangFarmasi = GudangFarmasiModel::find($product_id);

        // Mengambil nilai lama kolom keluar
        $nilaiLamaKeluar = $gudangFarmasi->keluar;

        // Menambahkan nilai baru kolom keluar dari request
        $nilaiBaruKeluar = $request->input('qty');

        // Menghitung stok akhir
        $stokAkhir = $gudangFarmasi->stok_awal + $gudangFarmasi->masuk - $nilaiLamaKeluar - $nilaiBaruKeluar;

        // Update kolom keluar dan stok_akhir
        $gudangFarmasi->keluar     = $nilaiLamaKeluar + $nilaiBaruKeluar;
        $gudangFarmasi->stok_akhir = $stokAkhir;

        // Simpan perubahan ke dalam database
        $gudangFarmasi->save();

        // Mengembalikan response
        return response()->json(['message' => 'Data berhasil diupdate']);
    }
    public function supplier()
    {

        $data = SupplierModel::on('mysql')->get();
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
    public function pabrikan()
    {

        $data = PabrikanModel::on('mysql')->get();
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
    public function gudangFarmasi()
    {
        $data = GudangFarmasiModel::with(['supplier', 'pabrikan'])
            ->get();
        foreach ($data as $transaksi) {
            if ($transaksi["jenis"] !== 1) {
                $nmjenis = "Obat";
            } else {
                $nmjenis = "BMHP";
            }
            $transaksi["nmjenis"] = $nmjenis;
        }
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
    public function gudangFarmasiLimit()
    {
        $limit = 200;
        // dd($limit);
        $data = GudangFarmasiModel::with(['supplier', 'pabrikan'])
            ->where('sisa', '<=', $limit)
            ->get();
        // ->toSql();
        $data = $data->toArray();
        foreach ($data as $transaksi) {
            if ($transaksi["jenis"] !== 1) {
                $nmjenis = "Obat";
            } else {
                $nmjenis = "BMHP";
            }
            $transaksi["nmjenis"] = $nmjenis;
        }
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
    public function gudangFarmasiIn()
    {
        $data = GudangFarmasiInStokModel::with(['supplier', 'pabrikan'])
            ->get();
        foreach ($data as $transaksi) {
            if ($transaksi["jenis"] !== 1) {
                $nmjenis = "Obat";
            } else {
                $nmjenis = "BMHP";
            }
            $transaksi["nmjenis"] = $nmjenis;
        }
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
    public function gudangObatIN()
    {

        $data = gudangObatInStokModel::with(['supplier', 'pabrikan'])
            ->get();
        foreach ($data as $transaksi) {
            if ($transaksi["jenis"] !== 1) {
                $nmjenis = "Obat";
            } else {
                $nmjenis = "BMHP";
            }
            $transaksi["nmjenis"] = $nmjenis;
        }

        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
    public function daftarInObatGudang()
    {

        $data = GudangObatInStokModel::with(['supplier', 'pabrikan'])
            ->whereNotNull('sisa')
            ->where('sisa', '!=', 0)
            ->get();

        foreach ($data as $transaksi) {
            if ($transaksi["jenis"] !== 1) {
                $nmjenis = "Obat";
            } else {
                $nmjenis = "BMHP";
            }
            $transaksi["nmjenis"] = $nmjenis;
        }

        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
    public function daftarGudangObatLimit()
    {

        $data = GudangObatModel::with(['supplier', 'pabrikan'])
            ->where('sisa', '<', 200)
            ->get();

        foreach ($data as $transaksi) {
            if ($transaksi["jenis"] !== 1) {
                $nmjenis = "Obat";
            } else {
                $nmjenis = "BMHP";
            }
            $transaksi["nmjenis"] = $nmjenis;
        }

        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
    public function daftarGudangObat()
    {

        $data = GudangObatModel::with(['supplier', 'pabrikan'])
            ->get();
        foreach ($data as $transaksi) {
            if ($transaksi["jenis"] !== 1) {
                $nmjenis = "Obat";
            } else {
                $nmjenis = "BMHP";
            }
            $transaksi["nmjenis"] = $nmjenis;
        }

        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
    public function gudangIGD()
    {

        $data = BMHPIGDInStokModel::with(['supplier', 'pabrikan'])
            ->get();
        foreach ($data as $transaksi) {
            if ($transaksi["jenis"] !== 1) {
                $nmjenis = "Obat";
            } else {
                $nmjenis = "BMHP";
            }
            $transaksi["nmjenis"] = $nmjenis;
        }

        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
    public function namaObat()
    {
        $data = ObatModel::on('mysql')
            ->get();
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }

    //igd
    public function addStokIGD(Request $request)
    {
        $idGudang   = $request->input('idGudang');
        $product_id = $request->input('product_id');
        $idObat     = $request->input('idObat');
        $nmObat     = $request->input('nmObat');
        $stok       = $request->input('stok');
        $beli       = $request->input('beli');
        $jual       = $request->input('jual');
        $pabrikan   = $request->input('pabrikan');
        $jenis      = $request->input('jenis');
        $sediaan    = $request->input('sediaan');
        $sumber     = $request->input('sumber');
        $supplier   = $request->input('supplier');
        $tglEd      = $request->input('tglEd');
        $tglBeli    = $request->input('tglBeli');

        if ($idObat !== null) {
            $addStokGudang = new BMHPIGDInStokModel();

            $addStokGudang->product_id   = $product_id;
            $addStokGudang->idObat       = $idObat;
            $addStokGudang->nmObat       = $nmObat;
            $addStokGudang->jenis        = $jenis;
            $addStokGudang->sediaan      = $sediaan;
            $addStokGudang->pabrikan     = $pabrikan;
            $addStokGudang->sumber       = $sumber;
            $addStokGudang->ed           = $tglEd;
            $addStokGudang->tglPembelian = $tglBeli;
            $addStokGudang->supplier     = $supplier;
            $addStokGudang->hargaBeli    = $beli;
            $addStokGudang->HargaJual    = $jual;
            $addStokGudang->stokBaru     = $stok;
            $addStokGudang->keluar       = 0;
            $addStokGudang->sisa         = $stok;

            // Simpan data ke dalam tabel
            $addStokGudang->save();

            $this->updateStokIGD($idObat, $product_id, $idGudang, $stok, $request->all());

            // Respon sukses atau redirect ke halaman lain
            return response()->json(['message' => 'Data berhasil disimpan']);
        } else {
            // Handle case when $kdObat is null, misalnya kirim respon error
            return response()->json(['message' => 'Obat tidak valid'], 400);
        }
    }
    private function updateStokIGD($idObat, $product_id, $idGudang, $stok, $requestData)
    {
        // dd($idGudang);
        $updateMasuk = BMHPModel::where('idObat', $idObat)->first();

        if ($updateMasuk) {
            $updateMasuk->update([
                'masuk' => $updateMasuk->masuk + $stok,
                'sisa'  => $this->calculateSisa($updateMasuk->stokBaru, $updateMasuk->masuk + $stok, $updateMasuk->keluar),
            ]);
        } else {
            BMHPModel::create([
                'product_id'   => $product_id,
                'stok'         => $stok,
                'idObat'       => $requestData['idObat'],
                'nmObat'       => $requestData['nmObat'],
                'jenis'        => $requestData['jenis'],
                'pabrikan'     => $requestData['pabrikan'],
                'sediaan'      => $requestData['sediaan'],
                'sumber'       => $requestData['sumber'],
                'supplier'     => $requestData['supplier'],
                'tglPembelian' => $requestData['tglBeli'],
                'ed'           => $requestData['tglEd'],
                'hargaBeli'    => $requestData['beli'],
                'hargaJual'    => $requestData['jual'],
                'stokBaru'     => $requestData['stok'],
                'masuk'        => 0,                                                // Assuming you want to start with 0 for a new record
                'keluar'       => 0,                                                // Assuming you want to start with 0 for a new record
                'sisa'         => $this->calculateSisa($requestData['stok'], 0, 0), // Calculate sisa for a new record
            ]);
        }

        $updateKeluar = GudangObatModel::where('product_id', $product_id)->first();

        if ($updateKeluar) {
            $updateKeluar->update([
                'keluar' => $updateKeluar->keluar + $stok,
                'sisa'   => $this->calculateSisa($updateKeluar->stokBaru, $updateKeluar->masuk, $updateKeluar->keluar + $stok),
            ]);
        } else {
            return response()->json(['message' => 'Obat tidak valid'], 400);
        }

        $updateKeluarInStok = gudangObatInStokModel::where('id', $idGudang)->first();
        // dd($updateKeluarInStok);
        if ($updateKeluarInStok) {
            $updateKeluarInStok->update([
                'keluar' => $updateKeluarInStok->keluar + $stok,
                'sisa'   => $this->calculateSisa($updateKeluarInStok->stokBaru, $updateKeluarInStok->masuk, $updateKeluarInStok->keluar + $stok),
            ]);
        } else {
            return response()->json(['message' => 'Obat tidak valid'], 400);
        }
    }

    //farmasi
    public function addStokFarmasi(Request $request)
    {
        $idGudang   = $request->input('idGudang');
        $product_id = $request->input('product_id');
        $idObat     = $request->input('idObat');
        $nmObat     = $request->input('nmObat');
        $stok       = $request->input('stok');
        $beli       = $request->input('beli');
        $jual       = $request->input('jual');
        $pabrikan   = $request->input('pabrikan');
        $jenis      = $request->input('jenis');
        $sediaan    = $request->input('sediaan');
        $sumber     = $request->input('sumber');
        $supplier   = $request->input('supplier');
        $tglEd      = $request->input('tglEd');
        $tglBeli    = $request->input('tglBeli');
        // $product_id = substr($nmObat, 0, 3) . $idObat . $pabrikan . $supplier . $sumber . $sediaan;

        // Pastikan $kdTind memiliki nilai yang valid sebelum menyimpan data
        if ($idObat !== null) {
            // Membuat instance dari model KunjunganTindakan
            $addStokGudang = new GudangFarmasiInStokModel();
            // Mengatur nilai-nilai kolom
            $addStokGudang->product_id   = $product_id;
            $addStokGudang->product_id   = $product_id;
            $addStokGudang->idObat       = $idObat;
            $addStokGudang->nmObat       = $nmObat;
            $addStokGudang->jenis        = $jenis;
            $addStokGudang->sediaan      = $sediaan;
            $addStokGudang->pabrikan     = $pabrikan;
            $addStokGudang->sumber       = $sumber;
            $addStokGudang->ed           = $tglEd;
            $addStokGudang->tglPembelian = $tglBeli;
            $addStokGudang->supplier     = $supplier;
            $addStokGudang->hargaBeli    = $beli;
            $addStokGudang->HargaJual    = $jual;
            $addStokGudang->stokBaru     = $stok;
            $addStokGudang->keluar       = 0;
            $addStokGudang->sisa         = $stok;

            // Simpan data ke dalam tabel
            $addStokGudang->save();

            $this->updateStokFarmasi($product_id, $idGudang, $stok, $request->all());

            // Respon sukses atau redirect ke halaman lain
            return response()->json(['message' => 'Data berhasil disimpan']);
        } else {
            // Handle case when $kdObat is null, misalnya kirim respon error
            return response()->json(['message' => 'Obat tidak valid'], 400);
        }
    }
    private function updateStokFarmasi($product_id, $idGudang, $stok, $requestData)
    {
        // dd($idGudang);
        $updateMasuk = GudangFarmasiModel::where('product_id', $product_id)->first();

        if ($updateMasuk) {
            $updateMasuk->update([
                'masuk' => $updateMasuk->masuk + $stok,
                'sisa'  => $this->calculateSisa($updateMasuk->stokBaru, $updateMasuk->masuk + $stok, $updateMasuk->keluar),
            ]);
        } else {
            GudangFarmasiModel::create([
                'product_id'   => $product_id,
                'stok'         => $stok,
                'idObat'       => $requestData['idObat'],
                'nmObat'       => $requestData['nmObat'],
                'jenis'        => $requestData['jenis'],
                'pabrikan'     => $requestData['pabrikan'],
                'sediaan'      => $requestData['sediaan'],
                'sumber'       => $requestData['sumber'],
                'supplier'     => $requestData['supplier'],
                'tglPembelian' => $requestData['tglBeli'],
                'ed'           => $requestData['tglEd'],
                'hargaBeli'    => $requestData['beli'],
                'hargaJual'    => $requestData['jual'],
                'stokBaru'     => $requestData['stok'],
                'masuk'        => 0,                                                // Assuming you want to start with 0 for a new record
                'keluar'       => 0,                                                // Assuming you want to start with 0 for a new record
                'sisa'         => $this->calculateSisa($requestData['stok'], 0, 0), // Calculate sisa for a new record
            ]);
        }

        $updateKeluar = GudangObatModel::where('product_id', $product_id)->first();

        if ($updateKeluar) {
            $updateKeluar->update([
                'keluar' => $updateKeluar->keluar + $stok,
                'sisa'   => $this->calculateSisa($updateKeluar->stokBaru, $updateKeluar->masuk, $updateKeluar->keluar + $stok),
            ]);
        } else {
            return response()->json(['message' => 'Obat tidak valid'], 400);
        }

        $updateKeluarInStok = gudangObatInStokModel::where('id', $idGudang)->first();
        // dd($updateKeluarInStok);
        if ($updateKeluarInStok) {
            $updateKeluarInStok->update([
                'keluar' => $updateKeluarInStok->keluar + $stok,
                'sisa'   => $this->calculateSisa($updateKeluarInStok->stokBaru, $updateKeluarInStok->masuk, $updateKeluarInStok->keluar + $stok),
            ]);
        } else {
            return response()->json(['message' => 'Obat tidak valid'], 400);
        }
    }

    //gudang obat
    public function addBasicObat(Request $request)
    {

        $nmObat = $request->input('nmObat');
        // Pastikan $kdTind memiliki nilai yang valid sebelum menyimpan data
        if ($nmObat !== null) {
            // Membuat instance dari model KunjunganTindakan
            $addBasicObat = new ObatModel();
            // Mengatur nilai-nilai kolom

            $addBasicObat->nmObat = $nmObat;

            // Simpan data ke dalam tabel
            $addBasicObat->save();

            // Respon sukses atau redirect ke halaman lain
            return response()->json(['message' => 'Data berhasil disimpan']);
        } else {
            // Handle case when $kdObat is null, misalnya kirim respon error
            return response()->json(['message' => 'Obat tidak valid'], 400);
        }
    }
    public function addStokGudang(Request $request)
    {

        $idObat     = $request->input('idObat');
        $nmObat     = $request->input('nmObat');
        $stok       = $request->input('stok');
        $beli       = $request->input('beli');
        $jual       = $request->input('jual');
        $pabrikan   = $request->input('pabrikan');
        $jenis      = $request->input('jenis');
        $sediaan    = $request->input('sediaan');
        $sumber     = $request->input('sumber');
        $supplier   = $request->input('supplier');
        $tglEd      = $request->input('tglEd');
        $tglBeli    = $request->input('tglBeli');
        $product_id = substr($nmObat, 0, 3) . str_pad($idObat, 3, '0', STR_PAD_LEFT) . substr($sediaan, 0, 3) . str_pad($pabrikan, 3, '0', STR_PAD_LEFT) . str_pad($supplier, 3, '0', STR_PAD_LEFT) . substr($sumber, 0, 3);
        // dd($product_id);

        // Pastikan $kdTind memiliki nilai yang valid sebelum menyimpan data
        if ($idObat !== null) {
            // Membuat instance dari model KunjunganTindakan
            $addStokGudang = new gudangObatInStokModel();
            // Mengatur nilai-nilai kolom
            $addStokGudang->product_id   = $product_id;
            $addStokGudang->idObat       = $idObat;
            $addStokGudang->nmObat       = $nmObat;
            $addStokGudang->jenis        = $jenis;
            $addStokGudang->sediaan      = $sediaan;
            $addStokGudang->pabrikan     = $pabrikan;
            $addStokGudang->sumber       = $sumber;
            $addStokGudang->ed           = $tglEd;
            $addStokGudang->tglPembelian = $tglBeli;
            $addStokGudang->supplier     = $supplier;
            $addStokGudang->hargaBeli    = $beli;
            $addStokGudang->HargaJual    = $jual;
            $addStokGudang->stokBaru     = $stok;
            $addStokGudang->sisa         = $stok;
            $addStokGudang->keluar       = 0;

            // Simpan data ke dalam tabel
            $addStokGudang->save();

            $this->updateStokGudang($product_id, $stok, $request->all());

            // Respon sukses atau redirect ke halaman lain
            return response()->json(['message' => 'Data berhasil disimpan']);
        } else {
            // Handle case when $kdObat is null, misalnya kirim respon error
            return response()->json(['message' => 'Obat tidak valid'], 400);
        }
    }

    private function updateStokGudang($product_id, $stok, $requestData)
    {
        // Check if the product_id already exists in gudangobatmodel
        $existingRecord = GudangObatModel::where('product_id', $product_id)->first();

        if ($existingRecord) {
            // Update the existing record
            $existingRecord->update([
                'masuk' => $existingRecord->masuk + $stok,
                'sisa'  => $this->calculateSisa($existingRecord->stokBaru, $existingRecord->masuk + $stok, $existingRecord->keluar),
                // Assuming sisa is the column you want to update
            ]);
        } else {
            // Create a new record in gudangobatmodel with specific values
            GudangObatModel::create([
                'product_id'   => $product_id,
                'stok'         => $stok,
                'idObat'       => $requestData['idObat'],
                'nmObat'       => $requestData['nmObat'],
                'jenis'        => $requestData['jenis'],
                'pabrikan'     => $requestData['pabrikan'],
                'sediaan'      => $requestData['sediaan'],
                'sumber'       => $requestData['sumber'],
                'supplier'     => $requestData['supplier'],
                'tglPembelian' => $requestData['tglBeli'],
                'ed'           => $requestData['tglEd'],
                'hargaBeli'    => $requestData['beli'],
                'hargaJual'    => $requestData['jual'],
                'stokBaru'     => $requestData['stok'],
                'masuk'        => 0,     // Assuming you want to start with 0 for a new record
                'keluar'       => 0,     // Assuming you want to start with 0 for a new record
                'sisa'         => $stok, // Calculate sisa for a new record
                                         // 'sisa' => $this->calculateSisa($requestData['stok'], 0, 0), // Calculate sisa for a new record
                                         // Add other columns as needed
            ]);
        }
    }

    private function calculateSisa($stokBaru, $masuk, $keluar)
    {
        // Calculate sisa based on the formula: sisa = stokBaru + masuk - keluar
        return $stokBaru + $masuk - $keluar;
    }

    public function deleteFarmasi(Request $request)
    {
        $idAptk = $request->input('idAptk');

        $farmasi = FarmasiModel::find($idAptk);

        // Hapus tindakan dan BMHP terkait jika ditemukan, jika tidak hapus tindakan saja
        if ($farmasi) {
            // Mengambil nilai jumlah yang akan dihapus dari transaksi farmasi
            $jumlahDihapus = $farmasi->jumlah;

            // Mengambil data GudangFarmasiModel berdasarkan product_id
            $gudangFarmasi = GudangFarmasiModel::find($farmasi->product_id);

            // Mengurangi nilai kolom keluar dengan jumlah yang dihapus dari transaksi
            $gudangFarmasi->keluar -= $jumlahDihapus;

            // Menghitung stok akhir
            $stokAkhir = $gudangFarmasi->stok_awal + $gudangFarmasi->masuk - $gudangFarmasi->keluar;

            // Update kolom stok_akhir
            $gudangFarmasi->stok_akhir = $stokAkhir;

            // Simpan perubahan ke dalam database
            $gudangFarmasi->save();

            $farmasi->delete();

            // Respon sukses
            return response()->json(['message' => 'Data tindakan berhasil dihapus']);
        } else {
            // Handle case when $kdTind is null, misalnya kirim respon error
            return response()->json(['message' => 'id tidak valid'], 400);
        }
    }
}
