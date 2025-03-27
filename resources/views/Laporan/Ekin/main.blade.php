@extends('Template.lte')

@section('content')
    <div class="card shadow">
        <div class="card-body">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a type="button" class="nav-link border border-primary active bg-blue" onclick=" toggleSections('#tab_1')"
                        id="link_tab_1"><b>Daftar Pegawai</b></a>
                </li>
                <li class="nav-item">
                    <a type="button" class="nav-link border border-primary" onclick=" toggleSections('#tab_2')"><b>Rekap
                            Kegiatan Bulanan</b></a>
                </li>
            </ul>
            @include('Laporan.Ekin.daftarPegawai')
            @include('Laporan.Ekin.rekapDX')
        </div>
    </div>




    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script>
        function cetak(nip, nama) {
            const url =
                `api/ekin/poin?tanggal_awal=${tglAwal}&tanggal_akhir=${tglAkhir}&nip=${nip}&nama=${encodeURIComponent(nama)}`;

            window.open(url, "_blank");
        }

        var tglAwal;
        var tglAkhir;

        window.addEventListener("load", function() {
            setTodayDate();
            var today = new Date().toISOString().split("T")[0];
            $("#tanggal").val(today);

            $('#pegawaiTable').DataTable({
                paging: true,
                order: [
                    [5, "dsc"],
                    [1, "asc"]
                ], // Mengurutkan berdasarkan tanggal
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    [5, 10, 25, 50, "All"]
                ],
                pageLength: 5,
                responsive: true,
                autoWidth: false,
                scrollX: true
            });

            // Inisialisasi tglAwal dan tglAkhir sebagai objek Moment.js
            // tglAwal = moment().subtract(30, "days").format("YYYY-MM-DD");
            tglAwal = moment().subtract(0, "days").format("YYYY-MM-DD");
            tglAkhir = moment().subtract(0, "days").format("YYYY-MM-DD");
            console.log("🚀 ~ tglAwal:", tglAwal)
            console.log("🚀 ~ tglAkhir:", tglAkhir)
            // Menetapkan nilai ke input tanggal
            $("#reservation, #reservation2").val(tglAwal + " to " + tglAkhir);

            // Date range picker
            $("#reservation, #reservation2").daterangepicker({
                startDate: tglAwal,
                endDate: tglAkhir,
                // autoApply: true,
                locale: {
                    format: "YYYY-MM-DD",
                    separator: " to ",
                    applyLabel: "Apply",
                    cancelLabel: "Cancel",
                    customRangeLabel: "Custom Range",
                },
            });

            $("#reservation").on("apply.daterangepicker", function(ev, picker) {
                tglAwal = picker.startDate.format("YYYY-MM-DD");
                console.log("🚀 ~ $ ~ tglAwal:", tglAwal)
                tglAkhir = picker.endDate.format("YYYY-MM-DD");
                console.log("🚀 ~ $ ~ tglAkhir:", tglAkhir)

                // Update nilai input dengan tanggal yang baru dipilih
                $(this).val(tglAwal + " to " + tglAkhir);
            });

        });
    </script>
@endsection
