<div id="antrianSpiro" class="card-body card-body-hidden p-2">
    <h5 class="mb-0 text-center"><b>List Pasien Spirometri</b></h5>
    <div class="table-responsive pt-2 px-2">
        <table id="tabelAntrianSpiro" class="table table-striped table-hover pt-0 mt-0 fs-6" style="width:100%"
            cellspacing="0">
            <thead class="bg bg-secondary">
                <tr>
                    <td>Aksi</td>
                    <td>Status</td>
                    <td>Tanggal</td>
                    <td>NoRM</td>
                    <td>Nama</td>
                    <td>Petugas</td>
                    <td>Dokter</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        @if ($item->hasilSpiro != null)
                            @php
                                $tgl = \Carbon\Carbon::parse($item->hasilSpiro->created_at)->format('Y-m-d');
                            @endphp
                            <td>
                                <button type="button" class="btn btn-warning" data-toggle="modal"
                                    data-target="#spirometriModal" {{-- onclick='setSpiroFormEdit(@json($item))' --}}
                                    onclick='setSpiroFormEdit(@json([
                                        'pasien' => $item->pasien,
                                        'hasilSpiro' => $item->hasilSpiro,
                                    ]), "{{ $tgl }}")'>edit</button>

                            </td>
                            <td>
                                <span class="badge badge-success">Selesai</span>
                            </td>
                        @else
                            <td>
                                <button type="button" class="btn btn-info" data-toggle="modal"
                                    data-target="#spirometriModal"
                                    onclick="setSpiroForm('{{ $item->norm }}','{{ $item->pasien->nama }}', '{{ $item->notrans }}', '{{ $item->petugas }}', '{{ $item->dokter }}','{{ \Carbon\Carbon::parse($item->created_at)->format('Y-m-d') }}')">Input</button>
                            </td>
                            <td>
                                <span class="badge badge-danger">Belum Selesai</span>
                            </td>
                        @endif
                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                        <td>{{ $item->norm }}</td>
                        <td>{{ $item->pasien->nama }}</td>
                        <td>{{ $item->pelaksana->biodata->nama }}</td>
                        <td>{{ $item->dok->biodata->nama }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
