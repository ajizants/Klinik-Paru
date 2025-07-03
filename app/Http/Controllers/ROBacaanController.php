<?php
namespace App\Http\Controllers;

use App\Models\KunjunganWaktuSelesai;
use App\Models\PegawaiModel;
use App\Models\ROBacaan;
use App\Models\ROJenisFilm;
use App\Models\ROJenisFoto;
use App\Models\ROJenisKondisi;
use App\Models\ROJenisMesin;
use App\Models\RoProyeksiModel;
use App\Models\ROTransaksiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ROBacaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title       = "Bacaan RO";
        $appUrlRo    = env('APP_URLRO');
        $appUrlRo    = env('APP_URLRO');
        $proyeksi    = RoProyeksiModel::all();
        $kondisi     = ROJenisKondisi::all();
        $mesin       = ROJenisMesin::all();
        $foto        = ROJenisFoto::all();
        $film        = ROJenisFilm::all();
        $pModel      = new PegawaiModel();
        $dokter      = $pModel->olahPegawai([1, 7, 8]);
        $radiografer = $pModel->olahPegawai([12]);

        $kv = array_filter($kondisi->toArray(), function ($p) {
            return $p['grup'] === 'KV';
        });

        $ma = array_filter($kondisi->toArray(), function ($p) {
            return $p['grup'] === 'mA';
        });

        $s = array_filter($kondisi->toArray(), function ($p) {
            return $p['grup'] === 's';
        });

        $kv = array_map(function ($item) {
            return (object) $item;
        }, $kv);

        $ma = array_map(function ($item) {
            return (object) $item;
        }, $ma);

        $s = array_map(function ($item) {
            return (object) $item;
        }, $s);
        $dokter = array_map(function ($item) {
            return (object) $item;
        }, $dokter);
        $radiografer = array_map(function ($item) {
            return (object) $item;
        }, $radiografer);

        return view('RO.Bacaan.main', compact('appUrlRo', 'proyeksi', 'mesin', 'foto', 'film', 'kv', 'ma', 's', 'dokter', 'radiografer'))->with([
            'title' => $title,
        ]);
    }

    private function dataKonsulRo()
    {
        $data = KunjunganWaktuSelesai::with('pasienRo', 'hasilBacaan', 'pasienRo.dokter.pegawai')
            ->where('konsul_ro', '1')
            ->get();

        foreach ($data as $item) {
            $hasil_bacaan        = $item->hasilBacaan;
            $item['hasilKonsul'] = isset($hasil_bacaan) ? "Sudah Selesai" : "Belum Selesai";

        }

        // Filter hanya data yang memiliki pasienRo
        $data = $data->filter(function ($item) {
            return $item->pasienRo !== null;
        })->values(); // reset index agar hasilnya rapi

        return $data;
    }

    public function getListBacaan()
    {
        $data = $this->dataKonsulRo();
        return $data;
    }
    public function getBacaan(Request $request)
    {
        $title  = "Hasil Bacaan RO";
        $norm   = $request->input('norm');
        $tgl    = $request->input('tgl');
        $listRO = ROTransaksiModel::with('pemeriksaan', 'hasilBacaan', 'pasien')->where('tgltrans', $tgl)->orderBy('created_at', 'asc')->get();
        // caritahu $norm ada di aray berapa
        $nomorRad = array_search($norm, array_column($listRO->toArray(), 'norm')) + 1;
        // ambil data lisrro yang normnya sama
        $data = $listRO->filter(function ($item) use ($norm) {
            return $item->norm === $norm;
        })->first();

        // $hasilBacaan = $data->hasil_bacaan->bacaan_radiolog;
        // return $hasilBacaan;
        // return $data;
        return view('RO.Bacaan.cetak', compact('title', 'data', 'nomorRad'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'norm'            => 'required|string|max:10',
            'notrans'         => 'required|string|max:20',
            'keterangan'      => 'nullable|string',
            'bacaan_radiolog' => 'required|string',
            'tanggal'         => 'required|date',
            'tanggal_ro'      => 'required|date',
        ]);
        // dd($validator);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }
        $data = ROBacaan::create($request->all());

        $data = $this->dataKonsulRo();

        return response()->json([
            'status'  => true,
            'message' => 'Data berhasil disimpan',
            'data'    => $data,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(ROBacaan $rOBacaan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ROBacaan $rOBacaan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ROBacaan $rOBacaan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ROBacaan $rOBacaan)
    {
        //
    }
}
