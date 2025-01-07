<?php

namespace App\Http\Controllers;

use App\Models\IGDTransModel;
use App\Models\KasirAddModel;
use App\Models\KasirTransModel;
use App\Models\KominfoModel;
use App\Models\LaboratoriumHasilModel;
use App\Models\LayananModel;
use App\Models\ROTransaksiModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KasirController extends Controller
{
    public function tagihan(Request $request)
    {
        // dd($request->all());
        $norm = $request->input('norm');
        $tanggal = $request->input('tgl', Carbon::now()->format('Y-m-d'));
        $params = [
            'tanggal_awal' => $tanggal,
            'tanggal_akhir' => $tanggal,
            'no_rm' => $norm ?? '',
        ];
        // dd($params);
        $model = new KominfoModel();
        $data = $model->pendaftaranRequest($params);
        // dd($data);
        $notrans = $data[0]['no_reg'];
        // dd($notrans);
        $tindakan = IGDTransModel::with(['tindakan'])->where('notrans', $notrans)->get();
        // return $tindakan;
        $dataTind = [];
        foreach ($tindakan as $item) {
            $dataTind[] = [
                'id' => $item->id,
                'norm' => $item->norm,
                'notrans' => $item->notrans,
                'kdTind' => $item->kdTind,
                'petugas' => $item->petugas,
                'nmTindakan' => $item->tindakan->nmTindakan,
                'tarif' => $item->tindakan->harga,
            ];
        }
        // return $dataTind;

        $ro = ROTransaksiModel::with(['foto'])->where('notrans', $notrans)->get();
        // return $ro;
        $dataRO = [];
        foreach ($ro as $item) {
            $dataRO[] = [
                'norm' => $item->norm,
                'notrans' => $item->notrans,
                'tgltrans' => $item->tgltrans,
                'kdFoto' => $item->kdFoto,
                'ro' => $item->foto->nmFoto,
                'tarif' => $item->foto->tarif,

            ];
        }
        // return $dataRO;
        $lab = LaboratoriumHasilModel::with(['pemeriksaan'])->where('notrans', $notrans)->get();
        // return $lab;
        $dataLab = [];
        foreach ($lab as $item) {
            $dataLab[] = [
                'id' => $item->idLab,
                'norm' => $item->norm,
                'notrans' => $item->notrans,
                'tgltrans' => $item->created_at,
                'kdPemeriksaan' => $item->idLayanan,
                'nmPemeriksaan' => $item->pemeriksaan->nmLayanan,
                'tarif' => $item->pemeriksaan->tarif,
            ];
        }

        // return $dataLab;

        $res = [
            'pasien' => $data[0],
            'tindakan' => $dataTind,
            'ro' => $dataRO,
            'lab' => $dataLab,
        ];
        return response()->json($res, 200, [], JSON_PRETTY_PRINT);

    }

    public function Layanan(Request $request)
    {

        $query = LayananModel::on('mysql')->where('status', '1');

        // Mengecek apakah request memiliki parameter kelas
        if ($request->has('kelas')) {
            $kelas = $request->input('kelas');
            $query->where('kelas', $kelas);
        }
        $query->orderBy('kelas', 'asc');
        // $query->orderBy('idLayanan', 'asc');

        $layanan = $query->get();

        return response()->json($layanan, 200, [], JSON_PRETTY_PRINT);
    }

    public function add(Request $request)
    {
        // dd($request->all());
        // Validate the incoming request data
        $validatedData = $request->validate([
            'nmLayanan' => 'required|string|max:255',
            'tarif' => 'required|string|max:255',
            'kelas' => 'required|int|max:10',
            'status' => 'required',
        ]);

        try {
            // Create a new instance of LayananModel with the validated data
            $layanan = LayananModel::create($validatedData);

            // Return a JSON response indicating success
            return response()->json(['message' => 'Data layanan berhasil ditambahkan', 'data' => $layanan], 201);
        } catch (\Exception $e) {
            // Return a JSON response indicating failure
            return response()->json(['message' => 'Data layanan gagal ditambahkan', 'error' => $e->getMessage()], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            LayananModel::where('idLayanan', $request->input('id'))->delete();
            return response()->json(['message' => 'Data layanan berhasil dihapus']);
        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
            return response()->json(['message' => 'Data layanan gagal dihapus']);
        }
    }

    public function updateLayanan(Request $request)
    {
        try {
            $data = LayananModel::where('idLayanan', $request->input('id'))->firstOrFail();
            // dd($data);

            $data->update($request->all());

            $hasilData = LayananModel::with('grup')->get();

            return response()->json(
                [
                    'message' => 'Data layanan berhasil diperbarui',
                    'data' => $hasilData,
                ]
            );
        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
            return response()->json(['message' => 'Data layanan gagal diperbarui']);
        }
    }

    public function addTagihan(Request $request)
    {
        $dataTerpilih = $request->input('dataTerpilih');

        if (!is_array($dataTerpilih) || empty($dataTerpilih)) {
            return response()->json(['message' => 'Data terpilih tidak valid atau kosong'], 400);
        }

        try {
            DB::beginTransaction();

            $dataToInsert = collect($dataTerpilih)
                ->filter(fn($data) => isset($data['idLayanan'], $data['notrans']))
                ->map(fn($data) => [
                    'notrans' => $data['notrans'],
                    'norm' => $data['norm'] ?? null,
                    'idLayanan' => $data['idLayanan'],
                    'qty' => $data['qty'] ?? 1,
                    'totalHarga' => $data['harga'],
                    'jaminan' => $data['jaminan'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ])->toArray();

            if (empty($dataToInsert)) {
                return response()->json(['message' => 'Data tidak lengkap'], 400);
            }

            KasirAddModel::insert($dataToInsert);

            if ($request->input('notrans')) {
                $req = [
                    'notrans' => $request->input('notrans'),
                    'norm' => $request->input('norm'),
                    'nama' => $request->input('nama'),
                    'jk' => $request->input('jk'),
                    'umur' => $request->input('umur'),
                    'alamat' => $request->input('alamat'),
                    'jaminan' => $request->input('jaminan'),
                    'tagihan' => 0,
                    'bayar' => 0,
                    'kembalian' => 0,
                    'petugas' => "Nasirin",
                ];
                $this->saveOrUpdateKunjungan($req);
            }

            DB::commit();
            Log::info('Transaksi berhasil disimpan: ' . json_encode($dataToInsert));

            return response()->json(['message' => 'Transaksi berhasil disimpan'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Terjadi kesalahan: ' . $e->getMessage());
            return response()->json(['message' => 'Kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function deleteTagihan(Request $request)
    {
        $id = $request->input('id');
        $notrans = $request->input('notrans');
        try {
            $data = KasirAddModel::with('layanan')->where('id', $id)->first();
            // $item = KasirAddModel::with('layanan')->where('notrans', $notrans)->get();

            $dataDelete = [
                'id' => $data->id,
                'notrans' => $data->notrans,
                'norm' => $data->norm,
                'jaminan' => $data->jaminan,
                'idLayanan' => $data->idLayanan,
                'nmLayanan' => $data->layanan->nmLayanan,
                'qty' => $data->qty,
                'totalHarga' => $data->totalHarga,
                'created_at' => $data->created_at,
                'updated_at' => $data->updated_at,

            ];

            // return ['data' => $dataDelete, 'items' => $item];
            if (!$data) {
                return response()->json(['message' => 'Data layanan tidak ditemukan'], 404);
            }
            $data->delete();

            $response = [
                'message' => 'Data layanan berhasil dihapus',
                'delete' => $dataDelete,
                // 'items' => $items,
            ];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
            return response()->json(['message' => 'Data layanan gagal dihapus']);
        }
    }

    public function addTransaksi(Request $request)
    {
        if ($request->input('notrans')) {
            $req = [
                'notrans' => $request->input('notrans'),
                'norm' => $request->input('norm'),
                'nama' => $request->input('nama'),
                'jk' => $request->input('jk'),
                'umur' => $request->input('umur'),
                'alamat' => $request->input('alamat'),
                'jaminan' => $request->input('jaminan'),
                'tagihan' => $request->input('tagihan'),
                'bayar' => $request->input('bayar'),
                'kembalian' => $request->input('kembalian'),
                'petugas' => $request->input('petugas'),
            ];
            $this->saveOrUpdateKunjungan($req);
            return response()->json(['message' => 'Kunjungan berhasil diproses...!!'], 200);
        }

        return response()->json(['message' => 'No Transaksi tidak valid'], 400);
    }
    public function deleteTransaksi(Request $request)
    {
        $notrans = $request->input('notrans');

        // Ambil kunjungan berdasarkan notrans
        $dataKunjungan = KasirTransModel::where('notrans', $notrans)->first();
        if (!$dataKunjungan) {
            return response()->json(['message' => 'No Transaksi tidak valid'], 400);
        }

        // Ambil item terkait berdasarkan notrans
        $dataItems = KasirAddModel::where('notrans', $notrans)->get();

        // Mulai transaksi database untuk menjaga konsistensi
        DB::beginTransaction();

        try {
            // Hapus semua item terkait
            foreach ($dataItems as $item) {
                $item->delete();
            }

            // Hapus kunjungan
            $dataKunjungan->delete();

            // Commit transaksi
            DB::commit();

            return response()->json(['message' => 'Kunjungan dan item berhasil dihapus'], 200);
        } catch (\Exception $e) {
            // Rollback jika terjadi kesalahan
            DB::rollBack();

            return response()->json([
                'message' => 'Terjadi kesalahan saat menghapus data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function saveOrUpdateKunjungan(array $request)
    {
        $notrans = $request['notrans'];
        $tagihan = $request['tagihan'];

        $dataKunjungan = KasirTransModel::firstOrNew(['notrans' => $notrans]);
        $dataKunjungan->fill([
            'norm' => $request['norm'],
            'nama' => $request['nama'],
            'jk' => $request['jk'],
            'umur' => $request['umur'],
            'alamat' => $request['alamat'],
            'jaminan' => $request['jaminan'],
            'tagihan' => str_replace(['Rp', '.', ',', ' '], '', $request['tagihan']),
            'bayar' => str_replace(['Rp', '.', ',', ' '], '', $request['bayar']),
            'kembalian' => str_replace(['Rp', '.', ',', ' '], '', $request['kembalian']),
            'petugas' => $request['petugas'],
        ]);
        $dataKunjungan->save();

        return $dataKunjungan;
    }

    public function order(Request $request)
    {
        $notrans = $request->input('notrans');
        $data = KasirAddModel::with('layanan', 'transaksi')
            ->where('notrans', $notrans)
            ->get();
        // dd($data);
        if ($data == null) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
    public function kunjungan(Request $request)
    {
        $notrans = $request->input('notrans');
        $data = KasirTransModel::with(['item.layanan'])->where('notrans', $notrans)->first();
        if ($data == null) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }

    public function deleteKunjungan(Request $request)
    {
        $data = KasirAddModel::where('notrans', $request->notrans)->first();

        if ($data == null) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        } else {
            $data->delete();
            return response()->json(['message' => 'Data berhasil dihapus'], 200);
        }
    }

    public function rekapKunjunganRupiah(Request $request)
    {
        $norm = $request->input('norm');
        $tglAwal = $request->input('tglAwal', now()->toDateString());
        $tglAkhir = $request->input('tglAkhir', now()->toDateString());

        $data = KasirTransModel::with('item.layanan')
            ->where('norm', 'like', '%' . $norm . '%')
            ->whereBetween('created_at', [
                \Carbon\Carbon::parse($tglAwal)->startOfDay(), // Menambahkan waktu mulai hari
                \Carbon\Carbon::parse($tglAkhir)->endOfDay(), // Menambahkan waktu akhir hari
            ])
            ->get();
        $dataKasir = json_decode($data, true);
        $pasien = [];

        foreach ($dataKasir as $d) {
            $tanggal = Carbon::parse($d['updated_at'])->format('d-m-Y');

            $pemeriksaanDetails = [];
            foreach ($d['item'] as $pemeriksaan) {
                $hasilLab = ($pemeriksaan['hasil'] ?? null) . " " . ($pemeriksaan['ket'] ?? null);
                $pemeriksaanDetails[] = [
                    'id' => $pemeriksaan['idLab'] ?? null,
                    'idLayanan' => $pemeriksaan['idLayanan'] ?? null,
                    'hasil_murni' => $pemeriksaan['hasil'] ?? null,
                    'qty' => $pemeriksaan['qty'] ?? null,
                    'totalHarga' => $pemeriksaan['totalHarga'] ?? null,
                    'nmLayanan' => $pemeriksaan['layanan']['nmLayanan'] ?? null,
                    'tarif' => $pemeriksaan['layanan']['tarif'] ?? null,
                ];
            }

            $pasien[] = [
                'id' => $d['id'] ?? null,
                'notrans' => $d['notrans'] ?? null,
                'tgl' => $tanggal ?? null,
                'norm' => $d['norm'] ?? null,
                'jaminan' => $d['jaminan'] ?? null,
                'nama' => $d['nama'] ?? null,
                'alamat' => $d['alamat'] ?? null,
                'petugas' => $d['petugas'] ?? null,
                'tagihan' => $d['tagihan'] ?? null,
                'bayar' => $d['bayar'] ?? null,
                'kembalian' => $d['kembalian'] ?? null,
                'pemeriksaan' => $pemeriksaanDetails ?? null,
            ];
        }
        return $pasien;
        // return response()->json($pasien, 200, [], JSON_PRETTY_PRINT);
    }

    public function prosesDataRupiah($data)
    {
        $dataKasir = json_decode($data, true);

        $result = [];
        $uniqueServices = [];

        // Inisialisasi array total
        $total = [
            'NO' => 'Total',
            'ID' => '-',
            'Tanggal' => '-',
            'NoRM' => '-',
            'Nama' => '-',
            'Jaminan' => '-',
            'Tagihan' => 0,
            'Bayar' => 0,
            'Kembalian' => 0,
        ];

        foreach ($dataKasir as $index => $d) {
            $row = [
                'NO' => $index + 1,
                'ID' => $d['id'] ?? '-',
                'Tanggal' => isset($d['created_at']) ? Carbon::parse($d['created_at'])->format('d-m-Y') : '-',
                'NoRM' => $d['norm'] ?? '-',
                'Nama' => $d['nama'] ?? '-',
                'Jaminan' => $d['jaminan'] ?? '-',
                'Tagihan' => $d['tagihan'] ?? 0,
                'Bayar' => $d['bayar'] ?? 0,
                'Kembalian' => $d['kembalian'] ?? 0,
            ];

            // Akumulasi total tagihan, bayar, dan kembalian
            $total['Tagihan'] += $row['Tagihan'];
            $total['Bayar'] += $row['Bayar'];
            $total['Kembalian'] += $row['Kembalian'];

            foreach ($d['item'] as $item) {
                $serviceName = $item['layanan']['nmLayanan'] ?? 'Unknown Service';
                $serviceId = $item['layanan']['idLayanan'] ?? 0; // Ambil ID Layanan
                $qty = $item['totalHarga'] ?? 0;

                // Kumpulkan layanan unik dengan ID-nya
                $uniqueServices[$serviceId] = $serviceName;

                // Tambahkan qty ke baris
                $row[$serviceName] = $qty;

                // Akumulasi total layanan
                if (!isset($total[$serviceName])) {
                    $total[$serviceName] = 0;
                }
                $total[$serviceName] += $qty;
            }

            // Format nilai uang dalam baris
            $row['Tagihan'] = number_format($row['Tagihan'], 0, ',', '.');
            $row['Bayar'] = number_format($row['Bayar'], 0, ',', '.');
            $row['Kembalian'] = number_format($row['Kembalian'], 0, ',', '.');

            $result[] = $row;
        }

        // Urutkan layanan berdasarkan idLayanan (key array)
        ksort($uniqueServices);

        // Tambahkan kolom dengan nilai "-" untuk layanan yang tidak ada di transaksi tertentu
        foreach ($result as &$row) {
            foreach (array_keys($uniqueServices) as $serviceId) {
                $serviceName = $uniqueServices[$serviceId]; // Ambil nama layanan berdasarkan ID
                if (!array_key_exists($serviceName, $row)) {
                    $row[$serviceName] = 0; // Berikan nilai 0 jika tidak ada layanan
                }
            }
        }

        // Format nilai uang dalam total
        $total['Tagihan'] = number_format($total['Tagihan'], 0, ',', '.');
        $total['Bayar'] = number_format($total['Bayar'], 0, ',', '.');
        $total['Kembalian'] = number_format($total['Kembalian'], 0, ',', '.');

        // Tambahkan baris total ke hasil
        foreach (array_keys($uniqueServices) as $serviceId) {
            $serviceName = $uniqueServices[$serviceId];
            if (!isset($total[$serviceName])) {
                $total[$serviceName] = 0;
            }
        }

        $result[] = $total;

        return $result;
    }

    // public function rekapKunjungan(Request $request)
    // {
    //     $norm = $request->input('norm');
    //     $tglAwal = $request->input('tglAwal', now()->toDateString());
    //     $tglAkhir = $request->input('tglAkhir', now()->toDateString());

    //     $data = KasirTransModel::with('item.layanan')
    //         ->where('norm', 'like', '%' . $norm . '%')
    //         ->whereBetween('created_at', [
    //             \Carbon\Carbon::parse($tglAwal)->startOfDay(),
    //             \Carbon\Carbon::parse($tglAkhir)->endOfDay(),
    //         ])
    //         ->get();
    //     // return $data;

    //     $dataRupiah = $this->prosesDataRupiah($data);
    //     // return $dataRupiah;

    //     $dataKasir = json_decode($data, true);

    //     $result = [];
    //     $uniqueServices = [];

    //     // Inisialisasi array total
    //     $total = [
    //         'NO' => 'Total',
    //         'ID' => '-',
    //         'Tanggal' => '-',
    //         'NoRM' => '-',
    //         'Nama' => '-',
    //         'Jaminan' => '-',
    //         'Tagihan' => 0,
    //         'Bayar' => 0,
    //         'Kembalian' => 0,
    //     ];

    //     foreach ($dataKasir as $index => $d) {
    //         $row = [
    //             'NO' => $index + 1,
    //             'ID' => $d['id'] ?? '-',
    //             'Tanggal' => isset($d['created_at']) ? Carbon::parse($d['created_at'])->format('d-m-Y') : '-',
    //             'NoRM' => $d['norm'] ?? '-',
    //             'Nama' => $d['nama'] ?? '-',
    //             'Jaminan' => $d['jaminan'] ?? '-',
    //             'Tagihan' => $d['tagihan'] ?? 0,
    //             'Bayar' => $d['bayar'] ?? 0,
    //             'Kembalian' => $d['kembalian'] ?? 0,
    //         ];

    //         // Akumulasi total tagihan, bayar, dan kembalian
    //         $total['Tagihan'] += $row['Tagihan'];
    //         $total['Bayar'] += $row['Bayar'];
    //         $total['Kembalian'] += $row['Kembalian'];

    //         foreach ($d['item'] as $item) {
    //             $serviceName = $item['layanan']['nmLayanan'] ?? 'Unknown Service';
    //             $qty = $item['qty'] ?? 0;

    //             // Kumpulkan layanan unik
    //             $uniqueServices[$serviceName] = true;

    //             // Tambahkan qty ke baris
    //             $row[$serviceName] = $qty;

    //             // Akumulasi total layanan
    //             if (!isset($total[$serviceName])) {
    //                 $total[$serviceName] = 0;
    //             }
    //             $total[$serviceName] += $qty;
    //         }
    //         // Format nilai uang dalam baris
    //         $row['Tagihan'] = number_format($row['Tagihan'], 0, ',', '.');
    //         $row['Bayar'] = number_format($row['Bayar'], 0, ',', '.');
    //         $row['Kembalian'] = number_format($row['Kembalian'], 0, ',', '.');

    //         $result[] = $row;
    //     }

    //     foreach ($result as &$row) {
    //         foreach (array_keys($uniqueServices) as $service) {
    //             if (!array_key_exists($service, $row)) {
    //                 $row[$service] = 0; // Berikan nilai 0 jika tidak ada layanan
    //             }
    //         }
    //     }
    //     // Format nilai uang dalam total
    //     $total['Tagihan'] = number_format($total['Tagihan'], 0, ',', '.');
    //     $total['Bayar'] = number_format($total['Bayar'], 0, ',', '.');
    //     $total['Kembalian'] = number_format($total['Kembalian'], 0, ',', '.');
    //     foreach (array_keys($uniqueServices) as $service) {
    //         if (!isset($total[$service])) {
    //             $total[$service] = 0;
    //         }
    //     }

    //     $result[] = $total;

    //     return response()->json([
    //         'data' => $result,
    //         'dataRupiah' => $dataRupiah,
    //         'columns' => array_merge(['NO', 'ID', 'Tanggal', 'NoRM', 'Nama', 'Jaminan', 'Tagihan', 'Bayar', 'Kembalian'], array_keys($uniqueServices)),
    //     ], 200, [], JSON_PRETTY_PRINT);

    // }
    public function rekapKunjungan(Request $request)
    {
        $norm = $request->input('norm');
        $tglAwal = $request->input('tglAwal', now()->toDateString());
        $tglAkhir = $request->input('tglAkhir', now()->toDateString());

        $data = KasirTransModel::with('item.layanan')
            ->where('norm', 'like', '%' . $norm . '%')
            ->whereBetween('created_at', [
                \Carbon\Carbon::parse($tglAwal)->startOfDay(),
                \Carbon\Carbon::parse($tglAkhir)->endOfDay(),
            ])
            ->get();
        // return $data;

        $dataRupiah = $this->prosesDataRupiah($data);
        // return $dataRupiah;

        $dataKasir = json_decode($data, true);

        $result = [];
        $uniqueServices = [];

        // Inisialisasi array total
        $total = [
            'NO' => 'Total',
            'ID' => '-',
            'Tanggal' => '-',
            'NoRM' => '-',
            'Nama' => '-',
            'Jaminan' => '-',
            'Tagihan' => 0,
            'Bayar' => 0,
            'Kembalian' => 0,
        ];

        // Looping untuk mengumpulkan data dan layanan unik
        foreach ($dataKasir as $index => $d) {
            $row = [
                'NO' => $index + 1,
                'ID' => $d['id'] ?? '-',
                'Tanggal' => isset($d['created_at']) ? Carbon::parse($d['created_at'])->format('d-m-Y') : '-',
                'NoRM' => $d['norm'] ?? '-',
                'Nama' => $d['nama'] ?? '-',
                'Jaminan' => $d['jaminan'] ?? '-',
                'Tagihan' => $d['tagihan'] ?? 0,
                'Bayar' => $d['bayar'] ?? 0,
                'Kembalian' => $d['kembalian'] ?? 0,
            ];

            // Akumulasi total tagihan, bayar, dan kembalian
            $total['Tagihan'] += $row['Tagihan'];
            $total['Bayar'] += $row['Bayar'];
            $total['Kembalian'] += $row['Kembalian'];

            foreach ($d['item'] as $item) {
                $serviceName = $item['layanan']['nmLayanan'] ?? 'Unknown Service';
                $serviceId = $item['layanan']['idLayanan'] ?? 0; // Ambil ID Layanan
                $qty = $item['qty'] ?? 0;

                // Kumpulkan layanan unik dengan ID-nya
                $uniqueServices[$serviceId] = $serviceName;

                // Tambahkan qty ke baris
                $row[$serviceName] = $qty;

                // Akumulasi total layanan
                if (!isset($total[$serviceName])) {
                    $total[$serviceName] = 0;
                }
                $total[$serviceName] += $qty;
            }

            // Format nilai uang dalam baris
            $row['Tagihan'] = number_format($row['Tagihan'], 0, ',', '.');
            $row['Bayar'] = number_format($row['Bayar'], 0, ',', '.');
            $row['Kembalian'] = number_format($row['Kembalian'], 0, ',', '.');

            $result[] = $row;
        }

        // Urutkan layanan berdasarkan idLayanan (key array)
        ksort($uniqueServices);

        // Tambahkan kolom dengan nilai "-" untuk layanan yang tidak ada di transaksi tertentu
        foreach ($result as &$row) {
            foreach (array_keys($uniqueServices) as $serviceId) {
                $serviceName = $uniqueServices[$serviceId]; // Ambil nama layanan berdasarkan ID
                if (!array_key_exists($serviceName, $row)) {
                    $row[$serviceName] = 0; // Berikan nilai 0 jika tidak ada layanan
                }
            }
        }

        // Format nilai uang dalam total
        $total['Tagihan'] = number_format($total['Tagihan'], 0, ',', '.');
        $total['Bayar'] = number_format($total['Bayar'], 0, ',', '.');
        $total['Kembalian'] = number_format($total['Kembalian'], 0, ',', '.');

        // Tambahkan baris total ke hasil
        foreach (array_keys($uniqueServices) as $serviceId) {
            $serviceName = $uniqueServices[$serviceId];
            if (!isset($total[$serviceName])) {
                $total[$serviceName] = 0;
            }
        }

        $result[] = $total;

        // Kembalikan respons JSON
        return response()->json([
            'data' => $result,
            'dataRupiah' => $dataRupiah,
            'columns' => array_merge(['NO', 'ID', 'Tanggal', 'NoRM', 'Nama', 'Jaminan', 'Tagihan', 'Bayar', 'Kembalian'], array_values($uniqueServices)),
        ], 200, [], JSON_PRETTY_PRINT);

    }

    public function cetakSBS($tgl, $tahun, $jaminan)
    {
        $title = 'SBS';

        // Fetch data from the model
        $model = new KasirTransModel();
        $datas = $model->pendapatan($tahun);

        // If the data is not an array, return an error
        if (!is_array($datas)) {
            return response()->json(['error' => 'Invalid data format']);
        }

        // Ensure the jaminan key exists in the data and process it
        if (isset($datas[$jaminan])) {
            // Convert the data to an array if it's an object
            $jaminanData = is_object($datas[$jaminan]) ? json_decode(json_encode($datas[$jaminan]), true) : $datas[$jaminan];

            // Validate that $jaminanData is an array
            if (!is_array($jaminanData)) {
                return response()->json(['error' => 'Invalid jaminan data format']);
            }

            // Filter the data by the specified date
            $filteredData = array_filter($jaminanData, function ($item) use ($tgl) {
                return isset($item['tanggal']) && $item['tanggal'] === $tgl;
            });

            $doc = reset($filteredData);
        } else {
            // Handle the case where jaminan is not found
            return response()->json(['error' => 'No data found for the specified jaminan']);
        }

        // return $doc;
        // Pass the filtered data to the view
        return view('Laporan.Kasir.sbs', compact('doc'))->with('title', $title);
    }

    public function cetakBAPH($tgl, $tahun, $jaminan)
    {
        $title = 'BAPH';

        // Fetch data from the model
        $model = new KasirTransModel();
        $datas = $model->pendapatan($tahun);

        // If the data is not an array, return an error
        if (!is_array($datas)) {
            return response()->json(['error' => 'Invalid data format']);
        }

        // Ensure the jaminan key exists in the data and process it
        if (isset($datas[$jaminan])) {
            // Convert the data to an array if it's an object
            $jaminanData = is_object($datas[$jaminan]) ? json_decode(json_encode($datas[$jaminan]), true) : $datas[$jaminan];

            // Validate that $jaminanData is an array
            if (!is_array($jaminanData)) {
                return response()->json(['error' => 'Invalid jaminan data format']);
            }

            // Filter the data by the specified date
            $filteredData = array_filter($jaminanData, function ($item) use ($tgl) {
                return isset($item['tanggal']) && $item['tanggal'] === $tgl;
            });

            $doc = reset($filteredData);
        } else {
            // Handle the case where jaminan is not found
            return response()->json(['error' => 'No data found for the specified jaminan']);
        }
        // Return the view with the filtered data
        return view('Laporan.Kasir.baph', compact('doc'))->with('title', $title);
    }

    public function pendapatan($tahun)
    {
        $model = new KasirTransModel();
        $res = $model->pendapatan($tahun);
        // dd($res);
        return response()->json($res, 200, [], JSON_PRETTY_PRINT);
    }

    public function pendapatanPerItem(Request $request)
    {
        $model = new KasirAddModel();
        $params = [
            'tglAwal' => $request->input('tglAwal'),
            'tglAkhir' => $request->input('tglAkhir'),
        ];
        return $model->pendapatanPerItem($params);
    }
    public function pendapatanPerRuang(Request $request)
    {
        $model = new KasirAddModel();
        $params = [
            'tglAwal' => $request->input('tglAwal'),
            'tglAkhir' => $request->input('tglAkhir'),
        ];
        return $model->pendapatanPerRuang($params);
    }
}
