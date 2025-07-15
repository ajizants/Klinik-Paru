{{-- <pre>{{ dd(Auth::user()->role) }}</pre> --}}


<table id="tabelDaftarPermohonanCuti" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>#</th>
            {{-- <th>NIP</th> --}}
            <th>Status</th>
            <th>Identitas</th>
            <th>Mulai</th>
            <th>Selesai</th>
            <th>Alasan</th>
            <th>Keterangan</th>
            <th>Tgl Pengajuan</th>
        </tr>
    </thead>
    <tbody>
        @php
            $email = Auth::user()->email;
            $email = explode('@', $email);
            $nip = $email[0];

        @endphp
        @forelse($dataCuti as $index => $cuti)
            <tr>
                <td>
                    @if (in_array(Auth::user()->role, ['admin', 'tu']))
                        @if ($cuti->persetujuan == 0)
                            <button class="mt-2 btn btn-danger btn-sm"
                                onclick="hapusPermohonanCuti({{ $cuti->id }})">Hapus</button>
                            <button class="mt-2 btn btn-warning btn-sm"
                                onclick="editPermohonanCuti({{ $cuti->id }})">Edit</button>
                            <button class="mt-2 btn bg-orange btn-sm"
                                onclick="persetujuanCuti({{ $cuti->id }}, 2)">Tolak</button>
                            <button class="mt-2 btn btn-success btn-sm"
                                onclick="persetujuanCuti({{ $cuti->id }}, 1)">Setujui</button>
                        @else
                            <button class="mt-2 btn btn-danger btn-sm"
                                onclick="hapusPermohonanCuti({{ $cuti->id }})">Hapus</button>
                            <button class="mt-2 btn btn-warning btn-sm"
                                onclick="editPermohonanCuti({{ $cuti->id }})">Edit</button>
                            <button class="mt-2 btn bg-orange btn-sm"
                                onclick="persetujuanCuti({{ $cuti->id }}, 2)">Tolak</button>
                            <a class="mt-2 btn btn-primary btn-sm" href="/tu/cuti/cetak/{{ $cuti->id }}"
                                target="_blank">Cetak</a>
                        @endif
                    @else
                        @if ($nip == $cuti->nip && $cuti->persetujuan == 0)
                            <button class="mt-2 btn btn-warning btn-sm"
                                onclick="editPermohonanCuti({{ $cuti->id }})">Edit</button>
                            <button class="mt-2 btn btn-danger btn-sm"
                                onclick="hapusPermohonanCuti({{ $cuti->id }})">Hapus</button>
                        @else
                            {{ $index + 1 }}
                        @endif
                    @endif
                </td>
                <td>
                    @if ($cuti->persetujuan == 0)
                        <span class="badge badge-warning">Menunggu</span>
                    @elseif($cuti->persetujuan == 1)
                        <span class="badge badge-success">Disetujui</span>
                    @elseif($cuti->persetujuan == 2)
                        <span class="badge badge-danger">Ditolak</span>
                    @endif
                </td>
                {{-- <td>{{ $cuti->nip }}</td> --}}
                <td>{{ $cuti->pegawai->nama ?? '-' }}<br>Nip. {{ $cuti->nip }}</td>
                <td>{{ $cuti->tgl_mulai }}</td>
                <td>{{ $cuti->tgl_selesai }}</td>
                <td>{{ $cuti->alasan }}</td>
                <td>{{ $cuti->keterangan }}</td>

                <td>{{ $cuti->created_at->format('d-m-Y') }}</td>
            </tr>
        @empty

        @endforelse
    </tbody>
</table>
