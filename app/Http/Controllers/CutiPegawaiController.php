<?php
namespace App\Http\Controllers;

use App\Models\CutiPegawai;
use App\Models\CutiTambahan;
use App\Models\PegawaiModel;
use App\Models\Vpegawai;
use App\Models\vPegawaiModel;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MehediJaman\LaravelZkteco\LaravelZkteco;

class CutiPegawaiController extends Controller
{

    private function dataCutiPegawai(array $params = [])
    {
        $query = CutiPegawai::with('pegawai');

        if (! empty($params['tgl_mulai'])) {
            $query->whereDate('tgl_mulai', '<=', $params['tgl_mulai']);
        }

        if (! empty($params['tgl_selesai'])) {
            $query->whereDate('tgl_selesai', '>=', $params['tgl_selesai']);
        }

        if (! empty($params['tgl_pengajuan'])) {
            $tgl = \Carbon\Carbon::parse($params['tgl_pengajuan']);
            $query->whereMonth('created_at', $tgl->month)
                ->whereYear('created_at', $tgl->year);
        }

        if (! empty($params['nip'])) {
            $query->where('nip', $params['nip']);
        }

        if (array_key_exists('persetujuan', $params)) {
            $query->where('persetujuan', $params['persetujuan']);
        }

        return $query->get();
    }

    private function dataSisaCuti($nip = null)
    {
        $query = Vpegawai::with('cuti', 'cutiTambahan')->whereNot('stat_pns', 'PENSIUNAN');
        if ($nip) {
            $query->where('nip', $nip);
        }

        // Ambil hari libur dari tabel
        $hariLibur = DB::table('hari_libur')->pluck('tanggal')->map(function ($tanggal) {
            return Carbon::parse($tanggal)->toDateString();
        })->toArray();

        $dataPegawai = $query->get();

        foreach ($dataPegawai as $key) {
            $jumlahCuti          = 0;
            $jumlahCutiDisetujui = 0;
            $jumlahCutiDitolak   = 0;
            $jumlahCutiTambahan  = 0;

            foreach ($key->cuti as $cuti) {
                // Hanya hitung cuti tahunan (misal ID jenis cuti = 1)
                if ($cuti->alasan !== 'Cuti Tahunan') {
                    continue;
                }

                $mulai   = Carbon::parse($cuti->tgl_mulai);
                $selesai = Carbon::parse($cuti->tgl_selesai);

                $hariCuti = 0;

                // Loop per hari, cek apakah hari itu bukan Minggu dan bukan hari libur
                for ($tanggal = $mulai->copy(); $tanggal->lte($selesai); $tanggal->addDay()) {
                    $isMinggu    = $tanggal->dayOfWeek == Carbon::SUNDAY;
                    $isHariLibur = in_array($tanggal->toDateString(), $hariLibur);

                    if (! $isMinggu && ! $isHariLibur) {
                        $hariCuti++;
                    }
                }

                $jumlahCuti += $hariCuti;

                if ($cuti->persetujuan == 1) {
                    $jumlahCutiDisetujui += $hariCuti;
                }
                if ($cuti->persetujuan == 2) {
                    $jumlahCutiDitolak += $hariCuti;
                }
            }

            foreach ($key->cutiTambahan as $cuti) {
                // Hanya hitung cuti tahunan (misal ID jenis cuti = 1)
                $jumlahCutiTambahan += $cuti->jumlah_tambahan;
            }

            $key['jumlahCutiDiambil']   = $jumlahCuti;
            $key['jumlahCutiDitolak']   = $jumlahCutiDitolak;
            $key['jumlahCutiTambahan']  = $jumlahCutiTambahan;
            $key['jumlahCutiDisetujui'] = $jumlahCutiDisetujui;

            $key['jumlahCutiDisetujui'] = $jumlahCutiDisetujui;

            // Step 1: Kurangi dari sisaCuti_2 (2 tahun lalu)
            if ($jumlahCutiDisetujui <= $key->sisa_2) {
                $key['sisaCuti_2'] = $key->sisa_2 - $jumlahCutiDisetujui;
                $jumlahSisa        = 0;
            } else {
                $key['sisaCuti_2'] = 0;
                $jumlahSisa        = $jumlahCutiDisetujui - $key->sisa_2;
            }

            // Step 2: Kurangi dari sisaCuti_1 (1 tahun lalu)
            if ($jumlahSisa <= $key->sisa_1) {
                $key['sisaCuti_1'] = $key->sisa_1 - $jumlahSisa;
                $jumlahSisa        = 0;
            } else {
                $key['sisaCuti_1'] = 0;
                $jumlahSisa        = $jumlahSisa - $key->sisa_1;
            }

            // Step 3: Kurangi dari jatahCuti (tahun ini)
            if ($jumlahSisa <= $key->jatah_cuti) {
                $key['sisaCuti'] = $key->jatah_cuti - $jumlahSisa;
            } else {
                $key['sisaCuti'] = 0; // atau bisa buat warning kalau cuti melebihi kuota
            }

            $key['jumlahSisaCuti'] = ($key->jatah_cuti + $key->tambahan_cuti + $key->jumlahCutiTambahan) - $jumlahCutiDisetujui;
        }

        return $dataPegawai;
    }

