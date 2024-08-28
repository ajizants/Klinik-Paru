<?php

namespace App\Http\Controllers;

use App\Models\DiagnosaModel;
use App\Models\GiziDxDomainModel;
use App\Models\GiziDxKelasModel;
use App\Models\GiziDxSubKelasModel;
use App\Models\LaboratoriumHasilModel;
use App\Models\LayananModel;
use App\Models\RoHasilModel;
use App\Models\ROJenisFoto;

class HomeController extends Controller
{
    public function lte()
    {
        $title = 'Dashboard';
        return view('Template.lte')->with('title', $title);
    }
    public function home()
    {
        $title = 'Dashboard';
        return view('dashboard')->with('title', $title);
    }
    public function forbidden()
    {
        $title = 'Forbidden';
        return view('403')->with('title', $title);
    }

    public function igd()
    {
        $title = 'IGD';
        return view('IGD.Trans.main')->with('title', $title);
    }
    public function askep()
    {
        $title = 'ASKEP';
        return view('Askep.main')->with('title', $title);
    }
    public function dots()
    {
        $title = 'Dots Center';
        return view('DotsCenter.Trans.main')->with('title', $title);
    }
    public function farmasi()
    {
        $title = 'FARMASI';
        return view('Farmasi.Transaksi.main')->with('title', $title);
    }
    public function logFarmasi()
    {
        $title = 'RIWAYAT TRANSAKSI FARMASI';
        return view('Farmasi.log')->with('title', $title);
    }
    public function gudangFarmasi()
    {
        $title = 'Gudang Farmasi';
        return view('Farmasi.GudangFarmasi.main')->with('title', $title);
    }
    public function gudangIGD()
    {
        $title = 'Gudang IGD';
        return view('IGD.GudangIGD.main')->with('title', $title);
    }
    public function kasir()
    {
        $title = 'KASIR';
        return view('Kasir.main')->with('title', $title);
    }
    public function lab()
    {
        $title = 'Pendaftaran Laboratorium';
        return view('Laboratorium.Pendaftaran.main')->with('title', $title);
    }
    public function hasilLab()
    {
        $title = 'Input Hasil Laboratorium';
        return view('Laboratorium.Hasil.main')->with('title', $title);
    }
    public function riwayatlab()
    {
        $title = 'Riwayat Laboratorium';

        $query = LayananModel::on('mysql')
            ->where('status', '1')
            ->where('kelas', 'like', '%9%')
            ->get();
        $col = [];
        foreach ($query as $d) {
            $col[] = [
                "idLayanan" => $d["idLayanan"] ?? null,
                "nmLayanan" => $d["nmLayanan"] ?? null,
            ];
        }

        return view('Laboratorium.RiwayatLab.main', ['col' => $col])->with('title', $title);
    }
    public function masterlab()
    {
        $title = 'Master Laboratorium';

        $query = LayananModel::on('mysql')
            ->where('status', '1')
            ->where('kelas', 'like', '%9%')
            ->get();
        $col = [];
        foreach ($query as $d) {
            $col[] = [
                "idLayanan" => $d["idLayanan"] ?? null,
                "nmLayanan" => $d["nmLayanan"] ?? null,
            ];
        }

        return view('Laboratorium.MasterLab.main', ['col' => $col])->with('title', $title);
    }
    public function report()
    {
        $title = 'Report IGD';

        return view('IGD.report')->with('title', $title);
    }
    public function laporanPendaftaran()
    {
        $title = 'Laporan Pendaftaran';

        return view('Laporan.pendaftaran')->with('title', $title);
    }
    public function dispenser()
    {
        $title = 'Ambil Antrian';

        return view('Dispenser.main')->with('title', $title);
    }
    public function verif()
    {
        $title = 'Anjungan Mandiri';

        return view('Dispenser.input')->with('title', $title);
    }
    public function displayAntrian()
    {
        $title = 'Daftar Tunggu';

        return view('Display.main')->with('title', $title);
    }
    public function masterRo()
    {
        $title = 'Master Radiologi';
        $dataROJenisFoto = ROJenisFoto::all(); // Mengambil semua data ROJenisFoto
        // dd($dataROJenisFoto);
        return view('RO.Master.main', compact('title', 'dataROJenisFoto'));
    }
    public function ro()
    {
        $title = 'Radiologi';
        $appUrlRo = env('APP_URLRO');
        // dd($appUrlRo);
        return view('RO.Trans.main', compact('appUrlRo'))->with([
            'title' => $title,

        ]);
    }

