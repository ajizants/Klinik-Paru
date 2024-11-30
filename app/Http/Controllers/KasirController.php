<?php

namespace App\Http\Controllers;

use App\Models\IGDTransModel;
use App\Models\KasirAddModel;
use App\Models\KasirTransModel;
use App\Models\KominfoModel;
use App\Models\LaboratoriumHasilModel;
use App\Models\LayananModel;
use App\Models\ROTransaksiModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KasirController extends Controller
{
    public function tagihan(Request $request)
    {
        // dd($request->all());
        $norm = $request->input('norm');
        $tanggal = $request->input('tgl', Carbon::now()->format('Y-m-d'));
        $params = [
            'tanggal_awal' => $tanggal,
            'tanggal_akhir' => $tanggal,
            'no_rm' => $norm ?? '',
        ];
        // dd($params);
        $model = new KominfoModel();
        $data = $model->pendaftaranRequest($params);
        // dd($data);
        $notrans = $data[0]['no_reg'];
        // dd($notrans);
        $tindakan = IGDTransModel::with(['tindakan'])->where('notrans', $notrans)->get();
        // return $tindakan;
        $dataTind = [];
        foreach ($tindakan as $item) {
            $dataTind[] = [
                'id' => $item->id,
                'norm' => $item->norm,
                'notrans' => $item->notrans,
                'kdTind' => $item->kdTind,
                'petugas' => $item->petugas,
                'nmTindakan' => $item->tindakan->nmTindakan,
                'tarif' => $item->tindakan->harga,
            ];
        }
        // return $dataTind;

        $ro = ROTransaksiModel::with(['foto'])->where('notrans', $notrans)->get();
        // return $ro;
        $dataRO = [];
        foreach ($ro as $item) {
            $dataRO[] = [
                'norm' => $item->norm,
                'notrans' => $item->notrans,
                'tgltrans' => $item->tgltrans,
                'kdFoto' => $item->kdFoto,
                'ro' => $item->foto->nmFoto,
                'tarif' => $item->foto->tarif,

            ];
        }
        // return $dataRO;
        $lab = LaboratoriumHasilModel::with(['pemeriksaan'])->where('notrans', $notrans)->get();
        // return $lab;
        $dataLab = [];
        foreach ($lab as $item) {
            $dataLab[] = [
                'id' => $item->idLab,
                'norm' => $item->norm,
                'notrans' => $item->notrans,
                'tgltrans' => $item->created_at,
                'kdPemeriksaan' => $item->idLayanan,
                'nmPemeriksaan' => $item->pemeriksaan->nmLayanan,
                'tarif' => $item->pemeriksaan->tarif,
            ];
        }

        // return $dataLab;

        $res = [
            'pasien' => $data[0],
            'tindakan' => $dataTind,
            'ro' => $dataRO,
            'lab' => $dataLab,
        ];
        return response()->json($res, 200, [], JSON_PRETTY_PRINT);

    }

    public function Layanan(Request $request)
    {

        $query = LayananModel::on('mysql')->where('status', '1');

        // Mengecek apakah request memiliki parameter kelas
        if ($request->has('kelas')) {
            $kelas = $request->input('kelas');
            $query->where('kelas', $kelas);
        }
        $query->orderBy('kelas', 'asc');
        // $query->orderBy('idLayanan', 'asc');

        $layanan = $query->get();

        return response()->json($layanan, 200, [], JSON_PRETTY_PRINT);
    }

    public function add(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'nmLayanan' => 'required|string|max:255',
            'tarif' => 'required|string|max:255',
            'kelas' => 'required|string|max:255',
            'status' => 'required',
        ]);

        try {
            // Create a new instance of LayananModel with the validated data
            $layanan = LayananModel::create($validatedData);

            // Return a JSON response indicating success
            return response()->json(['message' => 'Data layanan berhasil ditambahkan', 'data' => $layanan], 201);
        } catch (\Exception $e) {
            // Return a JSON response indicating failure
            return response()->json(['message' => 'Data layanan gagal ditambahkan', 'error' => $e->getMessage()], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            LayananModel::where('idLayanan', $request->input('id'))->delete();
            return response()->json(['message' => 'Data layanan berhasil dihapus']);
        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
            return response()->json(['message' => 'Data layanan gagal dihapus']);
        }
    }

    public function updateLayanan(Request $request)
    {
        try {
            $data = LayananModel::where('idLayanan', $request->input('id'))->firstOrFail();

            $data->update($request->only(['nmLayanan', 'tarif', 'kelas', 'status']));

            return response()->json(['message' => 'Data layanan berhasil diperbarui']);
        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
            return response()->json(['message' => 'Data layanan gagal diperbarui']);
        }
    }

    public function addTagihan(Request $request)
    {
        $dataTerpilih = $request->input('dataTerpilih');

        if (!is_array($dataTerpilih) || empty($dataTerpilih)) {
            return response()->json(['message' => 'Data terpilih tidak valid atau kosong'], 400);
        }

        try {
            DB::beginTransaction();

            $dataToInsert = collect($dataTerpilih)
                ->filter(fn($data) => isset($data['idLayanan'], $data['notrans']))
                ->map(fn($data) => [
                    'notrans' => $data['notrans'],
                    'norm' => $data['norm'] ?? null,
                    'idLayanan' => $data['idLayanan'],
                    'qty' => $data['qty'] ?? 1,
                    'totalHarga' => $data['harga'],
                ])->toArray();

            if (empty($dataToInsert)) {
                return response()->json(['message' => 'Data tidak lengkap'], 400);
            }

            KasirAddModel::insert($dataToInsert);

            if ($request->input('notrans')) {
                $dataKunjungan = $this->saveOrUpdateKunjungan($request);
                return response()->json(['message' => 'Kunjungan berhasil diproses...!!'], 200);
            }

            DB::commit();
            Log::info('Transaksi berhasil disimpan: ' . json_encode($dataToInsert));

            return response()->json(['message' => 'Transaksi berhasil disimpan'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Terjadi kesalahan: ' . $e->getMessage());
            return response()->json(['message' => 'Kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function addTransaksi(Request $request)
    {
        if ($request->input('notrans')) {
            $this->saveOrUpdateKunjungan($request);
            return response()->json(['message' => 'Kunjungan berhasil diproses...!!'], 200);
        }

        return response()->json(['message' => 'No Transaksi tidak valid'], 400);
    }

    private function saveOrUpdateKunjungan(Request $request)
    {
        $notrans = $request->input('notrans');

        $dataKunjungan = KasirTransModel::firstOrNew(['notrans' => $notrans]);
        $dataKunjungan->fill([
            'norm' => $request->input('norm'),
            'nama' => $request->input('nama'),
            'jk' => $request->input('jk'),
            'umur' => $request->input('umur'),
            'alamat' => $request->input('alamat'),
            'jaminan' => $request->input('jaminan'),
            'tagihan' => str_replace(['Rp', '.', ',', ' '], '', $request->input('tagihan')),
            'bayar' => str_replace(['Rp', '.', ',', ' '], '', $request->input('bayar')),
            'kembalian' => str_replace(['Rp', '.', ',', ' '], '', $request->input('kembalian')),
            'petugas' => $request->input('petugas'),
        ]);
        $dataKunjungan->save();

        return $dataKunjungan;
    }

    public function order(Request $request)
    {
        $notrans = $request->input('notrans');
        $data = KasirAddModel::with('layanan', 'transaksi')
            ->where('notrans', $notrans)
            ->get();
        // dd($data);
        if ($data == null) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
    public function kunjungan(Request $request)
    {
        $notrans = $request->input('notrans');
        $data = KasirTransModel::with(['item.layanan'])->where('notrans', $notrans)->first();
        if ($data == null) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }

    public function deleteKunjungan(Request $request)
    {
        $data = KasirAddModel::where('notrans', $request->notrans)->first();

        if ($data == null) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        } else {
            $data->delete();
            return response()->json(['message' => 'Data berhasil dihapus'], 200);
        }
    }

    public function rekapKunjungan(Request $request)
    {
        $norm = $request->input('norm');
        $tglAwal = $request->input('tglAwal', now()->toDateString());
        $tglAkhir = $request->input('tglAkhir', now()->toDateString());

        $data = KasirTransModel::with('item.layanan')
            ->where('norm', 'like', '%' . $norm . '%')
            ->whereBetween('created_at', [
                \Carbon\Carbon::parse($tglAwal)->startOfDay(), // Menambahkan waktu mulai hari
                \Carbon\Carbon::parse($tglAkhir)->endOfDay(), // Menambahkan waktu akhir hari
            ])
            ->get();
        $dataKasir = json_decode($data, true);
        $pasien = [];

        foreach ($dataKasir as $d) {
            $tanggal = Carbon::parse($d['updated_at'])->format('d-m-Y');

            $pemeriksaanDetails = [];
            foreach ($d['item'] as $pemeriksaan) {
                $hasilLab = ($pemeriksaan['hasil'] ?? null) . " " . ($pemeriksaan['ket'] ?? null);
                $pemeriksaanDetails[] = [
                    'id' => $pemeriksaan['idLab'] ?? null,
                    'idLayanan' => $pemeriksaan['idLayanan'] ?? null,
                    'hasil_murni' => $pemeriksaan['hasil'] ?? null,
                    'qty' => $pemeriksaan['qty'] ?? null,
                    'totalHarga' => $pemeriksaan['totalHarga'] ?? null,
                    'nmLayanan' => $pemeriksaan['layanan']['nmLayanan'] ?? null,
                    'tarif' => $pemeriksaan['layanan']['tarif'] ?? null,
                ];
            }

            $pasien[] = [
                'id' => $d['id'] ?? null,
                'notrans' => $d['notrans'] ?? null,
                'tgl' => $tanggal ?? null,
                'norm' => $d['norm'] ?? null,
                'jaminan' => $d['jaminan'] ?? null,
                'nama' => $d['nama'] ?? null,
                'alamat' => $d['alamat'] ?? null,
                'petugas' => $d['petugas'] ?? null,
                'tagihan' => $d['tagihan'] ?? null,
                'bayar' => $d['bayar'] ?? null,
                'kembalian' => $d['kembalian'] ?? null,
                'pemeriksaan' => $pemeriksaanDetails ?? null,
            ];
        }

        return response()->json($pasien, 200, [], JSON_PRETTY_PRINT);
    }

    public function cetakSBS($tanggal)
    {
        $title = 'LAPORAN KASIR';
        $tgl = date('Y-m-d', strtotime($tanggal));
        $data = KasirTransModel::where('created_at', 'like', '%' . $tgl . '%')->get();
        $totalTagihan = $data->sum('tagihan');
        // return $totalTagihan;
        // return $data;
        return view('Laporan.sbs', compact('tanggal', 'data', 'totalTagihan'))->with('title', $title);
    }
    public function cetakBAPH($tgl, $tahun)
    {
        $title = 'BAPH';

        // Fetch data and decode it
        $datas = $this->getPendapatan($tahun);
        // $datas = json_decode($datas, true);

        // Check if decoding was successful
        if (json_last_error() !== JSON_ERROR_NONE) {
            // Handle the error (for example, return a message)
            return response()->json(['error' => 'Invalid JSON data']);
        }

        // Check if $datas is an array before filtering
        if (is_array($datas)) {
            // Filter the data based on the provided date ($tgl)
            $data = array_filter($datas, function ($item) use ($tgl) {
                return isset($item['tanggal']) && $item['tanggal'] === $tgl;
            });
        } else {
            // If $datas is not an array, handle the case accordingly
            $data = [];
        }

        // Return the view with the filtered data
        return view('Laporan.baph', compact('data'))->with('title', $title);
    }

    public function pendapatan($tahun)
    {
        // Ambil data pendapatan berdasarkan tahun
        $data = $this->getPendapatan($tahun);
        // Inisialisasi array kosong untuk pendapatan

        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
    private function getPendapatan($tahun)
    {
        // Ambil data pendapatan berdasarkan tahun
        $data = KasirTransModel::selectRaw('DATE(created_at) as tanggal, SUM(tagihan) as pendapatan')
            ->whereYear('created_at', $tahun) // Filter berdasarkan tahun
            ->groupBy('tanggal') // Kelompokkan berdasarkan tanggal
            ->orderBy('tanggal', 'asc') // Urutkan berdasarkan tanggal
            ->get();

        // Inisialisasi array kosong untuk pendapatan
        $result = [];

        // Periksa apakah data ada
        if ($data->isEmpty()) {
            return response()->json([
                'message' => 'Tidak ada data pendapatan untuk tahun ' . $tahun,
                'data' => [],
            ], 200, [], JSON_PRETTY_PRINT);
        }

        // Looping data pendapatan
        foreach ($data as $d) {
            $tanggal = \Carbon\Carbon::parse($d->tanggal); // Menggunakan Carbon
            $formattedDate = $tanggal->format('d-m-Y');
            $hari = $tanggal->locale('id')->isoFormat('dddd'); // Hari dalam bahasa Indonesia
            $tglNomor = $tanggal->locale('id')->isoFormat('DD MMMM YYYY');
            $terbilangPendapatan = $this->terbilang($d->pendapatan); // Konversi terbilang

            // Format nomor
            $nomor = $tanggal->format('d') . './SBS/01/' . $tanggal->format('Y');

            // Tambahkan ke array hasil
            $result[] = [
                'nomor' => $nomor,
                'tanggal' => $formattedDate,
                'hari' => $hari,
                'tgl_nomor' => $tglNomor,
                'tgl_pendapatan' => $tglNomor,
                'tgl_setor' => $tglNomor,
                'pendapatan' => 'Rp ' . number_format($d->pendapatan, 0, ',', '.') . ',00',
                'jumlah' => $d->pendapatan,
                'terbilang' => ucfirst($terbilangPendapatan) . " rupiah.",
                'kode_akun' => 102010041411,
                'uraian' => 'Pendapatan Jasa Pelayanan Rawat Jalan 1',
            ];
        }
        return $result;
        // return response()->json($result, 200, [], JSON_PRETTY_PRINT);
    }

    private function terbilang($angka)
    {
        $angka = abs((int) $angka); // Pastikan angka dalam bentuk numerik
        $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $temp = "";

        if ($angka < 12) {
            $temp = $huruf[$angka];
        } elseif ($angka < 20) {
            $temp = $huruf[$angka - 10] . " belas";
        } elseif ($angka < 100) {
            $temp = $this->terbilang((int) ($angka / 10)) . " puluh " . $this->terbilang($angka % 10);
        } elseif ($angka < 200) {
            $temp = "seratus " . $this->terbilang($angka - 100);
        } elseif ($angka < 1000) {
            $temp = $this->terbilang((int) ($angka / 100)) . " ratus " . $this->terbilang($angka % 100);
        } elseif ($angka < 2000) {
            $temp = "seribu " . $this->terbilang($angka - 1000);
        } elseif ($angka < 1000000) {
            $temp = $this->terbilang((int) ($angka / 1000)) . " ribu " . $this->terbilang($angka % 1000);
        } elseif ($angka < 1000000000) {
            $temp = $this->terbilang((int) ($angka / 1000000)) . " juta " . $this->terbilang($angka % 1000000);
        } elseif ($angka < 1000000000000) {
            $temp = $this->terbilang((int) ($angka / 1000000000)) . " milyar " . $this->terbilang(fmod($angka, 1000000000));
        } elseif ($angka < 1000000000000000) {
            $temp = $this->terbilang((int) ($angka / 1000000000000)) . " triliun " . $this->terbilang(fmod($angka, 1000000000000));
        }

        return trim($temp); // Pastikan hasil akhir tanpa spasi berlebih
    }

}
