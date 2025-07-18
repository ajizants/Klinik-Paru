@extends('Template.lte')

@section('content')
    @include('Farmasi.Transaksi.antrian')
    {{-- @include('Farmasi.Transaksi.input2') --}}

    @include('IGD.Trans.input')


    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalInputObat">modalInputObat</button>

    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('js/antrianFarmasi.js') }}"></script>
    <script src="{{ asset('js/populate.js') }}"></script>
    <script>
        let sedangMemanggil = false;
        console.log("🚀 ~ sedangMemanggil:", sedangMemanggil)

        function scrollToInputBMHPSection() {
            $("html, body").animate({
                scrollTop: $("#formbmhp").offset().top
            }, 500);
        }
        var Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
        });

        function hitungTotalHarga() {
            var nilaiJual = $("#jual").val();
            nilaiJual = nilaiJual.replace(/[.,]/g, "");
            var hargaJual = parseFloat(nilaiJual) || 0;
            var qty = parseFloat($("#qty").val()) || 0;
            var totalharga = hargaJual * qty;
            $("#total").val(totalharga);
        }

        function dataTindakan(notrans, norm) {
            console.log("🚀 ~ dataTindakan ~ notrans:", notrans);
            var notrans = notrans ? notrans : $("#notrans").val();
            console.log("🚀 ~ dataTindakan ~ notrans:", notrans);

            if ($.fn.DataTable.isDataTable("#dataTindakan")) {
                var tabletindakan = $("#dataTindakan").DataTable();
                tabletindakan.destroy();
            }

            $.ajax({
                url: "/api/cariDataTindakan",
                type: "post",
                data: {
                    notrans: notrans
                },
                success: function(response) {
                    response.forEach(function(item, index) {
                        var dokter =
                            `${item.dokter.gelar_d} ${item.dokter.biodata.nama} ${item.dokter.gelar_b}`;
                        var petugas =
                            `${item.petugas.gelar_d} ${item.petugas.biodata.nama} ${item.petugas.gelar_b}`;
                        var tindakan = `${item.tindakan.nmTindakan}`;
                        item.actions = `<a type="button" class="mr-2 edit btn-sm btn-primary py-md-0 py-1 icon-link icon-link-hover"
                                    data-id="${item.id}"
                                    data-kdtind="${item.kdTind}"
                                    data-tindakan="${tindakan}"
                                    data-norm="${item.norm}"
                                    data-petugas="${petugas}"
                                    data-dokter="${dokter}"><i class="fas fa-pen-to-square"></i></a>
                                <a type="button" class="delete btn-sm btn-danger py-md-0 py-1 icon-link icon-link-hover"
                                    data-id="${item.id}"
                                    data-kdTind="${item.kdTind}"
                                    data-tindakan="${tindakan}"
                                    data-norm="${item.norm}"
                                    data-petugas="${petugas}"
                                    data-dokter="${dokter}"><i class="fas fa-trash"></i></a>`;
                        item.no = index + 1;
                        if (item.transbmhp.length > 0) {
                            item.status = "sudah";
                        } else {
                            item.status = "belum";
                        }
                    });

                    $("#dataTindakan").DataTable({
                        data: response,
                        columns: [{
                                data: "actions",
                                className: "text-center col-1 p-2"
                            },
                            {
                                data: "status",
                                name: "kdTind",
                                render: function(data, type, row) {
                                    var backgroundColor =
                                        data === "belum" ? "danger" : "success";
                                    return `<div class="badge badge-${backgroundColor}">${data}</div>`;
                                },
                                className: "p-2",
                            },
                            {
                                data: "norm",
                                className: "p-2"
                            },
                            {
                                data: "tindakan.nmTindakan",
                                className: "p-2"
                            },
                            {
                                data: "petugas.biodata.nama",
                                className: "p-2"
                            },
                            {
                                data: "dokter.biodata.nama",
                                className: "p-2"
                            },
                        ],
                        order: [2, "asc"],
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                },
            });
        }

        function addBmhp() {
            // Get data from input fields
            var idTind = $("#modalidTind").val();
            var kdTind = $("#modalkdTind").val();
            var kdBmhp = $("#bmhp").val();
            var jumlah = $("#qty").val();
            var total = $("#total").val();
            var notrans = $("#notrans").val();
            var productID = $("#productID").val();

            // Memeriksa apakah ada nilai yang kosong
            if (!kdBmhp || !jumlah) {
                // Menampilkan notifikasi jika ada nilai yang kosong
                var dataKurang = [];
                if (!kdBmhp) dataKurang.push("BMHP Belum Diisi");
                if (!jumlah) dataKurang.push("jumlah Belum Diisi");

                // Menampilkan notifikasi menggunakan Toast.fire
                Swal.fire({
                    icon: "error",
                    title: "Data Tidak Lengkap...!!! " + dataKurang.join(", "),
                });
            } else {
                // Send AJAX POST request to Laravel route
                $.ajax({
                    type: "POST",
                    url: "/api/addTransaksiBmhp", // Replace with your Laravel route
                    data: {
                        idTind: idTind,
                        kdTind: kdTind,
                        kdBmhp: kdBmhp,
                        jml: jumlah,
                        total: total,
                        notrans: notrans,
                        productID: productID,
                    },
                    success: function(response) {
                        Toast.fire({
                            icon: "success",
                            title: "Data Berhasil Disimpan, Maturnuwun...!!!",
                        });
                        dataBMHP();
                        $("#bmhp, #qty").val("");
                        $("#bmhp").trigger("change");
                        quantity = 0;
                        console.log(quantity);
                        dataTindakan();
                    },
                    error: function(error) {
                        // Handle error response here
                        console.log(error);
                    },
                });
            }
        }

        let tanggalFormat;
        let kdtgl;

        function setTglRo() {
            var inputTanggal = document.getElementById("tgltrans");
            var tanggalHariIni = new Date();

            var tahun = tanggalHariIni.getFullYear();
            var bulan = String(tanggalHariIni.getMonth() + 1).padStart(2, "0");
            var tanggal = String(tanggalHariIni.getDate()).padStart(2, "0");

            tanggalFormat = tahun + "-" + bulan + "-" + tanggal;
            kdtglFormat = tahun + "-" + bulan + "-" + tanggal;

            inputTanggal.value = tanggalFormat;
            kdtgl = kdtglFormat.replace(/-/g, "");
        }

        function batal() {
            $(
                "#norm, #nama, #alamat, #layanan,#tglTind,#tglTrans, #notrans, #dokter, #petugas, #tindakan, #asktind, #bmhp, #qty, #modalidTind, #modalkdTind, #modalnorm, #modaltindakan, #modaldokter, #modalpetugas"
            ).val("");

            $("#dokter, #petugas, #tindakan, #bmhp, #qty").trigger("change");

            $("#permintaan").html("");
            var tabletindakan = $("#dataTindakan").DataTable();
            tabletindakan.clear().destroy();
            var tablebmhp = $("#transaksiBMHP").DataTable();
            tablebmhp.clear().destroy();
            antrianFar();
            quantity = 0;
            scrollToTop();
            $("#formbmhp").hide();
            $("#formtind").show();
        }

        function selesai() {
            var norm = $("#norm").val();
            var notrans = $("#notrans").val();
            // Memeriksa apakah ada nilai yang kosong
            if (!norm || !notrans) {
                // Menampilkan notifikasi jika ada nilai yang kosong
                var dataKurang = [];
                if (!norm || !notrans) dataKurang.push("Belum Ada Data Transaksi");

                Toast.fire({
                    icon: "error",
                    title: "Data Tidak Lengkap...!!! " + dataKurang.join(", "),
                });
                scrollToTop();
            } else {
                $(
                    "#norm, #nama, #alamat, #layanan, #notrans, #dokter, #petugas, #tindakan, #asktind, #bmhp, #qty, #modalidTind, #modalkdTind, #modalnorm, #modaltindakan, #modaldokter, #modalpetugas"
                ).val("");
                $("#dokter, #petugas, #tindakan, #bmhp").trigger("change");

                var tabletindakan = $("#dataTindakan").DataTable();
                tabletindakan.clear().destroy();
                var tablebmhp = $("#transaksiBMHP").DataTable();
                tablebmhp.clear().destroy();
                quantity = 0;
                antrianFar();
                scrollToTop();
                $("#formbmhp").hide();
                $("#formtind").show();
                Toast.fire({
                    icon: "success",
                    title: "Transaksi Berhasil Disimpan, Maturnuwun...!!!",
                });
            }
        }

        function addTindakan() {
            var norm = $("#norm").val();
            var notrans = $("#notrans").val();
            var kdTind = $("#tindakan").val();
            var petugas = $("#petugas").val();
            var dokter = $("#dokter").val();
            var tgltrans = $("#tgltrans").val();
            // Memeriksa apakah ada nilai yang kosong
            if (!norm || !notrans || !kdTind || !petugas || !dokter || !tgltrans) {
                // Menampilkan notifikasi jika ada nilai yang kosong
                var dataKurang = [];
                if (!norm) dataKurang.push("Nomor Rekam Medis");
                if (!notrans) dataKurang.push("Nomor Transaksi");
                if (!kdTind) dataKurang.push("Tindakan");
                if (!petugas) dataKurang.push("Petugas");
                if (!dokter) dataKurang.push("Dokter");
                if (!tgltrans) dataKurang.push("Tanggal Transaksi");

                // Menampilkan notifikasi menggunakan Toast.fire
                swal.fire({
                    icon: "error",
                    title: "Data Tidak Lengkap...!!! " +
                        dataKurang.join(", ") +
                        " Belum Diisi, diisi disit mbok..!!!",
                });
            } else {
                $.ajax({
                    url: "/api/simpanTindakan",
                    type: "POST",
                    data: {
                        notrans: notrans,
                        kdTind: kdTind,
                        petugas: petugas,
                        dokter: dokter,
                        norm: norm,
                        tgltrans: tgltrans,
                    },

                    success: function(response) {
                        Toast.fire({
                            icon: "success",
                            title: "Data Berhasil Disimpan, Maturnuwun...!!!",
                        });
                        dataTindakan();
                        $("#tindakan,#petugas").val("");
                        $("#tindakan,#petugas").trigger("change");
                    },
                    error: function(xhr) {
                        swal.fire({
                            icon: "error",
                            title: "Data Tidak Lengkap...!!!",
                        });
                    },
                });
            }
        }

        function setHarga() {
            const bmhpElement = document.getElementById("bmhp");
            const selectedOption = bmhpElement.options[bmhpElement.selectedIndex];
            const produkId = selectedOption.getAttribute("prdukid");
            const jual = selectedOption.getAttribute("jual");
            console.log("🚀 ~ setHarga ~ jual:", jual);
            console.log("🚀 ~ setHarga ~ produkId:", produkId);
            $("#jual").val(jual);
            $("#productID").val(produkId);
            $("#total").val(0);
        }

        function setTransaksi1(button) {
            var norm = $(button).data("norm");
            var nama = $(button).data("nama");
            var dokter = $(button).data("kddokter");
            var alamat = $(button).data("alamat");
            var layanan = $(button).data("layanan");
            var notrans = $(button).data("notrans");
            var tgltrans = $(button).data("tgltrans");
            var tgl = $(button).data("tgl");
            var asktind = $(button).data("asktind");
            var tujuan = $(button).data("tujuan");

            $("#norm").val(norm);
            $("#nama").val(nama);
            $("#dokter").val(dokter);
            $("#dokter").trigger("change");
            $("#alamat").val(alamat);
            $("#layanan").val(layanan);
            $("#notrans").val(notrans);
            $("#tgltrans").val(tgltrans);
            $("#tgltind").val(tgl);
            $("#asktind").val(asktind);
            $("#permintaan").html(`<b>${asktind}</b>
            <br>
            <br>
            <div class="font-weight-bold bg-warning rounded">${tujuan}</div>`);

            // dataTindakan(notrans, norm);
            cariTsIgd(notrans, norm, tgl);
            scrollToInputSection();
        }
        $(document).ready(function() {
            setTglRo();
            $(".select2bs4").select2({
                theme: "bootstrap4"
            });
            $(".bmhp").select2({
                theme: "bootstrap4"
            });

            let quantity = 0;
            const qtyInput = document.getElementById("qty");
            const increaseBtn = document.getElementById("increaseBtn");
            const decreaseBtn = document.getElementById("decreaseBtn");
            // Menambahkan event listener untuk penambahan qty
            increaseBtn.addEventListener("click", function() {
                quantity++;
                qtyInput.value = quantity;
                hitungTotalHarga();
            });

            // Menambahkan event listener untuk pengurangan qty
            decreaseBtn.addEventListener("click", function() {
                if (quantity > 1) {
                    quantity--;
                    qtyInput.value = quantity;
                } else {
                    $("#qty").val("");
                }
                hitungTotalHarga();
            });

            setTodayDate();

            $("#norm").on("keyup", function(event) {
                if (event.key === "Enter") {
                    event.preventDefault();
                    // searchByRM($("#norm").val());
                    var tgl = $("#tgltrans").val();
                    var norm = $("#norm").val();
                    cariKominfo(norm, tgl, "igd");
                }
            });
            $("#qty").on("keyup", function(event) {
                if (event.key === "Enter") {
                    event.preventDefault();
                    addBmhp();
                }
            });

            $("#dataTindakan").on("click", ".edit", function(e) {
                e.preventDefault();
                var id = $(this).data("id");
                var kdtind = $(this).data("kdtind");
                var norm = $(this).data("norm");
                var tindakan = $(this).data("tindakan");
                var petugas = $(this).data("petugas");
                var dokter = $(this).data("dokter");

                $("#modalidTind").val(id);
                $("#modalkdTind").val(kdtind);
                $("#modalnorm").val(norm);
                $("#modaltindakan").val(tindakan);
                $("#modaldokter").val(dokter);
                $("#modalpetugas").val(petugas);
                $("#formbmhp").show();
                $("#formtind").hide();
                dataBMHP();
                scrollToInputBMHPSection();
            });

            $("#dataTindakan").on("click", ".delete", function(e) {
                e.preventDefault();

                var id = $(this).data("id");
                var tindakan = $(this).data("tindakan");
                Swal.fire({
                    icon: "question",
                    title: "Apakah Anda yakin ingin menghapus tindakan " + tindakan + " ?",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "YA",
                    cancelButtonText: "TIDAK",
                }).then((result) => {
                    // Display a confirmation dialog
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "/api/deleteTindakan",
                            type: "POST",
                            data: {
                                id: id
                            },
                            success: function(response) {
                                Toast.fire({
                                    icon: "success",
                                    title: "Data tindakan berhasil dihapus, Maturnuwun...!!!",
                                });

                                dataTindakan();
                                dataBMHP();
                            },
                            error: function(xhr, status, error) {
                                Toast.fire({
                                    icon: "success",
                                    title: error + ", Maturnuwun...!!!",
                                });
                                console.error("Error:", error);
                            },
                        });
                    } else {}
                });
            });

            $("#addBMHP").on("click", function() {
                // Get data from input fields
                var idTind = $("#modalidTind").val();
                var kdTind = $("#modalkdTind").val();
                var kdBmhp = $("#bmhp").val();
                var jumlah = $("#qty").val();
                var total = $("#total").val();
                var notrans = $("#notrans").val();
                var productID = $("#productID").val();

                // Memeriksa apakah ada nilai yang kosong
                if (!kdBmhp || !jumlah) {
                    // Menampilkan notifikasi jika ada nilai yang kosong
                    var dataKurang = [];
                    if (!kdBmhp) dataKurang.push("BMHP Belum Diisi");
                    if (!jumlah) dataKurang.push("jumlah Belum Diisi");

                    // Menampilkan notifikasi menggunakan Toast.fire
                    Swal.fire({
                        icon: "error",
                        title: "Data Tidak Lengkap...!!! " + dataKurang.join(", "),
                    });
                } else {
                    // Send AJAX POST request to Laravel route
                    $.ajax({
                        type: "POST",
                        url: "/api/addTransaksiBmhp", // Replace with your Laravel route
                        data: {
                            idTind: idTind,
                            kdTind: kdTind,
                            kdBmhp: kdBmhp,
                            jml: jumlah,
                            total: total,
                            notrans: notrans,
                            productID: productID,
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: "success",
                                title: "Data Berhasil Disimpan, Maturnuwun...!!!",
                            });
                            dataBMHP();
                            $("#bmhp, #qty").val("");
                            $("#bmhp").trigger("change");
                            quantity = 0;
                            dataTindakan();
                        },
                        error: function(error) {
                            // Handle error response here
                            console.log(error);
                        },
                    });
                }
            });

            $("#addBMHPSelesai").on("click", function(e) {
                e.preventDefault();

                $(
                    "#bmhp, #qty, #modalidTind, #modalkdTind, #modalnorm, #modaltindakan, #modaldokter, #modalpetugas"
                ).val("");
                $("#bmhp, #qty").trigger("change");

                var tablebmhp = $("#transaksiBMHP").DataTable();
                tablebmhp.clear().destroy();

                dataTindakan();
                scrollToInputSection();
                $("#formbmhp").hide();
                $("#formtind").show();
                Toast.fire({
                    icon: "success",
                    title: "Data BMHP Berhasil Disimpan, Maturnuwun...!!!",
                });
            });

            $("#transaksiBMHP").on("click", ".delete", function(e) {
                e.preventDefault();

                var id = $(this).data("id");
                var bmhp = $(this).data("bmhp");
                Swal.fire({
                    icon: "question",
                    title: "Apakah Anda yakin ingin menghapus " + bmhp + " ?",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "YA",
                    cancelButtonText: "TIDAK",
                }).then((result) => {
                    // Display a confirmation dialog
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "/api/deleteTransaksiBmhp",
                            type: "POST",
                            data: {
                                id: id
                            },
                            success: function(response) {
                                Toast.fire({
                                    icon: "success",
                                    title: "Data BMHP berhasil dihapus, Maturnuwun...!!!",
                                });
                                dataBMHP();
                            },
                            error: function(xhr, status, error) {
                                swal.fire({
                                    icon: "error",
                                    title: error + "...!!!",
                                });
                                console.error("Error:", error);
                            },
                        });
                    } else {}
                });
            });

            selamatBertugas();
        });

        $(document).on("select2:open", () => {
            document.querySelector(".select2-search__field").focus();
        });
    </script>
    <script>
        $(document).ready(function() {
            var today = new Date().toISOString().split("T")[0];
            $("#tanggal").val(today);
            antrianAll();
            antrianFar();
            // setInterval(function() {
            //     antrianFar();
            // }, 60000);
            // setInterval(function() {
            //     antrianAll();
            // }, 180000);

            let btnPanggil;
            console.log("🚀 ~ $ ~ btnPanggil:", btnPanggil)
        });

        function panggilFarmasi(button) {
            var tgl = button.getAttribute("data-tgl");
            console.log("🚀 ~ panggilFarmasi ~ tgl:", tgl)
            var norm = button.getAttribute("data-norm");
            console.log("🚀 ~ panggilFarmasi ~ norm:", norm)
            var log_id = button.getAttribute("data-log_id");
            console.log("🚀 ~ panggilFarmasi ~ log_id:", log_id)
            var notrans = button.getAttribute("data-notrans");
            console.log("🚀 ~ panggilFarmasi ~ notrans:", notrans)
            cariResepLocal(tgl, norm, log_id, notrans);
        }



        function panggil(log_id, norm, tgl) {
            console.log("🚀 ~ panggil ~ tgl:", tgl)
            console.log("🚀 ~ panggil ~ norm:", norm)
            console.log("🚀 ~ panggil ~ log_id:", log_id)
            // Mendapatkan tombol dengan class 'panggil'
            sedangMemanggil = true;

            // antrianFar()
            // return;
            const apiUrl = "/api/farmasi/panggil";

            // Data yang akan dikirim melalui POST
            const postData = {
                log_id: log_id,
            };
            Swal.fire({
                icon: "info",
                title: "Sedang memanggil pasien...!!!",
                didOpen: () => {
                    Swal.showLoading();
                },
            })

            fetch(apiUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json", // Pastikan tipe konten adalah JSON
                    },
                    body: JSON.stringify(postData), // Data dikonversi ke JSON
                })
                .then((response) => {
                    console.log("🚀 ~ .then ~ response:", response)
                    if (!response.ok) {
                        throw new Error("Network response was not ok " + response.statusText);
                    }
                    return response.json();
                })
                .then((data) => {
                    console.log("🚀 ~ .then ~ data:", data)
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil",
                        allowOutsideClick: false,
                        showConfirmButton: false,
                    })
                    // cetakResep(norm, tgl)
                    antrianFar();
                })
                .catch((error) => console.error("Error fetching data:", error));
        }

        function cetakResep(norm, tgl, btn) {
            btn.classList.remove("btn-secondary");
            btn.classList.add("btn-danger");
            const urlEtiket = "/api/etiket/" + norm + "/" + tgl;
            const urlResep = "/api/resep/" + norm + "/" + tgl;
            // window.open(urlEtiket, "_blank");
            window.open(urlResep, "_blank");

        }

        function pulangkan(norm, log_id, notrans) {
            console.log("🚀 ~ panggil ~ log_id:", log_id)
            console.log("🚀 ~ panggil ~ norm:", norm)
            console.log("🚀 ~ panggil ~ notrans:", notrans)

            sedangMemanggil = false;
            // antrianFar();
            // return;

            const apiUrl = "/api/farmasi/pulangkan";

            // Data yang akan dikirim melalui POST
            const postData = {
                norm: norm,
                log_id: log_id,
                notrans: notrans
            };
            Swal.fire({
                icon: "info",
                title: "Sedang memulangkan pasien...!!!",
                didOpen: () => {
                    Swal.showLoading();
                },
            })

            fetch(apiUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json", // Pastikan tipe konten adalah JSON
                    },
                    body: JSON.stringify(postData), // Data dikonversi ke JSON
                })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok " + response.statusText);
                    }
                    return response.json();
                })
                .then((data) => {
                    console.log("🚀 ~ .then ~ data:", data)
                    Swal.fire({
                        icon: "success",
                        title: data.message +
                            "\n \n" +
                            "\n\nKeterangan: " +
                            data.data.message,
                    })
                    btnPanggil.forEach((btn, index) => {
                        btn.disabled = true;
                    });
                    antrianFar();
                })
                .catch((error) => console.error("Error fetching data:", error));
        }

        // function cariResepLocal(tgl, norm, log_id) {
        function cariResepLocal(button) {
            var tgl = button.getAttribute("data-tgl");
            console.log("🚀 ~ panggilFarmasi ~ tgl:", tgl)
            var norm = button.getAttribute("data-norm");
            console.log("🚀 ~ panggilFarmasi ~ norm:", norm)
            var log_id = button.getAttribute("data-log_id");
            console.log("🚀 ~ panggilFarmasi ~ log_id:", log_id)
            var notrans = button.getAttribute("data-notrans");
            console.log("🚀 ~ panggilFarmasi ~ notrans:", notrans)


            const apiUrl = "/api/lists/obat";

            // Data yang akan dikirim melalui POST
            const postData = {
                tgl: tgl,
                norm: norm,
            };
            Swal.fire({
                icon: "info",
                title: "Sedang mencarikan data pasien...!!!",
                didOpen: () => {
                    Swal.showLoading();
                },
            })

            fetch(apiUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json", // Pastikan tipe konten adalah JSON
                    },
                    body: JSON.stringify(postData), // Data dikonversi ke JSON
                })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok " + response.statusText);
                    }
                    return response.json();
                })
                .then((data) => {
                    console.log("🚀 ~ .then ~ data:", data)
                    Swal.close();
                    $("#modal-resep").modal("show");
                    $("#divBtnCetak").html(`
                        <a href="/api/resep/${norm}/${tgl}" target="_blank" class="btn btn-success" >Cetak Resep</a>
                    `);
                    $("#divBtnPulang").html(`
                        <a type="button" onclick="pulangkan('${norm}', '${log_id}', '${notrans}')" target="_blank" class="btn btn-warning" >Pulangkan</a>
                    `);
                    // Hapus konten sebelumnya di tabel untuk menghindari duplikasi
                    const tindakanBody = document.getElementById("tindakan-body");
                    const obatBody = document.getElementById("obat-body");
                    tindakanBody.innerHTML = "";
                    obatBody.innerHTML = "";

                    // Muat data tindakan
                    data.tindakan.forEach((tindakan) => {
                        let rowspan = tindakan.bmhps.length;
                        tindakan.bmhps.forEach((bmhp, index) => {
                            const row = document.createElement("tr");

                            if (index === 0) {
                                row.innerHTML = `
                            <td rowspan="${rowspan}">${tindakan.id}</td>
                            <td rowspan="${rowspan}">${tindakan.notrans}</td>
                            <td rowspan="${rowspan}">${tindakan.norm}</td>
                            <td rowspan="${rowspan}">${tindakan.kdTind}</td>
                            <td rowspan="${rowspan}">${tindakan.tindakan}</td>
                            <td>${bmhp.bmhp} (${bmhp.qty})</td>
                        `;
                            } else {
                                row.innerHTML = `
                            <td>${bmhp.bmhp} (${bmhp.qty})</td>
                        `;
                            }

                            tindakanBody.appendChild(row);
                        });
                    });

                    // Muat data obats
                    data.obats.forEach((obat) => {
                        const row = document.createElement("tr");
                        row.innerHTML = `
                    <td>${obat.no_resep}</td>
                    <td>${obat.nmObat}</td>
                    <td>${obat.jumlah}</td>
                    <td>${obat.signa}</td>
                `;
                        obatBody.appendChild(row);
                    });

                })
                .catch((error) => console.error("Error fetching data:", error));
        }
    </script>

    <script>
        // var socketIO = io.connect("wss://kkpm.banyumaskab.go.id:3131/", {
        //     // path: '/socket.io',
        //     transports: ["websocket", "polling", "flashsocket"],
        // });

        // socketIO.on("connectParu", () => {
        //     const sessionID = socketIO.id;
        //     $("#socket-id").html(sessionID);
        //     console.log("Socket ID : " + sessionID);
        // });


        socketIO.on("reload", (msg) => {
            if (msg == "paru_loket_farmasi") {
                antrianFar();
            }
            if (msg == "paru_notifikasi_loket_farmasi") {
                const notif = new Audio("/audio/dingdong.mp3");
                notif.load();
                notif.play();
                antrianFar();
            }
            if (msg == "paru_notifikasi_ruang_tensi_1") {
                const notif = new Audio("/audio/dingdong.mp3");
                notif.load();
                notif.play();
                antrianAll();
            }

        });
    </script>


    <div class="modal fade" id="modal-resep">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container mt-4">
                        <div class="row">
                            <div class="col-md-6">
                                <h3>Daftar Tindakan</h3>
                            </div>
                            <div class="col-md-3" id="divBtnCetak">

                            </div>
                            <div class="col-md-3" id="divBtnPulang">

                            </div>
                        </div>
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>No Trans</th>
                                    <th>No RM</th>
                                    <th>Kode Tindakan</th>
                                    <th>Nama Tindakan</th>
                                    <th>BMHP</th>
                                </tr>
                            </thead>
                            <tbody id="tindakan-body">
                                <!-- Data akan dimuat di sini -->
                            </tbody>
                        </table>

                        <h3>Daftar Obat</h3>
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>No Resep</th>
                                    <th>Nama Obat</th>
                                    <th>Jumlah</th>
                                    <th>Signa</th>
                                </tr>
                            </thead>
                            <tbody id="obat-body">
                                <!-- Data akan dimuat di sini -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer justify-content-end">

                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <div class="modal fade" id="riwayatModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Riwayat Transaksi Farmasi</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="card-body">
                            <div class=" border border-black">
                                <div class="card-body card-body-hidden p-2">
                                    <table id="riwayat" class="table table-striped fs-6" style="width:100%"
                                        cellspacing="0">
                                        <thead class="table-secondary table-sm">
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>Data FArmasi</th>
                                                <th>Data Tindakan</th>
                                            </tr>
                                        </thead>
                                    </table>
                                    @include('Template.Table.loading')
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <div class="modal fade" id="modalInputObat">
        <div class="modal-dialog modal-dialog-scrollable modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Input Data Farmasi</h4>
                </div>
                <div class="modal-body">
                    <div class="card-body p-2">
                        @csrf
                        <form id="form_identitas_bpjs">
                            <div class="form-row">
                                <div class="form-group col">
                                    <label for="norm_bpjs" class="col-form-label font-weight-bold mb-0 ">No
                                        RM
                                        :</label>
                                    <div class=" input-group" style="overflow: hidden;">
                                        <input type="text" name="norm_bpjs" id="norm_bpjs" class="form-control"
                                            placeholder="No RM" maxlength="6" pattern="[0-9]{6}" required />
                                    </div>
                                </div>
                                <div class="form-group col">
                                    <label for="layanan_bpjs" class="col-form-label font-weight-bold mb-0">Jaminan
                                        :</label>
                                    <div class="">
                                        <input type="text" id="layanan_bpjs" class="form-control bg-white"
                                            placeholder="BPJS/UMUM" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="nama_bpjs" class="col-form-label font-weight-bold  mb-0">Nama
                                    :</label>
                                <input type="text" id="nama_bpjs" class="form-control bg-white"
                                    placeholder="Nama Pasien">
                            </div>
                            <div class="form-row">
                                <div class="form-group col">
                                    <label for="tgltrans_bpjs" class="col-form-label font-weight-bold mb-0">Tanggal
                                        :</label>
                                    <div class="">
                                        <input type="date" id="tgltrans_bpjs" class="form-control bg-white"
                                            placeholder="Tanggal Transaksi" />
                                    </div>
                                </div>
                                <div class="form-group col">
                                    <label for="notrans_bpjs" class="col-form-label font-weight-bold mb-0">NoTran
                                        :</label>
                                    <div class="">
                                        <input type="text" id="notrans_bpjs" class="form-control bg-white"
                                            placeholder="Nomor Transaksi" required />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="number" class="form-control" id="obat_bpjs"
                                    placeholder="Total harga Obat">
                            </div>
                            <div class="form-group">
                                <input type="number" class="form-control" id="bmhp_bpjs"
                                    placeholder="Total harga BMHP">
                            </div>
                            <div class="form-group">
                                <input type="number" class="form-control" id="obatKronis_bpjs"
                                    placeholder="Total Harga Obat kronis">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer justify-content-end">
                        {{-- <button type="button" class="btn btn-primary">Simpan</button> --}}
                        <button type="button" class="btn btn-primary" onclick="simpanDataFarmasi();">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
