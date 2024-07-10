// resources/js/scanner.js
import Quagga from "quagga";

document.addEventListener("DOMContentLoaded", function () {
    const startScanButton = document.getElementById("start-scan");
    const closeScanButton = document.getElementById("close-scan");
    const scannerContainer = document.getElementById("scanner-container");

    startScanButton.addEventListener("click", function () {
        startScanner();
    });

    closeScanButton.addEventListener("click", function () {
        Quagga.stop();
    });

    function startScanner() {
        Quagga.init(
            {
                inputStream: {
                    name: "Live",
                    type: "LiveStream",
                    target: scannerContainer, // Lokasi elemen HTML untuk menampilkan video
                    constraints: {
                        facingMode: "environment", // Menggunakan kamera belakang jika tersedia
                    },
                },
                decoder: {
                    readers: [
                        "code_128_reader",
                        "ean_reader",
                        "ean_8_reader",
                        "code_39_reader",
                        "code_39_vin_reader",
                        "codabar_reader",
                        "upc_reader",
                        "upc_e_reader",
                        "i2of5_reader",
                        "msi_reader",
                    ],
                },
            },
            function (err) {
                if (err) {
                    console.error(err);
                    return;
                }
                console.log("Initialization finished. Ready to start");
                Quagga.start();
            }
        );

        Quagga.onDetected(function (data) {
            console.log("Kode batang terdeteksi:", data.codeResult.code);
            alert("Kode batang terdeteksi: " + data.codeResult.code);
            Quagga.stop(); // Hentikan pemindaian setelah kode batang ditemukan
            const modal = bootstrap.Modal.getInstance(modalScan);
            modal.hide(); // Sembunyikan modal setelah pemindaian selesai
        });
    }
});
