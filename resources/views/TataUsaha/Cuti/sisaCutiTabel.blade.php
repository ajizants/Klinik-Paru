<table id="tabelDaftarSisaCuti" class="table table-bordered table-striped">
    <thead class="bg-warning">
        <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Jatah Cuti</th>
            <th>Tambahan Cuti</th>
            <th>Permohonan Cuti</th>
            <th>Cuti Disetujui</th>
            <th>Cuti Ditolak</th>
            <th>Sisa Cuti</th>
        </tr>
    </thead>
    <tbody>
        @forelse($dataSisaCutiAll as $index => $cuti)
            <tr>
                <td> {{ $index + 1 }} </td>
                <td>{{ $cuti->nama ?? '-' }}<br>Nip. {{ $cuti->nip }}</td>
                <td>{{ $cuti->jatah_cuti }} hari</td>
                <td>{{ $cuti->jumlahCutiTambahan }} hari</td>
                <td>{{ $cuti->jumlahCutiDiambil }} hari</td>
                <td>{{ $cuti->jumlahCutiDisetujui }} hari</td>
                <td>{{ $cuti->jumlahCutiDitolak }} hari</td>
                <td>{{ $cuti->jumlahSisaCuti }} hari</td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada data.</td>
            </tr>
        @endforelse
    </tbody>
</table>
