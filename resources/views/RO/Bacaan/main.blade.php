{{-- @extends('layouts.layout') --}}
@extends('Template.lte')

@section('content')
    @include('RO.Bacaan.antrian')
    @include('RO.Bacaan.input')


    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('js/populate.js') }}"></script>
    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
        });

        let keterangan; // Define status globally
        var appUrlRo = @json($appUrlRo);

        $(function() {
            $('#bacaanRO').summernote({
                height: 200,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        })


        function formatWaktu(dateTimeString) {
            const [datePart, timePart] = dateTimeString.split(" ");
            const [year, month, day] = datePart.split("-");
            const formattedDate = `${day}-${month}-${year}`;
            return `${formattedDate} ${timePart}`;
        }

        async function cetak(norm, tgl) {
            const requestData = {
                norm: norm,
                tgl: tgl
            };
            try {
                const response = await fetch("/api/hasil/lab/cetak", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify(requestData),
                });

                if (!response.ok) {
                    if (response.status == 404) {
                        console.error("No data found");
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Terjadi kesalahan saat mengambil data pasien...!!!",
                        });
                        throw new Error("Network response was not ok");
                    }
                } else {
                    const data = await response.json();
                    let printWindow = window.open(data, "_blank");

                    Swal.close();
                }
            } catch (error) {
                console.error("Terjadi kesalahan saat mencari data:", error);
                Swal.fire({
                    icon: "error",
                    title: "Terjadi kesalahan saat mencari data...!!! /n" + error,
                });
            }

            printWindow.open();
            // printWindow.print();
            // printWindow.close();
        }

        function simpanBacaan() {
            //masukan form_identitas
            let formData = new FormData(document.getElementById("form_identitas"));
            formData.append("keterangan", keterangan);
            formData.append("bacaan_radiolog", $("#bacaanRO").val());
            formData.append("tanggal", $("#tanggalBacaan").val());
            formData.append("tanggal_ro", $("#tanggal_ro").val());
            var inputsToValidate = [
                "bacaanRO",
                "tanggalBacaan",
                "tanggal_ro",

            ];

            var error = false;

            inputsToValidate.forEach(function(inputId) {
                var inputElement = document.getElementById(inputId);
                var inputValue = inputElement.value.trim();

                if (inputValue === "") {
                    if ($(inputElement).hasClass("select2-hidden-accessible")) {
                        // Select2 element
                        $(inputElement)
                            .next(".select2-container")
                            .addClass("input-error");
                    } else {
                        // Regular input element
                        inputElement.classList.add("input-error");
                    }
                    error = true;
                } else {
                    if ($(inputElement).hasClass("select2-hidden-accessible")) {
                        // Select2 element
                        $(inputElement)
                            .next(".select2-container")
                            .removeClass("input-error");
                    } else {
                        // Regular input element
                        inputElement.classList.remove("input-error");
                    }
                }
            });
            if (error) {
                // Tampilkan pesan error menggunakan Swal jika ada input yang kosong
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Ada data yang masih kosong! Mohon lengkapi semua data.",
                });
                return;
            }
            tampilkanLoading('Sedang menyimpan data...');
            $.ajax({
                url: "/api/ro/bacaan/hasil",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    tampilkanSukses(response.message);
                    rstForm("Data berhasil disimpan");
                    const data = response.data;
                    if (Array.isArray(data)) {
                        var belumTransaksi = data.filter(function(item) {
                            return (
                                item.hasilKonsul === "Belum Selesai"
                            );
                        });

                        var sudahTransakasi = data.filter(function(item) {
                            return item.hasilKonsul === "Sudah Selesai";
                        });
                        if ($.fn.DataTable.isDataTable("#antrianBelum, #antrianSudah")) {
                            $("#antrianBelum, #antrianSudah").DataTable().destroy();
                        }
                        generateTabelAntrian(belumTransaksi, "#antrianBelum");
                        generateTabelAntrian(sudahTransakasi, "#antrianSudah");
                    } else {
                        console.error("Invalid data format:", data);
                        Toast.fire({
                            icon: "error",
                            title: "Invalid data format:" + data,
                        })
                    }
                    setTimeout(() => {
                        Swal.close();
                    }, 500);
                },
                error: function(xhr, status, error) {
                    console.log("🚀 ~ simpanBacaan ~ xhr:", xhr)
                    console.log("🚀 ~ simpanBacaan ~ error:", error)
                    console.log("🚀 ~ simpanBacaan ~ xhr:", xhr.responseText)
                    tampilkanEror(error + ", \n Terjadi kesalahan saat mengambil data: " + xhr.responseText ||
                        "Unknown error");
                },
            });
        }

        function listBacaanRo() {
            $("#loadingSpinner").show();
            var tgl = $("#tanggal").val();

            if ($.fn.DataTable.isDataTable("#antrianBelum, #antrianSudah")) {
                $("#antrianBelum, #antrianSudah").DataTable().destroy();
            }

            $.ajax({
                url: "/api/ro/bacaan",
                type: "GET",
                data: {
                    tgl: tgl
                },
                success: function(response) {
                    $("#loadingSpinner").hide();
                    var data = response;

                    if (Array.isArray(data)) {
                        var belumTransaksi = data.filter(function(item) {
                            return (
                                item.hasilKonsul === "Belum Selesai"
                            );
                        });

                        var sudahTransakasi = data.filter(function(item) {
                            return item.hasilKonsul === "Sudah Selesai";
                        });

                        generateTabelAntrian(belumTransaksi, "#antrianBelum");
                        generateTabelAntrian(sudahTransakasi, "#antrianSudah");
                    } else {
                        console.error("Invalid data format:", data);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                },
            });
        }

        function generateTabelAntrian(dataArray, idTabel) {
            dataArray.forEach(function(item, index) {
                item.no = index + 1;

                // Cek jika pasien_ro tidak null sebelum akses tgltrans
                item.tgl = item.pasien_ro?.tgltrans || "";

                // console.log("🚀 ~ dataArray.forEach ~ tgl:", item.tgl);

                item.tanggal = moment(item.created_at).format("DD-MM-YYYY");
                const warna = item.hasilKonsul === "Belum Selesai" ? "warning" : "success";
                const hidden = item.hasilKonsul === "Belum Selesai" ? "hidden" : "";

                item.aksi = `<a class="btn btn-${warna}"
                        data-toggle="tooltip"
                        data-placement="right"
                        title="Input Hasil Lab"
                        data-norm="${item.norm}"
                        data-nama="${item.nama}"
                        data-alamat="${item.alamat}"
                        onclick="cariTsRo('${item.norm}', '${item.tgl}');">
                        <i class="fa-solid fa-file-pen"></i>
                     </a>
                      <a href="/api/ro/bacaan/cetak?norm=${item.norm}&tgl=${item.tgl}" method="get" target="_blank" ${hidden} class="${hidden} m-1 btn btn-info"
                            data-toggle="tooltip" data-placement="right" title="Cetak Hasil Lab">
                            <i class="fa-solid fa-print"></i>
                        </a>     `;

                // Cek jika struktur pasien_ro.dokter.pegawai tersedia
                const pegawai = item.pasien_ro?.dokter?.pegawai;
                item.dokter = pegawai ?
                    `${pegawai.gelar_d} ${pegawai.nama} ${pegawai.gelar_b}` :
                    "";
            });

            $(idTabel).DataTable({
                data: dataArray,
                columns: [{
                        data: "aksi",
                        className: "text-center col-1",
                        orderable: false
                    },
                    {
                        data: "hasilKonsul",
                        className: "text-center",
                        render: function(data) {
                            var backgroundColor =
                                data === "Belum Selesai" ? "warning" : "success";
                            return `<div class="badge badge-${backgroundColor}">${data}</div>`;
                        },
                    },
                    {
                        data: "tgl"
                    },
                    {
                        data: "pasien_ro.layanan"
                    },
                    {
                        data: "pasien_ro.norm"
                    },
                    {
                        data: "pasien_ro.nama",
                        className: "col-1"
                    },
                    {
                        data: "tanggal"
                    },
                    {
                        data: "pasien_ro.alamat",
                        className: "col-2"
                    },
                    {
                        data: "dokter",
                        className: "col-3"
                    },
                ],
                paging: true,
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    [5, 10, 25, 50, "All"],
                ],
                pageLength: 5,
                responsive: true,
            });
        }

        async function cariTsRo(norm, tgl) {
            //    jika no rm belum 6 digit, jadikan 6 digit
            norm = norm.padStart(6, "0");
            // rstForm();
            // formatNorm($("#norm"));
            norm = norm ? norm : formatNorm($("#norm"));
            tgl = tgl ? tgl : $("#tglRo").val();
            var requestData = {
                norm: norm,
                tgl: tgl
            };

            Swal.fire({
                icon: "info",
                title: "Sedang mencarikan data pasien...!!!",
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });
            try {
                const response = await fetch("/api/cariTsRO", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify(requestData),
                });

                if (!response.ok) {
                    if (response.status == 404) {
                        // searchRMObat(norm);
                        // cariKominfo(norm, tgl, "ro");
                        $("#tableRo").DataTable({
                            data: [{
                                ket: "Belum Ada Transaksi",
                            }, ],
                            columns: [{
                                data: "ket",
                                createdCell: function(
                                    td,
                                    cellData,
                                    rowData,
                                    row,
                                    col
                                ) {
                                    $(td)
                                        .attr("colspan", 5)
                                        .addClass("bg-warning text-center");
                                },
                            }, ],
                            paging: false, // Disable pagination if not needed
                            searching: false, // Disable searching if not needed
                            ordering: false, // Disable ordering if not needed
                            info: false, // Disable table information display
                        });
                        $("#noreg").val("");
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Terjadi kesalahan saat mengambil data pasien...!!!",
                        });
                        throw new Error("Network response was not ok");
                    }
                    Swal.close();
                } else {
                    const data = await response.json();

                    // Ensure data and the 'transaksi_ro' object exist before setting values
                    if (data && data.data && data.data.transaksi_ro) {
                        const transaksi = data.data.transaksi_ro;
                        const petugas = data.data.petugas;
                        const foto = data.data.foto_thorax;
                        const hasilBacaan = data.data.transaksi_ro.hasil_bacaan;
                        console.log("🚀 ~ cariTsRo ~ hasilBacaan:", hasilBacaan)
                        if (foto && foto.length > 0) {
                            showFoto(foto);
                        }
                        let noreg = transaksi.noreg;
                        console.log("🚀 ~ cariTsRo ~ noreg:", noreg);
                        jk = transaksi.jk || transaksi.pasien.jkel;
                        $("#norm").val(transaksi.norm || "");
                        $("#nama").val(transaksi.nama || "");
                        $("#alamat").val(transaksi.alamat || "");
                        $("#jk").val(transaksi.jk || transaksi.pasien.jkel);
                        $("#notrans").val(transaksi.notrans || "");
                        $(
                            "input[name=pasienRawat][value=" +
                            transaksi.pasienRawat +
                            "]"
                        ).prop("checked", true);
                        $("#noreg").val(transaksi.noreg || "");
                        $("#layanan")
                            .val(transaksi.layanan || "")
                            .trigger("change");
                        $("#kdFoto")
                            .val(transaksi.kdFoto || "")
                            .trigger("change");
                        $("#kdFilm")
                            .val(transaksi.kdFilm || "")
                            .trigger("change");
                        $("#kv")
                            .val(transaksi.kv || "")
                            .trigger("change");
                        $("#ma")
                            .val(transaksi.ma || "")
                            .trigger("change");
                        $("#s")
                            .val(transaksi.s || "")
                            .trigger("change");
                        $("#kdMesin")
                            .val(transaksi.kdMesin || "")
                            .trigger("change");
                        $("#jmlExpose").val(transaksi.jmlExpose || "1");
                        $("#jmlFilmDipakai").val(transaksi.jmlFilmDipakai || "1");
                        $("#jmlFilmRusak").val(transaksi.jmlFilmRusak || "0");
                        $("#kdProyeksi")
                            .val(transaksi.kdProyeksi || "")
                            .trigger("change");
                        $("#catatan").val(transaksi.catatan || "");
                        $("#dokter")
                            .val(petugas.p_dokter_poli || "")
                            .trigger("change");
                        $("#p_rontgen")
                            .val(petugas.p_rontgen || "")
                            .trigger("change");
                        $("#p_rontgen_evaluator")
                            .val(petugas.p_rontgen_evaluator || "")
                            .trigger("change");
                        $('#tanggal_ro').val(transaksi.tgltrans);
                        $('#tglRo').val(transaksi.tgltrans);
                        if (hasilBacaan !== null) {
                            $('#tanggalBacaan').val(hasilBacaan.tanggal);
                            $('#tanggal_ro').val(hasilBacaan.tanggal_ro);
                            $('#bacaanRO').summernote('code', hasilBacaan.bacaan_radiolog);
                        } else {
                            const template = `                                             
                                            <p style="margin: 2px 0 2px 50px;">Thorak emfisema</p>
                                            <p style="margin: 2px 0 2px 50px;">Cor dbn, aorta elongasi</p>
                                            <p style="margin: 2px 0 2px 50px;">Sinus dan diafragma baik</p>
                                            <p style="margin: 2px 0 2px 50px;">Pulmones:</p>
                                            <p style="margin: 2px 0 2px 125px;">Corakan paru kasar</p>
                                            <p style="margin: 2px 0 2px 125px;">Kedua paru suram berbercak</p>
                                            <p style="margin: 2px 0 2px 125px;"><br></p>
                                            <p style="margin: 2px 0px 2px 75px;"><b>Kesan : Bronkopneumonia</b></p>
                                            `;
                            $('#tanggalBacaan').val('');
                            $('#bacaanRO').summernote('code', template);
                        }


                        closeSwalAfterDelay()
                    } else {
                        console.error("No data received from API");
                        Swal.fire({
                            icon: "error",
                            title: "Terjadi kesalahan saat mengambil data pasien...!!!",
                        });
                    }
                }
            } catch (error) {
                console.error("Terjadi kesalahan saat mencari data:", error);
                Swal.fire({
                    icon: "error",
                    title: "Terjadi kesalahan saat mencari data...!!! /n" + error,
                });
                // Optionally, handle the error by informing the user or retrying
            }
        }

        async function hasilRo(norm, tgl) {
            var norm = norm || $("#norm").val();
            var tgl = tgl || $("#tglRo").val();
            try {
                const response = await fetch("/api/ro", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        norm,
                        tgl
                    }),
                });
                const data = await response.json();
                console.log(data);
            } catch (error) {
                console.error("Terjadi kesalahan saat mencari data:", error);
            }
        }

        function showFoto(foto) {
            console.log("🚀 ~ showFoto ~ foto:", foto);

            $("#preview").show();
            if ($.fn.DataTable.isDataTable("#tableRo")) {
                var tabel = $("#tableRo").DataTable();
                tabel.clear().destroy();
            }
            foto.forEach(function(item, index) {
                let ket = ""; // Default value if no valid 'ket' part is found
                // let ketFoto = item.norm + "-" + item.nama ;
                // item.ketFoto = ketFoto;
                // Check if the file name includes an underscore and has at least three parts
                if (item.foto.includes("_")) {
                    const parts = item.foto.split("_");

                    if (parts.length > 2) {
                        // Safely access the third part and remove the extension
                        ket = parts[2].split(".")[0];
                    }
                }

                // If 'ket' is empty or undefined, you can provide a fallback or leave it as an empty string
                if (!ket) {
                    ket = ""; // Or some other fallback value
                }

                item.actions = `<a type="button"  class="btn-sm btn-danger mx-2 mt-md-0 mt-2"
                            data-id="${item.id}"
                            data-foto="${item.foto}"
                            data-ketFoto="${item.ketFoto}"
                            data-norm="${item.norm}"
                            data-nama="${item.nama}"
                            onclick="deleteFoto('${item.id}')"
                            ><i class="fas fa-trash"></i></a>
                        <a type="button"  class="btn-sm btn-warning mx-2 mt-md-0 mt-2"
                            data-id="${item.id}"
                            data-foto="${item.foto}"
                            data-ketFoto="${item.ketFoto}"
                            data-norm="${item.norm}"
                            data-nama="${item.nama}"
                            data-toggle="modal" data-target="#staticBackdrop"
                            onclick="document.getElementById('idFoto').value = '${item.id}'; document.getElementById('nmFoto').value = '${item.foto}';document.getElementById('ket_foto_new').value = '${ket}';"
                            ><i class="fas fa-pen-to-square"></i></a> `;
                item.buttonShow = `<a type="button" class="btn-sm btn-primary px-5" data-toggle="modal" data-target="#modalFoto" onclick="modalFotoShow('${item.foto}','${item.norm} - ${item.nama}','${item.tanggal}')">
                            <i class="fa-solid fa-eye"></i></a>`;
                item.no = index + 1;
            });

            $("#tableRo").DataTable({
                data: foto,
                paging: false,
                searching: false,
                ordering: false,
                columns: [{
                        data: "actions",
                        className: "text-center col-2"
                    },
                    {
                        data: "id"
                    },
                    {
                        data: "buttonShow",
                    },
                    {
                        data: "tanggal"
                    },
                    {
                        data: "foto"
                    },
                ],
            });
        }

        function modalFotoShow(foto, ketFoto, tgl) {
            const fullImageUrl = appUrlRo + foto;
            // document.getElementById("modalFotoImage").src = fullImageUrl;
            document.getElementById("zoomed-image").src = fullImageUrl;
            $("#keteranganFoto").html(`<b>${ketFoto}</b>`);
            $("#keteranganFoto2").html(`<b>${tgl}</b>`);
            const container = document.getElementById("myPanzoom");
            const options = {
                click: "toggleCover",
                Toolbar: {
                    display: ["zoomIn", "zoomOut"],
                },
            };

            new Panzoom(container, options);
            $("#modalFoto").modal("show");
        }

        async function cariPasien() {
            var tgl = $("#tglRo").val();
            var norm = $("#norm").val();

            // Mengecek apakah norm dan tgl sudah diisi
            if (!norm || !tgl) {
                Swal.fire({
                    icon: "error",
                    title: "No RM dan Tanggal Belum di isi...!!!",
                });
            } else {
                // Memanggil fungsi cariTsRo jika norm dan tgl tidak kosong
                cariTsRo(norm, tgl);
            }
        }

        function rstForm() {
            document.getElementById("form_identitas").reset();
            document.getElementById("formtrans").reset();
            $("#preview").hide();
            $("#formtrans select").trigger("change");
            $("#form_identitas select").trigger("change");
            $("#permintaan").html("");
            $("#tujuanLain").html("Penunjang Hari ini:");
            scrollToTop();
            setTodayDate();
            $('#bacaanRO').summernote('code', '');
            // $('#bacaanRO').val('');

            console.log("🚀 ~ msgSelesai:", msgSelesai);
            if (msgSelesai != undefined)
                Swal.fire({
                    icon: "info",
                    title: msgSelesai,
                    allowOutsideClick: false,
                });
            msgSelesai = undefined;
        }

        function askRo(button) {
            var norm = $(button).data("norm");
            var nama = $(button).data("nama");
            var dokter = $(button).data("kddokter");
            var alamat = $(button).data("alamat");
            var layanan = $(button).data("layanan");
            var notrans = $(button).data("notrans");
            var tgltrans = $(button).data("tgltrans");
            var asktind = $(button).data("asktind");
            var tujuan = $(button).data("tujuan");
            jk = $(button).data("jk");

            $("#norm").val(norm);
            $("#nama").val(nama);
            $("#dokter").val(dokter);
            $("#dokter").trigger("change");
            $("#alamat").val(alamat);
            $("#layanan").val(layanan).trigger("change");
            $("#notrans").val(notrans);
            $("#tgltrans").val(tgltrans);
            $("#jk").val(jk);

            // Memperbarui konten asktindContent
            $("#permintaan").html(`<b>${asktind}</b>`);
            $("#tujuanLain").html(
                `<div class="font-weight-bold bg-warning rounded">${tujuan}</div>`
            );

            scrollToInputSection();
        }

        $(document).ready(function() {
            setTodayDate();
            listBacaanRo();
            scrollToTop();
            $("#norm").on("keyup", function(event) {
                if (event.key === "Enter") {
                    event.preventDefault();
                    cariPasien();
                }
            });
        });
    </script>
@endsection
