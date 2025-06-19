<?php
namespace App\Http\Controllers;

use App\Models\PegawaiModel;
use App\Models\PromkesModel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PromkesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Promkes';

        $nip = [4, 5, 9999];
        $data = PegawaiModel::with(['biodata', 'jabatan'])
            ->whereNotIn('nip', $nip)->get();

        $pegawai = [];
        foreach ($data as $peg) {
            $pegawai[] = array_map('strval', [
                "nip" => $peg["nip"] ?? null,
                "status" => $peg["stat_pns"] ?? null,
                "gelar_d" => $peg["gelar_d"] ?? null,
                "gelar_b" => $peg["gelar_b"] ?? null,
                "kd_jab" => $peg["kd_jab"] ?? null,
                "kd_pend" => $peg["kd_pend"] ?? null,
                "kd_jurusan" => $peg["kd_jurusan"] ?? null,
                "tgl_masuk" => $peg["tgl_masuk"] ?? null,
                "nama" => $peg["biodata"]["nama"] ?? null,
                "jeniskel" => $peg["biodata"]["jeniskel"] ?? null,
                "tempat_lahir" => $peg["biodata"]["tempat_lahir"] ?? null,
                "tgl_lahir" => $peg["biodata"]["tgl_lahir"] ?? null,
                "alamat" => $peg["biodata"]["alamat"] ?? null,
                "kd_prov" => $peg["biodata"]["kd_prov"] ?? null,
                "kd_kab" => $peg["biodata"]["kd_kab"] ?? null,
                "kd_kec" => $peg["biodata"]["kd_kec"] ?? null,
                "kd_kel" => $peg["biodata"]["kd_kel"] ?? null,
                "kdAgama" => $peg["biodata"]["kdAgama"] ?? null,
                "status_kawin" => $peg["biodata"]["status_kawin"] ?? null,
                "nm_jabatan" => $peg["jabatan"]["nm_jabatan"] ?? null,
            ]);
        }
        $request = new Request();

        $hasilKegiatan = $this->getData($request);
        return view('Promkes.main', compact('hasilKegiatan', 'pegawai'))->with('title', $title);
    }

    /**
     * Show the form for creating a new resource.
     */
    // method untuk ambil data mentah
    public function getData(Request $request)
    {
        $tglAwal = $request->input('tglAwal') ?? Carbon::now()->format('Y-m-d');
        $tglAkhir = $request->input('tglAkhir') ?? Carbon::now()->format('Y-m-d');
        // Tambahkan waktu ke filter
        $tglAwal = Carbon::parse($tglAwal)->startOfDay(); // 00:00:00
        $tglAkhir = Carbon::parse($tglAkhir)->endOfDay(); // 23:59:59

        $data = PromkesModel::whereBetween('created_at', [$tglAwal, $tglAkhir])
            ->with('petugas.biodata')
            ->get();

        foreach ($data as $d) {
            $d->aksi = '
           <button class="btn btn-danger btn-sm"
           data-id="' . $d->id . '"
           data-pasien="' . $d->pasien . '"
           data-created_at="' . $d->created_at . '"
           onclick="deletePromkes(' . $d->id . ')"><i class="fa fa-trash"></i></button>
           <button class="btn btn-info btn-sm"
           data-id="' . $d->id . '"
           data-pegawai="' . $d->pegawai . '"
           data-pasien="' . $d->pasien . '"
           data-noHp="' . $d->noHp . '"
           data-td="' . $d->td . '"
           data-nadi="' . $d->nadi . '"
           data-konsultasi="' . $d->konsultasi . '"
           onclick="editPromkes(this)"><i class="fa fa-edit"></i></button>
        ';
            $d->tanggal = Carbon::parse($d->created_at)->format('d-m-Y');
            $d->hasilPromkes = '
            <p>TD : ' . $d->td . ' mmHg</p>
            <p>Nadi : ' . $d->nadi . ' X/mnt</p>
            <p>Konsultasi : ' . $d->konsultasi . '</p>';
        }
        // return [];
        return $data;
    }

    public function data(Request $request)
    {
        $data = $this->getData($request);

        if (!$data || count($data) == 0 || empty($data)) {
            $res = [
                'status' => 'error',
                'message' => 'Data tidak ditemukan',
            ];
            return response()->json($res, 404, [], JSON_PRETTY_PRINT);
        }
        $res = [
            'status' => 'success',
            'message' => 'Data berhasil diambil',
            'data' => $data,
        ];
        return response()->json($res, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $savedData = PromkesModel::create($data);

            $promkes = $this->getData($request);

            $res = [
                'status' => 'success',
                'message' => 'Data berhasil disimpan',
                'table' => $promkes,
                'data' => $savedData,
            ];
            return response()->json($res, 200, [], JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            $res = [
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data',
                'error' => $e->getMessage(),
            ];
            return response()->json($res, 500, [], JSON_PRETTY_PRINT);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PromkesModel $promkesModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PromkesModel $promkesModel)
    {
        try {
            $data = $request->all();
            $promkesModel->update($data);

            $promkes = $this->getData($request);

            $res = [
                'status' => 'success',
                'message' => 'Data berhasil diperbarui',
                'data' => $promkesModel,
                'table' => $promkes,
            ];
            return response()->json($res, 200, [], JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            $res = [
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui data',
                'error' => $e->getMessage(),
            ];
            return response()->json($res, 500, [], JSON_PRETTY_PRINT);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $dataDelete = PromkesModel::find($id);
            $dataDelete->delete();
            $request = new Request();
            $promkes = $this->getData($request);
            $res = [
                'status' => 'success',
                'message' => 'Data berhasil dihapus',
                'table' => $promkes,
                'delete' => $dataDelete->pasien . ' - ' . $dataDelete->created_at,
            ];
            return response()->json($res, 200, [], JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            $res = [
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus data',
                'error' => $e->getMessage(),
            ];
            return response()->json($res, 500, [], JSON_PRETTY_PRINT);
        }
    }
}
