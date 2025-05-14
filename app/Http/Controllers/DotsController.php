<?php
namespace App\Http\Controllers;

use App\Models\DiagnosaModel;
use App\Models\DotsBlnModel;
use App\Models\DotsModel;
use App\Models\DotsObatModel;
use App\Models\DotsTransModel;
use App\Models\KominfoModel;
use App\Models\PegawaiKegiatanModel;
use App\Models\PegawaiModel;
use Carbon\Carbon;
use Exception;
use function PHPUnit\Framework\isNull;
use Illuminate\Http\Request;

class DotsController extends Controller
{
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

    public function dots()
    {
        $title = 'Dots Center';
        $pModel = new PegawaiModel();
        $dokter = $pModel->olahPegawai([1, 7, 8]);
        $perawat = $pModel->olahPegawai([10, 14, 15, 23]);
        $pegawai = $pModel->olahPegawai([]);
        $bulan = DotsBlnModel::all();
        $obat = DotsObatModel::all();
        $dxMed = DiagnosaModel::all();
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

    public function Ptb(Request $request)
    {
        $norm = $request->input('norm');
        $tanggal = $request->input('tanggal', Carbon::now()->toDateString());
        $kominfo = new KominfoModel();
        $ptbData = [];

        // Jika `norm` tidak diisi, ambil semua data pasien
        if (!$norm) {
            $Ptb = DotsModel::with(['dokter.biodata', 'diagnosa', 'pengobatan'])->get()->map(function ($d) {
                $d['diagnosa'] = $d->diagnosa->diagnosa ?? '-';

                $d['status'] = $this->getStatusPengobatan($d['statusPengobatan']);

                $d['hasilPengobatan'] = $d->pengobatan->nmBlnKe ?? "Belum Ada Pengobatan";

                $d['ket'] = $d['ket'] ?? '-';

                return $d;
            });

            return $this->responseJson(true, 'Data Semua Pasien Ditemukan...!!', $Ptb);
        }

        // Cari pasien berdasarkan `norm`
        $Ptb = DotsModel::with('dokter.biodata')->where('norm', $norm)->first();
        // dd($Ptb);
        if (!$Ptb) {
            // dd("no pasian");
            // Jika pasien tidak ditemukan
            $pasien = $kominfo->pasienRequest($norm);
            // dd($pasien);
            $params = ['tanggal_awal' => $tanggal, 'tanggal_akhir' => $tanggal, 'no_rm' => $norm];

            // Ambil data diagnosa dari CPPT
            $cppt = $kominfo->cpptRequest($params);
            // dd($cppt);
            $kodeDiagnosa = !empty($cppt['response']['data'])
            ? array_column(array_merge(...array_column($cppt['response']['data'], 'diagnosa')), 'kode_diagnosa')
            : '';

            // Ambil data pendaftaran dan tambahkan NIP dokter
            $pendaftaran = $this->filterDokterNip($kominfo->pendaftaranRequest($params));

            $ptbData[] = [
                'pendaftaran' => $pendaftaran,
                'pasien' => $pasien,
                'diagnosa' => $kodeDiagnosa,
            ];
            $res = [
                'exist' => false,
                'metadata' => [
                    'code' => 204,
                    'message' => 'Belum Terdaftar Sebagai Pasien TBC...!!',
                ],
                'data' => $ptbData,
            ];
            return response()->json($res, 200, [], JSON_PRETTY_PRINT);
        }

        // Jika pasien ditemukan
        $pasien = $kominfo->pasienRequest($Ptb['norm']);
        $params = ['tanggal' => $tanggal, 'no_rm' => $Ptb['norm']];
        $pendaftaran = $this->filterDokterNip($kominfo->waktuLayananRequest($params));

        if (isNull($Ptb->ket)) {
            $Ptb->ket = '-';
        }

        $Ptb['status'] = $this->getStatusPengobatan($Ptb['statusPengobatan']);
        $Ptb['statusPengobatan'] = $Ptb['hasilBerobat']
        ? DotsBlnModel::where('id', $Ptb['hasilBerobat'])->value('nmBlnKe')
        : "Belum Ada Pengobatan";

        $ptbData[] = [
            'pasien' => $pasien,
            'ptb' => $Ptb,
            'diagnosa' => DiagnosaModel::where('kdDiag', $Ptb['kdDx'])->get(),
            'pendaftaran' => $pendaftaran,
        ];

        return $this->responseJson(true, 'Pasien Ditemukan...!!', $ptbData);
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

    private function filterDokterNip($data)
    {
        $doctorNipMap = [
            'dr. Cempaka Nova Intani, Sp.P, FISR., MM.' => '198311142011012002',
            'dr. AGIL DANANJAYA, Sp.P' => '9',
            'dr. FILLY ULFA KUSUMAWARDANI' => '198907252019022004',
            'dr. SIGIT DWIYANTO' => '198903142022031005',
        ];

        return array_map(function ($d) use ($doctorNipMap) {
            $d['nip_dokter'] = $doctorNipMap[$d['dokter_nama']] ?? 'Unknown';
            return $d;
        }, $data);
    }

    private function responseJson($exist, $message, $data, $code = 200)
    {
        return response()->json([
            'exist' => $exist,
            'metadata' => [
                'code' => $code,
                'message' => $message,
            ],
            'data' => $data,
        ], $code, [], JSON_PRETTY_PRINT);
    }

    public function telat()
    {
        // Ambil semua data pasien dari DotsModel
        $Ptb = DotsModel::all();
        $pasien_telat = [];

        foreach ($Ptb as $d) {
            // Ambil transaksi terakhir untuk pasien ini
            $Pkontrol = DotsTransModel::with('bln')
                ->where('norm', $d->norm)
                ->latest('created_at')
                ->first();

            // Pastikan $Pkontrol tidak null sebelum akses atribut
            if ($Pkontrol) {
                $hasilBerobat = $d->hasilBerobat; // Ambil hasil berobat dari Ptb (DotsModel)
                $now = Carbon::now();
                $nxKontrolDate = Carbon::parse($Pkontrol->nxKontrol);
                $terakhir_kontrol = Carbon::parse($Pkontrol->created_at);

                $kdBlnke = $Pkontrol->bln->id ?? null; // Validasi null
                $blnke = $Pkontrol->bln->nmBlnKe ?? '-'; // Fallback jika null
                $selisihHari = $nxKontrolDate->diffInDays($now, false); // Hitung selisih hari (bisa negatif jika sudah lewat)

                // Mengambil data dokter
                $dataDokter = PegawaiModel::with('biodata')->where('nip', $Pkontrol->dokter)->first();
                $namaDokter = $dataDokter
                ? $dataDokter->gelar_d . " " . $dataDokter->biodata->nama . " " . $dataDokter->gelar_b
                : 'Tidak Diketahui';

                // Tentukan status pasien berdasarkan hasilBerobat dan selisih hari
                if (in_array($hasilBerobat, ["93", "94", "95", "96", "97", "98"])) {
                    $status = 'Tidak Diketahui';
                } elseif (abs($selisihHari) > 30) {
                    $status = 'DO';
                } elseif ($selisihHari > 0 && abs($selisihHari) <= 30) {
                    $status = 'Telat';
                } elseif ($selisihHari >= -7 && $selisihHari <= 0) {
                    $status = 'Tepat Waktu';
                } elseif ($selisihHari < -7) {
                    $status = 'Belum Saatnya';
                } else {
                    $status = 'Tidak Diketahui';
                }
                if (isNull($d->ket)) {
                    $d->ket = '-';
                }

                // Format tanggal dan tambahan data
                $d->terakhir = $terakhir_kontrol->format('d-m-Y');
                $d->selisih = $selisihHari;
                $d->nxKontrol = $nxKontrolDate->format('d-m-Y');
                $d->blnKe = $blnke;
                $d->kdPengobatan = $kdBlnke;
                $d->namaDokter = $namaDokter;
                $d->status = $status;

                $pasien_telat[] = $d;
            }
        }

        // Kembalikan data sebagai JSON response
        return response()->json([
            'metadata' => [
                'code' => 200,
                'message' => 'Data Pasien Ditemukan...!!',
            ],
            'data' => $pasien_telat,
        ], 200, [], JSON_PRETTY_PRINT);
    }

    public function obatDots()
    {
        $obat = DotsObatModel::on('mysql')
            ->get();
        return response()->json($obat, 200, [], JSON_PRETTY_PRINT);
    }
    public function kunjunganDots(Request $request)
    {
        $norm = $request->input('norm');
        $data = DotsTransModel::with('pasien', 'petugas.biodata', 'dokter.biodata', 'bln')
            ->where('norm', $norm)
            ->get();
        foreach ($data as $d) {
            $bta = $d->bta;
            if ($bta == null || $bta == '') {
                $d['bta'] = 'Tidak Diketahui/Tidak Cek';
            }
        }

        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
    public function FindKunjunganDots($id)
    {
        $data = DotsTransModel::with('pasien', 'petugas.biodata', 'dokter.biodata', 'bln')
            ->where('id', $id)
            ->get();
        // foreach ($data as $d) {
        //     $bta = $d->bta;
        //     if ($bta == null || $bta == '') {
        //         $d['bta'] = 'Tidak Diketahui/Tidak Cek';
        //     }
        // }
        // return response()->json($data, 200, [], JSON_PRETTY_PRINT);

        $pasien = [
            'pasien_no_rm' => $data[0]->norm,
            'pasien_nama' => $data[0]->pasien->nama,
            'pasien_alamat' => $data[0]->pasien->alamat,
            'penjamin_nama' => "-",
            'nip_dokter' => $data[0]->dokter,
        ];
        $pendaftaran = [
            'no_reg' => $data[0]->notrans,
        ];
        $kunjungan = [
            'id' => $data[0]->id,
            'petugas' => $data[0]->petugas,
            'tgl' => \Carbon\Carbon::parse($data[0]->created_at)->format('Y-m-d'),
            'bta' => $data[0]->bta ?? 'Tidak Cek BTA',
            'blnKe' => $data[0]->blnKe,
            'nxKontrol' => $data[0]->nxKontrol,
            'obatDots' => $data[0]->terapi,
            'bb' => $data[0]->bb,
            'ket' => $data[0]->ket,
        ];
        $response = [
            'pasien' => $pasien,
            'pendaftaran' => $pendaftaran,
            'kunjungan' => $kunjungan,
        ];

        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }
    public function blnKeDots()
    {
        $obat = DotsBlnModel::on('mysql')
            ->get();
        return response()->json($obat, 200, [], JSON_PRETTY_PRINT);
    }

    public function simpanKunjungan(Request $request)
    {
        // Ambil data dari permintaan Ajax
        $norm = $request->input('norm');
        $notrans = $request->input('notrans');
        $tgltrans = $request->input('tgltrans');
        $bta = $request->input('bta');
        $bb = $request->input('bb');
        $blnKe = $request->input('blnKe');
        $nxKontrol = $request->input('nxKontrol');
        $terapi = $request->input('terapi');
        $ket = $request->input('ket');
        $petugas = $request->input('petugas');
        $dokter = $request->input('dokter');

        if ($norm !== null) {
            // Cek apakah data sudah ada berdasarkan notrans
            $existingData = DotsTransModel::where('norm', $norm)->where('created_at', $tgltrans)->first();

            if ($existingData) {
                // Jika data ditemukan, lakukan update
                $existingData->created_at = $tgltrans;
                $existingData->bta = $bta;
                $existingData->blnKe = $blnKe;
                $existingData->bb = $bb;
                $existingData->nxKontrol = $nxKontrol;
                $existingData->terapi = $terapi;
                $existingData->ket = $ket;
                $existingData->petugas = $petugas;
                $existingData->dokter = $dokter;

                // Simpan perubahan
                $existingData->save();

                $msg = "Data kunjungan dengan NoTrans: $notrans berhasil diupdate.";
            } else {
                // Jika data tidak ditemukan, lakukan insert
                $addPTB = new DotsTransModel();
                $addPTB->norm = $norm;
                $addPTB->notrans = $notrans;
                $addPTB->created_at = $tgltrans;
                $addPTB->bta = $bta;
                $addPTB->blnKe = $blnKe;
                $addPTB->bb = $bb;
                $addPTB->nxKontrol = $nxKontrol;
                $addPTB->terapi = $terapi;
                $addPTB->ket = $ket;
                $addPTB->petugas = $petugas;
                $addPTB->dokter = $dokter;

                // Simpan data baru
                $addPTB->save();

                $msg = "Kunjungan pasien TBC berhasil disimpan.";
            }

            // Update data pada tabel DotsModel jika ditemukan
            $msgUpdate = "";
            $update = DotsModel::where('norm', $norm)->first();
            if ($update) {
                $update->hasilBerobat = $blnKe;
                $update->save();
                $msgUpdate = " Status Pengobatan berhasil diupdate.";
            }

            // Gabungkan pesan respon
            $res = $msg . $msgUpdate;

            // Respon sukses
            return response()->json(['message' => $res]);
        } else {
            // Jika $norm null, kirim respon error
            return response()->json(['message' => 'Kode tidak valid'], 400);
        }

    }

    public function addPasienTb(Request $request)
    {
        // Ambil data dari permintaan Ajax
        $norm = $request->input('norm');
        $nik = $request->input('nik');
        $hp = $request->input('hp');
        $nama = $request->input('nama');
        $alamat = $request->input('alamat');
        $tcm = $request->input('tcm');
        $sample = $request->input('sample');
        $dx = $request->input('dx');
        $mulai = $request->input('mulai');
        $bb = $request->input('bb');
        $terapi = $request->input('terapi');
        $hasilBerobat = $request->input('hasilBerobat');

        $hiv = $request->input('hiv');
        $dm = $request->input('dm');
        $ket = $request->input('ket');
        $status = $request->input('status');
        $petugas = $request->input('petugas');
        $dokter = $request->input('dokter');

        if ($norm !== null) {
            // Membuat instance dari model KunjunganTindakan
            $addPTB = new DotsModel();
            // Mengatur nilai-nilai kolom
            $addPTB->norm = $norm;
            $addPTB->nama = $nama;
            $addPTB->nik = $nik;
            $addPTB->alamat = $alamat;
            $addPTB->noHp = $hp;
            $addPTB->tcm = $tcm;
            $addPTB->sample = $sample;
            $addPTB->kdDx = $dx;
            $addPTB->tglMulai = $mulai;
            $addPTB->bb = $bb;
            $addPTB->obat = $terapi;
            $addPTB->hiv = $hiv;
            $addPTB->dm = $dm;
            $addPTB->ket = $ket;
            $addPTB->petugas = $petugas;
            $addPTB->dokter = $dokter;
            $addPTB->hasilBerobat = $hasilBerobat;
            $addPTB->statusPengobatan = $status;

            // Simpan data ke dalam tabel
            $addPTB->save();

            // Respon sukses atau redirect ke halaman lain
            return response()->json(['message' => 'Data berhasil disimpan']);
        } else {
            // Handle case when $kdTind is null, misalnya kirim respon error
            return response()->json(['message' => 'kdBmhp tidak valid'], 400);
        }
    }

    public function updatePasienTB(Request $request)
    {
        // dd($request->all());
        $id = $request->input('id');

        if ($id === null) {
            return response()->json(['message' => 'ID tidak valid'], 400);
        }

        $pasien = DotsModel::where('id', $id)->first();

        if ($pasien === null) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $pasien->norm = $request->input('norm');
        $pasien->nama = $request->input('nama');
        $pasien->nik = $request->input('nik');
        $pasien->alamat = $request->input('alamat');
        $pasien->noHp = $request->input('noHp');
        $pasien->tcm = $request->input('tcm');
        $pasien->sample = $request->input('sample');
        $pasien->kdDx = $request->input('kdDx');
        $pasien->tglMulai = $request->input('tglMulai');
        $pasien->bb = $request->input('bb');
        $pasien->obat = $request->input('obat');
        $pasien->hiv = $request->input('hiv');
        $pasien->dm = $request->input('dm');
        $pasien->ket = $request->input('ket');
        $pasien->petugas = $request->input('petugas');
        $pasien->dokter = $request->input('dokter');
        $pasien->hasilBerobat = $request->input('hasilBerobat');
        $pasien->statusPengobatan = $request->input('status');

        try {
            $pasien->save();
        } catch (Exception $e) {
            return response()->json(['message' => 'Gagal mengupdate data'], 500);
        }

        return response()->json(['message' => 'Data berhasil diupdate']);
    }

    public function poinPetugas(Request $request)
    {
        $mulaiTgl = $request->input('tglAwal', now()->toDateString());
        $selesaiTgl = $request->input('tglAkhir', now()->toDateString());

        $model = new DotsTransModel();
        $poin = $model->poinPetugas($mulaiTgl, $selesaiTgl);

        return response()->json($poin, 200, [], JSON_PRETTY_PRINT);
    }

    public function rencanaKontrol(Request $request)
    {
        $mulaiTgl = $request->input('tglAwal', now()->toDateString());
        $selesaiTgl = $request->input('tglAkhir', now()->toDateString());

        $data = DotsTransModel::with(['pasien', 'obat', 'dok.biodata']) // asumsi relasi sudah dibuat
            ->whereBetween('nxKontrol', [$mulaiTgl, $selesaiTgl])
            ->get();
        // return response()->json($data, 200, [], JSON_PRETTY_PRINT);
        if (!$data || count($data) == 0) {
            return response()->json([
                'html' => '<p class="text-center text-danger">Tidak ada data tersedia</p>',
                'data' => [],
            ]);
        }

        // Bangun tabel HTML secara manual
        $html = '<table class="table table-bordered table-hover dataTable dtr-inline" id="rencanaKontroTB" border="1" cellpadding="5" cellspacing="0">
                <thead class="bg bg-lime">
                    <tr>
                        <th>No.</th>
                        <th>Kontrol Selnajutnya</th>
                        <th>No RM</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>No HP</th>
                        <th>Pengobatan</th>
                        <th>Obat</th>
                        <th>Dokter</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($data as $index => $row) {
            $iteration = $index + 1;
            $html .= '<tr>
                    <td>' . $iteration . '</td>
                    <td>' . htmlspecialchars($row->nxKontrol ?? '-') . '</td>
                    <td>' . htmlspecialchars($row->norm ?? '-') . '</td>
                    <td>' . htmlspecialchars($row->pasien->nama ?? '-') . '</td>
                    <td>' . htmlspecialchars($row->pasien->alamat ?? '-') . '</td>
                    <td>' . htmlspecialchars($row->pasien->noHP ?? '-') . '</td>
                    <td>' . htmlspecialchars($row->blnKe ?? '-') . '</td>
                    <td>' . htmlspecialchars($row->terapi ?? '-') . '</td>
                    <td>' . htmlspecialchars($row->dok->biodata->nama ?? '-') . '</td>
                  </tr>';
        }

        $html .= '</tbody></table>';
        $res = [
            'html' => $html,
            'data' => $data,
        ];

        return response()->json($res, 200, [], JSON_PRETTY_PRINT);
    }

}
