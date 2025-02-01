<?php
namespace App\Http\Controllers;

use App\Models\BMHPIGDInStokModel;
use App\Models\BMHPModel;
use App\Models\IGDTransModel;
use App\Models\KunjunganWaktuSelesai;
use App\Models\TransaksiBMHPModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IgdController extends Controller
{

    public function chart(Request $request)
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

        $chart = DB::table('t_kunjungan_tindakan')
            ->select(
                DB::raw('MONTH(t_kunjungan_tindakan.created_at) as bulan'),
                DB::raw('COUNT(*) as jumlah'),
                'm_kelompok.kelompok'
            )
            ->join('m_tindakan', 't_kunjungan_tindakan.kdTind', '=', 'm_tindakan.kdTindakan')
            ->join('t_kunjungan', function ($join) {
                $join->on('t_kunjungan.norm', '=', 't_kunjungan_tindakan.norm')
                    ->whereRaw("DATE(t_kunjungan.tgltrans) = DATE(t_kunjungan_tindakan.created_at)");
            })
            ->join('m_kelompok', 't_kunjungan.kkelompok', '=', 'm_kelompok.kkelompok')
            ->whereIn('m_kelompok.kelompok', ['umum', 'bpjs'])
            ->whereYear('t_kunjungan_tindakan.created_at', $year)
            ->groupBy(DB::raw('MONTH(t_kunjungan_tindakan.created_at)'), 'm_kelompok.kelompok')
            ->orderBy('bulan') // Mengurutkan hasil berdasarkan bulan
            ->get();

        return response()->json($chart, 200, [], JSON_PRETTY_PRINT);
    }

    public function chart2025(Request $request)
    {
        $year = $request->input('year');

        // Ambil data IGD berdasarkan tahun
        $dataIGD = IGDTransModel::whereYear('created_at', $year)->get();

        // Proses setiap item
        foreach ($dataIGD as $item) {
            // Cari data kunjungan berdasarkan tgltrans dan norm
            $kunjungan = KunjunganWaktuSelesai::where('notrans', $item->notrans)->first();

            // Tentukan kelompok berdasarkan no_sep
            if (isset($kunjungan) && $kunjungan->no_sep != null) {
                $item->kelompok = "BPJS";
            } else if (isset($kunjungan) && $kunjungan->no_sep == null) {
                $item->kelompok = "UMUM";
            } else {
                $item->kelompok = "mbuh";
            }

            // Set no_sep dan format bulan dalam bahasa Indonesia
            $item->no_sep = $kunjungan->no_sep ?? '';

            // $item->bulan= ambil bulan dati creted at mm
            $item->bulan = Carbon::parse($item->created_at)->format('m');

        }

        // Rubah menjadi array
        $data = $dataIGD->toArray();

        // Kelompokkan data berdasarkan bulan dan kelompok
        $chart = collect($data)
            ->groupBy(function ($item) {
                return $item['bulan'] . '|' . $item['kelompok']; // Gabungkan bulan dan kelompok menjadi satu kunci
            })
            ->map(function ($group, $key) {
                list($bulan, $kelompok) = explode('|', $key); // Pisahkan bulan dan kelompok

                return [
                    'bulan'    => $bulan,
                    'nmBulan'  => Carbon::createFromFormat('m', $bulan)->locale('id')->translatedFormat('F'),
                    'kelompok' => $kelompok,
                    'jumlah'   => $group->count(),
                ];
            })
            ->values();

        // Return hasil dalam bentuk JSON
        return response()->json($chart, 200, [], JSON_PRETTY_PRINT);
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
        $norm    = $request->input('norm');
        $notrans = $request->input('notrans');
        $kdTind  = $request->input('kdTind');
        $petugas = $request->input('petugas');
        $dokter  = $request->input('dokter');
        $jaminan = $request->input('jaminan');
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
            // $kunjunganTindakan->created_at = $created_at;
            // $kunjunganTindakan->updated_at = $updated_at;

            // Simpan data ke dalam tabel
            $kunjunganTindakan->save();

            // Respon sukses atau redirect ke halaman lain
            return response()->json(['message' => 'Data berhasil disimpan']);
        } else {
            // Handle case when $kdTind is null, misalnya kirim respon error
            return response()->json(['message' => 'kdTind tidak valid'], 400);
        }
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

        // Respon sukses
        return response()->json(['message' => 'Data tindakan berhasil diperbarui']);
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

        // Respon sukses
        return response()->json(['message' => 'Data tindakan berhasil dihapus']);
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
}
