<?php
namespace App\Http\Controllers;

use App\Models\KominfoModel;
use App\Models\LaboratoriumKunjunganModel;
use App\Models\ROTransaksiHasilModel;
use App\Models\ROTransaksiModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DisplayController extends Controller
{
    public function loket()
    {
        $title       = 'Daftar Tunggu Loket';
        $client      = new KominfoModel();
        $params      = [];
        $jadwal      = $client->jadwalPoli($params);
        $listTunggu  = $this->listTungguLoket()['tunggu'];
        $listSelesai = $this->listTungguLoket()['panggil'];
        $loket1      = $this->listTungguLoket()['loket1'];
        $loket2      = $this->listTungguLoket()['loket2'];
        return view('Display.loket', compact('title', 'jadwal', 'listTunggu', 'listSelesai', 'loket1', 'loket2'));
    }

    public function listTungguLoket()
    {
        $client      = new KominfoModel();
        $listTunggu  = $client->getTungguLoket();
        $dataPanggil = $client->getDataLoket();
        $dataPanggil = $dataPanggil['data'];
        $loket1      = collect($dataPanggil)->where('menuju_ke', 'Loket Pendaftaran 1')->first();
        $loket2      = collect($dataPanggil)->where('menuju_ke', 'Loket Pendaftaran 2')->first();

        // dd($listTunggu);
        $listTunggu = $listTunggu['data'];
        if (is_array($listTunggu) && ! isset($listTunggu['error'])) {
            $listTunggu = array_filter($listTunggu, function ($item) {
                return in_array($item['keterangan'], ['SEDANG DIPANGGIL', 'MENUNGGU DIPANGGIL', 'SKIP']);
            });
        }
        $res = [
            'tunggu'  => $listTunggu,
            'panggil' => $dataPanggil,
            'loket1'  => $loket1 ?? "-",
            'loket2'  => $loket2 ?? "-",
        ];
        return $res;
    }

    public function farmasi()
    {
        $title      = 'Daftar Tunggu Farmasi';
        $videos     = null;
        $client     = new KominfoModel();
        $params     = [];
        $jadwal     = $client->jadwalPoli($params);
        $listTunggu = $this->listTungguFarmasi();

        // return $listTunggu;
        $filteredData = array_filter(array_map(function ($d) {
            $d['status'] = 'Antri';
            return $d;
        }, $listTunggu));

        $listTunggu = array_filter($filteredData);
        usort($listTunggu, function ($a, $b) {
            return ($a['ket'] === 'Menunggu' ? 0 : 1) <=> ($b['ket'] === 'Menunggu' ? 0 : 1);
        });
        // Filter listTunggu untuk membuat dua daftar: Menunggu dan Selesai
        $listMenunggu = array_filter($listTunggu, fn($item) => $item['ket'] === 'Menunggu');
        $listSelesai  = array_filter($listTunggu, fn($item) => $item['ket'] === 'Selesai');
        // return $listTunggu;

        return view('Display.farmasi', compact('title', 'videos', 'jadwal', 'listTunggu', 'listMenunggu', 'listSelesai'));
    }

    public function listTungguFarmasi()
    {
        $client     = new KominfoModel();
        $listTunggu = $client->getTungguFaramsi();
        $listTunggu = $listTunggu['data'];

        foreach ($listTunggu as &$item) {
            $now          = Carbon::now();
            $createdAtLog = Carbon::parse($item['created_at_log']);

            // Tambahkan 30 menit ke waktu `created_at_log`
            $createdAtPlus30 = $createdAtLog->addMinutes(30);

            if ($createdAtPlus30 >= $now) {
                $item['ket'] = 'Menunggu';
            } else {
                $item['ket'] = 'Selesai';
            }
            try {
                switch ($item['keterangan']) {
                    case 'SEDANG DIPANGGIL':
                        $item['status'] = 'Belum';
                        break;
                    case 'SELESAI DIPANGGIL':
                        $item['status'] = 'Antri';
                        break;
                    case 'PULANG':
                        $item['status'] = 'Antri';
                        break;
                    default:
                        $item['status'] = 'Unknown';
                }
            } catch (\Exception $e) {
                Log::error('Database connection failed: ' . $e->getMessage());
                $item['status'] = 'Database connection error';
            }
        }

        return $listTunggu;

    }

    public function listTungguTensi()
    {
        $listTunggu = [];

        $client     = new KominfoModel();
        $listTunggu = $client->getTungguTensi();

        // return $listTunggu;
        // Cek apakah $listTunggu adalah array dan tidak mengandung error
        if (is_array($listTunggu) && ! isset($listTunggu['error'])) {
            // Lakukan filter jika tidak ada error
            $listTunggu = array_filter($listTunggu['data'], function ($item) {
                return $item['keterangan'] !== 'SELESAI DIPANGGIL';
            });
        }
        return $listTunggu;

    }

    public function tensi()
    {

        $title = 'Daftar Tunggu Tensi';
        // Akses video dari folder yang di-share di jaringan
        $listTunggu = $this->listTungguTensi();
        return $listTunggu;
        if (isset($listTunggu['response']['data']) && is_array($listTunggu['response']['data'])) {
            $filteredData = array_filter(array_map(function ($d) {
                $d['status'] = 'belum';
                if (empty($d['laboratorium'])) {
                    // dd("kosong");
                    return null;
                }
                return $d;
            }, $listTunggu['response']['data']));
        }
        // return $data;

        $client = new KominfoModel();
        $params = [];
        $jadwal = $client->jadwalPoli($params);

        return view('Display.tensi2', compact('title', 'listTunggu', 'jadwal'));
    }

    public function tungguLab()
    {
        $tgl = date('Y-m-d');
        // $tgl = '2024-10-19'; // Bisa digunakan untuk tanggal tertentu
        $dataLab = LaboratoriumKunjunganModel::with('pemeriksaan.pemeriksaan')
            ->where('created_at', 'like', '%' . $tgl . '%')
            ->get();

        $tungguLab = []; // Inisialisasi array

        foreach ($dataLab as $d) {
            $estimasi          = 10; // Nilai default estimasi
            $pemeriksaan       = $d->pemeriksaan;
            $nonNullHasilCount = 0;
            $params            = ['BTA 1', 'BTA 2', 'Ureum darah', 'Creatinin darah', 'Asam Urat', 'SGOT', 'SGPT', 'Dlukosa darah', 'Trigliserid'];

            foreach ($pemeriksaan as $periksa) {
                // Mengecek apakah hasil pemeriksaan tidak null
                if (! is_null($periksa->hasil)) {
                    $nonNullHasilCount++;
                }

                // Menambahkan nama pemeriksaan dari relasi nmLayanan
                $periksa->nmPemeriksaan = $periksa->pemeriksaan->nmLayanan;
                $estimasiLayanan        = $periksa->pemeriksaan->estimasi;

                // Mengecek apakah nmPemeriksaan ada dalam array params
                if (in_array($periksa->nmPemeriksaan, $params)) {
                    $estimasi = 60; // Mengubah estimasi menjadi 60 jika nmPemeriksaan ditemukan dalam params
                }
            }

            $jmlh = $pemeriksaan->count();

            // Menentukan status
            if ($nonNullHasilCount == 0) {
                $status = 'Belum';
            } else if ($nonNullHasilCount < $jmlh) {
                $status = 'Belum';
            } else {
                $status = 'Selesai';
            }

            $jam_masuk = Carbon::parse($d->created_at)->format('H:i');

            // Menambahkan data tungguLab
            $tungguLab[] = [
                'id'        => $d->id,
                'norm'      => $d->norm,
                'nama'      => $d->nama,
                'alamat'    => $d->alamat,
                'jam_masuk' => $jam_masuk,
                'estimasi'  => $estimasi,
                'status'    => $status,
            ];
        }

        // Mengurutkan berdasarkan status
        usort($tungguLab, function ($a, $b) {
            return ($a['status'] === 'Belum' ? -1 : 1) <=> ($b['status'] === 'Belum' ? -1 : 1);
        });

        return $tungguLab;
    }

    public function tungguRo()
    {
        $tgl = date('Y-m-d');
        // $tgl = '2024-10-19';
        $dataRo = ROTransaksiModel::where('created_at', 'like', '%' . $tgl . '%')
            ->get();
        // return $dataRo;

        $tungguRo = []; // Inisialisasi array

        foreach ($dataRo as $d) {
            $jam_masuk = Carbon::parse($d->created_at)->format('H:i');
            $status    = "Belum";
            $hasil     = ROTransaksiHasilModel::where('norm', $d->norm)->where('tanggal', 'like', '%' . $tgl . '%')->first();

            if ($hasil) {
                $status = "Selesai";
            }

            $tungguRo[] = [
                'id'        => $d->id,
                'norm'      => $d->norm,
                'nama'      => $d->nama,
                'alamat'    => $d->alamat,
                'jam_masuk' => $jam_masuk,
                'estimasi'  => 15,
                'status'    => $status,
            ];
        }
        usort($tungguRo, function ($a, $b) {
            return ($a['status'] === 'Selesai' ? 0 : 1) <=> ($b['status'] === 'Selesai' ? 0 : 1);
        });

        return $tungguRo;
    }
    public function lab()
    {

        $title     = 'Daftar Tunggu';
        $tungguLab = $this->tungguLab();
        // return $tungguLab;
        $tungguRo = $this->tungguRo();

        return view('Display.lab', compact('title', 'tungguLab', 'tungguRo'));
    }
    public function poli($id)
    {
        // Akses video dari folder yang di-share di jaringan
        $videos = null;
        switch ($id) {
            case "agil":
                $dokter = 'dr. AGIL DANANJAYA, Sp.P';
                break;
            case "nova":
                $dokter = 'dr. Cempaka Nova Intani, Sp.P, FISR., MM.';
                break;
            case "filly":
                $dokter = 'dr. FILLY ULFA KUSUMAWARDANI';
                break;
            case "sigit":
                $dokter = 'dr. SIGIT DWIYANTO';
                break;
            default:
                abort(404, 'Dokter tidak ditemukan');
        }
        $title = 'Tunggu Poli ' . $dokter;
        // return $dokter;
        $params = [
            'no_rm'         => '',
            'tanggal_awal'  => Carbon::now()->format('Y-m-d'),
            'tanggal_akhir' => Carbon::now()->format('Y-m-d'),
        ];
        $dataPendaftaran = [];
        $listTunggu      = [];
        $client          = new KominfoModel();
        $dataPendaftaran = $client->pendaftaranRequest($params);
        // return $dataPendaftaran;
        $dataPendaftaran = ["error" => "Server error: `POST https:\/\/kkpm.banyumaskab.go.id\/api_kkpm\/v1\/pendaftaran\/data_pendaftaran` resulted in a `500 Internal Server Error` response:\n\n<div style=\"border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;\">\n\n<h4>A PHP Error was encountered<\/h4>\n\n<p>S (truncated...)\n"];
        if (is_array($dataPendaftaran) && ! isset($dataPendaftaran['error'])) {
            $listTunggu = array_filter($dataPendaftaran, function ($item) use ($dokter) {
                return $item['dokter_nama'] === $dokter && $item['status_pulang'] === 'Belum Pulang';
            });
        }
        // Mengubah hasil array filter menjadi object (Collection)
        $listTunggu = collect(array_values($listTunggu));

        // Mengembalikan sebagai objek
        // return $listTunggu;

        return view('Display.poli', compact('title', 'videos', 'listTunggu', 'dokter'));
    }
    public function rme()
    {

        $title = 'Grafik Penggunaan RME ';

        return view('Laporan.dokter')->with('title', $title);
    }

}
