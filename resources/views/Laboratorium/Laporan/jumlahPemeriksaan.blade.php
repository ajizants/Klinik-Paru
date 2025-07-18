<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="card-title mb-0">Jumlah Pemeriksaan Laboratorium</h5>
        <small>Periode: {{ $tglAwal }} - {{ $tglAkhir }}</small>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-bordered table-hover table-sm m-0" id="tabelJmlPemeriksaanNew">
            <thead class="text-center bg-light">
                <tr>
                    <th style="min-width: 100px">Tanggal</th>
                    @foreach ($layananList as $layanan)
                        <th>{{ $layanan }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($tanggalList as $tanggal)
                    <tr>
                        <td>{{ $tanggal }}</td>
                        @foreach ($layananList as $layanan)
                            <td class="text-center">
                                {{ $jumlahData[$tanggal][$layanan] ?? 0 }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach
                {{-- Baris total --}}
                <tr class="bg-secondary text-white font-weight-bold">
                    <td class="text-center">Total</td>
                    @foreach ($layananList as $layanan)
                        <td class="text-center">
                            {{ collect($tanggalList)->reduce(function ($carry, $tgl) use ($layanan, $jumlahData) {
                                return $carry + ($jumlahData[$tgl][$layanan] ?? 0);
                            }, 0) }}
                        </td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>
</div>
