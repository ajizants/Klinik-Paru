<?php
namespace App\Http\Controllers;

use App\Models\DotsTransModel;
use App\Models\IGDTransModel;
use App\Models\KominfoModel;
use App\Models\LaboratoriumHasilModel;
use App\Models\PegawaiKegiatanModel;
use App\Models\PegawaiModel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EkinController extends Controller
{
    public function index()
    {
        $title = 'E-Kinerja';
        $model = new PegawaiModel();
        $karyawan = PegawaiModel::with('biodata')
            ->whereNot('kd_jab', '22')
            ->get()
            ->sortBy('kd_jab');
        $pegawai = $model->olahPegawai([]);
        // return $pegawai;
        $modelKegiatanLain = new PegawaiKegiatanModel();
        $hasilKegiatan = $modelKegiatanLain->allData();
        $tablePegawai = $model->dataPegawai();

        return view('Laporan.Ekin.main', compact('pegawai', 'tablePegawai', 'hasilKegiatan'))->with('title', $title);
    }

    private function poinKominfo(Request $request)
    {
        $params = $request->only(['tanggal_awal', 'tanggal_akhir']);

        $nip = $request->input('nip');
        $nama = $request->input('nama'); // Bisa berupa sebagian dari nama
        $model = new KominfoModel();
        $data = $model->poinRequest($params);
        $poinKominfo = [];
        if (empty($data['response']['data'])) {
            return $poinKominfo;
        }

        // Filter data yang bukan "Ruang Poli" dan admin_nama mengandung $nama
        $filteredData = collect($data['response']['data'])->filter(function ($item) use ($nama) {
            return $item['ruang_nama'] !== 'Ruang Poli' && stripos($item['admin_nama'], $nama) !== false;
        });
        // return $filteredData;
        foreach ($filteredData as $item) {
            $key = strtolower(str_replace([' ', '(', ')'], '', $item['ruang_nama'])); // Buat key unik
            $poinKominfo[$key] = $item['jumlah'];
        }
        // return $poinKominfo;
        if (empty($poinKominfo)) {
            $kosong = [
                "anamnesa" => "-",
                "pasienBaru" => "-",
                "pasienLama" => "-",
                "ruangpoliperawatpoli" => "-",
                "ruangpolidoktercppt" => '-',
            ];
            return $kosong;
        }
        // return $poinKominfo;
        $tensi1 = $poinKominfo['ruangtensi1'] ?? 0;
        $tensi2 = $poinKominfo['ruangtensi2'] ?? 0;
        $anamnesa = $tensi1 + $tensi2;
        $pasienBaru = ceil($anamnesa / 2); // Membulatkan ke atas
        $pasienLama = floor($anamnesa / 2);

        $poinKominfo = [
            "anamnesa" => $anamnesa == 0 ? '-' : $anamnesa,
            "pasienBaru" => $pasienBaru == 0 ? '-' : $pasienBaru,
            "pasienLama" => $pasienLama == 0 ? '-' : $pasienLama,
            "ruangpoliperawatpoli" => $poinKominfo['ruangpoliperawatpoli'] ?? '-',
            "ruangpolidoktercppt" => $poinKominfo['ruangpolidoktercppt'] ?? '-',
        ];

        return $poinKominfo;
    }

    private function poinIGD(Request $request)
    {
        $tglAwal = $request->input('tanggal_awal');
        $tglAkhir = $request->input('tanggal_akhir');
        $nip = $request->input('nip');
        // dd($nip);
        $nama = $request->input('nama');
        $model = new IGDTransModel();
        $data = json_decode(json_encode($model->cariPoin($tglAwal, $tglAkhir)), true);

        //filter data berdasarkan nip
        $filteredData = collect($data)->filter(function ($item) use ($nip) {
            return $item['nip'] === $nip;
        });
        // dd($filteredData);

        $poinIgd = [];
        foreach ($filteredData as $item) {
            $key = strtolower(str_replace([' ', '(', ')'], '', $item['tindakan'])); // Buat key unik
            $poinIgd[$key] = $item['jml'];
        }
        return $poinIgd;
    }
    private function poinInputHiv(Request $request)
    {
        $tglAwal = $request->input('tanggal_awal');
        $tglAkhir = $request->input('tanggal_akhir');
        $data = LaboratoriumHasilModel::where('created_at', '>=', $tglAwal)
            ->where('created_at', '<=', $tglAkhir)
            ->whereIn('idLayanan', [124, 125, 129])
            ->count();

        return $data;
    }

    public function show(Request $request)
    {
        $params = [
            'tanggal_awal' => $request->input('tanggal_awal'),
            'tanggal_akhir' => $request->input('tanggal_akhir'),
            'nip' => $request->input('nip'),
            'nama' => $request->input('nama'),
        ];
        $tglAkhir = Carbon::parse($request->input('tanggal_akhir'))
            ->locale('id') // Atur lokal ke Indonesia
            ->translatedFormat('d F Y');

        $tgl = Carbon::parse($request->input('tanggal_akhir'));

        $poinIgd = $this->poinIGD(new Request($params));

        $poinKominfo = $this->poinKominfo(new Request($params));

        if ($request->input('nip') == '199806222022031007') {
            $inputPitc = $this->poinInputHiv(new Request($params));
        } else {
            $inputPitc = "-";
        }

        if ($request->input('nip') == '5') {
            $nipReplace = '199409052024211029';
        } else if ($request->input('nip') == '4') {
            $nipReplace = '199304112024212037';
        } else if ($request->input('nip') == '9999') {
            $nipReplace = '197511201996032001';
        } else {
            $nipReplace = $request->input('nip');
        }

        $pegawai = PegawaiModel::with('biodata', 'jabatan')->where('nip', $request->input('nip'))->first();
        // return $pegawai;
        $biodata = [
            'nip' => $nipReplace,
            'nama' => $pegawai->gelar_d . ' ' . $pegawai->biodata->nama . ', ' . $pegawai->gelar_b,
            'jabatan' => $pegawai->jabatan->nm_jabatan ?? "-",
            'pangkat' => $pegawai->pangkat_gol ?? "-",
        ];

        $modelPoinDots = new DotsTransModel();
        $poinDots = $modelPoinDots->poinPetugas($request->input('tanggal_awal'), $request->input('tanggal_akhir'), $request->input('nip'));
        // return $poinDots;

        $modelPoinLain = new PegawaiKegiatanModel();
        $poinLain = $modelPoinLain->rekap($params);

        $title = $pegawai->gelar_d . ' ' . $pegawai->biodata->nama . ', ' . $pegawai->gelar_b . ' Kinerja Bulan ' . $tgl->locale('id')->translatedFormat('F Y');

        $view = match (true) {
            in_array($pegawai->kd_jab, [7, 8]) => 'dokter',
            $pegawai->kd_jab === 14 => 'gizi',
            $pegawai->kd_jab === 15 => 'terapis',
            in_array($pegawai->kd_jab, [10, 23]) => 'nurse',
        };

        return view("Laporan.Ekin.$view", compact('title', 'poinIgd', 'poinKominfo', 'inputPitc', 'biodata', 'tglAkhir', 'tgl', 'poinDots', 'poinLain'));

        return [
            'inputPitc' => $inputPitc,
            'poinIgd' => $poinIgd,
            'poinKominfo' => $poinKominfo,
            'biodata' => $biodata,
            // 'pegawai' => $pegawai,
            'poinDots' => $poinDots,
            'poinLain' => $poinLain,
            'title' => $title,
        ];

    }

    public function store(Request $request)
    {
        $request->validate([
            'pegawai' => 'required|string',
            'tglKegiatan' => 'required|date',
            'kegiatan' => 'required|string',
            'jumlah' => 'required|numeric',
        ]);

        try {
            $data = PegawaiKegiatanModel::create([
                'nip' => $request->pegawai,
                'tanggal' => $request->tglKegiatan,
                'kegiatan' => $request->kegiatan,
                'keterangan' => $request->kegLain,
                'jumlah' => $request->jumlah,
            ]);
            $modelKegiatanLain = new PegawaiKegiatanModel();
            $hasil = $modelKegiatanLain->allData();

            return response()->json(['success' => true, 'data' => $data, 'table' => $hasil]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'pegawai' => 'required|string',
            'tglKegiatan' => 'required|date',
            'kegiatan' => 'required|string',
            'id' => 'required',
            'jumlah' => 'required|numeric',
        ]);
        $params = [
            'tanggal_awal' => $request->input('tglKegiatan'),
            'tanggal_akhir' => $request->input('tglKegiatan'),
            'nip' => "",
        ];

        try {
            $data = PegawaiKegiatanModel::where('id', $request->id)->update([
                'nip' => $request->pegawai,
                'tanggal' => $request->tglKegiatan,
                'kegiatan' => $request->kegiatan,
                'keterangan' => $request->kegLain,
                'jumlah' => $request->jumlah,
            ]);
            $modelKegiatanLain = new PegawaiKegiatanModel();
            $hasil = $modelKegiatanLain->allData();

            return response()->json(['success' => true, 'data' => $data, 'table' => $hasil]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        $data = PegawaiKegiatanModel::where('id', $id)->delete();
        $modelKegiatanLain = new PegawaiKegiatanModel();
        $hasil = $modelKegiatanLain->allData();

        return response()->json([
            'message' => $data ? 'Data Berhasil dihapus' : 'Data not found',
            'table' => $hasil,
        ], 200);
    }
}
