@extends('Template.lte')

@section('content')
    <div class="card shadow">
        <div class="card-body">
            {{-- <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a type="button" class="nav-link border border-primary active bg-blue" onclick=" toggleSections('#tab_1')"
                        id="link_tab_1"><b>Kepegawaian</b></a>
                </li>
                <li class="nav-item">
                    <a type="button" class="nav-link border border-primary" onclick=" toggleSections('#tab_2')"><b>Kegiatan
                            Lain</b></a>
                </li>
            </ul> --}}
            @include('Promkes.kegiatan')
        </div>
    </div>



    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script>
        window.addEventListener("load", function() {
            setTodayDate();
            var today = new Date().toISOString().split("T")[0];
            $("#tanggal").val(today);


            // Inisialisasi tglAwal dan tglAkhir sebagai objek Moment.js
            // tglAwal = moment().subtract(30, "days").format("YYYY-MM-DD");
            tglAwal = moment().subtract(0, "days").format("YYYY-MM-DD");
            tglAkhir = moment().subtract(0, "days").format("YYYY-MM-DD");
            console.log("🚀 ~ tglAwal:", tglAwal)
            console.log("🚀 ~ tglAkhir:", tglAkhir)
            // Menetapkan nilai ke input tanggal
            $("#tglEkin").val(tglAwal + " to " + tglAkhir);

            // Date range picker
            $("#tglEkin").daterangepicker({
                startDate: tglAwal,
                endDate: tglAkhir,
                autoApply: true,
                locale: {
                    format: "YYYY-MM-DD",
                    separator: " to ",
                    applyLabel: "Apply",
                    cancelLabel: "Cancel",
                    customRangeLabel: "Custom Range",
                },
            });

            $("#tglEkin").on("apply.daterangepicker", function(ev, picker) {
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
