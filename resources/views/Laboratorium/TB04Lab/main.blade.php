{{-- @extends('layouts.layout') --}}
@extends('Template.lte')

@section('content')
    @include('Laboratorium.Hasil.antrian')
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

        let data = @json($data);
        // console.log("🚀 ~ data:", data)

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

        async function dataLab(pemeriksaan, notrans) {
            const analisResponse = await fetch("/api/analis", {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                },
            });

            if (!analisResponse.ok) {
                throw new Error(`HTTP error! Status: ${analisResponse.status}`);
            }

            const analisData = await analisResponse.json();

            const data = pemeriksaan.map((item, index) => ({
                no: index + 1,
                norm: item.norm,
                nmLayanan: item.pemeriksaan?.nmLayanan ?? "",
                petugas: item.petugas,
                hasiLab: item.hasil || "",
                ket: item.ket || "",
                idLab: item.idLab,
                idLayanan: item.pemeriksaan?.idLayanan ?? "",
                no_reg_lab: item.pemeriksaan?.no_reg_lab ?? "",
                no_iden_sediaan: item.pemeriksaan?.no_iden_sediaan ?? "25/K3302730/",
                kelas: item.pemeriksaan?.kelas ?? "",
                kdTind: item.pemeriksaan?.kdTind ?? "",
            }));


            const table = $("#inputHasil").DataTable({
                data: data,
                destroy: true,
                columns: [{
                        data: "norm",
                        render: (data) =>
                            `<p type="text" class="form-control-sm col hasil">${data}</p>`,
                    },

                    {
                        data: "nmLayanan",
                        render: (data, type, row) =>
                            `<p type="text" class="form-control-sm col hasil" id="layanan${row.idLayanan}" value="${row.idLayanan}" readonly>${data}</p>`,
                    },
                    {
                        data: "petugas",
                        render: (data, type, row) => {
                            let inputId = "analis" + row.idLab;
                            let inputField =
                                `<select id="${inputId}" class="form-control-sm col analis ">`;
                            inputField += `<option value="">--- Pilih Petugas ---</option>`;
                            analisData.forEach((petugas) => {
                                let selected = data === petugas.nip ? "selected" : "";
                                inputField +=
                                    `<option value="${petugas.nip}" ${selected}>${petugas.gelar_d} ${petugas.nama} ${petugas.gelar_b}</option>`;
                            });
                            inputField += "</select>";
                            return inputField;
                        },
                    },
                    {
                        data: "no_reg_lab",
                        render: (data, type, row) =>
                            `<input type="text" class="form-control-sm col hasil" id="no_reg_lab${row.idLab}" value="${data}">`,
                    },
                    {
                        data: "no_iden_sediaan",
                        render: (data, type, row) =>
                            `<input type="text" class="form-control-sm col hasil" id="no_iden_sediaan${row.idLab}" value="${data}">`,
                    },
                    {
                        data: "hasiLab",
                        render: (data, type, row) => {
                            let hasilLabHtml = "";
                            // switch (row.kelas) {
                            switch (row.kdTind) {
                                case "94":
                                    hasilLabHtml =
                                        `<select class="form-control-sm col hasil" id="hasil${row.idLab}">`;
                                    hasilLabHtml += `<option value="">--Pilih Hasil--</option>`;
                                    hasilLabHtml += `<option value="Hasil di SITB" ${
                                data === "Hasil di SITB" ? "selected" : ""
                            }>Hasil di SITB (TCM)</option>`;
                                    hasilLabHtml += `<option value="Negatif" ${
                                data === "Negatif" ? "selected" : ""
                            }>Negatif (BTA/TCM)</option>`;
                                    hasilLabHtml += `<option value="+1" ${
                                data === "+1" ? "selected" : ""
                            }>+ 1 (BTA)</option>`;
                                    hasilLabHtml += `<option value="+2" ${
                                data === "+2" ? "selected" : ""
                            }>+ 2 (BTA)</option>`;
                                    hasilLabHtml += `<option value="+3" ${
                                data === "+3" ? "selected" : ""
                            }>+ 3 (BTA)</option>`;
                                    hasilLabHtml += `<option value="+1-9" ${
                                data === "+1-9" ? "selected" : ""
                            }>+ 1-9 (BTA)</option>`;
                                    hasilLabHtml += `</select>`;
                                    break;
                                case "93":
                                    hasilLabHtml =
                                        `<select class="form-control-sm col hasil" id="hasil${row.idLab}">`;
                                    hasilLabHtml += `<option value="">--Pilih Hasil--</option>`;
                                    hasilLabHtml += `<option value="NR" ${
                                data === "NR" ? "selected" : ""
                            }>Non Reaktif (HIV)</option>`;
                                    hasilLabHtml += `<option value="Reaktif" ${
                                data === "Reaktif" ? "selected" : ""
                            }>Reaktif (HIV)</option>`;
                                    hasilLabHtml += `<option value="Negatif" ${
                                data === "Negatif" || data === "NEGATIF"
                                    ? "selected"
                                    : ""
                            }>Negatif (Sifilis)</option>`;
                                    hasilLabHtml += `<option value="Positif" ${
                                data === "Positif" ? "selected" : ""
                            }>Positif (Sifilis)</option>`;
                                    hasilLabHtml += `</select>`;
                                    break;
                                case "99":
                                    hasilLabHtml =
                                        `<select class="form-control-sm col hasil" id="hasil${row.idLab}">`;
                                    hasilLabHtml += `<option value="">--Pilih Hasil--</option>`;
                                    hasilLabHtml += `<option value="A" ${
                                data === "A" ? "selected" : ""
                            }>A</option>`;
                                    hasilLabHtml += `<option value="B" ${
                                data === "B" ? "selected" : ""
                            }>B</option>`;
                                    hasilLabHtml += `<option value="AB" ${
                                data === "AB" ? "selected" : ""
                            }>AB</option>`;
                                    hasilLabHtml += `<option value="O" ${
                                data === "O" ? "selected" : ""
                            }>O</option>`;
                                    hasilLabHtml += `</select>`;
                                    break;
                                case "97":
                                    hasilLabHtml =
                                        `<select class="form-control-sm col hasil" id="hasil${row.idLab}">`;
                                    hasilLabHtml += `<option value="">--Pilih Hasil--</option>`;
                                    hasilLabHtml += `<option value="IGG NR, IGM NR" ${
                                data === "IGG NR, IGM NR" ? "selected" : ""
                            }>IGG dan IGM NR</option>`;
                                    hasilLabHtml += `<option value="IGG R, IGM R" ${
                                data === "IGG R, IGM R" ? "selected" : ""
                            }>IGG dan IGM R</option>`;
                                    hasilLabHtml += `<option value="IGG NR, IGM R" ${
                                data === "IGG NR, IGM R" ? "selected" : ""
                            }>IGG NR dan IGM R</option>`;
                                    hasilLabHtml += `<option value="IGG R, IGM NR" ${
                                data === "IGG R, IGM NR" ? "selected" : ""
                            }>IGG R dan IGM NR</option>`;

                                    hasilLabHtml += `</select>`;
                                    break;
                                case "98":
                                    hasilLabHtml =
                                        `<input type="text" class="form-control-sm col hasil" id="hasil${row.idLab}" value="Terlampir">`;
                                    break;
                                default:
                                    hasilLabHtml =
                                        `<input type="text" class="form-control-sm col hasil" id="hasil${row.idLab}" value="${data}">`;
                            }
                            return hasilLabHtml;
                        },
                    },
                    {
                        data: "ket",
                        render: (data, type, row) =>
                            `<input type="text" class="form-control-sm col hasil" id="ket${row.idLab}" value="${data}">`,
                    },
                ],
                order: [
                    [2, "desc"]
                ],
                scrollY: "320px",
                scrollCollapse: true,
                paging: false,
            });
            scrollToInputSection();
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
                        data: "pemeriksaan"
                    },
                    {
                        data: "alamat",
                        className: "col-2"
                    },
                    {
                        data: "nama_dokter",
                        className: "col-3"
                    },
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

            $("#antrianSudah").DataTable({
                data: sudahTransakasi,
                columns: [{
                        data: "aksi",
                        className: "col-1"
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
                        data: "pemeriksaan"
                    },
                    {
                        data: "alamat",
                        className: "col-2"
                    },
                    {
                        data: "nama_dokter",
                        className: "col-3"
                    },
                ],
                paging: true,
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    [5, 10, 25, 50, "All"],
                ],
                pageLength: 3,
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

            let belumTransaksi = data.filter(function(item) {
                return (
                    item.status === "Belum Lengkap" ||
                    item.status === "Belum"
                );
            });

            let sudahTransaksi = data.filter(function(item) {
                return item.status === "Selesai";
            });

            antrianBelum(belumTransaksi);
            antrianSudah(sudahTransaksi);
        });
    </script>
@endsection
