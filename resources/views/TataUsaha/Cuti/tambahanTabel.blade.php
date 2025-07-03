<table id="tabelDaftarSisaCuti" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Jumlah Tambahan</th>
            <th>Tgl Dibuat</th>
            <th>Tgl Update</th>
        </tr>
    </thead>
    <tbody>
        @forelse($dataTambahanCuti as $index => $cuti)
            <tr>
                <td> {{ $index + 1 }} </td>
                <td>{{ $cuti->nama ?? '-' }}<br>Nip. {{ $cuti->nip }}</td>
                <td>{{ $cuti->jumlah_tambahan }} hari</td>
                <td>{{ $cuti->created_at->format('d-m-Y') }}</td>
                <td>{{ $cuti->updated_at->format('d-m-Y') }}</td>
            </tr>
        @empty
        @endforelse
    </tbody>
</table>



<script>
    function tambahCuti(nip) {
        tampilkanLoading('Sedang Menambah Cuti...');
        $.ajax({
            url: "/tu/cuti/tambah/" + nip,
            type: "GET",
            success: function(data) {
                generateTabelPermohonanCuti(data.html);
                Swal.close();
            }
        })
    }
</script>
