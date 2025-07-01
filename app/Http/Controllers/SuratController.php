<?php
namespace App\Http\Controllers;

use App\Models\KominfoModel;
use App\Models\PegawaiModel;
use App\Models\SuratMedis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Roman;

class SuratController extends Controller
{
    private function pegawai($kdjab)
    {
        $data = PegawaiModel::with(['biodata', 'jabatan'])
            ->whereIn('kd_jab', $kdjab)
            ->whereNot('kd_jab', '22')
            ->whereNot('stat_pns', 'PENSIUNAN')->get();

        $pegawai = [];
        foreach ($data as $peg) {
            $pegawai[] = array_map('strval', [
                "nip"          => $peg["nip"] ?? null,
                "status"       => $peg["stat_pns"] ?? null,
                "gelar_d"      => $peg["gelar_d"] ?? null,
                "gelar_b"      => $peg["gelar_b"] ?? null,
                "kd_jab"       => $peg["kd_jab"] ?? null,
                "kd_pend"      => $peg["kd_pend"] ?? null,
                "kd_jurusan"   => $peg["kd_jurusan"] ?? null,
                "tgl_masuk"    => $peg["tgl_masuk"] ?? null,
                "nama"         => $peg["biodata"]["nama"] ?? null,
                "jeniskel"     => $peg["biodata"]["jeniskel"] ?? null,
                "tempat_lahir" => $peg["biodata"]["tempat_lahir"] ?? null,
                "tgl_lahir"    => $peg["biodata"]["tgl_lahir"] ?? null,
                "alamat"       => $peg["biodata"]["alamat"] ?? null,
                "kd_prov"      => $peg["biodata"]["kd_prov"] ?? null,
                "kd_kab"       => $peg["biodata"]["kd_kab"] ?? null,
                "kd_kec"       => $peg["biodata"]["kd_kec"] ?? null,
                "kd_kel"       => $peg["biodata"]["kd_kel"] ?? null,
                "kdAgama"      => $peg["biodata"]["kdAgama"] ?? null,
                "status_kawin" => $peg["biodata"]["status_kawin"] ?? null,
                "nm_jabatan"   => $peg["jabatan"]["nm_jabatan"] ?? null,
            ]);
        }
        //order pegawai by nama
        usort($pegawai, function ($a, $b) {
            return strcmp($a['nama'], $b['nama']);
        });
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
            $lists = SuratMedis::with('dok.biodata', 'adm.biodata')->get();

            // Panggil model KominfoModel untuk mendapatkan antrian
            $kominfoController = new KominfoModel();
            $param             = [
                'tanggal' => $tgl ?? date('Y-m-d'),
                'ruang'   => 'surat',
            ];
            $pasien = $kominfoController->antrianAll($param);
            // dd($pasien);

            // Hitung jumlah surat medis tahun ini dan tambahkan 1
            $jumlahSuratTahunIni = SuratMedis::whereYear('tanggal', date('Y'))->count() + 1;
            $jumlahSuratTahunIni = str_pad($jumlahSuratTahunIni, 3, '0', STR_PAD_LEFT);

            // Ambil data dokter dan petugas berdasarkan ID jabatan tertentu
            $dokter  = $this->mapToObject($this->pegawai([1, 7, 8]));
            $petugas = $this->mapToObject($this->pegawai([10, 15, 23]));

            return compact('lists', 'pasien', 'jumlahSuratTahunIni', 'dokter', 'petugas');
        } catch (\Exception $e) {
            // Tangani kesalahan jika terjadi
            Log::error('Error fetching Surat Medis data: ' . $e->getMessage());
            return [
                'lists'               => [],
                'pasien'              => [],
                'jumlahSuratTahunIni' => '000',
                'dokter'              => [],
                'petugas'             => [],
            ];
        }
    }

    public function index()
    {
        $title               = 'Surat Medis';
        $tgl                 = date('Y-m-d');
        $data                = $this->listSM($tgl);
        $pasien              = $data['pasien'];
        $jumlahSuratTahunIni = $data['jumlahSuratTahunIni'];
        $dokter              = $data['dokter'];
        $petugas             = $data['petugas'];
        $lists               = $data['lists'];

        return view('SuratMedis.main', compact('title', 'lists', 'pasien', 'jumlahSuratTahunIni', 'dokter', 'petugas'));
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'tglTrans'  => 'required|date',
                'norm'      => 'required|string|max:20',
                'nama'      => 'required|string|max:255',
                'tglLahir'  => 'required|date',
                'umur'      => 'required|string|min:0',
                'alamat'    => 'required|string|max:500',
                'hasil'     => 'required|string|max:500',
                'keperluan' => 'required|string|max:255',
                'dokter'    => 'required|string|max:100',
            ]);

            $tglTrans = $validatedData['tglTrans'];
            $existing = SuratMedis::where('norm', $validatedData['norm'])->where('tanggal', $tglTrans)->first();

            if ($existing) {
                return response()->json(['message' => 'Data sudah ada'], 400);
            }

            $no          = $this->listSM($tglTrans);
            $jumlahSurat = $no['jumlahSuratTahunIni'] ?? 0;

            $year        = date('Y', strtotime($tglTrans));
            $month       = (int) date('m', strtotime($tglTrans));
            $monthRomawi = Roman::month[$month] ?? 'I';
            $nomorSurat  = "440.6/{$jumlahSurat}/{$monthRomawi}/{$year}";

            $data = new SuratMedis([
                'tanggal'   => $tglTrans,
                'noSurat'   => $nomorSurat,
                'norm'      => $validatedData['norm'],
                'nama'      => $validatedData['nama'],
                'tglLahir'  => $validatedData['tglLahir'],
                'umur'      => $validatedData['umur'],
                'alamat'    => $validatedData['alamat'],
                'hasil'     => $validatedData['hasil'],
                'keperluan' => $validatedData['keperluan'],
                'dokter'    => $validatedData['dokter'],
                'nik'       => $request->nik,
                'petugas'   => $request->petugas,
                'td'        => $request->td,
                'bb'        => $request->bb,
                'tb'        => $request->tb,
                'nadi'      => $request->nadi,
                'pekerjaan' => $request->pekerjaan,
                'catatan'   => $request->catatan,
            ]);

            $data->save();

            $lists = SuratMedis::with('dok.biodata', 'adm.biodata')->get();

            return response()->json([
                'message'    => 'Data berhasil disimpan dengan No Surat: ' . $nomorSurat,
                'nomorSurat' => $nomorSurat,
                'surat'      => $data,
                'lists'      => $lists,
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        try {
            $noSurat    = SuratMedis::findOrFail($id);
            $namaPasien = $noSurat->nama;
            $no         = $noSurat->noSurat;
            $noSurat->delete();
            $data = $this->listSM(date('Y-m-d'));
            $tgl  = date('Y-m-d');
            $data = $this->listSM($tgl);

            $res = [
                'noSurat'    => $data['jumlahSuratTahunIni'],
                'namaPasien' => $namaPasien,
                "no"         => $no,
                'lists'      => $data['lists'],
            ];

            return response()->json($res, 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat menghapus data'], 500);
        }
    }

    public function cetakSM($id, $tgl)
    {
        $title  = 'SURAT MEDIS';
        $pasien = SuratMedis::with('dok.biodata', 'adm.biodata')->where('id', $id)->where('tanggal', $tgl)->first();
        $norm   = $pasien->norm;
        $model  = new KominfoModel();
        $param  = [
            'no_rm'         => $norm,
            'tanggal_awal'  => $tgl,
            'tanggal_akhir' => $tgl,
        ];
        $cppt = $model->cpptRequest($param)['response']['data'];
        // return $cppt;
        // return $pasien;

        // Ubah setiap kata pada 'keperluan' menjadi huruf kapital di awal
        $pasien->keperluan = ucwords(strtolower($pasien['keperluan']));
        // Ubah setiap kata pada 'hasil' menjadi huruf kapital semua
        $pasien->hasil = strtoupper($pasien->hasil);

        return view('SuratMedis.suratMedis', compact('pasien', 'title', 'cppt'));
    }

}
