<?php
namespace App\Http\Controllers;

use App\Models\KasirAddModel;
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

    public function rekapBulanan($bln = null, $tahun, $jaminan)
    {
        $title           = 'Rekap Bulanan';
        $doc             = [];
        $bulanPendapatan = [];
        $totalPendapatan = 0;
        $totalSetoran    = 0;
        $saldo           = 0;
        $target          = "Rp. 7.653.250.000,00";

        $model           = new KasirSetoranModel();
        $tanggalTerakhir = Carbon::create($tahun, $bln)->endOfMonth();

                                                       // Format tanggal terakhir sesuai kebutuhan
        $tglAkhir = $tanggalTerakhir->format('Y-m-d'); // Contoh: 2023-02-28

        $params = [
            'tahun'    => $tahun,
            'tglAkhir' => $tglAkhir,

        ];
        $data = $model->dataTahunan($params);
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
            $totalPendapatan += $d->setoran;
        }

        return view('Laporan.Kasir.stsBruto', compact('data', 'blnTahun', 'totalPendapatan', 'tglAkhir'))->with('title', $title);
    }
    public function stpbBruto($bulan, $tahun, $jaminan)
    {
        $title    = 'STPB';
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
        $title           = 'BKU Bruto';
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
            $tglSetor          = $request->input('tanggalSetor') === null ? $request->input('tanggal') : $request->input('tanggalSetor');
            $tglPendapatan     = $request->input('tanggalPendapatan');
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
                dd($tanggal);

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
        // dd($request->all());
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
            'pendapatan'      => 'required|min:0',
            'setoran'         => 'required|min:0',
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

        $data = $this->setoran(\Carbon\Carbon::now()->year);

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
        // abort(404);
        $title       = 'Retribusi Bruto';
        $model       = new KasirAddModel();
        $tglAwal     = \Carbon\Carbon::create($tahun, $bln, 1)->isoFormat('YYYY-MM-DD');
        $tglAkhir    = \Carbon\Carbon::create($tahun, $bln, 1)->lastOfMonth()->isoFormat('YYYY-MM-DD');
        $tglAwalIdn  = \Carbon\Carbon::create($tahun, $bln, 1)->locale('id')->isoFormat('DD MMMM YYYY');
        $tglAkhirIdn = \Carbon\Carbon::create($tahun, $bln, 1)->lastOfMonth()->locale('id')->isoFormat('DD MMMM YYYY');
        $params      = [
            'tglAwal'  => $tglAwal,
            'tglAkhir' => $tglAkhir,
        ];
        $tglAwalBlnLalu  = \Carbon\Carbon::create($tahun - 1, 1, 1)->isoFormat('YYYY-MM-DD');
        $tglAkhirBlnLalu = \Carbon\Carbon::create($tahun, $bln - 1, 1)->lastOfMonth()->isoFormat('YYYY-MM-DD');
        $paramsBlnLalu   = [
            'tglAwal'  => $tglAwalBlnLalu,
            'tglAkhir' => $tglAkhirBlnLalu,
        ];
        // return $paramsBlnLalu;
        $pendapatan        = $model->pendapatanPerItem($params)[$jaminan];
        $pendapatanBlnLalu = $model->pendapatanPerItem($paramsBlnLalu)[$jaminan];
        $dataBlnIni        = collect($this->grupedData($pendapatan));
        $dataBlnLalu       = collect($this->grupedData($pendapatanBlnLalu));
        // return $dataBlnIni;

        $grupKelasblnIni  = collect($this->grupedDataKelas($pendapatan));
        $grupKelasblnLalu = collect($this->grupedDataKelas($pendapatanBlnLalu));

        $pendapatanBlnIni  = $this->makeResponse($grupKelasblnIni, $dataBlnIni, $bln, $tahun, 'pendapatan');
        $pendapatanBlnLalu = $this->makeResponse($grupKelasblnLalu, $dataBlnLalu, $bln, $tahun, 'pendapatan');
        $setoranBlnIni     = $this->makeResponse($grupKelasblnIni, $dataBlnIni, $bln, $tahun, 'setoran');
        $setoranBlnLalu    = $this->makeResponse($grupKelasblnLalu, $dataBlnLalu, $bln, $tahun, 'setoran');

        $res = [
            'dataPendapatanBlnIni'  => $pendapatanBlnIni,
            'dataPendapatanBlnLalu' => $pendapatanBlnLalu,
            'dataSetoranBlnIni'     => $setoranBlnIni,
            'dataSetoranBlnLalu'    => $setoranBlnLalu,
        ];
        // return response()->json($res, 200, [], JSON_PRETTY_PRINT); //sghs
        return view('Laporan.Kasir.retribusiBruto', compact('res', 'title', 'tglAwalIdn', 'tglAkhirIdn', 'tahun'));
    }

    private function makeResponse($grup, $pendapatan, $bln, $tahun, $filter)
    {
        $lain      = $this->setoran($tahun . '-' . $bln);
        $bungaBank = $lain->where('asal_pendapatan', 'Bunga')->sum($filter);
        $bpjs      = $lain->where('asal_pendapatan', 'KLAIM BPJS')->sum($filter);
        $tcm       = $lain->where('asal_pendapatan', 'TCM')->sum($filter);
        $sirup     = $lain->where('asal_pendapatan', 'Sirup')->sum($filter);

        $data = [
            // 'laboratorium'           => $grup->whereIn('kelas', 9)->first()['totalJumlah'] ?? 0,
            // 'rekam_medis'            => $grup->whereIn('kelas', 1)->first()['totalJumlah'] ?? 0,
            // 'radiologiAll'           => ($grup->whereIn('kelas', 8)->first()['totalJumlah'] ?? 0),
            // 'radiologi'              => ($grup->whereIn('kelas', 8)->first()['totalJumlah'] ?? 0) - ($pendapatan->whereIn('nmLayanan', 'Konsultasi dokter Radiologi')->first()['totalJumlah'] ?? 0),
            // 'nebulizer'              => $pendapatan->whereIn('nmLayanan', 'Nebulasi ( tanpa harga obat )')->first()['totalJumlah'] ?? 0,
            // 'oksigensai'             => $pendapatan->whereIn('nmLayanan', 'Oksigenasi per jam')->first()['totalJumlah'] ?? 0,
            // 'obat'                   => $pendapatan->whereIn('nmLayanan', 'Biaya Obat')->first()['totalJumlah'] ?? 0,
            // 'dokter_umum_rajal'      => $pendapatan->whereIn('nmLayanan', 'Dokter umum Rajal')->first()['totalJumlah'] ?? 0,
            // 'dokter_spesialis_rajal' => $pendapatan->whereIn('nmLayanan', 'Dokter spesialis Rajal')->first()['totalJumlah'] ?? 0,
            // 'dokter_ro'              => $pendapatan->whereIn('nmLayanan', 'Konsultasi dokter Radiologi')->first()['totalJumlah'] ?? 0,
            // 'resep'                  => $grup->whereIn('kelas', 11)->first()['totalJumlah'] ?? 0,
            // 'bunga_bank'             => $bungaBank ?? 0,
            // 'sirup'                  => $sirup ?? 0,
            // 'surat_ket_medis'        => $pendapatan->whereIn('nmLayanan', 'Surat Keterangan Dokter')->first()['totalJumlah'] ?? 0,
            // 'injeksi'                => $pendapatan->whereIn('nmLayanan', 'Injeksi')->first()['totalJumlah'] ?? 0,
            // 'infus'                  => $pendapatan->whereIn('nmLayanan', 'Infus')->first()['totalJumlah'] ?? 0,
            // 'bpjs'                   => $bpjs ?? 0,
            // 'konsul_farmasi'         => $pendapatan->whereIn('nmLayanan', 'Konsultasi Kefarmasian')->first()['totalJumlah'] ?? 0,
            // 'tes_mantoux'            => $pendapatan->whereIn('nmLayanan', 'Mantoux Test')->first()['totalJumlah'] ?? 0,
            // 'pungsi'                 => $pendapatan->whereIn('nmLayanan', 'Punctie pleura')->first()['totalJumlah'] ?? 0,
            // 'wsd'                    => $pendapatan->whereIn('nmLayanan', 'W S D')->first()['totalJumlah'] ?? 0,
            // 'puyer'                  => $pendapatan->whereIn('nmLayanan', 'Ramuan puyer per bungkus/kapsul')->first()['totalJumlah'] ?? 0,
            // 'konsul_nurse'           => $pendapatan->whereIn('nmLayanan', 'Konsultasi Keperawatan')->first()['totalJumlah'] ?? 0,
            // 'ekg'                    => $pendapatan->whereIn('nmLayanan', 'EKG')->first()['totalJumlah'] ?? 0,
            // 'spirometri'             => $pendapatan->whereIn('nmLayanan', 'Spirometri')->first()['totalJumlah'] ?? 0,
            // 'vct-hiv'                => $pendapatan->whereIn('nmLayanan', 'Poli HIV')->first()['totalJumlah'] ?? 0,
            // 'ambulans'               => $pendapatan->whereIn('nmLayanan', 'Ambulasi')->first()['totalJumlah'] ?? 0,
            // 'dokter_umum_gadar'      => $pendapatan->whereIn('nmLayanan', 'Dokter umum Gadar')->first()['totalJumlah'] ?? 0,
            // 'dokter_spesialis_gadar' => $pendapatan->whereIn('nmLayanan', 'Dokter spesialis Gadar')->first()['totalJumlah'] ?? 0,
            // 'one_day_care'           => $pendapatan->whereIn('nmLayanan', 'Observasi one day care 6 - 12 jam')->first()['totalJumlah'] ?? 0,
            // 'tcm'                    => $pendapatan->whereIn('nmLayanan', 'TCM')->first()['totalJumlah'] ?? 0,
            // 'biopsi_halus'           => $pendapatan->whereIn('nmLayanan', 'Biopsi jarum halus')->first()['totalJumlah'] ?? 0,
            // 'perawatan_luka'         => $pendapatan->whereIn('nmLayanan', 'Perawatan Luka')->first()['totalJumlah'] ?? 0,
            // 'konsul_gizi'            => $pendapatan->whereIn('nmLayanan', 'Konsultasi Gizi')->first()['totalJumlah'] ?? 0,
            // 'konsul_lainnya'         => $pendapatan->whereIn('nmLayanan', 'Konsultasi Kesehatan lainnya')->first()['totalJumlah'] ?? 0,
            // 'klinik_vct'             => $pendapatan->whereIn('nmLayanan', 'Layanan Klinik VCT')->first()['totalJumlah'] ?? 0,
            // 'pendapatan_tcm'         => $tcm ?? 0,
            // 'suction_lendir'         => $pendapatan->whereIn('nmLayanan', 'Suction Lendir')->first()['totalJumlah'] ?? 0,
            // 'hecting'                => $pendapatan->whereIn('nmLayanan', 'Hecting')->first()['totalJumlah'] ?? 0,
            // 'aff_hecting'            => $pendapatan->whereIn('nmLayanan', 'Aff Hecting')->first()['totalJumlah'] ?? 0,
            'Laboratorium'                => $grup->whereIn('kelas', 9)->first()['totalJumlah'] ?? 0,
            'Rekam Medis'                 => $grup->whereIn('kelas', 1)->first()['totalJumlah'] ?? 0,
            'Radiologi'                   => ($grup->whereIn('kelas', 8)->first()['totalJumlah'] ?? 0) - ($pendapatan->whereIn('nmLayanan', 'Konsultasi dokter Radiologi')->first()['totalJumlah'] ?? 0),
            'Nebulizer'                   => $pendapatan->whereIn('nmLayanan', 'Nebulasi ( tanpa harga obat )')->first()['totalJumlah'] ?? 0,
            'Oksigensai'                  => $pendapatan->whereIn('nmLayanan', 'Oksigenasi per jam')->first()['totalJumlah'] ?? 0,
            'Obat'                        => $pendapatan->whereIn('nmLayanan', 'Biaya Obat')->first()['totalJumlah'] ?? 0,
            'Dokter Umum RAJAL'           => $pendapatan->whereIn('nmLayanan', 'Dokter umum Rajal')->first()['totalJumlah'] ?? 0,
            'Dokter Spesialis RAJAL'      => $pendapatan->whereIn('nmLayanan', 'Dokter spesialis Rajal')->first()['totalJumlah'] ?? 0,
            'Konsul Dokter Sp. Radiologi' => $pendapatan->whereIn('nmLayanan', 'Konsultasi dokter Radiologi')->first()['totalJumlah'] ?? 0,
            'Resep'                       => $grup->whereIn('kelas', 11)->first()['totalJumlah'] ?? 0,
            'Surat Ket Medis'             => $pendapatan->whereIn('nmLayanan', 'Surat Keterangan Dokter')->first()['totalJumlah'] ?? 0,
            'Injeksi'                     => $pendapatan->whereIn('nmLayanan', 'Injeksi')->first()['totalJumlah'] ?? 0,
            'Infus'                       => $pendapatan->whereIn('nmLayanan', 'Infus')->first()['totalJumlah'] ?? 0,
            'Konsultasi Kefarmasian'      => $pendapatan->whereIn('nmLayanan', 'Konsultasi Kefarmasian')->first()['totalJumlah'] ?? 0,
            'Tes Mantoux'                 => $pendapatan->whereIn('nmLayanan', 'Mantoux Test')->first()['totalJumlah'] ?? 0,
            'Pungsi'                      => $pendapatan->whereIn('nmLayanan', 'Punctie pleura')->first()['totalJumlah'] ?? 0,
            'WSD'                         => $pendapatan->whereIn('nmLayanan', 'W S D')->first()['totalJumlah'] ?? 0,
            'Puyer'                       => $pendapatan->whereIn('nmLayanan', 'Ramuan puyer per bungkus/kapsul')->first()['totalJumlah'] ?? 0,
            'Konsultasi Keperawatan'      => $pendapatan->whereIn('nmLayanan', 'Konsultasi Keperawatan')->first()['totalJumlah'] ?? 0,
            'EKG'                         => $pendapatan->whereIn('nmLayanan', 'EKG')->first()['totalJumlah'] ?? 0,
            'Spirometri'                  => $pendapatan->whereIn('nmLayanan', 'Spirometri')->first()['totalJumlah'] ?? 0,
            'VCT-HIV'                     => $pendapatan->whereIn('nmLayanan', 'Poli HIV')->first()['totalJumlah'] ?? 0,
            'Ambulans'                    => $pendapatan->whereIn('nmLayanan', 'Ambulasi')->first()['totalJumlah'] ?? 0,
            'Dokter Umum IGD'             => $pendapatan->whereIn('nmLayanan', 'Dokter umum Gadar')->first()['totalJumlah'] ?? 0,
            'Dokter Spesialis IGD'        => $pendapatan->whereIn('nmLayanan', 'Dokter spesialis Gadar')->first()['totalJumlah'] ?? 0,
            'Observasi one day care'      => $pendapatan->whereIn('nmLayanan', 'Observasi one day care 6 - 12 jam')->first()['totalJumlah'] ?? 0,
            'TCM'                         => $pendapatan->whereIn('nmLayanan', 'TCM')->first()['totalJumlah'] ?? 0,
            'Biopsi Jarum Hasul'          => $pendapatan->whereIn('nmLayanan', 'Biopsi jarum halus')->first()['totalJumlah'] ?? 0,
            'Perawatan Luka'              => $pendapatan->whereIn('nmLayanan', 'Perawatan Luka')->first()['totalJumlah'] ?? 0,
            'Konsultasi Gizi'             => $pendapatan->whereIn('nmLayanan', 'Konsultasi Gizi')->first()['totalJumlah'] ?? 0,
            'Konsultasi Lainnya'          => $pendapatan->whereIn('nmLayanan', 'Konsultasi Kesehatan lainnya')->first()['totalJumlah'] ?? 0,
            'Klinik VCT'                  => $pendapatan->whereIn('nmLayanan', 'Layanan Klinik VCT')->first()['totalJumlah'] ?? 0,
            'Suction Lendir'              => $pendapatan->whereIn('nmLayanan', 'Suction Lendir')->first()['totalJumlah'] ?? 0,
            'Hecting'                     => $pendapatan->whereIn('nmLayanan', 'Hecting')->first()['totalJumlah'] ?? 0,
            'Aff Hecting'                 => $pendapatan->whereIn('nmLayanan', 'Aff Hecting')->first()['totalJumlah'] ?? 0,
            'Bunga Bank'                  => $bungaBank ?? 0,
            'Sirup'                       => $sirup ?? 0,
            'BPJS'                        => $bpjs ?? 0,
            'Pendapatan TCM'              => $tcm ?? 0,
        ];
        return $data;
    }

    private function grupedData($pendapatan)
    {
        $groupedData = [];
        foreach ($pendapatan as $item) {
            $nmLayanan = $item['nmLayanan'];
            $jumlah    = (int) $item['jumlah'];

            if (! isset($groupedData[$nmLayanan])) {
                $groupedData[$nmLayanan] = [
                    'nmLayanan'   => $nmLayanan,
                    'kelas'       => $item['kelas'],
                    'totalJumlah' => 0,
                    'totalItem'   => 0,
                ];
            }

            $groupedData[$nmLayanan]['totalJumlah'] += $jumlah;
            $groupedData[$nmLayanan]['totalItem'] += (int) $item['totalItem'];
        }

        // Ubah array menjadi numerik untuk pengurutan
        $groupedData = array_values($groupedData);

        // Urutkan berdasarkan kelas (ascending)
        usort($groupedData, function ($a, $b) {
            return $a['kelas'] <=> $b['kelas'];
        });

        return $groupedData;
    }

    private function grupedDataKelas($pendapatan)
    {
        $groupedData = [];
        foreach ($pendapatan as $item) {
            $kelas  = $item['kelas'];
            $jumlah = (int) $item['jumlah'];

            if (! isset($groupedData[$kelas])) {$groupedData[$kelas] = [
                'nmkelas'     => $this->mapKelas($kelas),
                'kelas'       => $item['kelas'],
                'totalJumlah' => 0,
                'totalItem'   => 0,
            ];}

            $groupedData[$kelas]['totalJumlah'] += $jumlah;
            $groupedData[$kelas]['totalItem'] += (int) $item['totalItem'];
        }

        // Ubah array menjadi numerik untuk pengurutan
        $groupedData = array_values($groupedData);

        // Urutkan berdasarkan kelas (ascending)
        usort($groupedData, function ($a, $b) {
            return $a['kelas'] <=> $b['kelas'];
        });

        return $groupedData;
    }

    private function mapKelas($kelas)
    {
        $mapKelas = [
            '1'  => 'LAYANAN REKAM MEDIS',
            '2'  => 'LAYANAN RAWAT JALAN ',
            '3'  => 'LAYANAN KEGAWATDARURATAN',
            '4'  => 'LAYANAN RAWAT INAP',
            '5'  => 'LAYANAN TINDAKAN MEDIK ',
            '6'  => 'LAYANAN TINDAKAN MEDIK OPERASI',
            '7'  => 'LAYANAN REHABILITASI MEDIK',
            '8'  => 'LAYANAN RADIOLOGI',
            '9'  => 'LAYANAN LABORATORIUM',
            '10' => 'LAYANAN KONSULTASI DAN PEMERIKSAAN',
            '11' => 'LAYANAN FARMASI',
            '12' => 'LAYANAN PENUNJANG NON MEDIS',
            '13' => 'LAYANAN RAWAT RUMAH/HOME CARE',
            '14' => 'LAYANAN SUMBER DAYA MANUSIA KESEHATAN KEPADA PIHAK LAIN',
            '15' => 'LAYANAN ATAS PENGGUNAAN ASET OLEH PIHAK LAIN',
            '21' => 'DOKTER SPESIALIS',
            '22' => 'DOKTER UMUM',
            '91' => 'HEMATOLOGI',
            '92' => 'KIMIA DARAH',
            '93' => 'IMUNO SEROLOGI',
            '94' => 'BAKTERIOLOGI',
        ];
        return $mapKelas[$kelas];
    }
}
