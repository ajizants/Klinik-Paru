<?php
namespace App\Http\Controllers;

use App\Models\KasirPenutupanKasModel;
use App\Models\KasirSetoranModel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KasirPenutupanKasController extends Controller
{
    public function data(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $res = $this->dataPenutupan([
            'bulan' => $bulan,
            'tahun' => $tahun,
            'semua' => $tahun == "all" ? true : false,
        ]);

        return $res;
    }
    private function dataPenutupan($params)
    {
        $tahun = $params['tahun'];
        $bulan = $params['bulan'];
        $totalPendapatan = 0;
        $totalPengeluaran = 0;
        $saldo_bku = 0;

        $model = new KasirSetoranModel();
        $penerimaan = $model->penerimaan($tahun, $bulan);
        $pengeluaran = $model->pengeluaran($tahun, $bulan);
        // dd($pengeluaran);

        if ($params['semua'] == true) {
            $dataPenutupan = KasirPenutupanKasModel::all();
        } else {
            $dataPenutupan = KasirPenutupanKasModel::whereYear('tanggal_sekarang', $tahun)
                ->whereMonth('tanggal_sekarang', $bulan)
                ->get();
        }

        foreach ($penerimaan as $d) {
            $totalPendapatan += $d->pendapatan ?? 0;
        }
        foreach ($pengeluaran as $d) {
            $totalPengeluaran += $d->setoran ?? 0;
        }

        $saldo_bku = $totalPendapatan - $totalPengeluaran;

        return [
            'total_penerimaan' => $totalPendapatan,
            'total_pengeluaran' => $totalPengeluaran,
            'saldo_bku' => $saldo_bku,
            'penerimaan' => $penerimaan,
            'pengeluaran' => $pengeluaran,
            'data' => $dataPenutupan,
        ];
    }

    public function cetakRegPenutupan($id, $tanggal)
    {
        $title = 'Reg Penutupan Kas';
        $tahun = Carbon::parse($tanggal)->format('Y');
        $bulan = Carbon::parse($tanggal)->format('m');
        $params = [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'semua' => false,
        ];

        $data = $this->dataPenutupan($params);
        $bulanTahun = Carbon::parse($tanggal)->format('Y-m');

        $res = $data['data']->where('id', $id)->first();

        if (!$res) {
            abort(404, 'Data penutupan kas tidak ditemukan.');
        }

        $res = $this->prosesJumlah($res);
        // return $res;

        $blnTahun = Carbon::create($tahun, $bulan)->isoFormat('MMMM YYYY');
        $blnTahunCompare = Carbon::create('2025', '05')->isoFormat('MMMM YYYY');
        if ($blnTahun < $blnTahunCompare) {
            $kepala = 'dr. RENDI RETISSU';
            $nipKepala = '198810162019021002';
        } else {
            $kepala = 'dr. ANWAR HUDIONO, M.P.H.';
            $nipKepala = '198212242010011022';
        }
//         198212242010011022
// dr. ANWAR HUDIONO, M.P.H.
        $tglAkhir = \Carbon\Carbon::create($tahun, $bulan, 1)->lastOfMonth()->locale('id')->isoFormat('DD MMMM YYYY');

        return view('Laporan.Kasir.Cetak.cetakPenutupanKas', compact('res', 'title', 'kepala', 'nipKepala', 'blnTahun', 'tglAkhir'));
    }

    public function cetakRegTupan($bulan, $tahun)
    {
        $title = 'Reg Penutupan Kas';
        $params = [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'semua' => false,
        ];
        $data = $this->dataPenutupan($params);

        $bulanTahun = $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT);

        $res = $data['data']->filter(function ($item) use ($bulanTahun) {
            $itemBulanTahun = Carbon::parse($item->tanggal_sekarang)->format('Y-m');
            return $itemBulanTahun == $bulanTahun;
        })->first();

        if (!$res) {
            abort(404, 'Data penutupan kas tidak ditemukan.');
        }

        $res = $this->prosesJumlah($res);

        return view('Laporan.Kasir.cetakPenutupanKas', compact('res', 'title'));
    }

    private function prosesJumlah($res)
    {
        if (!$res) {
            return null;
        }

        $res['jml_kertas100k'] = ($res['kertas100k'] ?? 0) * 100000;
        $res['jml_kertas50k'] = ($res['kertas50k'] ?? 0) * 50000;
        $res['jml_kertas20k'] = ($res['kertas20k'] ?? 0) * 20000;
        $res['jml_kertas10k'] = ($res['kertas10k'] ?? 0) * 10000;
        $res['jml_kertas5k'] = ($res['kertas5k'] ?? 0) * 5000;
        $res['jml_kertas2k'] = ($res['kertas2k'] ?? 0) * 2000;
        $res['jml_kertas1k'] = ($res['kertas1k'] ?? 0) * 1000;
        $res['jml_logam1k'] = ($res['logam1k'] ?? 0) * 1000;
        $res['jml_logam500'] = ($res['logam500'] ?? 0) * 500;
        $res['jml_logam200'] = ($res['logam200'] ?? 0) * 200;
        $res['jml_logam100'] = ($res['logam100'] ?? 0) * 100;

        $res['jumlah'] = $res['jml_kertas100k'] +
            $res['jml_kertas50k'] +
            $res['jml_kertas20k'] +
            $res['jml_kertas10k'] +
            $res['jml_kertas5k'] +
            $res['jml_kertas2k'] +
            $res['jml_kertas1k'] +
            $res['jml_logam1k'] +
            $res['jml_logam500'] +
            $res['jml_logam200'] +
            $res['jml_logam100'];

        return $res;
    }

    public function store(Request $request)
    {
        // Validasi data input
        $validatedData = $request->validate([
            'tanggal_sekarang' => 'required|date',
            'tanggal_lalu' => 'required|date',
            'petugas' => 'required|string|max:255',
            'total_penerimaan' => 'required|string', // To handle formatted currency input
            'total_pengeluaran' => 'required|string', // To handle formatted currency input
            'saldo_bku' => 'required|string', // To handle formatted currency input
            'saldo_kas' => 'required|string', // To handle formatted currency input
            'selisih_saldo' => 'required|string', // To handle formatted currency input
            'kertas100k' => 'nullable|integer',
            'kertas50k' => 'nullable|integer',
            'kertas20k' => 'nullable|integer',
            'kertas10k' => 'nullable|integer',
            'kertas5k' => 'nullable|integer',
            'kertas2k' => 'nullable|integer',
            'kertas1k' => 'nullable|integer',
            'logam1k' => 'nullable|integer',
            'logam500' => 'nullable|integer',
            'logam200' => 'nullable|integer',
            'logam100' => 'nullable|integer',
        ]);

        // Convert formatted currency values to numbers
        $total_penerimaan = $this->convertCurrencyToNumber($request->input('total_penerimaan'));
        $total_pengeluaran = $this->convertCurrencyToNumber($request->input('total_pengeluaran'));
        $saldo_bku = $this->convertCurrencyToNumber($request->input('saldo_bku'));
        $saldo_kas = $this->convertCurrencyToNumber($request->input('saldo_kas'));
        $selisih_saldo = abs($saldo_bku - $saldo_kas);
        try {
            // Simpan data ke database
            $model = new KasirPenutupanKasModel();

            // Fill the validated data
            $model->fill($validatedData);

            // Assign converted currency values
            $model->total_penerimaan = $total_penerimaan;
            $model->total_pengeluaran = $total_pengeluaran;
            $model->saldo_bku = $saldo_bku;
            $model->saldo_kas = $saldo_kas;
            $model->selisih_saldo = $selisih_saldo;

            // Save the model
            $model->save();
            $data = $model->all();
            // Kembalikan respons sukses
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil disimpan.',
                'data' => $data,
            ], 201);
        } catch (\Exception $e) {
            // Handle exception if something goes wrong
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Helper function to convert currency formatted strings to numbers
    private function convertCurrencyToNumber($currency)
    {
        // Remove 'Rp.' and replace dots (thousand separators) with nothing
        return floatval(str_replace(['Rp.', '.', ','], '', $currency));
    }

    public function update(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'id' => 'required|exists:t_kasir_penutupanKas,id',
            'tanggal_sekarang' => 'required|date',
            'tanggal_lalu' => 'required|date',
            'petugas' => 'required|string|max:255',
            'total_penerimaan' => 'required|numeric',
            'total_pengeluaran' => 'required|numeric',
            'saldo_bku' => 'required|numeric',
            'saldo_kas' => 'required|numeric',
            'selisih_saldo' => 'required|numeric',
            'kertas100k' => 'nullable|integer',
            'kertas50k' => 'nullable|integer',
            'kertas20k' => 'nullable|integer',
            'kertas10k' => 'nullable|integer',
            'kertas5k' => 'nullable|integer',
            'kertas2k' => 'nullable|integer',
            'kertas1k' => 'nullable|integer',
            'logam1k' => 'nullable|integer',
            'logam500' => 'nullable|integer',
            'logam200' => 'nullable|integer',
            'logam100' => 'nullable|integer',
        ]);

        try {
            // Cari data berdasarkan ID
            $model = KasirPenutupanKasModel::findOrFail($validatedData['id']);

            // Update data
            $model->update($validatedData);

            // Kembalikan respons sukses
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diperbarui.',
                'data' => $model,
            ], 200);
        } catch (\Exception $e) {
            // Tangani kesalahan dan kembalikan respons gagal
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'id' => 'required|exists:t_kasir_penutupanKas,id',
        ]);
        $tahun = date('Y');
        // return $validatedData;
        try {
            // Cari data berdasarkan ID
            $model = KasirPenutupanKasModel::findOrFail($validatedData['id']);

            // Hapus data
            $model->delete();
            $data = KasirPenutupanKasModel::where('tanggal_sekarang', 'like', '%' . $tahun . '%')->get();

            // Kembalikan respons sukses
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil dihapus.',
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            // Tangani kesalahan dan kembalikan respons gagal
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
