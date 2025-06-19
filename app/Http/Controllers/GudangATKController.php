<?php
namespace App\Http\Controllers;

use App\Models\GudangAtkKeluarModel;
use App\Models\GudangAtkModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GudangATKController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title    = 'Gudang ATK';
        $model    = new GudangAtkModel();
        $tglAwal  = date('Y-m-01') . ' 00:00:00'; // Tanggal 1 awal bulan sekarang
        $tglAkhir = date('Y-m-t') . ' 23:59:59';  // Tanggal terakhir di bulan sekarang

        $params = [
            'tglAwal'  => $tglAwal,
            'tglAkhir' => $tglAkhir,
        ];
        $atkKeluar = $model->getAtkKeluar($params);
        $atkMasuk  = $model->getAtkMasuk($params);
        $listAtk   = GudangAtkModel::select('idBarang')->distinct()->get();

        $tableStok      = $model->getStokAtk($params);
        $tableAtkMasuk  = $this->generateTable($atkMasuk, 'tableAtkMasuk');
        $tableAtkKeluar = $this->generateTable($atkKeluar, 'tableAtkKeluar');
        return view('GudangATK.main', compact('tableStok', 'tableAtkMasuk', 'tableAtkKeluar', 'listAtk'))->with('title', $title);
    }
    private function generateTable($data, $tableId)
    {
        // Generate HTML tabel
        $ket  = $tableId == 'tableAtkMasuk' ? 'Masuk' : 'Keluar';
        $html = '<table class="table table-bordered" id="' . $tableId . '" style="width: 100%;">';
        $html .= '<thead>
                    <tr>
                        <th>Aksi</th>
                        <th>ID Barang</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                        <th>Tanggal Input</th>
                        <th>Tanggal Update</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($data as $item) {
            $html .= '<tr>';
            $html .= '<td>
                        <button class="btn btn-sm btn-warning"
                        data-id="' . $item['id'] . '"
                        data-idBarang="' . $item['idBarang'] . '"
                        data-namaBarang="' . $item['namaBarang'] . '"
                        data-jumlah="' . $item['jumlah'] . '"
                        data-keterangan="' . $item['keterangan'] . '"
                        onclick="editAtk(this,' . $ket . ', \'Edit ATK\', \'Update\')">Edit</button>

                        <button class="btn btn-sm btn-danger"
                        data-id="' . $item['id'] . '"
                        data-idBarang="' . $item['idBarang'] . '"
                        data-namaBarang="' . $item['namaBarang'] . '"
                        data-jumlah="' . $item['jumlah'] . '"
                        data-keterangan="' . $item['keterangan'] . '"
                        onclick="hapusAtk(this,' . $ket . ')">Hapus</button>
                     </td>';
            $html .= '<td>' . $item['idBarang'] . '</td>';
            $html .= '<td>' . $item['NamaBarang'] . '</td>';
            $html .= '<td>' . $item['jumlah'] . '</td>';
            $html .= '<td>' . $item['keterangan'] . '</td>';
            $html .= '<td>' . $item['created_at'] . '</td>';
            $html .= '<td>' . $item['updated_at'] . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';
        return $html;
    }

    public function addAtk(Request $request)
    {
        try {
            $model             = new GudangAtkModel();
            $model->idBarang   = $request->idBarang;
            $model->NamaBarang = $request->NamaBarang;
            $model->jumlah     = $request->jumlah;
            $model->keterangan = $request->keterangan;
            $model->save();

            return response()->json(['success' => true, 'message' => 'Data berhasil disimpan']);
        } catch (\Exception $e) {
            // Catat log error jika diperlukan
            Log::error('Gagal menambah ATK: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
    public function updateAddAtk(Request $request)
    {
        try {
            $data = GudangAtkModel::where('id', $request->id)->first();

            if (! $data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan untuk Barang: ' . $request->namaBarang . ' ID: ' . $request->idBarang,
                ], 404);
            }

            $data->idBarang   = $request->idBarang;
            $data->namaBarang = $request->namaBarang;
            $data->jumlah     = $request->jumlah;
            $data->keterangan = $request->keterangan;
            $data->save();

            return response()->json(['success' => true, 'message' => 'Data berhasil diperbarui']);
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui ATK keluar: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function hapusAddAtk(Request $request)
    {
        try {
            $data = GudangAtkModel::where('id', $request->id)->first();

            if (! $data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan untuk Barang: ' . $request->namaBarang . ' ID: ' . $request->idBarang,
                ], 404);
            }

            $data->delete();

            return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
        } catch (\Exception $e) {
            Log::error('Gagal menghapus ATK keluar: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function keluarAtk(Request $request)
    {
        try {
            $model             = new GudangAtkKeluarModel();
            $model->idBarang   = $request->idBarang;
            $model->NamaBarang = $request->NamaBarang;
            $model->jumlah     = $request->jumlah;
            $model->keterangan = $request->keterangan;
            $model->save();

            return response()->json(['success' => true, 'message' => 'Data berhasil disimpan']);
        } catch (\Exception $e) {
            // Catat log error jika diperlukan
            Log::error('Gagal mengeluarkan ATK: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
    public function updateKeluarAtk(Request $request)
    {
        try {
            $data = GudangAtkKeluarModel::where('id', $request->id)->first();

            if (! $data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan untuk Barang: ' . $request->namaBarang . ' ID: ' . $request->idBarang,
                ], 404);
            }

            $data->idBarang   = $request->idBarang;
            $data->namaBarang = $request->namaBarang;
            $data->jumlah     = $request->jumlah;
            $data->keterangan = $request->keterangan;
            $data->save();

            return response()->json(['success' => true, 'message' => 'Data berhasil diperbarui']);
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui ATK keluar: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function hapusKeluarAtk(Request $request)
    {
        try {
            $data = GudangAtkKeluarModel::where('id', $request->id)->first();

            if (! $data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan untuk Barang: ' . $request->namaBarang . ' ID: ' . $request->idBarang,
                ], 404);
            }

            $data->delete();

            return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui ATK keluar: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

}