    public function riwayatRo()
    {
        $title = 'Riwayat Rontgen';

        return view('RO.LogBook.main')->with('title', $title);
    }
    public function rontgenHasil($id)
    {
        $title = 'Hasil Rontgen';
        $appUrlRo = env('APP_URLRO');
        $norm = str_pad($id, 6, '0', STR_PAD_LEFT); // Normalize ID to 6 digits

        $hasilRo = "";
        try {
            $hasilRo = RoHasilModel::when($norm !== null && $norm !== '' && $norm !== '000000', function ($query) use ($norm) {
                return $query->where('norm', $norm); // Filter by norm if valid
            })
                ->get();

            if ($hasilRo->isEmpty()) {
                $hasilRo = "Data Foto Thorax pada Pasien dengan Norm: <u><b>" . $norm . "</b></u> tidak ditemukan,<br> Jika pasien melakukan Foto Thorax di KKPM, silahkan Menghubungi Bagian Radiologi. Terima Kasih...";
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengakses database. Silahkan hubungi radiologi untuk menghidupkan server.',
                'status' => 500,
            ], 500, [], JSON_PRETTY_PRINT);
        }

        try {
            $hasilLab = LaboratoriumHasilModel::with('pasien', 'pemeriksaan', 'petugas.biodata', 'dokter.biodata')
                ->where('norm', $norm ) // Filter by norm using a LIKE condition
                ->get();
            if ($hasilLab->isEmpty()) {
                $hasilLab = "Data Hasil Laboratorium pada Pasien dengan Norm: <u><b>" . $norm . "</b></u> tidak ditemukan,
                    <br> Jika pasien melakukan Pemeriksaan Lab di KKPM, silahkan Menghubungi Bagian Laboratorium.
                    <br> Dengan catatan pemeriksaan dilakukan Setelah Tanggal : <u><b>18 Juli 2024</b></u>, sebelum tanggal tersebut data tidak ada di sistem. Terima Kasih...";
            }
            // dd($hasilLab); // Debug: Dump and Die
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengakses database Lab. Silahkan hubungi TIM IT.',
                'error' => $e->getMessage(),
                'status' => 500,
            ], 500, [], JSON_PRETTY_PRINT);
        }

        return view('RO.Hasil.main', compact('appUrlRo', 'hasilRo', 'hasilLab'))->with([
            'title' => $title,
        ]);
    }

    public function roHasil()
    {
        $title = 'Hasil Rontgen';
        $appUrlRo = env('APP_URLRO');
        $hasilRo = "Silahkan Ketikan No RM dan tekan Enter/Klik Tombol Cari";
        $hasilLab = "Silahkan Ketikan No RM dan tekan Enter/Klik Tombol Cari";
        return view('RO.Hasil.main', compact('appUrlRo', 'hasilRo', 'hasilLab'))->with([
            'title' => $title,

        ]);
    }

    public function gizi()
    {
        $title = 'Gizi';
        $sub = GiziDxSubKelasModel::with('domain')->get();
        $dxMed = DiagnosaModel::get();
        // dd($sub);
        return view('Gizi.Trans.main', compact('title', 'sub', "dxMed"));
    }
    public function riwayatGizi()
    {
        $title = 'Riwayat Gizi';
        return view('Gizi.Riwayat.main')->with('title', $title);
    }
    public function masterGizi()
    {
        $title = 'Master Gizi';
        $domain = GiziDxDomainModel::all();
        $kelas = GiziDxKelasModel::all();

        return view('Gizi.Master.main', compact('title', 'domain', 'kelas'));
    }

    public function riwayatKunjungan()
    {
        $title = 'Riwayat Kunjungan';
        return view('Laporan.diagnosa')->with('title', $title);
    }

}
