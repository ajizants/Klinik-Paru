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
        // dd($request->all());wis
        // Mendapatkan dataTerpilih dari permintaan
        $dataTerpilih = $request->input('dataTerpilih');
        // Validasi bahwa dataTerpilih harus array dan tidak boleh kosong
        if (!is_array($dataTerpilih) || empty($dataTerpilih)) {
            return response()->json([
                'message' => 'Data terpilih tidak valid atau kosong',
            ], 400);
        }
        // $tglTrans = $request->input('tgltrans'); // Assuming tglTrans is in 'Y-m-d' format
        // $currentDateTime = Carbon::now(); // Get current date and time
        // $today = $currentDateTime->format('Y-m-d');

        // // Check if today's date is not the same as tglTrans
        // if ($today !== $tglTrans) {
        //     // Create a Carbon instance using tglTrans and the current time
        //     $tanggal = Carbon::createFromFormat('Y-m-d H:i:s', $tglTrans . ' ' . $currentDateTime->format('H:i:s'));
        // } else {
        //     // Use the current date and time
        //     $tanggal = $currentDateTime;
        // }

        try {
            // Memulai transaksi database
            DB::beginTransaction();

            // Membuat array untuk menyimpan data yang akan disimpan
            $dataToInsert = [];

            // Looping untuk mengolah dataTerpilih
            foreach ($dataTerpilih as $data) {
                // Validasi data yang diperlukan pada setiap elemen dataTerpilih
                if (isset($data['idLayanan']) && isset($data['notrans'])) {
                    $dataToInsert[] = [
                        'notrans' => $data['notrans'],
                        'norm' => $data['norm'],
                        'idLayanan' => $data['idLayanan'],
                        // 'created_at' => $currentDateTime,
                        // 'updated_at' => $currentDateTime,
                    ];
                } else {
                    return response()->json([
                        'message' => 'Data tidak lengkap',
                    ], 500);
                }
            }
            // dd($dataToInsert);
            // Simpan data permintaan laborat ke database
            KasirAddModel::insert($dataToInsert);
            //tambahkan log data yang di simpan ke db

            DB::commit();
            Log::info('Transaksi berhasil disimpan: ' . json_encode($dataToInsert));
            return response()->json(['message' => 'Transaksi berhasil disimpan'], 200);

        } catch (\Exception $e) {
            // Rollback transaksi database jika terjadi kesalahan
            DB::rollback(); // Rollback transaksi jika terjadi kesalahan
            Log::error('Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()], 500);
        }
    }

    public function order(Request $request)
    {
        $notrans = $request->input('notrans');
        $data = KasirAddModel::with('layanan', 'transaksi')
            ->where('notrans', $notrans)
            ->get();

        if ($data->isEmpty()) {
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
    public function addTransaksi(Request $request)
    {
        $notrans = $request->input('notrans');
        $norm = $request->input('norm');
        $nama = $request->input('nama');
        $jk = $request->input('jk');
        $tagihan = str_replace(['Rp', '.', ',', ' '], '', $request->input('tagihan'));
        $bayar = str_replace(['Rp', '.', ',', ' '], '', $request->input('bayar'));
        $kembalian = str_replace(['Rp', '.', ',', ' '], '', $request->input('kembalian'));
        // dd($kembalian);

        $umur = $request->input('umur');
        $alamat = $request->input('alamat');
        $jaminan = $request->input('jaminan');
        $petugas = $request->input('petugas');
        // Pastikan $kdTind memiliki nilai yang valid sebelum menyimpan data
        if ($notrans !== null) {
            $kunjunganLab = new KasirTransModel();
            $kunjunganLab->notrans = $notrans;
            $kunjunganLab->norm = $norm;
            $kunjunganLab->nama = $nama;
            $kunjunganLab->umur = $umur;
            $kunjunganLab->jk = $jk;
            $kunjunganLab->alamat = $alamat;
            $kunjunganLab->jaminan = $jaminan;
            $kunjunganLab->tagihan = $tagihan;
            $kunjunganLab->bayar = $bayar;
            $kunjunganLab->kembalian = $kembalian;
            $kunjunganLab->petugas = $petugas;
            $kunjunganLab->save();

            // Respon sukses atau redirect ke halaman lain
            return response()->json(['message' => 'Data berhasil disimpan...!!']);
        } else {
            // Handle case when $kdTind is null, misalnya kirim respon error
            return response()->json(['message' => 'NO Trans tidak valid'], 400);
        }
    }

}
