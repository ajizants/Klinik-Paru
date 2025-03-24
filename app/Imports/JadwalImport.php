<?php
namespace App\Imports;

use App\Models\JadwalModel;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class JadwalImport implements ToCollection
{
    protected $tahunBulan;
    protected $jabatan;

    public function __construct($tahunBulan, $jabatan)
    {
        $this->tahunBulan = $tahunBulan;
        $this->jabatan    = $jabatan;
    }

    public function collection(Collection $rows)
    {
        $header = $rows->shift(); // Ambil header (baris pertama)
                                  // dd($this->tahunBulan);

        foreach ($rows as $row) {
            $nama = $row[1]; // Nama dokter di kolom pertama

            for ($i = 2; $i < count($row); $i++) {
                $tanggal = $header[$i];
                $shift   = $row[$i];

                if ($shift) {
                    JadwalModel::create([
                        'nama'    => $nama,
                        'tanggal' => Carbon::parse("{$this->tahunBulan}-$tanggal"),
                        'shift'   => $shift,
                        'jabatan' => $this->jabatan, // Simpan jabatan ke database
                    ]);
                }
            }
        }
    }
}
