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

        $client = new KominfoModel();
        $data   = $client->getTungguTensi();

        $dataTunggu = $data['data']['data'];

        // Cek apakah $data adalah array dan tidak mengandung error
        if (is_array($data['data']['data']) && ! isset($data['error'])) {
            // Lakukan filter jika tidak ada error
            $listTunggu = array_filter($dataTunggu, function ($item) {
                return $item['keterangan'] === 'MENUNGGU DIPANGGIL';
            });
            $listSelesai = array_filter($dataTunggu, function ($item) {
                return $item['keterangan'] === 'SELESAI DIPANGGIL';
            });
            $skip = array_filter($dataTunggu, function ($item) {
                return $item['keterangan'] === 'SKIP';
            });

        }
        $dataAtas = $data['dataAtas']['data'];
        return [
            'tunggu'   => array_values($listTunggu),
            'selesai'  => array_values($listSelesai),
            'skip'     => array_values($skip),
            'dataAtas' => $dataAtas,
        ];
    }

    public function tensi()
    {

        $title = 'Daftar Tunggu Tensi';
        // Akses video dari folder yang di-share di jaringan
        $data = $this->listTungguTensi();
        // return $data;
        $dataAtas = $data['dataAtas'];
        // return $dataAtas;
        $jumlahMenunggu = count($data['tunggu']);
        // return $jumlahMenunggu;
        $jumlahSelesai = count($data['selesai']);
        // return $jumlahSelesai;
        $listTunggu  = $data['tunggu'];
        $listSelesai = $data['selesai'];
        ///ambil 3 data dari dataAtas
        $sedangDipanggil = array_slice($dataAtas, 0, 3);
        // return $sedangDipanggil;
        // $sedangDipanggil = [];

        $compact = compact('title', 'listTunggu', 'listSelesai', 'sedangDipanggil', 'jumlahMenunggu', 'jumlahSelesai');
        // return $compact;
        $client = new KominfoModel();
        $params = [];
        $jadwal = $client->jadwalPoli($params);

        return view('Display.tensi', $compact)->with('jadwal', $jadwal);
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
    // public function poli($id)
    // {
    //     switch ($id) {
    //         case "agil":
    //             $dokter = 'dr. Agil Dananjaya, Sp.P';
    //             break;
    //         case "nova":
    //             $dokter = 'dr. Cempaka Nova Intani, Sp.P, FISR., MM.';
    //             break;
    //         case "filly":
    //             $dokter = 'dr. Filly Ulfa Kusumawardani';
    //             break;
    //         case "sigit":
    //             $dokter = 'dr. Sigit Dwiyanto';
    //             break;
    //         default:
    //             abort(404, 'Dokter tidak ditemukan');
    //     }
    //     $title = 'Tunggu Poli ' . $dokter;
    //     // return $dokter;
    //     $params = [
    //         'no_rm' => '',
    //         'tanggal_awal' => Carbon::now()->format('Y-m-d'),
    //         'tanggal_akhir' => Carbon::now()->format('Y-m-d'),
    //     ];
    //     $params2 = [
    //         'no_rm' => '',
    //         'tgl_awal' => Carbon::now()->format('Y-m-d'),
    //         'tgl_akhir' => Carbon::now()->format('Y-m-d'),
    //     ];
    //     $dataPendaftaran = [];
    //     $listTunggu = [];
    //     $client = new KominfoModel();
    //     $dataPendaftaran = $client->pendaftaranRequest($params);
    //     $data = $client->getTungguPoli($params2);
    //     // return $dataPendaftaran;
    //     if (is_array($dataPendaftaran) && !isset($dataPendaftaran['error'])) {
    //         $listTunggu = array_filter($dataPendaftaran, function ($item) use ($dokter) {
    //             return $item['dokter_nama'] === $dokter && $item['status_pulang'] === 'Belum Pulang';
    //         });
    //     }
    //     // Mengubah hasil array filter menjadi object (Collection)
    //     $listTunggu = collect(array_values($listTunggu));
    //     $dataAtas = $data['data2']['data'];
    //     // return $dataAtas;
    //     return $listTunggu;

    //     return view('Display.poli', compact('title', 'videos', 'listTunggu', 'dokter'));
    // }

    public function rme()
    {

        $title = 'Grafik Penggunaan RME ';

        return view('Laporan.dokter')->with('title', $title);
    }

    public function poli($id)
    {
        $dokter = $this->getDokterName($id);
        $title  = 'Tunggu Poli ' . $dokter;

        $listTunggu = $this->getListTungguByDokter($dokter);
        // $dataAtas = $this->getDataAtas();
        $dataPanggil = $this->getDataPanggilPoli($id);
        $client      = new KominfoModel();
        $jadwal      = $client->jadwalPoli($params = []);

        // return $listTunggu;

        // Jika ingin kembalikan ke tampilan:
        return view('Display.poli', compact('title', 'listTunggu', 'dokter', 'dataPanggil', 'jadwal', 'id'));
        // return view('Display.poli', compact('title', 'listTunggu', 'dokter', 'dataAtas', 'dataPanggil'));
    }

/**
 * Ambil nama dokter berdasarkan ID
 */
    private function getDokterName($id)
    {
        return match ($id) {
            "agil" => 'dr. Agil Dananjaya, Sp.P',
            "nova" => 'dr. Cempaka Nova Intani, Sp.P, FISR., MM.',
            "filly" => 'dr. Filly Ulfa Kusumawardani',
            "sigit" => 'dr. Sigit Dwiyanto',
            default => abort(404, 'Dokter tidak ditemukan'),
        };
    }

/**
 * Ambil daftar pasien yang belum pulang berdasarkan nama dokter
 */
    private function getListTungguByDokter($dokter)
    {
        $params = [
            'no_rm'         => '',
            'tanggal_awal'  => Carbon::now()->format('Y-m-d'),
            'tanggal_akhir' => Carbon::now()->format('Y-m-d'),
        ];
        $client          = new KominfoModel();
        $dataPendaftaran = $client->pendaftaranRequest($params);

        if (is_array($dataPendaftaran) && ! isset($dataPendaftaran['error'])) {
            $listTunggu = array_filter($dataPendaftaran, function ($item) use ($dokter) {
                return $item['dokter_nama'] === $dokter && $item['status_pulang'] === 'Belum Pulang';
            });

            return collect(array_values($listTunggu));
        }

        return collect();
    }

/**
 * Ambil data atas dari KominfoModel
 */
    private function getDataAtas()
    {
        $params2 = [
            'no_rm'     => '',
            'tgl_awal'  => Carbon::now()->format('Y-m-d'),
            'tgl_akhir' => Carbon::now()->format('Y-m-d'),
        ];
        $client = new KominfoModel();
        $data   = $client->getTungguPoli($params2);

        return $data['data2']['data'] ?? [];
    }
    private function getDataPanggilPoli($id)
    {
        $params2 = [
            'no_rm'     => '',
            'tgl_awal'  => Carbon::now()->format('Y-m-d'),
            'tgl_akhir' => Carbon::now()->format('Y-m-d'),
        ];
        $client       = new KominfoModel();
        $data         = $client->getTungguPoli($params2);
        $data         = $data['data3']['data'];
        $ruangPeriksa = [
            'filly' => 'Ruang Periksa 1',
            'nova'  => 'Ruang Periksa 2',
            'sigit' => 'Ruang Periksa 3',
            'agil'  => 'Ruang Periksa 4',
        ];
        $ruang = $ruangPeriksa[$id];

        // dd($data);
        //filter berdasarkan menuju_ke
        $data = array_filter($data, function ($item) use ($ruang) {
            return $item['menuju_ke'] === $ruang;
        });
        $data = array_values($data);

        return $data[0] ?? [];
    }

    public function listTungguPoli($id)
    {
        $dokter     = $this->getDokterName($id);
        $listTunggu = $this->getListTungguByDokter($dokter);
        // $dataAtas = $this->getDataAtas();
        $dataPanggil = $this->getDataPanggilPoli($id);
        $res         = [
            // 'dataAtas' => $dataAtas,
            'dataPanggil' => $dataPanggil,
            'tunggu'      => $listTunggu,
        ];

        return response()->json($res);
    }

    public function dokter()
    {
        $title = 'Jumlah Antrian Poli';
        $data  = $this->dataJumlahTiapdokter();

        return view('Display.dokter', compact('title', 'data'));
    }

    private function getListTungguPoli()
    {
        $params = [
            'no_rm'     => '',
            'tgl_awal'  => Carbon::now()->format('Y-m-d'),
            'tgl_akhir' => Carbon::now()->format('Y-m-d'),
        ];
        // $params = [
        //     'no_rm' => '',
        //     'tgl_awal' => '2025-03-01',
        //     'tgl_akhir' => '2025-03-01',
        // ];
        $client          = new KominfoModel();
        $dataPendaftaran = $client->tungguPoli($params);
        $data            = $dataPendaftaran['data'];
        // $data = array_filter($dataPendaftaran['data'], function ($item) {
        //     return isset($item['keterangan']) && $item['keterangan'] === 'SELESAI DIPANGGIL';
        // });

        $dataPenunjang = $client->tungguRoLab($params);
        // dd($dataPenunjang);

        $tungguLab = array_filter($dataPenunjang['data']['data'], function ($item) {
            return isset($item['keterangan']) && $item['keterangan'] === 'MENUNGGU DIPANGGIL';
        });
        // dd($tungguLab);

        $tungguRo = array_filter($dataPenunjang['data2']['data'], function ($item) {
            return isset($item['keterangan']) && $item['keterangan'] === 'MENUNGGU DIPANGGIL';
        });

        // Gabungkan dan hilangkan duplikat berdasarkan pasien_no_rm + created_at
        $combined = array_merge($data, $tungguLab, $tungguRo);
        // dd($combined);

        $finalData = array_values($combined);
        return $finalData;
        // Gunakan associative key gabungan untuk menyaring duplikat
        $unique = [];
        foreach ($combined as $item) {
            $key = $item['no_reg'];
            if (! isset($unique[$key])) {
                $unique[$key] = $item;
            }
        }

        $finalData = array_values($unique);
        return $finalData;

    }

    public function dataJumlahTiapdokter()
    {
        $agil  = $this->getDokterName('agil');
        $sigit = $this->getDokterName('sigit');
        $filly = $this->getDokterName('filly');
        $nova  = $this->getDokterName('nova');

        $data = $this->getListTungguPoli();
        // $params = [
        //     'no_rm'         => '',
        //     'tanggal_awal'  => Carbon::now()->format('Y-m-d'),
        //     'tanggal_akhir' => Carbon::now()->format('Y-m-d'),
        // ];
        // $client = new KominfoModel();
        // $data   = $client->pendaftaranRequest($params);

        if (is_array($data) && ! isset($data['error'])) {
            $listAgil = array_filter($data, function ($item) use ($agil) {
                return $item['dokter_nama'] === $agil;
            });
            $listSigit = array_filter($data, function ($item) use ($sigit) {
                return $item['dokter_nama'] === $sigit;
            });
            $listFilly = array_filter($data, function ($item) use ($filly) {
                return $item['dokter_nama'] === $filly;
            });
            $listNova = array_filter($data, function ($item) use ($nova) {
                return $item['dokter_nama'] === $nova;
            });

            $listTungguAgil = array_filter($data, function ($item) use ($agil) {
                return $item['dokter_nama'] === $agil && $item['keterangan'] === 'MENUNGGU DIPANGGIL';
            });
            $listTungguSigit = array_filter($data, function ($item) use ($sigit) {
                return $item['dokter_nama'] === $sigit && $item['keterangan'] === 'MENUNGGU DIPANGGIL';
            });
            $listTungguFilly = array_filter($data, function ($item) use ($filly) {
                return $item['dokter_nama'] === $filly && $item['keterangan'] === 'MENUNGGU DIPANGGIL';
            });
            $listTungguNova = array_filter($data, function ($item) use ($nova) {
                return $item['dokter_nama'] === $nova && $item['keterangan'] === 'MENUNGGU DIPANGGIL';
            });

            // return collect(array_values($listTunggu));
        }

        //carikan jumlah masing msing list tunggu
        $listTungguAgil  = count($listTungguAgil);
        $listTungguSigit = count($listTungguSigit);
        $listTungguFilly = count($listTungguFilly);
        $listTungguNova  = count($listTungguNova);

        $listAgil  = count($listAgil);
        $listSigit = count($listSigit);
        $listFilly = count($listFilly);
        $listNova  = count($listNova);

        $listSelesaiAgil  = $listAgil - $listTungguAgil;
        $listSelesaiSigit = $listSigit - $listTungguSigit;
        $listSelesaiFilly = $listFilly - $listTungguFilly;
        $listSelesaiNova  = $listNova - $listTungguNova;

        return [
            'listTungguAgil'   => $listTungguAgil,
            'listTungguSigit'  => $listTungguSigit,
            'listTungguFilly'  => $listTungguFilly,
            'listTungguNova'   => $listTungguNova,
            'listAgil'         => $listAgil,
            'listSigit'        => $listSigit,
            'listFilly'        => $listFilly,
            'listNova'         => $listNova,
            'listSelesaiAgil'  => $listSelesaiAgil,
            'listSelesaiSigit' => $listSelesaiSigit,
            'listSelesaiFilly' => $listSelesaiFilly,
            'listSelesaiNova'  => $listSelesaiNova,
        ];
    }
}
