<?php
namespace App\Imports;

use App\Models\HariLibur;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class HariLiburImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            if ($index === 0) {
                continue; // skip header
            }

            $tanggal    = $row[0];
            $keterangan = trim($row[1]);

            if ($tanggal && $keterangan) {
                try {
                    $tanggalCarbon = \Carbon\Carbon::instance(Date::excelToDateTimeObject($tanggal));
                } catch (\Exception $e) {
                    continue; // skip baris invalid
                }

                HariLibur::create([
                    'tanggal'    => $tanggalCarbon->format('Y-m-d'),
                    'keterangan' => $keterangan,
                ]);
            }
        }
    }
}
