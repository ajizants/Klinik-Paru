{{-- <pre>{{ dd(Auth::user()->role) }}</pre> --}}

<table id="tabelDaftarPermohonanCuti" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>#</th>
            {{-- <th>NIP</th> --}}
            <th>Identitas</th>
            <th>Mulai</th>
            <th>Selesai</th>
            <th>Alasan</th>
            <th>Keterangan</th>
            <th>Status</th>
            <th>Tgl Pengajuan</th>
        </tr>
    </thead>
    <tbody>
        @forelse($dataCuti as $index => $cuti)
            <tr>
                <td>
                    @if (in_array(Auth::user()->role, ['admin', 'tu']))
                        <button class="btn btn-success btn-sm"
                            onclick="persetujuanCuti({{ $cuti->id }}, 1)">Setujui</button>
                        <button class="btn btn-danger btn-sm"
                            onclick="persetujuanCuti({{ $cuti->id }}, 2)">Tolak</button>
                    @else
                        {{ $index + 1 }}
                    @endif
                </td>
                {{-- <td>{{ $cuti->nip }}</td> --}}
                <td>{{ $cuti->pegawai->nama ?? '-' }}<br>Nip. {{ $cuti->nip }}</td>
                <td>{{ $cuti->tgl_mulai }}</td>
                <td>{{ $cuti->tgl_selesai }}</td>
                <td>{{ $cuti->alasan }}</td>
                <td>{{ $cuti->keterangan }}</td>
                <td>
                    @if ($cuti->persetujuan == 0)
                        <span class="badge badge-warning">Menunggu</span>
                    @elseif($cuti->persetujuan == 1)
                        <span class="badge badge-success">Disetujui</span>
                    @elseif($cuti->persetujuan == 2)
                        <span class="badge badge-danger">Ditolak</span>
                    @endif
                </td>
                <td>{{ $cuti->created_at->format('d-m-Y') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada data cuti untuk bulan ini.</td>
            </tr>
        @endforelse
    </tbody>
</table>


{{-- <div class="card shadow-sm border">
    <div class="card-body">
        <div class="form-row">
            <p class="mb-1 col"><strong>Nama:</strong> ${item.nama}</p>
            <p class="mb-1 col"><strong>NIP:</strong> ${item.nip}</p>
        </div>
        <div class="form-row">
            <p class="mb-1 col"><strong>Jatah Cuti:</strong> ${item.jatah_cuti}</p>
            <p class="mb-1 col"><strong>Tambahan Cuti:</strong> ${item.tambahan_cuti}</p>
        </div>
        <div class="form-row">
            <p class="mb-1 col"><strong>Diambil:</strong> ${item.jumalhCutiDiambil}</p>
            <p class="mb-0 col"><strong>Sisa Cuti:</strong> ${item.jumlahSisaCuti}</p>
        </div>
    </div>
</div> --}}
