<div class="table-responsive">
    <table id="tabelHasilSpiro" class="table table-striped table-hover pt-0 mt-0 fs-6" style="width:100%" cellspacing="0">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">Tanggal</th>
                <th class="text-center">Hasil</th>
                <th class="text-center">Petugas</th>
                <th class="text-center">Dokter</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dataSpirometri as $item)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($item->created_at)->locale('id')->isoFormat('DD MMMM YYYY') }}</td>
                    <td class="text-center">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover pt-0 mt-0 fs-6" style="width:100%"
                                cellspacing="0">
                                <thead>
                                    <tr style="background-color: darkgray;">
                                        <th class="text-center">Param</th>
                                        <th class="text-center">Pred</th>
                                        <th class="text-center">Value</th>
                                        <th class="text-center">% Pred 1</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th class="text-center" style="background-color: darkgray;">FVC(L)</th>
                                        <td class="text-center">{{ $item->pred_fvc }}</td>
                                        <td class="text-center">{{ $item->value_fvc }}</td>
                                        <td class="text-center">{{ $item->pred_fvc_2 }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-center" style="background-color: darkgray;">FEV1(L)</th>
                                        <td class="text-center">{{ $item->pred_fvc }}</td>
                                        <td class="text-center">{{ $item->value_fvc }}</td>
                                        <td class="text-center">{{ $item->pred_fvc_2 }}</td>

                                    </tr>
                                    <tr>
                                        <th class="text-center" style="background-color: darkgray;">FEV1/FVC(%)</th>
                                        <td class="text-center">{{ $item->pred_fvc }}</td>
                                        <td class="text-center">{{ $item->value_fvc }}</td>
                                        <td class="text-center">{{ $item->pred_fvc_2 }}</td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>


                    </td>
                    <td class="text-center">{{ $item->biodataPetugas->nama }}</td>
                    <td class="text-center">{{ $item->biodataDokter->nama }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
