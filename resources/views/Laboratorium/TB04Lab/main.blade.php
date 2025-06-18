{{-- @extends('layouts.layout') --}}
@extends('Template.lte')

@section('content')
    @include('Laboratorium.TB04Lab.antrian')
    @include('Laboratorium.Hasil.input')


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

        document.getElementById("statusSwitch").addEventListener("change", function() {
            var statusLabel = document.getElementById("statusLabel");

            if (this.checked) {
                statusLabel.textContent = "Selesai";
                keterangan = "Selesai";
            } else {
                statusLabel.textContent = "Belum";
                keterangan = "Belum";
            }
            console.log("🚀 ~ status:", keterangan); // This will log the correct status
        });

        function formatWaktu(dateTimeString) {
            const [datePart, timePart] = dateTimeString.split(" ");
            const [year, month, day] = datePart.split("-");
            const formattedDate = `${day}-${month}-${year}`;
            return `${formattedDate} ${timePart}`;
        }

        async function cariTsLab(norm, tgl, task) {
            formatNorm($("#norm"));
            norm = norm || $("#norm").val();
            tgl = tgl || $("#tanggal").val();
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
                const response = await fetch("/api/cariTsLab", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify(requestData),
                });

                if (!response.ok) {
                    if (response.status == 404) {
                        // searchRMObat(norm);
                        // cariKominfo(norm, tgl);
                        Swal.fire({
                            icon: "error",
                            title: "Pasien dengan NO RM : " +
                                norm +
                                " tidak ditemukan di pendaftaran laboratorium...!!!",
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Terjadi kesalahan saat mengambil data pasien...!!!",
                        });
                        throw new Error("Network response was not ok");
                    }
                } else {
                    const data = await response.json();
                    if (task == "tampil") {
                        $("#norm").val(data.norm);
                        $("#nama").val(data.nama);
                        $("#nik").val(data.nik);
                        $("#alamat").val(data.alamat);
                        $("#notrans").val(data.notrans);
                        $("#layanan").val(data.layanan);
                        $("#no_reg_lab_next").val(data.no_reg_lab_next);

                        $("#no_reg_lab_next").html(data.no_reg_lab_next);
                        $("#dokter").val(data.dokter).trigger("change");
                        $("#analis").val(data.petugas).trigger("change");
                        var rawDateTime = data.waktu_selesai;
                        if (rawDateTime !== null) {
                            const waktuSelesai = formatWaktu(rawDateTime);
                            console.log("🚀 ~ cariTsLab ~ waktuSelesai:", waktuSelesai);
                            $("#waktuSelesai").text(waktuSelesai);
                            $("#divSwitch").hide();
                        }
                        const notrans = data.notrans;
                        // var pemeriksaan = data.pemeriksaan;
                        var pemeriksaan = data.pemeriksaan.filter(item => [130, 131, 214].includes(item.pemeriksaan
                            ?.idLayanan));

                        dataLab(pemeriksaan);
                    } else {
                        cetak(data);
                    }
                    Swal.close();
                }
            } catch (error) {
                console.error("Terjadi kesalahan saat mencari data:", error);
                Swal.fire({
                    icon: "error",
                    title: "Terjadi kesalahan saat mencari data...!!! /n" + error,
                });
            }
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

        function simpan() {
            const norm = $("#norm").val();
            const notrans = $("#notrans").val();
            const tglTrans = $("#tgltrans").val();

            if (!norm || !notrans) {
                const dataKurang = [];
                if (!norm) dataKurang.push("No RM");
                if (!notrans) dataKurang.push("Nomor Transaksi");

                Swal.fire({
                    icon: "error",
                    title: `Data Tidak Lengkap...!!! ${dataKurang.join(
                ", "
            )} Belum Diisi`,
                });
                return;
            }

            const table = $("#inputHasil").DataTable();
            const dataRows = table.rows().data();

            const dataTerpilih = dataRows
                .map((row) => ({
                    idLab: row.idLab,
                    idLayanan: row.idLayanan,
                    norm: norm,
                    notrans: notrans,
                    hasil: $("#hasil" + row.idLab).val(),
                    petugas: $("#analis" + row.idLab).val(),
                    ket: $("#ket" + row.idLab).val(),
                    no_reg_lab: $("#no_reg_lab" + row.idLab).val(),
                    no_iden_sediaan: $("#no_iden_sediaan" + row.idLab).val(),
                    tgl_hasil: $("#tgl_hasil" + row.idLab).val(),
                    alasan_periksa: $("#alasan_periksa" + row.idLab).val(),
                    kode_tcm: $("#kode_tcm" + row.idLab).val(),
                }))
                .toArray();

            fetch("/api/addHasilLab", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        dataTerpilih: dataTerpilih,
                        keterangan: keterangan,
                        tglTrans: tglTrans,
                    }),
                })
                .then((response) => {
                    if (!response.ok) throw new Error("Network response was not ok");
                    return response.json();
                })
                .then((data) => {
                    Swal.fire({
                        icon: "success",
                        title: "Data berhasil tersimpan...!!!",
                    });
                    resetForm("Data berhasil tersimpan...!!!");
                })
                .catch((error) => {
                    console.error(
                        "There has been a problem with your fetch operation:",
                        error
                    );
                    Swal.fire({
                        icon: "error",
                        title: `There has been a problem with your fetch operation: ${error}`,
                    });
                });
        }

        function resetForm(message) {
            document.getElementById("form_identitas").reset();

            document.getElementById("statusSwitch").checked = false; // Uncheck the switch
            document.getElementById("statusLabel").textContent = "Belum"; // Update the text
            keterangan = "Belum";

            if ($.fn.DataTable.isDataTable("#inputHasil")) {
                let tableTrans = $("#inputHasil").DataTable();
                tableTrans.clear().destroy();
            }
            Swal.fire({
                icon: "info",
                title: message + "\n Maturnuwun...!!!",
            });

            document.getElementById("tgltrans").value = new Date()
                .toISOString()
                .split("T")[0];
            antrian();

            $("#waktuSelesai").text("-");
            $("#divSwitch").show();
        }

        function antrian() {
            $("#loadingSpinner").show();
            const tgl = $("#tanggal").val();
            if ($.fn.DataTable.isDataTable("#antrianBelum, #antrianSudah")) {
                $("#antrianBelum, #antrianSudah").DataTable().destroy();
            }

            $.ajax({
                url: "/api/tb04/antrian/" + tgl,
                type: "GET",
                success: function(response) {
                    $("#loadingSpinner").hide();
                    var data = response;

                    if (Array.isArray(data)) {
                        var belumTransaksi = data.filter(function(item) {
                            return (
                                item.status === "Belum Lengkap" ||
                                item.status === "Belum"
                            );
                        });

                        var sudahTransakasi = data.filter(function(item) {
                            return item.status === "Lengkap";
                        });

                        antrianBelum(belumTransaksi);
                        antrianSudah(sudahTransakasi);
                    } else {
                        console.error("Invalid data format:", data);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                },
            });
        }

        function antrianBelum(belumTransaksi) {
            console.log("🚀 ~ antrianBelum ~ belumTransaksi:", belumTransaksi)

            $("#antrianBelum").DataTable({
                data: belumTransaksi,
                columns: [{
                        data: "aksi"
                    },
                    {
                        data: "status",
                        className: "text-center",
                        render: function(data) {
                            var backgroundColor =
                                data === "Belum Lengkap" ? "warning" : "danger";
                            return `<div class="badge badge-${backgroundColor}">${data}</div>`;
                        },
                    },
                    {
                        data: "tanggal"
                    },
                    {
                        data: "layanan"
                    },
                    {
                        data: "norm"
                    },
                    {
                        data: "nama",
                        className: "col-1"
                    },
                    {
                        data: "alamat",
                        className: "col-2"
                    },
                    {
                        data: "tb04.0.no_reg_lab"
                    },
                    {
                        data: "tb04.0.kode_tcm"
                    },
                    {
                        data: "tb04.0.no_iden_sediaan"
                    },
                    {
                        data: "tb04.0.alasan_periksa"
                    },
                    {
                        data: "pemeriksaan"
                    },

                    // {
                    //     data: "nama_dokter",
                    //     className: "col-3"
                    // },
                ],
                paging: true,
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    [5, 10, 25, 50, "All"],
                ],
                pageLength: 5,
                order: [
                    [1, "asc"]
                ],
                responsive: true,
            });
        }

        function antrianSudah(sudahTransakasi) {
            console.log("🚀 ~ antrianSudah ~ sudahTransakasi:", sudahTransakasi)

            $("#antrianSudah").DataTable({
                data: sudahTransakasi,
                columns: [{
                        data: "aksi",
                        widh: "15px"
                    },
                    {
                        data: "status",
                        className: "text-center",
                        render: function(data) {
                            var backgroundColor = "success";
                            return `<div class="badge badge-${backgroundColor}">${data}</div>`;
                        },
                    },
                    {
                        data: "tanggal"
                    },
                    {
                        data: "layanan"
                    },
                    {
                        data: "norm"
                    },
                    {
                        data: "nama",
                        className: "col-1"
                    },
                    {
                        data: "alamat",
                        className: "col-2"
                    },
                    {
                        data: "tb04.0.no_reg_lab"
                    },
                    {
                        data: "tb04.0.kode_tcm"
                    },
                    {
                        data: "tb04.0.no_iden_sediaan"
                    },
                    {
                        data: "tb04.0.alasan_periksa"
                    },
                    {
                        data: "pemeriksaan"
                    },

                    // {
                    //     data: "nama_dokter",
                    //     className: "col-3"
                    // },
                ],
                paging: true,
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    [5, 10, 25, 50, "All"],
                ],
                pageLength: 5,
                order: [
                    [1, "asc"]
                ],
                responsive: true,
            });
        }


        $(document).ready(function() {
            setTodayDate();
            // antrian();
            $("#dataTrans").on("click", ".delete", function(e) {
                e.preventDefault();
                let idLab = $(this).data("id");
                let layanan = $(this).data("layanan");
                deletLab(idLab, layanan);
            });

            antrian();
        });
    </script>
@endsection