    public function ajukanCuti(Request $request)
    {
        if ($request->input('tgl_mulai') > $request->input('tgl_selesai')) {
            return response()->json(['error' => true, 'message' => 'Tanggal selesai harus lebih besar dari tanggal mulai'], 500);
        }
        $validated = $request->validate([
            'nip'         => 'required|string|exists:peg_t_pegawai,nip',
            'nama'        => 'required|string',
            'tgl_mulai'   => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'alasan'      => 'required|string',
            'keterangan'  => 'required|string',
        ]);
        $nip            = $validated['nip'];
        $tglMulaiBaru   = $validated['tgl_mulai'];
        $tglSelesaiBaru = $validated['tgl_selesai'];
        $user           = Auth::user();
        $roleUser       = $user->role;
        $nip            = explode('@', $user->email)[0];
        $params         = [
            'nip' => $nip,
        ];

        // Cek apakah ada cuti yang bertabrakan
        $cekTabrakan = DB::table('peg_t_cuti')
            ->where('nip', $nip)
            ->where(function ($query) use ($tglMulaiBaru, $tglSelesaiBaru) {
                $query->whereBetween('tgl_mulai', [$tglMulaiBaru, $tglSelesaiBaru])
                    ->orWhereBetween('tgl_selesai', [$tglMulaiBaru, $tglSelesaiBaru])
                    ->orWhere(function ($q) use ($tglMulaiBaru, $tglSelesaiBaru) {
                        $q->where('tgl_mulai', '<=', $tglMulaiBaru)
                            ->where('tgl_selesai', '>=', $tglSelesaiBaru);
                    });
            })
            ->exists();

        if ($cekTabrakan) {
            switch ($roleUser) {
                case 'admin' || 'tu':
                    $dataCuti = $this->dataCutiPegawai();
                    break;
                default:
                    $dataCuti = $this->dataCutiPegawai($params);
                    break;
            }
            return response()->json([
                'message' => 'Pengajuan cuti ini bertabrakan dengan tanggal pengajuan sebelumnya.',
                'html'    => view('TataUsaha.Cuti.permohonanCutiTabel', compact('dataCuti', 'user'))->render(),
            ], 422);
        }

        $cuti = CutiPegawai::create($validated);

        $user     = Auth::user();
        $roleUser = $user->role;
        $nip      = explode('@', $user->email)[0];
        $params   = [
            // 'tgl_mulai'   => $tgl_mulai,
            // 'tgl_selesai' => $tgl_selesai,
            'nip' => $nip,
        ];
        switch ($roleUser) {
            case 'admin' || 'tu':
                $dataCuti = $this->dataCutiPegawai();
                break;
            default:
                $dataCuti = $this->dataCutiPegawai($params);
                break;
        }
        $html = view('TataUsaha.Cuti.permohonanCutiTabel', compact('dataCuti'))->render();

        $dataSisaCutiAll = $this->dataSisaCuti();
        // return $dataSisaCutiAll;
        $sisaCutiAll = view('TataUsaha.Cuti.sisaCutiTabel', compact('dataSisaCutiAll'))->render();

        $sisaCutiUser = $this->dataSisaCuti($nip);
        return response()->json([
            'message'     => 'Pengajuan cuti berhasil dikirim.',
            'permohonan'  => $cuti,
            'html'        => $html,
            'sisaCuti'    => $sisaCutiUser,
            'sisaCutiAll' => $sisaCutiAll,
        ]);
    }

