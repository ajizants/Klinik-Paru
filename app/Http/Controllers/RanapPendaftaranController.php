<?php
namespace App\Http\Controllers;

use App\Models\KominfoModel;
use App\Models\PegawaiModel;
use App\Models\RanapPendaftaran;
use App\Models\RanapRuangan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RanapPendaftaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function home()
    {
        $title = 'Dashboard Pendaftaran';
        return view('Ranap.dashboard', compact('title'));
    }
    public function index()
    {
        $title        = 'Ranap Pendaftaran';
        $pegawaiModel = new PegawaiModel();
        $dokter       = $pegawaiModel->olahPegawai([1, 7, 8]);
        $dokter       = array_map(function ($item) {
            return (object) $item;
        }, $dokter);
        $petugas = $pegawaiModel->olahPegawai([16, 17]);
        $petugas = array_map(function ($item) {
            return (object) $item;
        }, $petugas);
        $ruangan = $this->getRuangTerpakai();

        $dataPasien = $this->getPasienRanap();
        // return $dataPasien;
        // return ['dokter' => $dokter, 'petugas' => $petugas];
        return view('Ranap.Pendaftaran.main', compact('title', 'dokter', 'petugas', 'ruangan', 'dataPasien'));
    }

    private function getRuangTerpakai()
    {
        $ruangDipakai = RanapPendaftaran::where('status_pulang', null)->pluck('ruang')->toArray();
        $ruangan      = RanapRuangan::whereNotIn('id', $ruangDipakai)->get();
        return $ruangan;
    }

    private function getPasienRanap()
    {
        $data = RanapPendaftaran::whereNull('status_pulang')
            ->with('dokter', 'kamar', 'petugas')->get();
        // return $data;
        $model     = new KominfoModel();
        $allPasien = [];

        foreach ($data as $item) {
            $pasien = $model->pasienRequest($item->norm);
            if ($pasien) {
                $allPasien[$item->norm] = $pasien;
            }
        }

        // return $allPasien;

        $data = $data->map(function ($item) use ($allPasien) {
            return [
                'id'            => $item->id,
                'norm'          => $item->norm,
                'jaminan'       => $item->jaminan,
                'notrans'       => $item->notrans,
                'pasien_no_rm'  => $item->norm,
                'pasien_nama'   => $allPasien[$item->norm]['pasien_nama'] ?? '-',
                'pasien_alamat' => $allPasien[$item->norm]['pasien_alamat'] ?? '-',
                'tgl_masuk'     => $item->tgl_masuk,
                'ruang'         => $item->ruang,
                'dokter'        => $item->dokter->gelar_d . ' ' . $item->dokter->nama . ' ' . $item->dokter->gelar_b,
                'admin'         => $item->petugas->gelar_d . ' ' . $item->petugas->nama . ' ' . $item->petugas->gelar_b,
                'ruang'         => $item->kamar->nama_ruangan,
            ];
        });

        // return $data;

        //buat table bootstrap
        $table = '<table class="table table-striped table-bordered" id="tablePasienRanap">
            <thead>
                <tr>
                    <th>Aksi</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Tgl Masuk</th>
                    <th>Ruangan</th>
                    <th>Dokter</th>
                    <th>Admin</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($data as $item) {
            $table .= '<tr id="row-' . $item['id'] . '">
                <td>
                    <div class="btn-group">
                    <a class="mx-1 btn btn-primary" type="button" onclick="editPasienRanap(' . $item['id'] . ')"><i class="fas fa-edit"></i></a>
                    <a class="mx-1 btn btn-danger" type="button" onclick="deletePasienRanap(' . $item['id'] . ')"><i class="fas fa-trash"></i></a>
                    </div>
                    <a class="mt-1 btn btn-warning" type="button" onclick="pulangkanPasien(' . "'" . $item['notrans'] . "'" . ')">Pulangkan</a>
                </td>
                <td>' . $item['pasien_nama'] . ' <br> ( ' . $item['pasien_no_rm'] . ' )</td>
                <td>' . $item['pasien_alamat'] . '</td>
                <td>' . Carbon::parse($item['tgl_masuk'])->format('d-m-Y') . '</td>
                <td>' . $item['ruang'] . '</td>
                <td>' . $item['dokter'] . '</td>
                <td>' . $item['admin'] . '</td>
            </tr>';
        }

        $table .= '</tbody>
        </table>';

        return $table;
    }
    public function store(Request $request)
    {
        // Validasi awal (optional tapi sangat direkomendasikan)
        $request->validate([
            'pasien_no_rm'  => 'required|string|max:6',
            'jaminan'       => 'required|string',
            'tgl_masuk'     => 'required|date',
            'dpjp'          => 'required|string',
            'admin'         => 'required|string',
            'status_pulang' => 'nullable|string',
            'ruang'         => 'required|string',
            'hub_p_jawab'   => 'required|string',
            'p_jawab'       => 'required|string',
        ]);

        try {
            $norm         = $request->pasien_no_rm;
            $jaminan      = $request->jaminan;
            $statusPulang = $request->status_pulang ?? null;
            $tglMasuk     = Carbon::parse($request->tgl_masuk)->format('Y-m-d');
            $dpjp         = $request->dpjp;
            $ruang        = $request->ruang;
            $admin        = $request->admin;
            $hub_p_jawab  = $request->hub_p_jawab;
            $p_jawab      = $request->p_jawab;
            $tgl          = Carbon::parse($tglMasuk);

            // Cek apakah ruang sedang digunakan (status_pulang masih null)
            $ruangDipakai = RanapPendaftaran::where('ruang', $ruang)
                ->whereNull('status_pulang')
                ->exists();

            if ($ruangDipakai) {
                return response()->json([
                    'message' => 'Ruang sedang dipakai. Silakan pilih ruang lain.',
                ], 422); // 422 Unprocessable Entity
            }

            $cekData = RanapPendaftaran::where('norm', $norm)->where('tgl_masuk', $tglMasuk)->first();

            if ($cekData) {
                return response()->json([
                    'status'  => 'error',
                    'success' => false,
                    'message' => 'Data pasien dengan No RM ' . $norm . ' dan tanggal masuk ' . $tglMasuk . ' sudah terdaftar.',
                ], 400);
            }

            $jumlahBulanIni = RanapPendaftaran::whereMonth('created_at', $tgl->month)
                ->whereYear('created_at', $tgl->year)
                ->count() + 1;
            $nomorUrut = str_pad($jumlahBulanIni, 4, '0', STR_PAD_LEFT);

            // Buat No Transaksi: RI + tglMasuk (tanpa -) + norm + urutan
            $noTrans = 'RI' . str_replace('-', '', $tglMasuk) . $norm . $nomorUrut;

            // Simpan data ke DB
            $ranapPendaftaran                = new RanapPendaftaran();
            $ranapPendaftaran->norm          = $norm;
            $ranapPendaftaran->notrans       = $noTrans;
            $ranapPendaftaran->jaminan       = $jaminan;
            $ranapPendaftaran->status_pulang = $statusPulang;
            $ranapPendaftaran->tgl_masuk     = $tglMasuk;
            $ranapPendaftaran->dpjp          = $dpjp;
            $ranapPendaftaran->ruang         = $ruang;
            $ranapPendaftaran->admin         = $admin;
            $ranapPendaftaran->hub_p_jawab   = $hub_p_jawab;
            $ranapPendaftaran->p_jawab       = $p_jawab;
            $ranapPendaftaran->save();
            $ruangDipakai = $this->getRuangTerpakai();

            $tablePasienRanap = $this->getPasienRanap();
            return response()->json([
                'message' => 'Data berhasil disimpan',
                'success' => true,
                'table'   => $tablePasienRanap,
                'ruangan' => $ruangDipakai,
            ], 200);

        } catch (\Exception $e) {
            // Log error dan kirim response yang aman
            Log::error('Gagal simpan rawat inap: ' . $e->getMessage());

            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function show(RanapPendaftaran $ranapPendaftaran)
    {
        $norm = $ranapPendaftaran->norm;

        // Ambil detail pasien dari KominfoModel
        $kominfo        = new KominfoModel();
        $pasien         = $kominfo->pasienRequest($norm);
        $pasien['umur'] = date_diff(date_create($pasien['pasien_tgl_lahir']), date_create('today'))->y;
        $ruanganPasien  = RanapRuangan::where('id', $ranapPendaftaran->ruang)->first()->toArray();
        // return $pasien;

        // Konversi model Eloquent ke array
        $ranapData = $ranapPendaftaran->toArray();

        // Merge data ranap dan pasien
        $dataGabungan = array_merge($ruanganPasien, $ranapData, $pasien ?? []);

        return response()->json($dataGabungan);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RanapPendaftaran $ranapPendaftaran)
    {
        //
    }

    public function destroy(RanapPendaftaran $ranapPendaftaran)
    {
        $ruanganDipakai = $this->getRuangTerpakai();
        return response()->json([
            'ruangan' => $ruanganDipakai,
            'success' => true,
            'message' => 'Data berhasil dihapus.',
        ]);
        try {
            $ranapPendaftaran->delete();

            $ruanganDipakai = $this->getRuangTerpakai();
            return response()->json([
                'ruangan' => $ruanganDipakai,
                'success' => true,
                'message' => 'Data berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage(),
            ], 500);
        }

    }

    public function pulangkanPasien(Request $request)
    {
        $ranapPendaftaran = RanapPendaftaran::where('notrans', $request->notrans)->first();
        if ($ranapPendaftaran == null) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }
        $ranapPendaftaran->status_pulang = 'Pulang';
        $ranapPendaftaran->tgl_pulang    = $request->tgl_pulang ?? Carbon::now()->format('Y-m-d');
        $ranapPendaftaran->save();
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dipulangkan',
        ], 200);
    }
}
