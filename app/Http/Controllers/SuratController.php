<?php

namespace App\Http\Controllers;

use App\Models\KominfoModel;
use App\Models\PegawaiModel;
use App\Models\SuratMedis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SuratController extends Controller
{
    private function pegawai($kdjab)
    {
        $data = PegawaiModel::with(['biodata', 'jabatan'])->whereIn('kd_jab', $kdjab)->get();

        $pegawai = [];
        foreach ($data as $peg) {
            $pegawai[] = array_map('strval', [
                "nip" => $peg["nip"] ?? null,
                "status" => $peg["stat_pns"] ?? null,
                "gelar_d" => $peg["gelar_d"] ?? null,
                "gelar_b" => $peg["gelar_b"] ?? null,
                "kd_jab" => $peg["kd_jab"] ?? null,
                "kd_pend" => $peg["kd_pend"] ?? null,
                "kd_jurusan" => $peg["kd_jurusan"] ?? null,
                "tgl_masuk" => $peg["tgl_masuk"] ?? null,
                "nama" => $peg["biodata"]["nama"] ?? null,
                "jeniskel" => $peg["biodata"]["jeniskel"] ?? null,
                "tempat_lahir" => $peg["biodata"]["tempat_lahir"] ?? null,
                "tgl_lahir" => $peg["biodata"]["tgl_lahir"] ?? null,
                "alamat" => $peg["biodata"]["alamat"] ?? null,
                "kd_prov" => $peg["biodata"]["kd_prov"] ?? null,
                "kd_kab" => $peg["biodata"]["kd_kab"] ?? null,
                "kd_kec" => $peg["biodata"]["kd_kec"] ?? null,
                "kd_kel" => $peg["biodata"]["kd_kel"] ?? null,
                "kdAgama" => $peg["biodata"]["kdAgama"] ?? null,
                "status_kawin" => $peg["biodata"]["status_kawin"] ?? null,
                "nm_jabatan" => $peg["jabatan"]["nm_jabatan"] ?? null,
            ]);
        }
        return $pegawai;
    }
    /**
     * Map an array to an array of objects.
     *
     * @param array $data
     * @return array
     */
    private function mapToObject(array $data)
    {
        return array_map(function ($item) {
            return (object) $item;
        }, $data);
    }

    private function listSM($tgl)
    {
        try {
            // Ambil semua data Surat Medis
            $lists = SuratMedis::all();

            // Panggil model KominfoModel untuk mendapatkan antrian
            $kominfoController = new KominfoModel();
            $param = [
                'tanggal' => $tgl ?? date('Y-m-d'),
                'ruang' => 'surat',
            ];
            $pasien = $kominfoController->antrianAll($param);

            // Hitung jumlah surat medis tahun ini dan tambahkan 1
            $jumlahSuratTahunIni = SuratMedis::whereYear('tanggal', date('Y'))->count() + 1;
            $jumlahSuratTahunIni = str_pad($jumlahSuratTahunIni, 3, '0', STR_PAD_LEFT);

            // Ambil data dokter dan petugas berdasarkan ID jabatan tertentu
            $dokter = $this->mapToObject($this->pegawai([1, 7, 8]));
            $petugas = $this->mapToObject($this->pegawai([10, 15]));

            return compact('lists', 'pasien', 'jumlahSuratTahunIni', 'dokter', 'petugas');
        } catch (\Exception $e) {
            // Tangani kesalahan jika terjadi
            Log::error('Error fetching Surat Medis data: ' . $e->getMessage());
            return [
                'lists' => [],
                'pasien' => [],
                'jumlahSuratTahunIni' => '000',
                'dokter' => [],
                'petugas' => [],
            ];
        }
    }

    public function index()
    {
        $title = 'Surat Medis';
        $tgl = date('Y-m-d');
        $data = $this->listSM($tgl);
        $pasien = $data['pasien'];
        $jumlahSuratTahunIni = $data['jumlahSuratTahunIni'];
        $dokter = $data['dokter'];
        $petugas = $data['petugas'];
        $lists = $data['lists'];

        return view('Laporan.SuratMedis.noSuratMedis', compact('title', 'lists', 'pasien', 'jumlahSuratTahunIni', 'dokter', 'petugas'));
    }

    public function store(Request $request)
    {
        try {
            // Validasi input
            $validatedData = $request->validate([
                'tglTrans' => 'required|date',
                'noSurat' => 'required|string|max:50|unique:t_no_surat_medis,noSurat',
                'norm' => 'required|string|max:20',
                'nama' => 'required|string|max:255',
                'tglLahir' => 'required|date',
                'umur' => 'required|string|min:0',
                'nik' => 'required|string|max:16',
                'alamat' => 'required|string|max:500',
                'hasil' => 'required|string|max:500',
                'keperluan' => 'required|string|max:255',
                'petugas' => 'required|string|max:100',
                'dokter' => 'required|string|max:100',
            ]);

            $norm = $request->norm;
            $tgl = $request->tglTrans;
            $data = SuratMedis::where('norm', $norm)->where('tanggal', $tgl)->first();
            if ($data) {
                return response()->json(['message' => 'Data sudah ada'], 400);
            }

            // Membuat instance dari model SuratMedis
            $noSurat = new SuratMedis();

            // Mengatur nilai-nilai kolom dari data validasi
            $noSurat->tanggal = $validatedData['tglTrans'];
            $noSurat->noSurat = $validatedData['noSurat'];
            $noSurat->norm = $validatedData['norm'];
            $noSurat->nama = $validatedData['nama'];
            $noSurat->tglLahir = $validatedData['tglLahir'];
            $noSurat->umur = $validatedData['umur'];
            $noSurat->nik = $validatedData['nik'];
            $noSurat->alamat = $validatedData['alamat'];
            $noSurat->hasil = $validatedData['hasil'];
            $noSurat->keperluan = $validatedData['keperluan'];
            $noSurat->petugas = $validatedData['petugas'];
            $noSurat->dokter = $validatedData['dokter'];

            // Simpan data ke dalam tabel
            $noSurat->save();

            $tgl = date('Y-m-d');
            $data = $this->listSM($tgl);

            // Respon sukses
            return response()->json(['message' => 'Data berhasil disimpan', 'lists' => $data['lists'], "noSurat" => $data['jumlahSuratTahunIni']], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Respon jika validasi gagal
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Respon jika terjadi error lainnya
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function cetakSM($id, $tgl)
    {
        $title = 'SURAT MEDIS';
        $pasien = SuratMedis::where('id', $id)->where('tanggal', $tgl)->first();

        // Ubah setiap kata pada 'keperluan' menjadi huruf kapital di awal
        $pasien->keperluan = ucwords(strtolower($pasien['keperluan']));
        // Ubah setiap kata pada 'hasil' menjadi huruf kapital semua
        $pasien->hasil = strtoupper($pasien->hasil);

        return view('Laporan.SuratMedis.suratMedis', compact('pasien'));
    }

}