    public function getPermohonanCutiPegawai($nip)
    {
        $params = [
            'nip' => $nip,
            //  'tgl_selesai' => $carbon->endOfMonth()->toDateString(),
        ];

        $user     = Auth::user();
        $roleUser = $user->role;
        $dataCuti = $this->dataCutiPegawai($params);
        $params   = [
            // 'tgl_mulai'   => $tgl_mulai,
            // 'tgl_selesai' => $tgl_selesai,
            'nip' => $nip,
        ];
        switch ($roleUser) {
            case 'admin' || 'tu':
                $dataCuti = $this->dataCutiPegawai();
                break;
            default:
                $dataCuti = $this->dataCutiPegawai($params);
                break;
        }

        $html = view('TataUsaha.Cuti.permohonanCutiTabel', compact('dataCuti', 'user'))->render();
        return response()->json([
            'message' => 'Permohonan cuti berhasil diambil.',
            'html'    => $html,
        ], 200);
    }
    public function getCutiPegawai($tanggal)
    {
        try {
            $tanggal = Carbon::parse($tanggal)->toDateString();

            $params = [
                'tgl_mulai'   => $tanggal,
                'tgl_selesai' => $tanggal,
            ];

            $html       = $this->dataCutiPegawai($params);
            $dataCutiWa = CutiPegawai::with('pegawai')->whereDate('tgl_mulai', '<=', $tanggal)
                ->whereDate('tgl_selesai', '>=', $tanggal)
                ->where('persetujuan', '1')
                ->get();

            $html = view('TataUsaha.Cuti.wa', compact('dataCutiWa', 'tanggal'))->render();

            return response()->json([
                'message' => 'Data cuti berhasil diambil.',
                'html'    => $html,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
    public function getDataSisaCutiPerson($nip)
    {
        try {
            $dataSisaCuti = $this->dataSisaCuti($nip);
            return $dataSisaCuti;
            $html = view('TataUsaha.Cuti.sisaCutiTabel', compact('dataSisaCuti'))->render();
            return response()->json([
                'message' => 'Permohonan cuti berhasil diambil.',
                'html'    => $html,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
    public function getDataSisaCuti(Request $request)
    {
        $tahun_cuti = $request->input('tahun_cuti');

        try {
            $dataSisaCutiAll = $this->dataSisaCuti();
            // return $dataSisaCutiAll;
            $html = view('TataUsaha.Cuti.sisaCutiTabel', compact('dataSisaCutiAll'))->render();
            return response()->json([
                'message' => 'Permohonan cuti berhasil diambil.',
                'html'    => $html,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
    public function index()
    {
        $title     = 'Cuti Pegawai';
        $model     = new PegawaiModel();
        $user      = Auth::user();
        $roleUser  = $user->role;
        $emailUser = $user->email;
        $nip       = explode('@', $emailUser)[0];
        // dd($roleUser);

        $tanggal = Carbon::parse(Carbon::now())->toDateString();
        // tgl mulai dan selesai, adalah awal bulan dan akhir bulan sekarang

        $tgl_mulai   = Carbon::parse($tanggal)->startOfMonth()->toDateString();
        $tgl_selesai = Carbon::parse($tanggal)->endOfMonth()->toDateString();
        $params      = [
            // 'tgl_mulai'   => $tgl_mulai,
            // 'tgl_selesai' => $tgl_selesai,
            'nip' => $nip,
        ];

        switch ($roleUser) {
            case 'admin' || 'tu':
                $dataCuti = $this->dataCutiPegawai();
                break;
            default:
                $dataCuti = $this->dataCutiPegawai($params);
                break;
        }
        // return $dataCuti;
        $dataPengajuanCuti = view('TataUsaha.Cuti.permohonanCutiTabel', compact('dataCuti', 'tanggal', 'user'))->render();
        // return $html;

        $dataCutiWa = CutiPegawai::with('pegawai')->whereDate('tgl_mulai', '<=', $tanggal)
            ->whereDate('tgl_selesai', '>=', $tanggal)
            ->get();

        $cutiHariIni = view('TataUsaha.Cuti.wa', compact('dataCutiWa', 'tanggal'))->render();

        $sisaCutiUser    = $this->dataSisaCuti($nip);
        $dataSisaCutiAll = $this->dataSisaCuti();
        // return $dataSisaCutiAll;
        $sisaCutiAll = view('TataUsaha.Cuti.sisaCutiTabel', compact('dataSisaCutiAll'))->render();
        // return $sisaCutiAll;
        $cutiTambahan = $this->getCutiTambahan();

        // return $cutiTambahan;

        $pegawai = vPegawaiModel::whereNot('stat_pns', 'PENSIUNAN')->orderBy('nama')->get();
        // return $pegawai;

        return view('TataUsaha.Cuti.main', compact('pegawai', 'dataPengajuanCuti', 'cutiHariIni', 'sisaCutiUser', 'sisaCutiAll', 'pegawai', 'cutiTambahan'))->with('title', $title);
    }

    private function getCutiTambahan()
    {
        $dataTambahanCuti = CutiTambahan::with('pegawai')->whereYear('created_at', now()->year)->get();
        // return $dataTambahanCuti;
        $html = view('TataUsaha.Cuti.tambahanTabel', compact('dataTambahanCuti'))->render();
        return $html;
    }

    public function update($id, $persetujuan)
    {
        CutiPegawai::where('id', $id)->update(['persetujuan' => $persetujuan]);

        $user     = Auth::user();
        $roleUser = $user->role;
        $nip      = explode('@', $user->email)[0];
        $params   = [
            // 'tgl_mulai'   => $tgl_mulai,
            // 'tgl_selesai' => $tgl_selesai,
            'nip' => $nip,
        ];
        switch ($roleUser) {
            case 'admin' || 'tu':
                $dataCuti = $this->dataCutiPegawai();
                break;
            default:
                $dataCuti = $this->dataCutiPegawai($params);
                break;
        }
        $html = view('TataUsaha.Cuti.permohonanCutiTabel', compact('dataCuti'))->render();

        $sisaCutiUser    = $this->dataSisaCuti($nip);
        $dataSisaCutiAll = $this->dataSisaCuti();
        // return $dataSisaCutiAll;
        $sisaCutiAll = view('TataUsaha.Cuti.sisaCutiTabel', compact('dataSisaCutiAll'))->render();
        return response()->json([
            'message'     => 'Permohonan cuti berhasil diubah.',
            'html'        => $html,
            'sisaCuti'    => $sisaCutiUser,
            'sisaCutiAll' => $sisaCutiAll,
        ], 200);
    }

    public function destroy(CutiPegawai $cutiPegawai): JsonResponse
    {
        try {
            $cutiPegawai->delete();

            // Ambil ulang data setelah penghapusan
            $user     = Auth::user();
            $roleUser = $user->role;
            $nip      = explode('@', $user->email)[0];
            $params   = [
                // 'tgl_mulai'   => $tgl_mulai,
                // 'tgl_selesai' => $tgl_selesai,
                'nip' => $nip,
            ];
            switch ($roleUser) {
                case 'admin' || 'tu':
                    $dataCuti = $this->dataCutiPegawai();
                    break;
                default:
                    $dataCuti = $this->dataCutiPegawai($params);
                    break;
            }
            $html = view('TataUsaha.Cuti.permohonanCutiTabel', compact('dataCuti'))->render();

            $sisaCutiUser    = $this->dataSisaCuti($nip);
            $dataSisaCutiAll = $this->dataSisaCuti();
            // return $dataSisaCutiAll;
            $sisaCutiAll = view('TataUsaha.Cuti.sisaCutiTabel', compact('dataSisaCutiAll'))->render();

            return response()->json([
                'message'     => 'Data cuti berhasil dihapus.',
                'html'        => $html,
                'sisaCuti'    => $sisaCutiUser,
                'sisaCutiAll' => $sisaCutiAll,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menghapus data.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function eidtSisaCuti($nip)
    {
        $data = Vpegawai::where('nip', $nip)->first();
        return $data;
    }
    public function cetak($id)
    {
        $title    = 'Cetak Form Cuti';
        $pModel   = new Vpegawai();
        $data     = CutiPegawai::with('pegawai')->find($id);
        $sisaCuti = $this->dataSisaCuti($data->pegawai->nip)->first();
        // return $sisaCuti;
        $data->sisaCuti = $sisaCuti;
        $bln            = Carbon::parse($data->created_at)->format('m');
        $thn            = Carbon::parse($data->created_at)->format('Y');
        $pimpinan       = $pModel->pimpinan($bln, $thn);
        // return $data;
        return view('TataUsaha.Cuti.cetakFormCuti', compact('data', 'sisaCuti', 'pimpinan', 'title'));
    }

    public function ambilLog()
    {
        $zk = new LaravelZkteco('192.168.10.27', 4370);

        try {
            $zk->connect();
            $zk->disableDevice();

            $logs = $zk->getAttendance();

            $zk->enableDevice();
            $zk->disconnect();

            return response()->json($logs);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

}
