function searchByRM(norm) {
    $.ajax({
        url: "/api/riwayat",
        type: "post",
        data: {
            norm: norm,
        },
        success: function (response) {
            // Mendapatkan data dari respons JSON
            var noRM = response[0].norm; // Menggunakan indeks 0 karena respons adalah array
            var nama = response[0].biodata.nama;
            var notrans = response[0].notrans;
            var layanan = response[0].kelompok.kelompok;
            var dokter = response[0].petugas.p_dokter_poli;
            var dokter = response[0].poli.tgltrans;
            var alamat = `${response[0].biodata.kelurahan}, ${response[0].biodata.rtrw}, ${response[0].biodata.kecamatan}, ${response[0].biodata.kabupaten}`;
            var asktind = "";
            if (response[0].poli.nebulizer)
                asktind += "Nebu: " + response[0].poli.nebulizer + "\n";
            if (response[0].poli.oksigenasi)
                asktind += "O2: " + response[0].poli.oksigenasi + "\n";
            if (response[0].poli.injeksi)
                asktind += "Injeksi: " + response[0].poli.injeksi + "\n";
            if (response[0].poli.infus)
                asktind += "Infus: " + response[0].poli.infus + "\n";
            if (response[0].poli.mantoux) asktind += "Mantoux" + ", ";
            if (response[0].poli.ekg) asktind += "EKG" + ", ";
            if (response[0].poli.spirometri) asktind += "Spirometri";
            // Dapatkan data lainnya dari respons JSON sesuai kebutuhan

            // Mengisikan data ke dalam elemen-elemen HTML
            $("#asktind").val(asktind);
            $("#norm").val(noRM);
            $("#nama").val(nama);
            $("#alamat").val(alamat);
            $("#notrans").val(notrans);
            $("#tgltrans").val(tgltrans);
            $("#layanan").val(layanan);
            $("#dokter").val(dokter);
            $("#dokter").trigger("change");

            dataTindakan();
            // Mengisi elemen-elemen lainnya sesuai kebutuhan
        },
        error: function (xhr) {
            // Handle error
        },
    });
}
