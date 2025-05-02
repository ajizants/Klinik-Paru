<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangAtkModel extends Model
{
    use HasFactory;
    protected $table = 'gudang_atk';
    protected $fillable = [
        'id',
        'idBarang',
        'NamaBarang',
        'jumlah',
        'keterangan',
    ];

    public function getStokAtk($params)
    {
        $dataAtk = $this->whereBetween('created_at', [$params['tglAwal'], $params['tglAkhir']])->get();
        $dataAtkKeluar = $this->getAtkKeluar($params);
        $table = $this->generateTableStokAtk($dataAtk, $dataAtkKeluar);
        return $table;
    }
    public function getAtkKeluar($params)
    {
        $modelAtkKeluar = new GudangAtkKeluarModel();
        $dataAtkKeluar = $modelAtkKeluar->whereBetween('created_at', [$params['tglAwal'], $params['tglAkhir']])->get();
        return $dataAtkKeluar;
    }
    public function getAtkMasuk($params)
    {
        $dataAtkMasuk = $this->whereBetween('created_at', [$params['tglAwal'], $params['tglAkhir']])->get();
        return $dataAtkMasuk;
    }

    private function generateTableStokAtk($dataAtk, $dataAtkKeluar)
    {
        // Hitung total masuk
        $masuk = [];
        foreach ($dataAtk as $item) {
            $id = $item->idBarang;
            if (!isset($masuk[$id])) {
                $masuk[$id] = [
                    'idBarang' => $id,
                    'NamaBarang' => $item->NamaBarang,
                    'masuk' => 0,
                ];
            }
            $masuk[$id]['masuk'] += $item->jumlah;
        }

        // Hitung total keluar
        $keluar = [];
        foreach ($dataAtkKeluar as $item) {
            $id = $item->idBarang;
            if (!isset($keluar[$id])) {
                $keluar[$id] = [
                    'idBarang' => $id,
                    'NamaBarang' => $item->NamaBarang,
                    'keluar' => 0,
                ];
            }
            $keluar[$id]['keluar'] += $item->jumlah;
        }

        // Gabungkan data
        $barangList = [];
        foreach ($masuk as $id => $item) {
            $barangList[$id] = [
                'idBarang' => $id,
                'NamaBarang' => $item['NamaBarang'],
                'masuk' => $item['masuk'],
                'keluar' => isset($keluar[$id]) ? $keluar[$id]['keluar'] : 0,
            ];
            $barangList[$id]['sisa'] = $barangList[$id]['masuk'] - $barangList[$id]['keluar'];
        }

        foreach ($keluar as $id => $item) {
            if (!isset($barangList[$id])) {
                $barangList[$id] = [
                    'idBarang' => $id,
                    'NamaBarang' => $item['NamaBarang'],
                    'masuk' => 0,
                    'keluar' => $item['keluar'],
                    'sisa' => 0 - $item['keluar'],
                ];
            }
        }

        // Tabel
        $html = '<table class="table table-bordered table-striped" id="tableStokAtk">';
        $html .= '<thead>
                    <tr>
                        <th>Aksi</th>
                        <th>ID Barang</th>
                        <th>Nama Barang</th>
                        <th>Masuk</th>
                        <th>Keluar</th>
                        <th>Sisa Stok</th>
                    </tr>
                  </thead><tbody>';

        foreach ($barangList as $item) {
            $html .= '<tr>
                        <td>
                            <button class="btn btn-sm btn-success" data-id="' . $item['idBarang'] . '" data-nama="' . $item['NamaBarang'] . '" onclick="tambahAtk(this,"Form Tambah ATK","Tambah")">Tambah</button>
                            <button class="btn btn-sm btn-danger" data-id="' . $item['idBarang'] . '" data-nama="' . $item['NamaBarang'] . '" onclick="keluarAtk(this,"Form Keluarkan ATK","Keluarkan")">Keluarkan</button>
                        </td>
                        <td>' . $item['idBarang'] . '</td>
                        <td>' . $item['NamaBarang'] . '</td>
                        <td>' . $item['masuk'] . '</td>
                        <td>' . $item['keluar'] . '</td>
                        <td>' . $item['sisa'] . '</td>
                      </tr>';
        }

        $html .= '</tbody></table>';

        // Modals akan ditampilkan di tempat terpisah dalam blade
        return $html;
    }

}
