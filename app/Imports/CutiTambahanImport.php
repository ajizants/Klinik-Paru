<?php
namespace App\Imports;

use App\Models\CutiTambahan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class CutiTambahanImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            if ($index === 0) {
                continue;
            }
            // skip header

            $nip    = trim($row[1]); // kolom B = NIP
            $jumlah = trim($row[2]); // kolom C = Tambahan Cuti

            if ($nip && is_numeric($jumlah)) {
                CutiTambahan::create([
                    'nip'             => $nip,
                    'jumlah_tambahan' => $jumlah,
                ]);
            }
        }
    }
}
