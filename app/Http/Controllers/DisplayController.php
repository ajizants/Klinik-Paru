<?php

namespace App\Http\Controllers;

use App\Models\KominfoModel;
use App\Models\LaboratoriumKunjunganModel;
use App\Models\ROTransaksiHasilModel;
use App\Models\ROTransaksiModel;
use Carbon\Carbon;

class DisplayController extends Controller
{
    public function loket()
    {
        $title = 'Daftar Tunggu Loket';
        // Akses video dari folder yang di-share di jaringan
        $videos = null;
        $client = new KominfoModel();
        $params = [];
        $jadwal = $client->jadwalPoli($params);
        // return $jadwal;

        return view('Display.loket', compact('title', 'videos', 'jadwal'));
    }

    public function listTungguTensi()
    {
        $listTunggu = [];

        $client = new KominfoModel();
        $listTunggu = $client->getTungguTensi();
        // return $listTunggu;
        // Cek apakah $listTunggu adalah array dan tidak mengandung error
        if (is_array($listTunggu) && !isset($listTunggu['error'])) {
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
        $videos = null;

        $listTunggu = $this->listTungguTensi();
        // return $data;

        if (isset($listTunggu['response']['data']) && is_array($listTunggu['response']['data'])) {
            $filteredData = array_filter(array_map(function ($d) {
                $d['status'] = 'belum';
                if (empty($d['laboratorium'])) {
                    dd("kosong");
                    return null;
                }
                return $d;
            }, $listTunggu['response']['data']));
        }
        // return $data;

        return view('Display.tensi', compact('title', 'listTunggu'));
    }

    public function tungguLab()
    {
        $tgl = date('Y-m-d');
        // $tgl = '2024-10-19';
        $dataLab = LaboratoriumKunjunganModel::with('pemeriksaan.pemeriksaan')
            ->where('created_at', 'like', '%' . $tgl . '%')
            ->get();

        $tungguLab = []; // Inisialisasi array
        $estimasiCounts = []; // Untuk menghitung frekuensi estimasi

        foreach ($dataLab as $d) {
            $pemeriksaan = $d->pemeriksaan;
            $nonNullHasilCount = 0;

            foreach ($pemeriksaan as $periksa) {
                if (!is_null($periksa->hasil)) {
                    $nonNullHasilCount++;
                }
            }

            $jmlh = $pemeriksaan->count();

            if ($nonNullHasilCount == 0) {
                $status = 'Belum';
            } else if ($nonNullHasilCount < $jmlh) {
                $status = 'Belum';
            } else {
                $status = 'Selesai';
            }

            // Cek apakah 'pemeriksaan' dan 'pemeriksaan' di dalamnya tersedia
            if (!empty($d->pemeriksaan)) {
                foreach ($d->pemeriksaan as $pemeriksaan) {
                    if (isset($pemeriksaan->pemeriksaan)) {
                        $estimasi = $pemeriksaan->pemeriksaan->estimasi;

                        // Hitung frekuensi kemunculan estimasi
                        if (isset($estimasiCounts[$estimasi])) {
                            $estimasiCounts[$estimasi]++;
                        } else {
                            $estimasiCounts[$estimasi] = 1;
                        }
                    }
                }
            }

            // Dapatkan estimasi dengan frekuensi tertinggi
            $estimasiTerbanyak = !empty($estimasiCounts)
            ? array_search(max($estimasiCounts), $estimasiCounts)
            : null;

            $jam_masuk = Carbon::parse($d->created_at)->format('H:i');

            $tungguLab[] = [
                'id' => $d->id,
                'norm' => $d->norm,
                'nama' => $d->nama,
                'alamat' => $d->alamat,
                'jam_masuk' => $jam_masuk,
                'satuan' => $pemeriksaan->pemeriksaan->satuan ?? null,
                'normal' => $pemeriksaan->pemeriksaan->normal ?? null,
                'estimasi' => $estimasiTerbanyak,
                'status' => $status,
            ];
        }

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
            $status = "Belum";
            $hasil = ROTransaksiHasilModel::where('norm', $d->norm)->where('tanggal', 'like', '%' . $tgl . '%')->first();

            if ($hasil) {
                $status = "Selesai";
            }

            $tungguRo[] = [
                'id' => $d->id,
                'norm' => $d->norm,
                'nama' => $d->nama,
                'alamat' => $d->alamat,
                'jam_masuk' => $jam_masuk,
                'estimasi' => 15,
                'status' => $status,
            ];
        }

        return $tungguRo;
    }
    public function lab()
    {

        $title = 'Daftar Tunggu';
        $tungguLab = $this->tungguLab();
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
            'no_rm' => '',
            'tanggal_awal' => Carbon::now()->format('Y-m-d'),
            'tanggal_akhir' => Carbon::now()->format('Y-m-d'),
        ];
        $dataPendaftaran = [];
        $listTunggu = [];
        $client = new KominfoModel();
        $dataPendaftaran = $client->pendaftaranRequest($params);
        // return $dataPendaftaran;
        $dataPendaftaran = ["error" => "Server error: `POST https:\/\/kkpm.banyumaskab.go.id\/api_kkpm\/v1\/pendaftaran\/data_pendaftaran` resulted in a `500 Internal Server Error` response:\n\n<div style=\"border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;\">\n\n<h4>A PHP Error was encountered<\/h4>\n\n<p>S (truncated...)\n"];
        if (is_array($dataPendaftaran) && !isset($dataPendaftaran['error'])) {
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

}
