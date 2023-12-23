<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Radiologi RS Paru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous" />
    <link rel="stylesheet" type="text/css" href="style.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  </head>
  <body>
    <?php
     $norm = isset($_GET['norm']) ? $_GET['norm'] : '';
     $pesan = isset($_GET['pesan']) ? $_GET['pesan'] : '';
    include "navbar.php";
    ?>

    <!-- From Upload -->
    <section class="container-sm" id="upload">
      <div class="container-sm">
      <?php 
        $norm = isset($_GET['norm']) ? $_GET['norm'] : '';
      ?>
      <div class="ms-5 me-5 upload">
        <div>
          <h3 class="display-6 text-center fw-bold">Form Upload Rontgen Thorax</h3>
        </div>
        <div class="cara ms-2 mt-3 mb-3 text-center"></div>
        <p>Selamat datang di halaman upload Rontgen. Untuk melakukan upload data:</p>
        <ol>
          <li>Isikan Nomor RM lalu tekan Enter. Jika nama tidak muncul, isikan nama secara manual.</li>
          <li>Selanjutnya, pilih foto yang akan diupload, lalu tekan tombol "Kirim".</li>
        </ol>
      </div>
      <div>
        <div class="container-md mt-3 mb-5">
          <form id="myForm">
            <div class="row">
              <div class="col mb-5">
                <!-- <label class="form-label" for="tanggal">Tanggal:</label> -->
                <input type="date" id="tanggal" name="tanggal" class="form-control" required title="Tanggal RO" />
              </div>
              <div class="col mb-5">
                <!-- <label class="form-label">No RM</label> -->
                <input type="text" name="norm" id="norm" class="form-control" value="<?php echo $norm; ?>" required placeholder=" NO RM" title="No RM" />
              </div>
              <div class="mb-5">
                <!-- <label class="form-label">Nama</label> -->
                <input type="text" name="nama" id="nama" class="form-control"  required placeholder=" Nama" title="Nama" />
              </div>
              <div class="col-md-5 mb-5">
                <!-- <label class="form-label">Foto</label> -->
                <input type="file" name="foto" id="fotoInput" class="form-control" value="<?php echo $foto; ?>" required placeholder=" Pilih Foto" title="Foto Ro" />
              </div>
              <div class="col-auto">
                <button type="button" id="submitButton" class="btn btn-success mx-auto submit-button">Kirim</button>
              </div>
            </div>
          </form>
        </div>
      </div>
      </div>
    </section>
    <!-- akhir Form Upload -->

    <!-- Data RO -->
    <!-- <section class="dataro pt-5" id="dataro">
      <div class="container">
        <div class="col-md-12 row justify-content-center">
          <div class="col-md-0 mt-4">
            <h2 class="text-center fw-bold pb-2">Data Rontgn Thorax</h2>
            <div class="container col-auto">
              <form method="GET" action="index.php">
                <label for="search">Cari No RM:</label>
                <input type="text" class="form" id="search" name="search" />
                <input type="submit" class="btn btn-outline-info mb-2 text-dark" value="Cari" />
                <a href="#upload" class="btn btn-outline-success mb-2 text-dark">Upload</a>
              </form>
              <div>
                <?php include "data_ro.php"; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section> -->
    <!-- Akhir Data RO -->

    <footer>
      <p class="text-center mt-6 pt-6">Hak Cipta &copy; 2023</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script>
      // // // reload
      // window.onload = function () {
      //   // Set search parameter to 0 when page is loaded
      //   history.replaceState(null, null, window.location.pathname);
      // };

      // Mendapatkan elemen input tanggal, nomor RM, dan nama
      var inputTanggal = document.getElementById("tanggal");
      var inputNorm = document.getElementById("norm");
      var inputNama = document.getElementById("nama");
      inputNama.setAttribute("readonly", true);
 inputNorm.focus();
      //Mengatur tanggal hari ini sebagai nilai awal tanggal
      var today = new Date().toISOString().substr(0, 10);
      inputTanggal.value = today;

      // mancari nama
      // Ketika tombol "Enter" ditekan pada input nomor RM
      inputNorm.addEventListener("keypress", function (event) {
        if (event.key === "Enter") {
          event.preventDefault();
          cekNama();
          formatNoRM();
        }
      });

      // Fungsi untuk melakukan pemeriksaan nama
      function cekNama() {
        var normValue = inputNorm.value;

        // Lakukan permintaan ke server untuk memeriksa nomor RM
        // Ganti URL dengan URL yang sesuai di server Anda
        fetch("cek_nama.php?norm=" + normValue)
          .then((response) => response.json())
          .then((data) => {
            if (data.nama) {
              // Jika nama ditemukan, isi kolom nama
              inputNama.value = data.nama;
              inputNama.setAttribute("readonly", true);
              inputNama.focus();
            } else {
              // Jika nama tidak ditemukan, aktifkan kolom nama
              inputNama.value = "";
              inputNama.removeAttribute("readonly");
              inputNama.focus();
            }
          })
          .catch((error) => console.error(error));
      }
      function formatNoRM() {
        var nomorRMInput = document.getElementById("norm");
        var nomorRMValue = nomorRMInput.value;

        // Jika panjang nomor RM kurang dari 6, tambahkan angka 0 di depannya
        while (nomorRMValue.length < 6) {
          nomorRMValue = "0" + nomorRMValue;
        }

        nomorRMInput.value = nomorRMValue;
      }
    </script>
    <script>
      $(document).ready(function () {
        var pesan = "<?php echo $pesan; ?>";
        if (pesan !== "") {
          alert(pesan);
        }

        // Menangkap tombol "Kirim" diklik
        $("#submitButton").click(function () {
          var formData = new FormData($("#myForm")[0]); // Mengumpulkan data form
          $.ajax({
            url: "upload.php", // Ganti dengan path ke script PHP yang memproses data
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
              if (response.success) {
                alert("Data berhasil diunggah.");
                //Setel kembali nilai tanggal setelah berhasil mengunggah data
                $("#norm").val("");
                $("#nama").val("");
                $("#fotoInput").val("");
              } else {
                alert("Gagal mengunggah data.");
              }
            },
          });
        });
      });
    </script>
  </body>
</html>
