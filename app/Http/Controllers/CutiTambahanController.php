<?php
namespace App\Http\Controllers;

use App\Imports\CutiTambahanImport;
use App\Models\CutiTambahan;
use App\Models\Vpegawai;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class CutiTambahanController extends Controller
{

    public function cutiTambahanByNip(Request $request)
    {
        $request->validate([
            'nip_manual'  => 'required|exists:v_pegawai,nip',
            'cuti_manual' => 'required|integer|min:1',
        ]);

        $cuti = CutiTambahan::create([
            'nip'             => $request->nip_manual,
            'jumlah_tambahan' => $request->cuti_manual,
        ]);

        $cuti->load('pegawai');

        return response()->json([
            'success' => true,
            'message' => 'Tambahan cuti berhasil disimpan.',
            'data'    => $cuti,
        ]);
    }

    public function cutiTambahanKolektif(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xls,xlsx',
        ]);
        // dd($request->all());

        try {
            Excel::import(new CutiTambahanImport, $request->file('file'));

            $data = CutiTambahan::with('pegawai')->whereYear('created_at', now()->year)->get();

            return response()->json([
                'success' => true,
                'message' => 'Data tambahan cuti berhasil diimpor.',
                'data'    => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengimpor file: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getCutiTambahan($tahun)
    {
        $dataTambahanCuti = CutiTambahan::with('pegawai')->whereYear('created_at', $tahun)->get();
        $dataTambahanCuti = view('TataUsaha.Cuti.tambahanTabel', compact('dataTambahanCuti'))->render();
        return $dataTambahanCuti;
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        $path = public_path('templates/template_tambahan_cuti.xls');

        return response()->download($path, 'template_tambahan_cuti.xls', $headers);
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
            $key['jumlahCutiDisetujui'] = $jumlahCutiDisetujui;
            $key['jumlahCutiDitolak']   = $jumlahCutiDitolak;
            $key['jumlahCutiTambahan']  = $jumlahCutiTambahan;
            $key['jumlahSisaCuti']      = ($key->jatah_cuti + $key->tambahan_cuti + $key->jumlahCutiTambahan) - $jumlahCutiDisetujui;
        }
        $dataSisaCutiAll = $dataPegawai;

        $dataSisaCutiAll = view('TataUsaha.Cuti.sisaCutiTabel', compact('dataSisaCutiAll'))->render();
        return $dataSisaCutiAll;
    }
    public function update(Request $request, cutiTambahan $cutiTambahan)
    {
        if (! $cutiTambahan) {
            return response()->json([
                'message' => 'Data cuti tidak ditemukan.',
                'status'  => 'error',
            ], 404);
        }
        try {
            $cutiTambahan->update([
                'jumlah_tambahan' => $request->jumlah_tambahan,
            ]);

            return response()->json([
                'message'      => 'Data cuti berhasil diupdate.',
                'status'       => 'success',
                'cutiTambahan' => $this->getCutiTambahan(now()->year),
                'sisaCutiAll'  => $this->dataSisaCuti(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengupdate data.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(cutiTambahan $cutiTambahan): JsonResponse
    {
        // dd($cutiTambahan);
        if (! $cutiTambahan) {
            return response()->json([
                'message' => 'Data cuti tidak ditemukan.',
                'status'  => 'error',
            ], 404);
        }
        try {
            $cutiTambahan->delete();

            return response()->json([
                'message'      => 'Data cuti berhasil dihapus.',
                'status'       => 'success',
                'cutiTambahan' => $this->getCutiTambahan(now()->year),
                'sisaCutiAll'  => $this->dataSisaCuti(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menghapus data.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
