<?php
namespace App\Http\Controllers;

use App\Models\KasirSetoranModel;
use App\Models\KasirTransModel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KasirSetoranController extends Controller
{
    private function data($bulan, $tahun, $jaminan)
    {
        $doc      = [];
        $tglAkhir = \Carbon\Carbon::create($tahun, $bulan, 1)->lastOfMonth()->locale('id')->isoFormat('DD MMMM YYYY');
        $blnTahun = \Carbon\Carbon::create($tahun, $bulan, 1)->locale('id')->isoFormat('MMMM YYYY');

        $model                = new KasirTransModel();
        $data                 = $model->pendapatan($tahun);
        $totalPendapatanRajal = 0;
        $paramPendLain        = $tahun . '-' . $bulan;

        $doc = array_filter($data[$jaminan], function ($item) use ($bulan) {
            return $item['bln_number'] == $bulan;
        });

        foreach ($doc as $d) {
            $totalPendapatanRajal += $d['jumlah'];
        }
        //tanggal like $param PendLain

        $pendapatanLain = KasirSetoranModel::where('tanggal', 'like', '%' . $paramPendLain . '%')->get();
        // return $pendapatanLain;
        $docLain             = [];
        $totalPendapatanLain = 0;
        if ($pendapatanLain->isEmpty()) {
            $docLain = [];
        } else {

            foreach ($pendapatanLain as $d) {
                $tanggal             = \Carbon\Carbon::parse($d->tanggal); // Menggunakan Carbon
                $formattedDate       = $tanggal->format('d-m-Y');
                $hari                = $tanggal->locale('id')->isoFormat('dddd'); // Hari dalam bahasa Indonesia
                $bulan               = $tanggal->locale('id')->isoFormat('MMMM');
                $blnNumber           = $tanggal->format('m');
                $tglNomor            = $tanggal->locale('id')->isoFormat('DD MMMM YYYY');
                $tgl                 = $tanggal->locale('id')->isoFormat('DD MMM YYYY');
                $model               = new KasirTransModel();
                $terbilangPendapatan = $model->terbilang($d->jumlah); // Konversi terbilang

                // Format nomor
                $nomor     = $tanggal->format('d') . '/SBS/01/' . $tanggal->format('Y');
                $nomor_sts = $tanggal->format('d') . '/KKPM/' . $tanggal->locale('id')->isoFormat('MMM') . '/' . $tanggal->format('Y');

                $totalPendapatanLain += $d->jumlah;

                // Tambahkan ke array hasil
                $docLain[] = [
                    'nomor'           => $nomor,
                    'nomor_sts'       => $nomor_sts,
                    'tanggal'         => $formattedDate,
                    'tgl'             => $tgl,
                    'hari'            => $hari,
                    'bulan'           => $bulan,
                    'tahun'           => $tahun,
                    'bln_number'      => $blnNumber,
                    'tgl_nomor'       => $tglNomor,
                    'tgl_pendapatan'  => $tglNomor,
                    'tgl_setor'       => $tglNomor,
                    'pendapatan'      => 'Rp ' . number_format($d->jumlah, 0, ',', '.') . ',00',
                    'jumlah'          => $d->jumlah,
                    'terbilang'       => ucfirst($terbilangPendapatan) . " rupiah.",
                    'kode_akun'       => 102010041411,
                    'kode_rek'        => "3.003.25581.5",
                    'asal_pendapatan' => $d->asal_pendapatan,
                    'bank'            => "BPD",
                    'uraian'          => 'Pendapatan Jasa Pelayanan Rawat Jalan 1',
                ];
            }
        }
        //gabungkan $doc dan $docLain dan urutkan berdasarkan tanggal ter awal
        $doc = array_merge($doc, $docLain);
        usort($doc, function ($a, $b) {
            return strtotime($a['tanggal']) - strtotime($b['tanggal']);
        });

        $totalPendapatan = $totalPendapatanRajal + $totalPendapatanLain;

        return compact('doc', 'totalPendapatan', 'totalPendapatanRajal', 'totalPendapatanLain', 'tglAkhir', 'blnTahun');
    }

    public function rekapBulanan($tahun, $jaminan)
    {
        $title           = 'Rekap Bulanan';
        $doc             = [];
        $bulanPendapatan = [];
        $totalPendapatan = 0;
        $totalSetoran    = 0;
        $saldo           = 0;
        $target          = "Rp. 7.653.250.000,00";

        $model = new KasirSetoranModel();
        $data  = $model->dataTahunan($tahun);
        // return $data;
        $doc = $data['dataBulanan'];
        // return $doc;
        $totalPendapatan   = $data['totalPendapatan'];
        $totalSetoran      = $data['totalSetoran'];
        $totalSaldo        = $data['totalSaldo'];
        $totalPendapatanRp = $data['totalPendapatanRp'];
        $totalSetoranRp    = $data['totalSetoranRp'];
        $totalSaldoRp      = $data['totalSaldoRp'];

        return view('Laporan.Kasir.rekapBulanan', compact(
            'doc',
            'totalPendapatan',
            'totalSetoran',
            'totalSaldo',
            'totalPendapatanRp',
            'totalSetoranRp',
            'totalSaldoRp',
            'target',
            'title',
            'tahun'
        ));
    }

    public function stsBruto($bulan, $tahun, $jaminan)
    {
        $title    = 'STS Bruto';
        $model    = new KasirSetoranModel();
        $blnTahun = \Carbon\Carbon::create($tahun, $bulan, 1)->locale('id')->isoFormat('MMMM YYYY');
        $tglAkhir = \Carbon\Carbon::create($tahun, $bulan, 1)->lastOfMonth()->locale('id')->isoFormat('DD MMMM YYYY');
        $data     = $model->pengeluaran($tahun, $bulan);
        // return $data;
        $totalPendapatan = 0;
        foreach ($data as $d) {
            $totalPendapatan += $d->pendapatan;
        }

        return view('Laporan.Kasir.stsBruto', compact('data', 'blnTahun', 'totalPendapatan', 'tglAkhir'))->with('title', $title);
    }
    public function stpbBruto($bulan, $tahun, $jaminan)
    {
        $title    = 'BKU Bruto';
        $model    = new KasirSetoranModel();
        $blnTahun = \Carbon\Carbon::create($tahun, $bulan, 1)->locale('id')->isoFormat('MMMM YYYY');
        $tglAkhir = \Carbon\Carbon::create($tahun, $bulan, 1)->lastOfMonth()->locale('id')->isoFormat('DD MMMM YYYY');
        $data     = $model->penerimaan($tahun, $bulan);
        // return $data;
        $totalPendapatan = 0;
        foreach ($data as $d) {
            $totalPendapatan += $d->pendapatan;
        }
        return view('Laporan.Kasir.stpbBruto', compact('data', 'blnTahun', 'totalPendapatan', 'tglAkhir'))->with('title', $title);
    }
    public function bkuBruto($bulan, $tahun)
    {
        $title           = 'STPB Bruto';
        $model           = new KasirSetoranModel();
        $blnTahun        = \Carbon\Carbon::create($tahun, $bulan, 1)->locale('id')->isoFormat('MMMM YYYY');
        $tglAkhir        = \Carbon\Carbon::create($tahun, $bulan, 1)->lastOfMonth()->locale('id')->isoFormat('DD MMMM YYYY');
        $tglAkhirBlnLalu = $bulan == 1
        ? \Carbon\Carbon::create($tahun - 1, 12, 31)->locale('id')->isoFormat('DD MMMM YYYY')
        : \Carbon\Carbon::create($tahun, $bulan, 1)->subMonth()->lastOfMonth()->locale('id')->isoFormat('DD MMMM YYYY');

        $data = $model->data($tahun, $bulan);
        // return $data;
        // $result = $data->map(function ($item) {
        //     return [
        //         'uraian' => $item->asal_pendapatan, // Tambahkan uraian
        //         'pendapatan' => (int) $item->pendapatan, // Pastikan data dalam bentuk integer
        //         'setoran' => (int) $item->setoran, // Pastikan data dalam bentuk integer
        //     ];
        // });
        // Menghapus leading zero dan menghitung bulan sebelumnya
        $bulanSebelumnya = str_pad($bulan - 1, 2, '0', STR_PAD_LEFT);

        // Menangani kasus Januari (bulan 1) menjadi Desember (bulan 12)
        if ($bulanSebelumnya == 0) {
            $bulanSebelumnya = 12;
            $tahun--; // Menyesuaikan tahun menjadi setahun lebih awal jika bulan sebelumnya adalah Desember
        }

        // Ambil data untuk bulan sebelumnya
        $dataSebelumnya = $model->data($tahun, $bulanSebelumnya)->toArray();

        // Filter data untuk mendapatkan item dengan asal_pendapatan tertentu dan setoran tidak nol
        $filteredData = array_filter($dataSebelumnya, function ($item) {
            return $item['asal_pendapatan'] == "3.003.25581.5" && $item['setoran'] == 0;
        });

        // Jika ada hasil filter, cari item dengan tanggal paling terakhir
        if (! empty($filteredData)) {
            usort($filteredData, function ($a, $b) {
                return strtotime($b['tanggal']) <=> strtotime($a['tanggal']);
            });

            // Ambil item dengan tanggal paling terakhir
            $lastItem  = $filteredData[0];
            $tglBefore = \Carbon\Carbon::create($lastItem['tanggal'])->locale('id')->isoFormat('DD MMMM YYYY');
        } else {
            $lastItem  = null; // Jika tidak ada data yang sesuai
            $tglBefore = "";
        }
        // return $lastItem;
        foreach ($data as $d) {
            if ($d->asal_pendapatan == "3.003.25581.5") {
                if ($d->pendapatan != 0 && $d->setoran != 0) {
                    $d->uraian  = "Penerimaan: Pendapatan Rawat Jalan";
                    $d->uraian2 = "Pengeluaran: Setor ke Kas BLUD";
                } elseif ($d->pendapatan == 0) {
                    $d->uraian  = "Penerimaan: Pendapatan Rawat Jalan";
                    $d->uraian2 = "Pengeluaran: Setor ke Kas BLUD, Pendapatan tgl: " . $tglBefore;
                } elseif ($d->setoran == 0) {
                    $d->uraian  = "Penerimaan: Pendapatan Rawat Jalan";
                    $d->uraian2 = "Pengeluaran: Belum di Setorkan";
                }
            } else {
                $d->uraian  = $d->asal_pendapatan;
                $d->uraian2 = "";
            }
        }
        $totalPendapatanSampaiBlnLalu  = $model->pendapatanSebelumnya($tahun, $bulan);
        $totalPengeluaranSampaiBlnLalu = $model->pengeluaranSebelumnya($tahun, $bulan);
        // return $data;
        $totalPendapatan  = 0;
        $totalPengeluaran = 0;
        foreach ($data as $d) {
            $totalPendapatan += $d->pendapatan;
            $totalPengeluaran += $d->setoran;
        }
        return view('Laporan.Kasir.bkuBruto', compact(
            'data',
            'blnTahun',
            'totalPendapatan',
            'totalPengeluaran',
            'tglAkhir',
            'tglAkhirBlnLalu',
            'totalPendapatanSampaiBlnLalu',
            'totalPengeluaranSampaiBlnLalu'))->with('title', $title);
    }

    public function setorkan(Request $request)
    {
        // dd($request);

        try {
            // Ambil data dari request
            $tglSetor      = $request->input('tanggalSetor');
            $tglPendapatan = $request->input('tanggalPendapatan');

            $tanggal           = Carbon::parse($tglSetor)->format('Y-m-d');
            $tanggalPendapatan = Carbon::parse($tglPendapatan)->format('Y-m-d');
            $pendapatan        = $request->input('pendapatan');
            $setoran           = $request->input('setoran');
            $asalPendapatan    = $request->input('asal_pendapatan');
            $penyetor          = $request->input('penyetor');
            $noSbs             = $request->input('noSbs') ?? null;

            $bulanTahun = Carbon::parse($tanggal)->format('Y-m');
            $tahun      = Carbon::parse($tanggal)->format('Y');
            //format bulan seperti ini JAN
            $bulanText = Carbon::parse($tanggal)->format('M');

            // Buat nomor setoran
            $jumlahData = KasirSetoranModel::where('tanggal', 'like', '%' . $bulanTahun . '%')
                ->count();
            $no    = $jumlahData + 1;
            $nomor = $no . "/KKPM/" . strtoupper($bulanText) . "/" . $tahun;
            // dd($jumlahData);

            if ($tanggal != $tanggalPendapatan) {
                // return "Tanggal Setor dan Tanggal Pendapatan harus sama";
                // Simpan data ke database
                $pendapatanLain = KasirSetoranModel::create([
                    'nomor'           => $nomor,
                    'noSbs'           => $noSbs,
                    'tanggal'         => $tanggalPendapatan,
                    'pendapatan'      => $pendapatan,
                    'setoran'         => 0,
                    'asal_pendapatan' => $asalPendapatan,
                    'penyetor'        => $penyetor,
                ]);

                // Buat nomor setoran
                $jumlahData = KasirSetoranModel::where('tanggal', 'like', '%' . $bulanTahun . '%')
                    ->count();
                $no    = $jumlahData + 1;
                $nomor = $no . "/KKPM/" . strtoupper($bulanText) . "/" . $tahun;

                // Simpan data ke database
                $pendapatanLain = KasirSetoranModel::create([
                    'nomor'           => $nomor,
                    'noSbs'           => $noSbs,
                    'tanggal'         => $tanggal,
                    'pendapatan'      => 0,
                    'setoran'         => $setoran,
                    'asal_pendapatan' => $asalPendapatan,
                    'penyetor'        => $penyetor,
                ]);

                // Kembalikan respons sukses
                return response()->json([
                    'status'  => 'success',
                    'message' => 'Data berhasil disetorkan di tanggal berbeda, tgl pendapatan: ' . $tanggalPendapatan . ', tgl setoran: ' . $tanggal,
                    'data'    => $pendapatanLain,
                ], 201);

            } else {

                // Simpan data ke database
                $pendapatanLain = KasirSetoranModel::create([
                    'nomor'           => $nomor,
                    'noSbs'           => $noSbs,
                    'tanggal'         => $tanggal,
                    'pendapatan'      => $pendapatan,
                    'setoran'         => $setoran,
                    'asal_pendapatan' => $asalPendapatan,
                    'penyetor'        => $penyetor,
                ]);

                // Kembalikan respons sukses
                return response()->json([
                    'status'  => 'success',
                    'message' => 'Data berhasil disetorkan tanggal yang sama',
                    'data'    => $pendapatanLain,
                ], 201);
            }

        } catch (\Exception $e) {
            // Tangani error
            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function setoran($tahun)
    {
        $currentYear = $tahun ?? \Carbon\Carbon::now()->year;
        if ($tahun == 'all') {
            $data = KasirSetoranModel::all();
        } else {
            $data = KasirSetoranModel::where('tanggal', 'like', '%' . $currentYear . '%')->get();
        }
        return $data;
    }

    public function setoranSimpan(Request $request)
    {
        $simpan = $this->setorkan($request);
        $data   = $this->setoran(\Carbon\Carbon::now()->year);

        return response()->json([
            'status'  => 'success',
            'message' => 'Data pendapatan lain berhasil disimpan.',
            'data'    => $data,
            'simpan'  => $simpan,
        ]);
    }

    public function setoranUpdate(Request $request, $id)
    {
        // Validasi data
        $validated = $request->validate([
            'asal_pendapatan' => 'required|string|max:255',
            'jumlah'          => 'required|min:0',
            'tanggal'         => 'required|date',
            'penyetor'        => 'required|string|max:255',
        ]);

        // Cari data berdasarkan ID
        $pendapatanLain = KasirSetoranModel::find($id);

        if (! $pendapatanLain) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data pendapatan lain tidak ditemukan.',
            ], 404);
        }

        // Update data
        $pendapatanLain->update($validated);

        $data = $this->pendapatanLain(\Carbon\Carbon::now()->year);

        return response()->json([
            'status'  => 'success',
            'message' => 'Data pendapatan lain berhasil diperbarui.',
            'data'    => $data,
        ]);
    }

    public function setoranDelete(Request $request)
    {
        $id = $request->input('id');
        // Cari data berdasarkan ID
        $pendapatanLain = KasirSetoranModel::find($id);

        if (! $pendapatanLain) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data pendapatan lain tidak ditemukan.',
            ], 404);
        }

        // Hapus data
        $pendapatanLain->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Data pendapatan lain berhasil dihapus.',
        ]);
    }

    public function retriBruto($bln, $tahun, $jaminan)
    {
        abort(404);
    }
}
