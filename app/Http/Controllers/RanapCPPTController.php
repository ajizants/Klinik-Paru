<?php
namespace App\Http\Controllers;

use App\Models\BMHPModel;
use App\Models\DiagnosaModel;
use App\Models\GiziDxSubKelasModel;
use App\Models\LayananModel;
use App\Models\PegawaiModel;
use App\Models\RanapCPPT;
use App\Models\RanapOrder;
use App\Models\RanapPendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RanapCPPTController extends Controller
{

    public function index($module)
    {
        $title = 'Ranap CPPT ' . strtoupper($module);
        $email = auth()->user()->email;
        $role  = auth()->user()->role;
        if (strpos($email, '@') !== false) {
            $username = explode('@', $email)[0];
        } else {
            $username = $email; // fallback
        }

        $pegawaiModel = new PegawaiModel();
        $dokter       = $pegawaiModel->olahPegawai([1, 7, 8]);
        $dokter       = array_map(function ($item) {
            return (object) $item;
        }, $dokter);
        $petugas = $pegawaiModel->olahPegawai([1, 7, 8, 9, 10, 12, 14, 15, 23]);
        $petugas = array_map(function ($item) {
            return (object) $item;
        }, $petugas);

        $sub                   = GiziDxSubKelasModel::with('domain')->get();
        $dxMed                 = DiagnosaModel::get();
        $modelRanapPendaftaran = new RanapPendaftaran();

        $lModel   = new LayananModel();
        $tindakan = $lModel->layanans([2, 3, 5, 6]);
        $bmhp     = BMHPModel::all();
        if ($role == 'dpjp') {
            $dataPasien = $modelRanapPendaftaran->getPasienRanap($username);

        } else {
            $dataPasien = $modelRanapPendaftaran->getPasienRanap();
        }
        $compact = compact('title', 'dokter', 'petugas', 'sub', 'dxMed', 'dataPasien', 'tindakan', 'bmhp', 'role');

        switch ($module) {
            case 'dokter':
                return view('Ranap.Cppt.main', $compact);
                break;
            case 'perawat':
                return view('Ranap.Cppt.Perawat.main', $compact);
                break;
            case 'gizi':
                return view('Ranap.Cppt.main', $compact);
                break;
            case 'terapis':
                return view('Ranap.Cppt.main', $compact);
                break;
            default:
                return view('Ranap.Cppt.main', $compact);
                break;
        }

    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'norm'        => 'required|string|max:10',
                'notrans'     => 'required|string',
                'form_id'     => 'required|string',
                'td'          => 'nullable|string',
                'nadi'        => 'nullable|string',
                'suhu'        => 'nullable|string',
                'rr'          => 'nullable|string',
                'bb'          => 'nullable|string',
                'tb'          => 'nullable|string',
                'bbi'         => 'nullable|string',
                'lla'         => 'nullable|string',
                'imt'         => 'nullable|string',
                'status_gizi' => 'nullable|string',
                'objektif'    => 'nullable|string',
                'subjektif'   => 'nullable|string',
                'assesment'   => 'nullable|string',
                'dx1'         => 'nullable|string',
                'ket_dx1'     => 'nullable|string',
                'dx2'         => 'nullable|string',
                'ket_dx2'     => 'nullable|string',
                'dx3'         => 'nullable|string',
                'ket_dx3'     => 'nullable|string',
                'dx4'         => 'nullable|string',
                'ket_dx4'     => 'nullable|string',
                'petugas'     => 'required|string',
            ]);

            // Simpan CPPT utama
            $entry = RanapCPPT::create([
                'norm'        => $validated['norm'],
                'notrans'     => $validated['notrans'],
                'form_id'     => $validated['form_id'],
                'td'          => $validated['td'],
                'nadi'        => $validated['nadi'],
                'suhu'        => $validated['suhu'],
                'rr'          => $validated['rr'],
                'bb'          => $validated['bb'],
                'tb'          => $validated['tb'],
                'bbi'         => $validated['bbi'],
                'lla'         => $validated['lla'],
                'imt'         => $validated['imt'],
                'status_gizi' => $validated['status_gizi'],
                'objektif'    => $validated['objektif'],
                'subjektif'   => $validated['subjektif'],
                'assesment'   => $validated['assesment'],
                'dx1'         => $validated['dx1'],
                'ket_dx1'     => $validated['ket_dx1'],
                'dx2'         => $validated['dx2'],
                'ket_dx2'     => $validated['ket_dx2'],
                'dx3'         => $validated['dx3'],
                'ket_dx3'     => $validated['ket_dx3'],
                'dx4'         => $validated['dx4'],
                'ket_dx4'     => $validated['ket_dx4'],
                'petugas'     => $validated['petugas'],
            ]);

            $notrans = $validated['notrans'];

            return response()->json(['success' => true, 'message' => 'Data berhasil disimpan', 'notrans' => $notrans], 200);
        } catch (\Exception $e) {
            Log::error('CPPT Store Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menyimpan data.' . $e->getMessage()], 500);
        }
    }

    // public function order_tindakan(Request $request)
    // {
    //     try {
    //         $validator = Validator::make($request->all(), [
    //             'norm'        => 'required|string|max:10',
    //             'notrans'     => 'required|string',
    //             'form_id'     => 'required|string',
    //             'tindakan_id' => 'required|array|min:1',
    //             'obat_id'     => 'required|array|min:1',
    //             'signa_1'     => 'required|array|min:1',
    //             'signa_2'     => 'required|array|min:1',
    //             'keterangan'  => 'nullable|array',
    //         ]);

    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'message' => 'Validasi gagal',
    //                 'errors'  => $validator->errors(),
    //             ], 422);
    //         }

    //         $norm       = $request->input('norm');
    //         $notrans    = $request->input('notrans');
    //         $form_id    = $request->input('form_id');
    //         $order      = $request->input('tindakan_id');
    //         $obatIds    = $request->input('obat_id');
    //         $signa1     = $request->input('signa_1');
    //         $signa2     = $request->input('signa_2');
    //         $keterangan = $request->input('keterangan');

    //         foreach ($obatIds as $i => $obatId) {
    //             // dd($obatId);
    //             if (! $obatId) {
    //                 continue;
    //             }

    //             RanapOrder::create([
    //                 'norm'    => $norm,
    //                 'notrans' => $notrans,
    //                 'form_id' => $form_id,
    //                 'order'   => $order[0] ?? null, // hanya satu tindakan diambil
    //                 'obat_id' => $obatId,
    //                 'signa_1' => $signa1[$i] ?? null,
    //                 'signa_2' => $signa2[$i] ?? null,
    //                 'ket'     => $keterangan[$i] ?? null,
    //             ]);
    //         }

    //         return response()->json(['message' => 'Tindakan & obat berhasil disimpan'], 200);

    //     } catch (\Throwable $th) {
    //         return response()->json([
    //             'message' => 'Gagal menyimpan',
    //             'error'   => $th->getMessage(),
    //         ], 500);
    //     }
    // }

    public function order_tindakan(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'norm'        => 'required|string|max:10',
                'notrans'     => 'required|string',
                'form_id'     => 'required|string',
                'tindakan_id' => 'required|array|min:1',
                'obat_id'     => 'required|array|min:1',
                'signa_1'     => 'required|array|min:1',
                'signa_2'     => 'required|array|min:1',
                'keterangan'  => 'nullable|array',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validasi gagal',
                    'errors'  => $validator->errors(),
                ], 422);
            }

            // DEBUG: log seluruh data yang diterima
            Log::info('Data diterima untuk order_tindakan:', $request->all());

            $norm       = $request->input('norm');
            $notrans    = $request->input('notrans');
            $form_id    = $request->input('form_id');
            $order      = $request->input('tindakan_id');
            $obatIds    = $request->input('obat_id');
            $signa1     = $request->input('signa_1');
            $signa2     = $request->input('signa_2');
            $keterangan = $request->input('keterangan');

            foreach ($obatIds as $i => $obatId) {
                if (! $obatId) {
                    continue;
                }

                $rowData = [
                    'norm'    => $norm,
                    'notrans' => $notrans,
                    'form_id' => $form_id,
                    'order'   => $order[0] ?? null,
                    'obat_id' => $obatId,
                    'signa_1' => $signa1[$i] ?? null,
                    'signa_2' => $signa2[$i] ?? null,
                    'ket'     => $keterangan[$i] ?? null,
                ];

                Log::info('Data akan disimpan:', $rowData); // DEBUG

                RanapOrder::create($rowData);
            }

            return response()->json(['message' => 'Tindakan & obat berhasil disimpan'], 200);

        } catch (\Throwable $th) {
            Log::error('Gagal simpan order_tindakan: ' . $th->getMessage());
            return response()->json([
                'message' => 'Gagal menyimpan',
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    private function getByNotrans($notrans)
    {
        $data = RanapCPPT::with([
            'pasien.identitas', 'pasien.dokter',
            'diagnosa1', 'diagnosa2', 'diagnosa3', 'diagnosa4', 'nakes',
        ])->where('notrans', $notrans)->orderBy('created_at', 'desc')->get();

        $html = '';

        foreach ($data as $item) {
            $tanggal = \Carbon\Carbon::parse($item->created_at)->format('d/m/Y');
            $petugas = $item->nakes->nama ?? '-';

            $hasilAssessment = '';
            $hasilAssessment .= $item->objektif ? "<strong>O:</strong> {$item->objektif}<br>" : '';
            $hasilAssessment .= $item->subjektif ? "<strong>S:</strong> {$item->subjektif}<br>" : '';
            $hasilAssessment .= $item->assesment ? "<strong>A:</strong> {$item->assesment}<br>" : '';

            $instruksi = '';
            $instruksi .= $item->dx1 ? "<strong>DX1:</strong> {$item->dx1} - {$item->ket_dx1}<br>" : '';
            $instruksi .= $item->dx2 ? "<strong>DX2:</strong> {$item->dx2} - {$item->ket_dx2}<br>" : '';
            $instruksi .= $item->dx3 ? "<strong>DX3:</strong> {$item->dx3} - {$item->ket_dx3}<br>" : '';
            $instruksi .= $item->dx4 ? "<strong>DX4:</strong> {$item->dx4} - {$item->ket_dx4}<br>" : '';

            $html .= '<tr>
            <td>
                <button class="btn btn-sm btn-danger" onclick="deleteCPPT(\'' . $item->id . '\')"><i class="fa fa-trash"></i></button>
                <button class="btn btn-sm btn-primary" onclick="editCPPT(\'' . $item->id . '\')"><i class="fa fa-edit"></i></button>
            </td>
            <td>' . $tanggal . '</td>
            <td>' . $petugas . '<br>DPJP</td>
            <td>' . $hasilAssessment . '</td>
            <td>' . $instruksi . '</td>
            <td>' . $petugas . '</td>
        </tr>';
        }

        return $html;
    }

    public function show($notrans)
    {
        // return response()->json($this->getByNotrans($notrans));
        $data = RanapCPPT::with('nakes', 'pasien.dokter', 'diagnosa1', 'diagnosa2', 'diagnosa3', 'diagnosa4')->where('notrans', $notrans)->orderBy('created_at', 'desc')->get();

        $result = $data->map(function ($item) {
            $orders = RanapOrder::with('detail')->where('form_id', $item->form_id)->get();
            // dd($orders);
            $instruksiList = collect();
            foreach ($orders as $order) {
                $sortedDetails = $order->detail->sortBy('kelas'); // urutkan berdasarkan kelas
                foreach ($sortedDetails as $detail) {
                    $baris = $detail->nmLayanan;
                    if ($order->ket) {
                        $baris .= " - " . $order->ket;
                    }
                    $instruksiList->push($baris);
                }
            }

            return [
                'id'               => $item->id,
                'created_at'       => $item->created_at->format('d/m/Y'),
                'petugas'          => trim(collect([
                    optional($item->nakes)->gelar_d,
                    optional($item->nakes)->nama,
                    optional($item->nakes)->gelar_b,
                ])->filter()->implode(' ')) . '<br>' . (optional($item->nakes)->nm_jabatan ?? '-'),
                'dpjp'             => trim(collect([
                    optional(optional($item->pasien)->dokter)->gelar_d,
                    optional(optional($item->pasien)->dokter)->nama,
                    optional(optional($item->pasien)->dokter)->gelar_b,
                ])->filter()->implode(' ')) ?: '-',
                'hasil_assessment' => collect([
                    $item->subjektif ? "<strong>S:</strong> $item->subjektif" : null,
                    $item->objektif ? "<strong>O:</strong> $item->objektif" : null,
                    $item->assesment ? "<strong>A:</strong> $item->assesment" : null,
                    $item->dx1 ? "<strong>DX1:</strong> $item->dx1 - $item->ket_dx1" : null,
                    $item->dx2 ? "<strong>DX2:</strong> $item->dx2 - $item->ket_dx2" : null,
                    $item->dx3 ? "<strong>DX3:</strong> $item->dx3 - $item->ket_dx3" : null,
                    $item->dx4 ? "<strong>DX4:</strong> $item->dx4 - $item->ket_dx4" : null,
                    $item->assesment ? "<strong>P:</strong> $item->assesment" : null,
                    $item->dx1 ? "<strong>DX1:</strong> $item->dx1 - $item->ket_dx1" : null,
                    $item->dx2 ? "<strong>DX2:</strong> $item->dx2 - $item->ket_dx2" : null,
                    $item->dx3 ? "<strong>DX3:</strong> $item->dx3 - $item->ket_dx3" : null,
                    $item->dx4 ? "<strong>DX4:</strong> $item->dx4 - $item->ket_dx4" : null,
                ])->filter()->implode('<br>'),
                'instruksi'        => $instruksiList->filter()->implode('<br>'),
            ];
        });

        return response()->json($result);
    }

    public function getFormId()
    {
        $date       = date('Y-m-d');
        $jumlahCppt = RanapCPPT::where('created_at', 'like', $date . '%')->count();
        $formId     = 'CPPT' . date('dmy') . sprintf('%04d', $jumlahCppt + 1);
        return response()->json(['success' => true, 'message' => 'Data berhasil disimpan', 'id' => $formId], 200);
    }

    // public function show($notrans)
    // {
    //     $data = RanapCPPT::with([
    //         'nakes', 'diagnosa1', 'diagnosa2', 'diagnosa3', 'diagnosa4', 'order.detail',
    //     ])
    //         ->where('notrans', $notrans)
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     $result = $data->map(function ($item) {
    //         $petugas = $item->nakes->nama ?? '-';

    //         $assessment = collect([
    //             $item->subjektif ? "<strong>S:</strong> {$item->subjektif}" : null,
    //             $item->objektif ? "<strong>O:</strong> {$item->objektif}" : null,
    //             $item->assesment ? "<strong>A:</strong> {$item->assesment}" : null,
    //         ])->filter()->implode('<br>');

    //         $instruksi = collect([
    //             $item->dx1 ? "<strong>DX1:</strong> {$item->dx1} - {$item->ket_dx1}" : null,
    //             $item->dx2 ? "<strong>DX2:</strong> {$item->dx2} - {$item->ket_dx2}" : null,
    //             $item->dx3 ? "<strong>DX3:</strong> {$item->dx3} - {$item->ket_dx3}" : null,
    //             $item->dx4 ? "<strong>DX4:</strong> {$item->dx4} - {$item->ket_dx4}" : null,
    //         ])->filter()->implode('<br>');

    //         return [
    //             'id'               => $item->id,
    //             'created_at'       => $item->created_at->format('d/m/Y'),
    //             'petugas'          => $petugas,
    //             'hasil_assessment' => $assessment,
    //             'instruksi'        => $instruksi,
    //         ];
    //     });

    //     return response()->json($result); // dataSrc: "" di JS
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RanapCPPT $ranapCPPT)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RanapCPPT $ranapCPPT)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RanapCPPT $ranapCPPT)
    {
        //
    }

    public function simpan(Request $request)
    {
        $obat_ids   = $request->input('obat_id');
        $signa1     = $request->input('signa_1');
        $signa2     = $request->input('signa_2');
        $keterangan = $request->input('keterangan');

        foreach ($obat_ids as $index => $obat_id) {
            // ObatTindakan::create([
            //     'obat_id'    => $obat_id,
            //     'signa_1'    => $signa1[$index],
            //     'signa_2'    => $signa2[$index],
            //     'keterangan' => $keterangan[$index],
            // ]);
        }

        return back()->with('success', 'Data tindakan berhasil disimpan.');
    }

}
