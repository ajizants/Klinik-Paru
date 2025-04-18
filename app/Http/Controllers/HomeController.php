<?php
namespace App\Http\Controllers;

use App\Models\BMHPModel;
use App\Models\DiagnosaMapModel;
use App\Models\DiagnosaModel;
use App\Models\DotsBlnModel;
use App\Models\DotsModel;
use App\Models\DotsObatModel;
use App\Models\GiziDxDomainModel;
use App\Models\GiziDxKelasModel;
use App\Models\GiziDxSubKelasModel;
use App\Models\IGDTransModel;
use App\Models\KasirAddModel;
use App\Models\KasirPenutupanKasModel;
use App\Models\KasirSetoranModel;
use App\Models\KasirTransModel;
use App\Models\LaboratoriumHasilModel;
use App\Models\LayananKelasModel;
use App\Models\LayananModel;
use App\Models\PegawaiKegiatanModel;
use App\Models\PegawaiModel;
use App\Models\RoHasilModel;
use App\Models\ROJenisFilm;
use App\Models\ROJenisFoto;
use App\Models\ROJenisKondisi;
use App\Models\ROJenisMesin;
use App\Models\RoProyeksiModel;

class HomeController extends Controller
{

    private function pegawai($kdjab)
    {
        $nip = [4, 5, 9999];
        if ($kdjab == []) {
            $data = PegawaiModel::with(['biodata', 'jabatan'])
                ->whereNotIn('nip', $nip)->get();
        } else {

            $data = PegawaiModel::with(['biodata', 'jabatan'])->whereIn('kd_jab', $kdjab)
                ->whereNotIn('nip', $nip)->get();
        }

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
        return $pegawai;
    }
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
        return view('Template.403')->with('title', $title);
    }

    public function pendaftaran()
    {
        $title = 'PENDAFTARAN';
        $admin = $this->pegawai([10, 15]);

        $admin = array_map(function ($item) {
            return (object) $item;
        }, $admin);

        return view('Pendaftaran.main', compact('admin'))->with('title', $title);
    }
    public function igd()
    {
        $title    = 'IGD';
        $dokter   = $this->pegawai([1, 7, 8]);
        $perawat  = $this->pegawai([10, 14, 15, 23]);
        $tindakan = $this->layanan([2, 3, 5, 6, 10]);
        $bmhp     = BMHPModel::all();
        $dxMed    = DiagnosaModel::all();

        $dokter = array_map(function ($item) {
            return (object) $item;
        }, $dokter);
        $perawat = array_map(function ($item) {
            return (object) $item;
        }, $perawat);

        $model   = new IGDTransModel();
        $dataIgd = $model->getPelaksanaLast();
        // return $dataIgd;

        return view('IGD.Trans.main', compact('tindakan', 'bmhp', 'dxMed', 'dokter', 'perawat', 'dataIgd'))->with('title', $title);
    }
    public function askep()
    {
        $title = 'ASKEP';
        return view('Askep.main')->with('title', $title);
    }

    private function pasienTB()
    {
        $pasienTB = DotsModel::with(['dokter.biodata', 'diagnosa', 'pengobatan'])->get()->map(function ($d) {
            $d['diagnosa'] = $d->diagnosa->diagnosa ?? '-';

            $d['status'] = $this->getStatusPengobatan($d['statusPengobatan']);

            $d['hasilPengobatan'] = $d->pengobatan->nmBlnKe ?? "Belum Ada Pengobatan";

            $d['ket'] = $d['ket'] ?? '-';

            return $d;
        });

        return $pasienTB;
    }

    private function getStatusPengobatan($status)
    {
        $statuses = [
            "1" => "Pengobatan Pertama",
            "2" => "Pengobatan Kedua",
            "3" => "Pengobatan Ketiga",
            "4" => "Pengobatan Keempat",
        ];
        return $statuses[$status] ?? "Tidak Diketahui";
    }

    public function dots()
    {
        $title   = 'Dots Center';
        $dokter  = $this->pegawai([1, 7, 8]);
        $perawat = $this->pegawai([10, 14, 15, 23]);
        $pegawai = $this->pegawai([]);
        $bulan   = DotsBlnModel::all();
        $obat    = DotsObatModel::all();
        $dxMed   = DiagnosaModel::all();
        // dd($dxMed);
        $pasienTB = $this->pasienTB();
        // return $pasienTB;
        // Converting arrays to objects for use in the view
        $dokter = array_map(function ($item) {
            return (object) $item;
        }, $dokter);

        $perawat = array_map(function ($item) {
            return (object) $item;
        }, $perawat);

        $modelKegiatan = new PegawaiKegiatanModel();
        $hasilKegiatan = $modelKegiatan->allData();
        return view('DotsCenter.Trans.main', compact('bulan', 'obat', 'dxMed', 'dokter', 'perawat', 'pegawai', 'pasienTB', 'hasilKegiatan'))
            ->with('title', $title);
    }

    public function farmasi()
    {
        $title    = 'FARMASI';
        $dokter   = $this->pegawai([1, 7, 8]);
        $perawat  = $this->pegawai([10, 14, 15, 23]);
        $tindakan = $this->layanan([2, 3, 5, 6, 10]);
        $bmhp     = BMHPModel::all();
        $dxMed    = DiagnosaModel::all();
        $model    = new IGDTransModel();
        $dataIgd  = $model->getPelaksanaLast();

        $dokter = array_map(function ($item) {
            return (object) $item;
        }, $dokter);
        $perawat = array_map(function ($item) {
            return (object) $item;
        }, $perawat);

        return view('Farmasi.Transaksi.main', compact('tindakan', 'dataIgd', 'bmhp', 'dxMed', 'dokter', 'perawat'))->with('title', $title);
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
        $title   = 'KASIR';
        $layanan = LayananModel::where('status', 'like', '%1%')
            ->orderBy('grup', 'asc')
            ->orderBy('kelas', 'asc')
            ->get();
        // return $layanan;
        return view('Kasir.main', compact('layanan'))->with('title', $title);
    }
    public function masterKasir()
    {
        $title = 'Master Kasir';
        // $layanan = LayananModel::with('grup')->where('status', 'like', '%1%')->get();
        $layanan = LayananModel::with('grup')->get();
        $kelas   = LayananKelasModel::get();
        // return $layanan;
        return view('Kasir.Master.main', compact('layanan', 'kelas'))->with('title', $title);
    }
    public function rekapKasir()
    {
        $title = 'LAPORAN KASIR';
        $date  = date('Y-m-d');
        // Mendapatkan tahun sekarang
        $currentYear = \Carbon\Carbon::now()->year;

        // Membuat array tahun untuk 5 tahun terakhir
        $listYear = [];
        for ($i = 0; $i < 5; $i++) {
            $listYear[] = $currentYear - $i;
        }

        // dd($year);
        $model  = new KasirAddModel();
        $params = [
            'tglAwal'  => $date,
            'tglAkhir' => $date,
        ];
        $perItem = $model->pendapatanPerItem($params);
        // $perItem = array_map(function ($item) {
        //     return (object) $item;
        // }, $perItem);
        // return $perItem;
        $perRuang = $model->pendapatanPerRuang($params);
        // $perRuang = array_map(function ($item) {
        //     return (object) $item;
        // }, $perRuang);

        $kasir           = new KasirTransModel();
        $pendapatanTotal = $kasir->pendapatan($currentYear);
        // return $pendapatanTotal;
        return view('Laporan.Kasir.rekap', compact('perItem', 'perRuang', 'listYear', 'pendapatanTotal', 'title'));
    }

    public function pendapatan()
    {
        $title = 'LAPORAN KASIR';
        // Mendapatkan tahun sekarang
        $currentYear = \Carbon\Carbon::now()->year;

        // Membuat array tahun untuk 5 tahun terakhir
        $listYear = [];
        for ($i = 0; $i < 5; $i++) {
            $listYear[] = $currentYear - $i;
        }

        return view('Laporan.Kasir.pendapatan')->with('title', $title)->with('listYear', $listYear);
    }
    public function pendapatanLain()
    {
        $title = 'PENDAPATAN LAIN';
        // Mendapatkan tahun sekarang
        $currentYear = \Carbon\Carbon::now()->year;
        // $currentYear = 2024;

        // Membuat array tahun untuk 5 tahun terakhir
        $listYear = [];
        for ($i = 0; $i < 5; $i++) {
            $listYear[] = $currentYear - $i;
        }
        $data         = KasirSetoranModel::where('tanggal', 'like', '%' . $currentYear . '%')->get();
        $dataTutupKas = KasirPenutupanKasModel::all();

        return view('Kasir.PendapatanLain.main', compact('data', 'listYear', 'dataTutupKas'))->with('title', $title);
    }

    private function layanan($kelas)
    {
        $data = LayananModel::where('status', "1")
            ->whereIn('kelas', $kelas)
        // ->whereIn('kelas', 'like', '%' . $kelas . '%')
            ->get();

        $layanan = [];

        foreach ($data as $d) {
            $layanan[] = [
                'idLayanan' => $d->idLayanan,
                'kdTind'    => $d->kdTind,
                'kdFoto'    => $d->kdFoto,
                'kelas'     => $d->kelas,
                'nmLayanan' => $d->nmLayanan,
                'tarif'     => $d->tarif,
                'status'    => $d->status,
            ];
        }
        // dd($layanan);
        $layanan = array_map(function ($item) {
            return (object) $item;
        }, $layanan);

        return $layanan;
    }
    public function lab()
    {
        $title      = 'Pendaftaran Laboratorium';
        $layananLab = $this->layanan([9]);
        // dd($layananLab);
        $dokter = $this->pegawai([1, 7, 8]);
        $analis = $this->pegawai([11]);

        $dokter = array_map(function ($item) {
            return (object) $item;
        }, $dokter);
        $analis = array_map(function ($item) {
            return (object) $item;
        }, $analis);
        $layananLab = array_map(function ($item) {
            return (object) $item;
        }, $layananLab);
        return view('Laboratorium.Pendaftaran.main', compact('layananLab', 'dokter', 'analis'))->with('title', $title);
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

        return view('Laporan.Pendaftaran.main')->with('title', $title);
    }
    public function dispenser()
    {
        $title = 'Ambil Antrian';

        return view('Dispenser.main')->with('title', $title);
    }
    public function displayLoket()
    {
        $title = 'Ambil Antrian';

        return view('Display.main')->with('title', $title);
    }
    public function verif($id_server)
    {
        $title = 'Anjungan Mandiri';

        return view('Dispenser.anjunganMandiri', compact('id_server'))->with('title', $title);
    }
    public function displayAntrian()
    {
        $title = 'Daftar Tunggu';
        // Akses video dari folder yang di-share di jaringan
        $videos = null;

        return view('Display.main', compact('title', 'videos'));
    }
    public function masterRo()
    {
        $title           = 'Master Radiologi';
        $dataROJenisFoto = ROJenisFoto::all();
        return view('RO.Master.main', compact('title', 'dataROJenisFoto'));
    }
    public function ro()
    {
        $title       = 'Radiologi';
        $appUrlRo    = env('APP_URLRO');
        $proyeksi    = RoProyeksiModel::all();
        $kondisi     = ROJenisKondisi::all();
        $mesin       = ROJenisMesin::all();
        $foto        = ROJenisFoto::all();
        $film        = ROJenisFilm::all();
        $dokter      = $this->pegawai([1, 7, 8]);
        $radiografer = $this->pegawai([12]);

        $kv = array_filter($kondisi->toArray(), function ($p) {
            return $p['grup'] === 'KV';
        });

        $ma = array_filter($kondisi->toArray(), function ($p) {
            return $p['grup'] === 'mA';
        });

        $s = array_filter($kondisi->toArray(), function ($p) {
            return $p['grup'] === 's';
        });

        $kv = array_map(function ($item) {
            return (object) $item;
        }, $kv);

        $ma = array_map(function ($item) {
            return (object) $item;
        }, $ma);

        $s = array_map(function ($item) {
            return (object) $item;
        }, $s);
        $dokter = array_map(function ($item) {
            return (object) $item;
        }, $dokter);
        $radiografer = array_map(function ($item) {
            return (object) $item;
        }, $radiografer);

        return view('RO.Trans.main', compact('appUrlRo', 'proyeksi', 'mesin', 'foto', 'film', 'kv', 'ma', 's', 'dokter', 'radiografer'))->with([
            'title' => $title,
        ]);
    }
    public function ro2()
    {
        $title       = 'Radiologi';
        $appUrlRo    = env('APP_URLRO');
        $proyeksi    = RoProyeksiModel::all();
        $kondisi     = ROJenisKondisi::all();
        $mesin       = ROJenisMesin::all();
        $foto        = ROJenisFoto::all();
        $film        = ROJenisFilm::all();
        $dokter      = $this->pegawai([1, 7, 8]);
        $radiografer = $this->pegawai([12]);

        $kv = array_filter($kondisi->toArray(), function ($p) {
            return $p['grup'] === 'KV';
        });

        $ma = array_filter($kondisi->toArray(), function ($p) {
            return $p['grup'] === 'mA';
        });

        $s = array_filter($kondisi->toArray(), function ($p) {
            return $p['grup'] === 's';
        });

        $kv = array_map(function ($item) {
            return (object) $item;
        }, $kv);

        $ma = array_map(function ($item) {
            return (object) $item;
        }, $ma);

        $s = array_map(function ($item) {
            return (object) $item;
        }, $s);
        $dokter = array_map(function ($item) {
            return (object) $item;
        }, $dokter);
        $radiografer = array_map(function ($item) {
            return (object) $item;
        }, $radiografer);

        return view('RO.Trans.main2', compact('appUrlRo', 'proyeksi', 'mesin', 'foto', 'film', 'kv', 'ma', 's', 'dokter', 'radiografer'))->with([
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
        $title    = 'Hasil Penunjang';
        $appUrlRo = env('APP_URLRO');
        $norm     = str_pad($id, 6, '0', STR_PAD_LEFT); // Normalize ID to 6 digits

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
                'status'  => 500,
            ], 500, [], JSON_PRETTY_PRINT);
        }

        try {
            $hasilLab = LaboratoriumHasilModel::with('pasien', 'pemeriksaan', 'petugas.biodata', 'dokter.biodata')
                ->where('norm', $norm) // Filter by norm using a LIKE condition
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
                'error'   => $e->getMessage(),
                'status'  => 500,
            ], 500, [], JSON_PRETTY_PRINT);
        }

        return view('RO.Hasil.main', compact('appUrlRo', 'hasilRo', 'hasilLab'))->with([
            'title' => $title,
        ]);
    }

    public function roHasil()
    {
        $title    = 'Hasil Penunjang';
        $appUrlRo = env('APP_URLRO');
        $hasilRo  = "Silahkan Ketikan No RM dan tekan Enter/Klik Tombol Cari";
        $hasilLab = "Silahkan Ketikan No RM dan tekan Enter/Klik Tombol Cari";
        return view('RO.Hasil.main', compact('appUrlRo', 'hasilRo', 'hasilLab'))->with([
            'title' => $title,

        ]);
    }

    public function gizi()
    {
        $title  = 'Gizi';
        $sub    = GiziDxSubKelasModel::with('domain')->get();
        $dxMed  = DiagnosaModel::get();
        $dokter = $this->pegawai([1, 7, 8]);

        $dokter = array_map(function ($item) {
            return (object) $item;
        }, $dokter);
        // dd($sub);
        return view('Gizi.Trans.main', compact('title', 'sub', "dxMed", "dokter"));
    }
    public function riwayatGizi()
    {
        $title = 'Riwayat Gizi';
        return view('Gizi.Riwayat.main')->with('title', $title);
    }
    public function masterGizi()
    {
        $title  = 'Master Gizi';
        $domain = GiziDxDomainModel::all();
        $kelas  = GiziDxKelasModel::all();

        return view('Gizi.Master.main', compact('title', 'domain', 'kelas'));
    }

    public function riwayatKunjungan()
    {
        $title = 'Riwayat Kunjungan';
        return view('Laporan.Pasien.main')->with('title', $title);
    }

    public function mappingDx()
    {
        $title = 'Mapping Diagnosa';
        $data  = DiagnosaMapModel::orderBy('updated_at', 'desc')->get();
        return view('Diagnosa.main', compact('data'))->with('title', $title);
    }

}
