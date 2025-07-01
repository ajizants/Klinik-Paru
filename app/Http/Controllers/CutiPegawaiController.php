<?php
namespace App\Http\Controllers;

use App\Models\CutiPegawai;
use App\Models\PegawaiModel;
use App\Models\Vpegawai;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CutiPegawaiController extends Controller
{

    // private function dataCutiPegawai($tgl_mulai = null, $tgl_selesai = null, $tgl_pengajuan = null, $nip = null, $persetujuan = null)
    // {
    //     $query = CutiPegawai::with('pegawai');

    //     if ($tgl_mulai) {
    //         $query->whereDate('tgl_mulai', '<=', $tgl_mulai);
    //     }

    //     if ($tgl_selesai) {
    //         $query->whereDate('tgl_selesai', '>=', $tgl_selesai);
    //     }

    //     if ($tgl_pengajuan) {
    //         $tgl_pengajuan = Carbon::parse($tgl_pengajuan);

    //         // dd($tgl_pengajuan);
    //         $query->whereMounth('tgl_pengajuan', $tgl_pengajuan->month)
    //             ->whereYear('tgl_pengajuan', $tgl_pengajuan->year);
    //     }

    //     if ($nip) {
    //         $query->where('nip', $nip);
    //     }

    //     if (! is_null($persetujuan)) {
    //         $query->where('persetujuan', $persetujuan);
    //     }

    //     return $query->get();
    // }

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
        $query = Vpegawai::with('cuti');
        if ($nip) {
            $query->where('nip', $nip);
        }
        $dataPegawai = $query->get();

        foreach ($dataPegawai as $key) {
            $jumlahCuti = 0;

            foreach ($key->cuti as $cuti) {
                // Hitung selisih hari cuti (inklusif)
                $mulai    = Carbon::parse($cuti->tgl_mulai);
                $selesai  = Carbon::parse($cuti->tgl_selesai);
                $hariCuti = $selesai->diffInDays($mulai) + 1;

                $jumlahCuti += $hariCuti;
            }

            $key['jumalhCutiDiambil'] = $jumlahCuti;
            $key['jumlahSisaCuti']    = ($key->jatah_cuti + $key->tambahan_cuti) - $jumlahCuti;
        }

        return $dataPegawai;
    }

    public function ajukanCuti(Request $request)
    {
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

        switch ($roleUser) {
            case 'admin' || 'tu':
                $dataCuti = $this->dataCutiPegawai();
                break;
            default:
                $dataCuti = $this->dataCutiPegawai($params);
                break;
        }

        return response()->json([
            'message'    => 'Pengajuan cuti berhasil dikirim.',
            'permohonan' => $cuti,
            'html'       => view('TataUsaha.Cuti.permohonanCutiTabel', compact('dataCuti', 'user'))->render(),
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
    public function getDataSisaCuti(Request $request)
    {
        $tahun_cuti = $request->input('tahun_cuti');

        try {
            $dataSisaCutiAll = $this->dataSisaCuti();
            $html            = view('TataUsaha.Cuti.sisaCutiTabel', compact('dataSisaCutiAll'))->render();
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

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title     = 'Cuti Pegawai';
        $model     = new PegawaiModel();
        $pegawai   = $model->olahPegawai([]);
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
        $html = view('TataUsaha.Cuti.permohonanCutiTabel', compact('dataCuti', 'tanggal', 'user'))->render();
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

        return view('TataUsaha.Cuti.main', compact('pegawai', 'html', 'cutiHariIni', 'sisaCutiUser', 'sisaCutiAll'))->with('title', $title);
    }

    public function tambahkanCuti(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CutiPegawai $cutiPegawai)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CutiPegawai $cutiPegawai)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, $persetujuan)
    {
        CutiPegawai::where('id', $id)->update(['persetujuan' => $persetujuan]);

        $dataCuti = CutiPegawai::with('pegawai')
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->get();
        $html = view('TataUsaha.Cuti.permohonanCutiTabel', compact('dataCuti'))->render();
        return response()->json([
            'message' => 'Permohonan cuti berhasil diubah.',
            'html'    => $html,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CutiPegawai $cutiPegawai)
    {
        //
    }
}
