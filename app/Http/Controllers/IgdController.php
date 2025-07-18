<?php
namespace App\Http\Controllers;

use App\Models\BMHPIGDInStokModel;
use App\Models\BMHPModel;
use App\Models\DiagnosaModel;
use App\Models\IGDTransModel;
use App\Models\KominfoModel;
use App\Models\KunjunganModel;
use App\Models\KunjunganWaktuSelesai;
use App\Models\LayananModel;
use App\Models\PegawaiKegiatanModel;
use App\Models\PegawaiModel;
use App\Models\TransaksiBMHPModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IgdController extends Controller
{
    public function igd()
    {
        $title    = 'IGD';
        $pModel   = new PegawaiModel();
        $dokter   = $pModel->olahPegawai([1, 7, 8]);
        $perawat  = $pModel->olahPegawai([10, 14, 15, 23]);
        $lModel   = new LayananModel();
        $tindakan = $lModel->layanans([2, 3, 5, 6]);
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
    public function gudangIGD()
    {
        $title = 'Gudang IGD';
        return view('IGD.GudangIGD.main')->with('title', $title);
    }

    public function chart(Request $request)
    {
        $year = $request->input('year');
        if ($year <= 2024) {
            return $this->chart2024($request);
        } else {
            return $this->chart2025($request);
        }
    }
    public function report_igd(Request $request)
    {
        $year = $request->input('year');
        if ($year <= 2024) {
            return $this->chart2024($request);
        } else {
            return $this->chart2025($request);
        }
    }

    public function chart2024(Request $request)
    {
        $year = $request->input('year', date('Y')); // Gunakan tahun saat ini jika tidak ada input

        if ($year == 2024) {
            // Mengambil data awal dari tabel 't_kunjungan_tindakan'
            $awal = DB::table('t_kunjungan_tindakan')
                ->select(
                    DB::raw('MONTH(t_kunjungan_tindakan.created_at) as bulan'),
                    DB::raw('COUNT(DISTINCT t_kunjungan_tindakan.notrans) as jumlah'),
                    'm_kelompok.kelompok'
                )
                ->join('m_tindakan', 't_kunjungan_tindakan.kdTind', '=', 'm_tindakan.kdTindakan')
                ->join('t_kunjungan', 't_kunjungan.notrans', '=', 't_kunjungan_tindakan.notrans')
                ->join('m_kelompok', 't_kunjungan.kkelompok', '=', 'm_kelompok.kkelompok')
                ->whereIn('m_kelompok.kelompok', ['umum', 'bpjs'])
                ->whereBetween('t_kunjungan_tindakan.created_at', [$year . '-01-01', $year . '-12-31'])
                ->groupBy(DB::raw('MONTH(t_kunjungan_tindakan.created_at)'), 'm_kelompok.kelompok')
                ->orderBy('bulan')
                ->get()
                ->map(function ($item) {
                    return (array) $item; // Konversi objek stdClass ke array
                });
            // return $awal;

            $sisa = $this->cariSisa($year);

            $jumlahKunjunganPerBulan = $this->cariKunjunganPerBulan($year);
            // return $jumlahKunjunganPerBulan;

            // Gabungkan data awal dan sisa, lalu jumlahkan berdasarkan bulan dan kelompok
            $chart = collect($awal)
                ->merge($sisa)
                ->merge($jumlahKunjunganPerBulan)
                ->groupBy(function ($item) {
                    return $item['bulan'] . '|' . $item['kelompok']; // Gabungkan bulan dan kelompok
                })
                ->map(function ($group, $key) use ($jumlahKunjunganPerBulan) {
                    list($bulan, $kelompok) = explode('|', $key);

                    // Find the total kunjungan for the corresponding month
                    $totalKunjungan = $jumlahKunjunganPerBulan->firstWhere('bulan', (int) $bulan)->total ?? 0;

                    return [
                        'bulan'          => (int) $bulan,
                        'kelompok'       => ucfirst($kelompok), // Pastikan format nama kelompok rapi
                        'jumlah'         => $group->sum('jumlah'),
                        'totalKunjungan' => $totalKunjungan, // Assign the correct total kunjungan for the month
                    ];
                })
                ->values()
                ->sortBy('bulan')
                ->values();

            //jangan ambil $chart yang kelompoknya ""
            $chart = $chart->where('kelompok', '!=', '');

            return response()->json($chart, 200, [], JSON_PRETTY_PRINT);
        } else {
            // Jika bukan tahun 2024, ambil data langsung dari tabel
            $chart = DB::table('t_kunjungan_tindakan')
                ->select(
                    DB::raw('MONTH(t_kunjungan_tindakan.created_at) as bulan'),
                    DB::raw('COUNT(DISTINCT t_kunjungan_tindakan.notrans) as jumlah'),
                    'm_kelompok.kelompok'
                )
                ->join('m_tindakan', 't_kunjungan_tindakan.kdTind', '=', 'm_tindakan.kdTindakan')
                ->join('t_kunjungan', 't_kunjungan.notrans', '=', 't_kunjungan_tindakan.notrans')
                ->join('m_kelompok', 't_kunjungan.kkelompok', '=', 'm_kelompok.kkelompok')
                ->whereIn('m_kelompok.kelompok', ['umum', 'bpjs'])
                ->whereYear('t_kunjungan_tindakan.created_at', $year)
                ->groupBy(DB::raw('MONTH(t_kunjungan_tindakan.created_at)'), 'm_kelompok.kelompok')
                ->orderBy('bulan')
                ->get()
                ->map(function ($item) {
                    return [
                        'bulan'    => (int) $item->bulan,
                        'kelompok' => ucfirst($item->kelompok),
                        'jumlah'   => (int) $item->jumlah,
                    ];
                })
                ->values();

            return response()->json($chart, 200, [], JSON_PRETTY_PRINT);
        }
    }
    public function cariKunjunganPerBulan($year)
    {
        $data = KunjunganModel::select(DB::raw('MONTH(tgltrans) as bulan, COUNT(*) as total'))
            ->whereBetween('tgltrans', [$year . '-01-01', $year . '-12-31'])
            ->groupBy(DB::raw('MONTH(tgltrans)'))
            ->get();
        return $data;
    }

    public function cariSisa($year)
    {
        // Mengambil data IGD
        $dataIGD = IGDTransModel::whereBetween('created_at', [$year . '-08-01', $year . '-12-31'])
            ->whereRaw('LENGTH(notrans) = 13')->get();
        // return $dataIGD;
        // return count($dataIGD);

        // Proses setiap item
        foreach ($dataIGD as $item) {
            // Cari data kunjungan berdasarkan notrans
            $kunjungan = KunjunganWaktuSelesai::where('norm', $item->norm)
                ->whereDate('created_at', $item->created_at) // Hanya membandingkan tahun, bulan, dan tanggal
                ->get();

            $noSep = $kunjungan[0]->no_sep ?? null;
            // return $noSep;

            // Tentukan kelompok berdasarkan no_sep
            if ($kunjungan && $noSep != null) {
                $item->kelompok = "BPJS";
            } elseif ($kunjungan && $noSep == null) {
                $item->kelompok = "UMUM";
            } else {
                $item->kelompok = "mbuh";
            }

            // Set nilai bulan dalam format numerik
            $item->bulan = Carbon::parse($item->created_at)->format('m');
        }

        // Hapus duplikasi berdasarkan notrans
        $dataIGD = $dataIGD->unique('notrans')->values();
        // dd($dataIGD);

        // Konversi ke array
        $data = $dataIGD->toArray();
        // return $data;

        // Kelompokkan data berdasarkan bulan dan kelompok
        $sisa = collect($data)
            ->filter(function ($item) {
                return $item['kelompok'] !== 'mbuh'; // Hanya ambil kelompok selain "mbuh"
            })
            ->groupBy(function ($item) {
                // return $item->bulan . '|' . $item->kelompok; // Gabungkan bulan dan kelompok sebagai kunci
                return $item['bulan'] . '|' . $item['kelompok'];
            })
            ->map(function ($group, $key) {
                list($bulan, $kelompok) = explode('|', $key); // Pisahkan bulan dan kelompok
                return [
                    'bulan'    => (int) $bulan, // Konversi bulan ke integer
                    'kelompok' => $kelompok,
                    'jumlah'   => $group->count(),
                ];
            })
            ->values();
        return $sisa;
    }

    public function chart2025(Request $request)
    {
        $year = $request->input('year');

        // Ambil data IGD berdasarkan tahun
        $dataIGD = IGDTransModel::whereYear('created_at', $year)->get(['notrans', 'created_at']);

        // Ambil data kunjungan dalam 1 query, lalu gunakan keyBy untuk akses cepat
        $kunjunganData = KunjunganWaktuSelesai::whereIn('notrans', $dataIGD->pluck('notrans'))
            ->get(['notrans', 'no_sep'])
            ->keyBy('notrans');

        // Hitung jumlah kunjungan per bulan (dari fungsi yang sudah ada)
        $jumlahKunjunganPerBulan = $this->cariKunjunganPerBulan($year);

        // Proses data dengan map, tanpa looping eksplisit
        $dataIGD = $dataIGD->map(function ($item) use ($kunjunganData) {
            $kunjungan = $kunjunganData->get($item->notrans);

            return [
                'notrans'  => $item->notrans,
                'bulan'    => Carbon::parse($item->created_at)->format('m'),
                'kelompok' => $kunjungan ? ($kunjungan->no_sep ? "BPJS" : "UMUM") : "mbuh",
            ];
        })->unique('notrans')->values();

        // Gabungkan data awal dan jumlah kunjungan per bulan
        $chart = $dataIGD->merge($jumlahKunjunganPerBulan)
            ->groupBy(fn($item) => $item['bulan'] . '|' . $item['kelompok'])
            ->map(function ($group, $key) use ($jumlahKunjunganPerBulan) {
                list($bulan, $kelompok) = explode('|', $key);
                $totalKunjungan         = $jumlahKunjunganPerBulan->firstWhere('bulan', (int) $bulan)->total ?? 0;

                return [
                    'bulan'          => (int) $bulan,
                    'kelompok'       => ucfirst($kelompok),
                    'jumlah'         => $group->count(),
                    'totalKunjungan' => $totalKunjungan,
                ];
            })
            ->values()
            ->sortBy('bulan')
            ->where('kelompok', '!=', '') // Filter kelompok kosong
            ->values();

        return response()->json($chart, 200, [], JSON_PRETTY_PRINT);
    }

    public function reportPoin()
    {
        $title = 'Report IGD';

        return view('IGD.report')->with('title', $title);
    }

    public function cariDataTindakan(Request $request)
    {
        $notrans      = $request->input('notrans');
        $dataTindakan = IGDTransModel::with(['tindakan', 'transbmhp.tindakan', 'transbmhp.bmhp', 'petugas.biodata', 'dokter.biodata'])
            ->where('notrans', 'LIKE', '%' . $notrans . '%')
            ->get();

        if ($dataTindakan->isEmpty()) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json($dataTindakan, 200, [], JSON_PRETTY_PRINT);
    }

    public function simpanTindakan(Request $request)
    {
        // Mengambil nilai dari input pengguna
        $norm     = $request->input('norm');
        $notrans  = $request->input('notrans');
        $kdTind   = $request->input('kdTind');
        $petugas  = $request->input('petugas');
        $dokter   = $request->input('dokter');
        $jaminan  = $request->input('jaminan');
        $tglTrans = $request->input('tgltrans');
        $tglNow   = Carbon::now()->format('Y-m-d');

        // dd($jaminan);
        // $created_at = $request->input('tgltrans');
        // $updated_at = $request->input('tgltind');
        // Pastikan $kdTind memiliki nilai yang valid sebelum menyimpan data
        if ($kdTind !== null) {
            // Membuat instance dari model KunjunganTindakan
            $kunjunganTindakan = new IGDTransModel();
            // Mengatur nilai-nilai kolom
            $kunjunganTindakan->kdTind  = $kdTind;
            $kunjunganTindakan->norm    = $norm;
            $kunjunganTindakan->notrans = $notrans;
            $kunjunganTindakan->petugas = $petugas;
            $kunjunganTindakan->dokter  = $dokter;
            $kunjunganTindakan->jaminan = $jaminan;
            //    jika tgl trans sebelum tgl sekarang maka isi creted_at dengan tglTrans dan jam sekarang
            if ($tglTrans < $tglNow) {
                $kunjunganTindakan->created_at = $tglTrans . ' ' . Carbon::now()->format('H:i:s');
                $kunjunganTindakan->updated_at = $tglTrans . ' ' . Carbon::now()->format('H:i:s');
            }
            // $kunjunganTindakan->created_at = $created_at;
            // $kunjunganTindakan->updated_at = $updated_at;

            // Simpan data ke dalam tabel
            $kunjunganTindakan->save();
            $dataIGD = $this->getIgdLast();

            // Respon sukses atau redirect ke halaman lain
            return response()->json(['message' => 'Data berhasil disimpan', 'dataIGD' => $dataIGD], 200, [], JSON_PRETTY_PRINT);
        } else {
            // Handle case when $kdTind is null, misalnya kirim respon error
            return response()->json(['message' => 'kdTind tidak valid'], 400);
        }
    }

    private function getIgdLast()
    {
        $model   = new IGDTransModel();
        $dataIgd = $model->getPelaksanaLast();
        return $dataIgd;
    }

    public function updateTindakan(Request $request)
    {
        $id      = $request->input('id');
        $kdTind  = $request->input('kdTind');
        $petugas = $request->input('petugas');
        $dokter  = $request->input('dokter');

        // Cek apakah ID yang diterima adalah ID yang valid dalam database
        $tindakan = IGDTransModel::find($id);

        if (! $tindakan) {
            return response()->json(['message' => 'Data tindakan tidak ditemukan'], 404);
        }

        // Update nilai kolom dengan nilai yang diterima dari input pengguna
        $tindakan->kdTind  = $kdTind;
        $tindakan->petugas = $petugas;
        $tindakan->dokter  = $dokter;

        // Simpan perubahan ke dalam database
        $tindakan->save();
        $dataIGD = $this->getIgdLast();

        // Respon sukses
        return response()->json(['message' => 'Data tindakan berhasil diperbarui', 'dataIGD' => $dataIGD]);
    }

    public function deleteTindakan(Request $request)
    {
        $id     = $request->input('id');
        $idTind = $request->input('id');
        // Cek apakah ID yang diterima adalah ID yang valid dalam database
        $tindakan = IGDTransModel::find($id);
        $bmhp     = TransaksiBMHPModel::find($idTind);
        if (! $tindakan) {
            return response()->json(['message' => 'Data tindakan tidak ditemukan'], 404);
        }

        // Hapus tindakan dan BMHP terkait jika ditemukan, jika tidak hapus tindakan saja
        if ($bmhp) {
            $bmhp->delete();
        }

        $tindakan->delete();
        $dataIGD = $this->getIgdLast();

        // Respon sukses
        return response()->json(['message' => 'Data tindakan berhasil dihapus', 'dataIGD' => $dataIGD]);
    }
    public function deleteTransaksiBmhp(Request $request)
    {
        $id = $request->input('id');

        $bmhp = TransaksiBMHPModel::find($id);

        if ($bmhp) {
            // dd($bmhp);
            $kdBmhp     = $bmhp->kdBmhp;
            $jml        = $bmhp->jml;
            $instokigd  = BMHPModel::find($kdBmhp);
            $product_id = $instokigd->product_id;
            // dd($product_id);

            $this->updateDeleteStokIGD($product_id, $kdBmhp, $jml, $request->all());

            $bmhp->delete();

            // Respon sukses
            return response()->json(['message' => 'Data tindakan berhasil dihapus']);
        } else {
            // Handle case when $kdTind is null, misalnya kirim respon error
            return response()->json(['message' => 'id tidak valid'], 400);
        }
    }
    private function updateDeleteStokIGD($product_id, $kdBmhp, $jml)
    {
        $updateKeluar = BMHPModel::where('id', $kdBmhp)->first();
        // dd($updateKeluar);
        if ($updateKeluar) {
            $updateKeluar->update([
                'keluar' => $updateKeluar->keluar - $jml,
                'sisa'   => $this->calculateSisa($updateKeluar->stokBaru, $updateKeluar->masuk, $updateKeluar->keluar - $jml),
            ]);
        } else {
            return response()->json(['message' => 'Obat tidak valid'], 400);
        }

        $updateKeluarInStok = BMHPIGDInStokModel::where('product_id', $product_id)->first();
        // dd($updateKeluarInStok);
        if ($updateKeluarInStok) {
            $updateKeluarInStok->update([
                'keluar' => $updateKeluarInStok->keluar - $jml,
                'sisa'   => $this->calculateSisa($updateKeluarInStok->stokBaru, $updateKeluarInStok->masuk, $updateKeluarInStok->keluar - $jml),
            ]);
        } else {
            return response()->json(['message' => 'Obat tidak valid'], 400);
        }
    }

    public function addTransaksiBmhp(Request $request)
    {
        // Ambil data dari permintaan Ajax
        $idTind     = $request->input('idTind');
        $kdTind     = $request->input('kdTind');
        $kdBmhp     = $request->input('kdBmhp');
        $jml        = $request->input('jml');
        $total      = $request->input('total');
        $product_id = $request->input('productID');
        $notrans    = $request->input('notrans');
        // dd($idTind, $kdTind, $kdBmhp, $jml);
        if ($kdBmhp !== null) {
            // Membuat instance dari model KunjunganTindakan
            $transaksibmhp = new TransaksiBMHPModel();
            // Mengatur nilai-nilai kolom
            $transaksibmhp->notrans = $notrans;
            $transaksibmhp->idTind  = $idTind;
            $transaksibmhp->kdTind  = $kdTind;
            $transaksibmhp->kdBmhp  = $kdBmhp;
            $transaksibmhp->jml     = $jml;
            $transaksibmhp->biaya   = $total;

            // Simpan data ke dalam tabel
            $transaksibmhp->save();

            $this->updateStokIGD($product_id, $kdBmhp, $jml, $request->all());
            // Respon sukses atau redirect ke halaman lain
            return response()->json(['message' => 'Data berhasil disimpan']);
        } else {
            // Handle case when $kdTind is null, misalnya kirim respon error
            return response()->json(['message' => 'kdBmhp tidak valid'], 400);
        }
    }
    private function updateStokIGD($product_id, $kdBmhp, $jml)
    {
        $updateKeluar = BMHPModel::where('id', $kdBmhp)->first();
        // dd($updateKeluar);
        if ($updateKeluar) {
            $updateKeluar->update([
                'keluar' => $updateKeluar->keluar + $jml,
                'sisa'   => $this->calculateSisa($updateKeluar->stokBaru, $updateKeluar->masuk, $updateKeluar->keluar + $jml),
            ]);
        } else {
            return response()->json(['message' => 'Obat tidak valid'], 400);
        }

        $updateKeluarInStok = BMHPIGDInStokModel::where('product_id', $product_id)->first();
        // dd($updateKeluarInStok);
        if ($updateKeluarInStok) {
            $updateKeluarInStok->update([
                'keluar' => $updateKeluarInStok->keluar + $jml,
                'sisa'   => $this->calculateSisa($updateKeluarInStok->stokBaru, $updateKeluarInStok->masuk, $updateKeluarInStok->keluar + $jml),
            ]);
        } else {
            return response()->json(['message' => 'Obat tidak valid'], 400);
        }
    }

    private function calculateSisa($stokBaru, $masuk, $keluar)
    {
        // Calculate sisa based on the formula: sisa = stokBaru + masuk - keluar
        return $stokBaru + $masuk - $keluar;
    }
    public function cariTransaksiBmhp(Request $request)
    {
        $idTind = $request->input('idTind');
        // dd($idTind);
        $data = TransaksiBMHPModel::with(['bmhp', 'tindakan'])
            ->where('idTind', 'LIKE', '%' . $idTind . '%')
            ->get();

        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }

    public function cariPoinTotal(Request $request)
    {
        $mulaiTgl   = $request->input('mulaiTgl');
        $selesaiTgl = $request->input('selesaiTgl');

        $query = DB::table(DB::raw('(
            SELECT COUNT(t_kunjungan_tindakan.notrans) AS jml,
                   peg_m_biodata.nip,
                   peg_m_biodata.nama,
                   "Dokter" AS sts
            FROM t_kunjungan_tindakan
            INNER JOIN peg_m_biodata ON t_kunjungan_tindakan.dokter = peg_m_biodata.nip
            WHERE DATE_FORMAT(t_kunjungan_tindakan.created_at, "%Y-%m-%d") BETWEEN ? AND ?
            GROUP BY peg_m_biodata.nip, peg_m_biodata.nama

            UNION

            SELECT COUNT(t_kunjungan_tindakan.notrans) AS jml,
                   peg_m_biodata.nip,
                   peg_m_biodata.nama,
                   "Petugas" AS sts
            FROM t_kunjungan_tindakan
            INNER JOIN peg_m_biodata ON t_kunjungan_tindakan.petugas = peg_m_biodata.nip
            WHERE DATE_FORMAT(t_kunjungan_tindakan.created_at, "%Y-%m-%d") BETWEEN ? AND ?
            GROUP BY peg_m_biodata.nip, peg_m_biodata.nama
        ) as subquery'))
            ->setBindings([$mulaiTgl, $selesaiTgl, $mulaiTgl, $selesaiTgl])
            ->get();

        return response()->json($query, 200, [], JSON_PRETTY_PRINT);
    }
    public function cariPoin(Request $request)
    {
        $mulaiTgl   = $request->input('mulaiTgl');
        $selesaiTgl = $request->input('selesaiTgl');

        $query = DB::table('t_kunjungan_tindakan')
            ->select(
                DB::raw('COUNT(t_kunjungan_tindakan.notrans) AS jml'),
                'peg_m_biodata.nip',
                'peg_m_biodata.nama',
                'm_tindakan.nmTindakan AS tindakan',
                DB::raw('"Dokter" AS sts')
            )
            ->join('peg_m_biodata', 't_kunjungan_tindakan.dokter', '=', 'peg_m_biodata.nip')
            ->join('m_tindakan', 't_kunjungan_tindakan.kdTind', '=', 'm_tindakan.kdTindakan')
            ->whereBetween(DB::raw('DATE_FORMAT(t_kunjungan_tindakan.created_at, "%Y-%m-%d")'), [$mulaiTgl, $selesaiTgl])
            ->groupBy('peg_m_biodata.nip', 'peg_m_biodata.nama', 'm_tindakan.kdTindakan', 'm_tindakan.nmTindakan')

            ->union(

                DB::table('t_kunjungan_tindakan')
                    ->select(
                        DB::raw('COUNT(t_kunjungan_tindakan.notrans) AS jml'),
                        'peg_m_biodata.nip',
                        'peg_m_biodata.nama',
                        'm_tindakan.nmTindakan AS tindakan',
                        DB::raw('"Petugas" AS sts')
                    )
                    ->join('peg_m_biodata', 't_kunjungan_tindakan.petugas', '=', 'peg_m_biodata.nip')
                    ->join('m_tindakan', 't_kunjungan_tindakan.kdTind', '=', 'm_tindakan.kdTindakan')
                    ->whereBetween(DB::raw('DATE_FORMAT(t_kunjungan_tindakan.created_at, "%Y-%m-%d")'), [$mulaiTgl, $selesaiTgl])
                    ->groupBy('peg_m_biodata.nip', 'peg_m_biodata.nama', 'm_tindakan.kdTindakan', 'm_tindakan.nmTindakan')

            )
            ->get();

        return response()->json($query, 200, [], JSON_PRETTY_PRINT);
    }

    public function getRekapJumlahTindakan(Request $request)
    {
        $tglAwal  = $request->input('tglAwal') . ' 00:00:00';
        $tglAkhir = $request->input('tglAkhir') . ' 23:59:59';

        $data = IGDTransModel::with('tindakan')
            ->whereBetween('created_at', [$tglAwal, $tglAkhir])
            ->get();

        $rekap = [];

        foreach ($data as $item) {
            $kdTindakan = $item->kdTind;
            $nmTindakan = $item->tindakan->nmTindakan ?? '-';

            if (! isset($rekap[$kdTindakan])) {
                $rekap[$kdTindakan] = [
                    'kdTindakan' => $kdTindakan,
                    'nmTindakan' => $nmTindakan,
                    'jumlah'     => 0,
                ];
            }

            $rekap[$kdTindakan]['jumlah']++;
        }

        $result = array_values($rekap);

        // Bangun tabel HTML
        $table = '<table class="table table-sm table-bordered table-hover table-striped" id="tableRekapJumlahTindakan" cellspacing="0">';
        $table .= '<thead class="bg bg-orange">';
        $table .= '<tr>
                <th>No</th>
                <th>Kode Tindakan</th>
                <th>Nama Tindakan</th>
                <th>Jumlah</th>
              </tr></thead><tbody>';

        foreach ($result as $i => $row) {
            $table .= '<tr>
                    <td>' . ($i + 1) . '</td>
                    <td>' . $row['kdTindakan'] . '</td>
                    <td>' . $row['nmTindakan'] . '</td>
                    <td>' . $row['jumlah'] . '</td>
                   </tr>';
        }

        $table .= '</tbody></table>';

        // Return JSON dengan data mentah dan HTML tabel
        return response()->json([
            'data' => $result,
            'html' => $table,
        ], 200, [], JSON_PRETTY_PRINT);
    }

    public function poinPegawai($bln, $tahun, $petugas = null)
    {
        $tglAwal  = \Carbon\Carbon::create($tahun, $bln, 1)->isoFormat('YYYY-MM-DD');
        $tglAkhir = \Carbon\Carbon::create($tahun, $bln, 1)->lastOfMonth()->isoFormat('YYYY-MM-DD');

        $tindakanData = $this->getTindakanArray($tglAwal, $tglAkhir)[0];
        // return $tindakanData;
        $dokterData = $this->getTindakanArray($tglAwal, $tglAkhir)[1];
        // return $tindakanData;
        $kominfoData = $this->getKominfoArray($tglAwal, $tglAkhir);
        // return $kominfoData;
        $poinHIV = $this->getPoinHIV($tglAwal, $tglAkhir);
        // return $poinHIV;

        // Gabungkan tindakan dan kominfo
        $combined = $tindakanData;

        foreach ($kominfoData as $jenis => $admins) {
            if (! isset($combined[$jenis])) {
                $combined[$jenis] = [];
            }

            foreach ($admins as $admin => $jumlah) {
                $combined[$jenis][$admin] = ($combined[$jenis][$admin] ?? 0) + $jumlah;
            }
        }

        foreach ($poinHIV as $jenis => $admins) {
            if (! isset($combined[$jenis])) {
                $combined[$jenis] = [];
            }

            foreach ($admins as $admin => $jumlah) {
                $combined[$jenis][$admin] = ($combined[$jenis][$admin] ?? 0) + $jumlah;
            }
        }

        foreach ($dokterData as $jenis => $admins) {
            if (! isset($combined[$jenis])) {
                $combined[$jenis] = [];
            }

            foreach ($admins as $admin => $jumlah) {
                $combined[$jenis][$admin] = ($combined[$jenis][$admin] ?? 0) + $jumlah;
            }
        }

        // Ambil semua nama pelaksana/admin unik
        $allNama = [];
        foreach ($combined as $admins) {
            foreach ($admins as $nama => $jumlah) {
                $allNama[$nama] = true;
            }
        }
        $allNama = array_keys($allNama); // ambil array nama
        sort($allNama);                  // urutkan dari A ke Z

        // Geser REGINA dan VANNIA ke akhir array
        $namaKhusus = ['SIGIT DWIYANTO', 'FILLY ULFA KUSUMAWARDANI', 'CEMPAKA NOVA INTANI', 'AGIL DANANJAYA', 'REGINA DONA ZHAFIRA', 'VANNIA MAULIDINA PRASETYO'];
        $namaDokter = ['SIGIT DWIYANTO', 'FILLY ULFA KUSUMAWARDANI', 'CEMPAKA NOVA INTANI', 'AGIL DANANJAYA'];

        // Buat array baru tanpa nama khusus
        $filtered = array_filter($allNama, function ($nama) use ($namaKhusus) {
            return ! in_array($nama, $namaKhusus);
        });

        // Gabungkan: hasil sort tanpa REGINA & VANNIA + REGINA & VANNIA di akhir
        $allNama = array_merge(array_values($filtered), $namaKhusus);

        // Gabungkan daftar jenis tindakan dari kedua sumber
        $order = [
            'Punctie pleura',
            'Infus',
            'Mantoux Test',
            'E K G',
            'Observasi infus',
            'Penanganan pasien Hemaptoe',
            'Injeksi',
            'Nebulasi ( tanpa harga obat )',
            'Mengantar pasien dirujuk',
            'Spirometri',
            'Konseling VCT',
            'Konseling PITC',
            'Oksigenasi per jam',
            'Anamnesa pasien baru',
            'Asisten dokter',
            'Anamnesa pasien lama',
            'Timbang dan tensi',
            'Lain lain (mencari kartu)',
            'Input data',
        ];

        // Tambahkan tindakan dari Kominfo yang tidak ada di $order
        foreach (array_keys($combined) as $jenis) {
            if (! in_array($jenis, $order)) {
                $order[] = $jenis;
            }
        }

        // Bangun HTML gabungan
        $html = '<table border="1" cellpadding="8" cellspacing="0" id="tablePoinJaspel">';
        $html .= '<thead><tr><th>NO.</th><th class="col-2" width="20%">JENIS PELAYANAN</th>';

        foreach ($allNama as $nama) {
            $html .= '<th>' . htmlspecialchars($nama) . '</th>';
        }
        $html .= '</tr></thead><tbody>';

        $no = 1;
        foreach ($order as $jenis) {
            if (! isset($combined[$jenis])) {
                continue;
            }

            $html .= '<tr>';
            $html .= '<td>' . $no++ . '</td>';
            $html .= '<td>' . htmlspecialchars($jenis) . '</td>';
            foreach ($allNama as $nama) {
                $jumlah = $combined[$jenis][$nama] ?? 0;
                $html .= '<td>' . $jumlah . '</td>';
            }
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';

        return $html;

    }

    private function getTindakanArray($tglAwal, $tglAkhir)
    {
        // $tglAkhir = \Carbon\Carbon::create($tglAkhir)->addDay()->isoFormat('YYYY-MM-DD') . ' 23:59:59';
        // $tglAwal  = \Carbon\Carbon::create($tglAwal)->isoFormat('YYYY-MM-DD') . ' 00:00:00';
        $tindakan = IGDTransModel::with('pelaksana.biodata', 'tindakan', 'dok.biodata')
            ->whereBetween('created_at', [$tglAwal . ' 00:00:00', $tglAkhir . ' 23:59:59'])
        // ->where('kdTind', 9)
        // ->where('petugas', '197907231999032002')
            ->get();
        // return $tindakan;
        $tindakanDokter = $tindakan;

        $data = [];

        foreach ($tindakan as $item) {
            // dd($item);
            $nmTindakan    = $item->tindakan->nmTindakan ?? '-';
            $namaPelaksana = $item->pelaksana->biodata->nama ?? 'Tidak diketahui';

            $data[$nmTindakan][$namaPelaksana] = ($data[$nmTindakan][$namaPelaksana] ?? 0) + 1;
        }

        // Tambahkan Observasi Infus dengan jumlah yang sama seperti Infus
        if (isset($data['Infus'])) {
            $data['Observasi infus'] = $data['Infus'];
        }

        // Tambahkan "Lain lain (mencari kartu)" untuk semua pelaksana yang ada
        $pelaksanaTersedia = [];
        foreach ($data as $tindakan) {
            foreach ($tindakan as $pelaksana => $jumlah) {
                $pelaksanaTersedia[$pelaksana] = true;
            }
        }

        foreach (array_keys($pelaksanaTersedia) as $pelaksana) {
            $data['Lain lain (mencari kartu)'][$pelaksana] = 0;
        }

        $dokter = [];

        foreach ($tindakanDokter as $item) {
            $nmTindakan    = $item->tindakan->nmTindakan ?? '-';
            $namaPelaksana = $item->dok->biodata->nama ?? 'Tidak diketahui';

            $dokter[$nmTindakan][$namaPelaksana] = ($dokter[$nmTindakan][$namaPelaksana] ?? 0) + 1;
        }

        // Tambahkan Observasi Infus dengan jumlah yang sama seperti Infus
        if (isset($dokter['Infus'])) {
            $dokter['Observasi infus'] = $dokter['Infus'];
        }

        // Tambahkan "Lain lain (mencari kartu)" untuk semua pelaksana yang ada
        $pelaksanaTersedia = [];
        foreach ($dokter as $tindakan) {
            foreach ($tindakan as $pelaksana => $jumlah) {
                $pelaksanaTersedia[$pelaksana] = true;
            }
        }

        foreach (array_keys($pelaksanaTersedia) as $pelaksana) {
            $dokter['Lain lain (mencari kartu)'][$pelaksana] = 0;
        }
        // return $data;
        return [$data, $dokter];
    }
    // private function getTindakanArray($tglAwal, $tglAkhir)
    // {
    //     // $tglAkhir = \Carbon\Carbon::create($tglAkhir)->addDay()->isoFormat('YYYY-MM-DD') . ' 23:59:59';
    //     // $tglAwal  = \Carbon\Carbon::create($tglAwal)->isoFormat('YYYY-MM-DD') . ' 00:00:00';
    //     $tindakan = IGDTransModel::with('pelaksana.biodata', 'tindakan', 'dok.biodata')
    //         ->whereBetween('created_at', [$tglAwal . ' 00:00:00', $tglAkhir . ' 23:59:59'])
    //     // ->where('kdTind', 9)
    //     // ->where('petugas', '197907231999032002')
    //         ->get();
    //     // return $tindakan;

    //     $data = [];

    //     foreach ($tindakan as $item) {
    //         $nmTindakan    = $item->tindakan->nmTindakan ?? '-';
    //         $namaPelaksana = $item->pelaksana->biodata->nama ?? 'Tidak diketahui';

    //         $data[$nmTindakan][$namaPelaksana] = ($data[$nmTindakan][$namaPelaksana] ?? 0) + 1;
    //     }

    //     // Tambahkan Observasi Infus dengan jumlah yang sama seperti Infus
    //     if (isset($data['Infus'])) {
    //         $data['Observasi infus'] = $data['Infus'];
    //     }

    //     // Tambahkan "Lain lain (mencari kartu)" untuk semua pelaksana yang ada
    //     $pelaksanaTersedia = [];
    //     foreach ($data as $tindakan) {
    //         foreach ($tindakan as $pelaksana => $jumlah) {
    //             $pelaksanaTersedia[$pelaksana] = true;
    //         }
    //     }

    //     foreach (array_keys($pelaksanaTersedia) as $pelaksana) {
    //         $data['Lain lain (mencari kartu)'][$pelaksana] = 0;
    //     }
    //     return $data;
    // }

    private function getKominfoArray($tglAwal, $tglAkhir, $petugas = null)
    {
        $params = [
            'tanggal_awal'  => $tglAwal,
            'tanggal_akhir' => $tglAkhir,
        ];

        $model = new KominfoModel();
        $data  = $model->poinRequest($params);
        $data  = $data['response']['data'] ?? [];
        // dd($data);
        // return $items;
        // dd($items);
        // if ($petugas == "dokter") {
        //     $items = array_filter($data, function ($item) {
        //         return in_array($item['ruang_nama'], ['Ruang Tensi 1', 'Ruang Poli (Perawat Poli)', 'Petugas Assessment Awal']);
        //     });
        // } else {

        $items = array_filter($data, function ($item) {
            return in_array($item['ruang_nama'], ['Ruang Poli (Dokter CPPT)', 'Ruang Tensi 1', 'Ruang Poli (Perawat Poli)', 'Petugas Assessment Awal']);
        });
        // }

        $items = array_map(function ($item) {
            $item['admin_nama'] = preg_replace('/^dr\.\s*/i', '', $item['admin_nama']); // hilangkan dr.
            $item['admin_nama'] = preg_replace('/,.*$/', '', $item['admin_nama']);      // hilangkan setelah koma
            $item['admin_nama'] = strtoupper($item['admin_nama']);
            // Tambahkan "IMAM " jika nama admin adalah "AJI SANTOSO"
            if ($item['admin_nama'] === 'AJI SANTOSO') {
                $item['admin_nama'] = 'IMAM ' . $item['admin_nama'];
                // $item['admin_nama'] = 'IMAM ' . $item['admin_nama'] . ' A.Md.Kep.';
            }
            return $item;
        }, $items);

        $result = [
            'Anamnesa pasien lama'  => [],
            'Anamnesa pasien lama2' => [],
            'Anamnesa pasien baru'  => [],
            'Asisten dokter'        => [],
            'Timbang dan tensi'     => [],
            'Input data'            => [],
            'Pemeriksaan dokter'    => [],
        ];

        foreach ($items as $item) {
            $admin  = $item['admin_nama'];
            $jumlah = (int) $item['jumlah'];
            $ruang  = $item['ruang_nama'];
            if ($ruang === 'Ruang Tensi 1') {
                $jumlahLama = (int) $item['jumlah'];
            }

            if ($ruang === 'Ruang Tensi 1') {
                // $half = (int) floor($jumlah / 2);
                // $result['Anamnesa pasien lama'][$admin] = ($result['Anamnesa pasien lama'][$admin] ?? 0) + $half;
                // $result['Anamnesa pasien baru'][$admin] = ($result['Anamnesa pasien baru'][$admin] ?? 0) + ($jumlah - $half);

                $result['Anamnesa pasien lama2'][$admin] = ($result['Ruang Tensi 1'][$admin] ?? 0) + $jumlah;
                $result['Timbang dan tensi'][$admin]     = ($result['Timbang dan tensi'][$admin] ?? 0) + $jumlah;
                $result['Input data'][$admin]            = ($result['Input data'][$admin] ?? 0) + $jumlah;
            } elseif ($ruang === 'Petugas Assessment Awal') {
                $result['Anamnesa pasien baru'][$admin] = ($result['Petugas Assessment Awal'][$admin] ?? 0) + $jumlah;
            } elseif ($ruang === 'Ruang Poli (Perawat Poli)') {
                $result['Asisten dokter'][$admin] = ($result['Asisten dokter'][$admin] ?? 0) + $jumlah;
            } elseif ($ruang === 'Ruang Poli (Dokter CPPT)') {
                $result['Pemeriksaan dokter'][$admin] = ($result['Pemeriksaan dokter'][$admin] ?? 0) + $jumlah;
            }
        }
        // $result['Anamnesa pasien lama2'] = [];

        foreach ($result['Anamnesa pasien lama2'] as $nama => $nilaiLama) {
            if (isset($result['Anamnesa pasien baru'][$nama])) {
                $result['Anamnesa pasien lama'][$nama] = $nilaiLama - $result['Anamnesa pasien baru'][$nama];
            } else {
                $result['Anamnesa pasien lama'][$nama] = $nilaiLama;
            }
        }
        unset($result['Anamnesa pasien lama2']);
        // dd($result);
        return $result;
    }

    private function getPoinHIV($tglAwal, $tglAkhir)
    {
        $data = PegawaiKegiatanModel::with('biodata')
            ->whereBetween('tanggal', [$tglAwal, $tglAkhir])
            ->get();

        // Jika tidak ada data, pastikan untuk mengembalikan array kosong
        if ($data->isEmpty()) {
            return []; // Kembalikan array kosong jika tidak ada data
        }

        $data = $data->toArray();

        $items = array_filter($data, function ($item) {
            return in_array($item['kegiatan'], ['Konseling VCT', 'Konseling PITC']);
        });

        $items = array_values($items);

        $result = [
            'Konseling VCT'              => [],
            'Konseling PITC'             => [],
            'Penanganan pasien Hemaptoe' => [],
        ];

        foreach ($items as $item) {
            $admin  = $item['biodata']['nama'];
            $jumlah = (int) $item['jumlah'];
            $ruang  = $item['kegiatan'];

            if ($ruang === 'Konseling PITC') {
                $result['Konseling PITC'][$admin] = ($result['Konseling PITC'][$admin] ?? 0) + $jumlah;
            } elseif ($ruang === 'Konseling VCT') {
                $result['Konseling VCT'][$admin] = ($result['Konseling VCT'][$admin] ?? 0) + $jumlah;
            } elseif ($ruang === 'Penanganan pasien Hemaptoe') {
                $result['Penanganan pasien Hemaptoe'][$admin] = ($result['Penanganan pasien Hemaptoe'][$admin] ?? 0) + $jumlah;
            }
        }

        return $result;
    }
}
