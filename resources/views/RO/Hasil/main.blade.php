@extends('Template.lte')

@section('content')
    @include('RO.Hasil.input')

    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/panzoom/panzoom.umd.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/panzoom/panzoom.css" />
    <script>
        var appUrlRo = @json($appUrlRo);

        async function cari() {
            Swal.fire({
                title: 'Loading',
                didOpen: () => {
                    Swal.showLoading();
                },
            });

            var norm = $('#norm').val().padStart(6, '0');

            try {
                const response = await fetch("/api/hasilRo", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        norm
                    }),
                });

                if (!response.ok) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Data tidak ditemukan',
                    });
                    return; // Exit if data not found
                }

                const data = await response.json();
                const foto = data.data;
                show(foto);
                Swal.close();
            } catch (error) {
                console.error("Terjadi kesalahan saat mencari data:", error);
            }
        }
    </script>
    <script>
        function show(foto) {
            const preview = document.getElementById('preview');
            preview.innerHTML = ''; // Clear existing items

            if (!Array.isArray(foto) || foto.length === 0) {
                preview.innerHTML =
                    '<div class="carousel-item active"><img src="placeholder.jpg" class="d-block w-100" alt="No images available" style="width: 18rem;"></div>';
                return;
            }

            foto.forEach((item, index) => {
                const imageUrl = `${appUrlRo}${item.foto}`;
                const card = `
                    <div class="col gallery">
                        <a data-toggle="modal" data-target="#exampleModal"onclick="openPanZoom('${imageUrl}')">
                        <div class="card m-2" style="cursor: pointer;" >
                            <div class="card-body" style="height: 25rem;">
                                <img src="${imageUrl}" class="card-img-top" alt="Image ${index + 1}" style="height: 100%; width: 100%; object-fit: cover;">
                            </div>
                            <div class="card-footer">
                                <h5 class="text-center">${item.norm} - ${item.nama}</h5>
                                <h5 class="text-center">Tanggal: ${item.tanggal}</h5>
                            </div>
                        </div>
                        </a>
                    </div>`;
                preview.insertAdjacentHTML('beforeend', card);
            });
        }

        function openPanZoom(imageUrl) {
            const modal = document.getElementById('exampleModal');
            const zoomedImage = document.getElementById('zoomed-image');
            const container = document.getElementById("myPanzoom");
            const options = {
                click: "toggleCover",
                Toolbar: {
                    display: ["zoomIn", "zoomOut"],
                },
            };

            zoomedImage.src = imageUrl;

            new Panzoom(container, options, {
                // Toolbar
            });

        }
    </script>


    <style>
        #myPanzoom {
            height: 500px;
        }


        .f-custom-controls {
            position: absolute;

            border-radius: 4px;
            overflow: hidden;
            z-index: 1;
        }

        .f-custom-controls.top-right {
            right: 16px;
            top: 16px;
        }

        .f-custom-controls.bottom-right {
            right: 16px;
            bottom: 16px;
        }

        .f-custom-controls button {
            width: 32px;
            height: 32px;
            background: none;
            border: none;
            margin: 0;
            padding: 0;
            background: #222;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .f-custom-controls svg {
            pointer-events: none;
            width: 18px;
            height: 18px;
            stroke: #fff;
            stroke-width: 2;
        }

        .f-custom-controls button[disabled] svg {
            opacity: 0.7;
        }

        [data-panzoom-action=toggleFS] g:first-child {
            display: flex
        }

        [data-panzoom-action=toggleFS] g:last-child {
            display: none
        }

        .in-fullscreen [data-panzoom-action=toggleFS] g:first-child {
            display: none
        }

        .in-fullscreen [data-panzoom-action=toggleFS] g:last-child {
            display: flex
        }
    </style>
@endsection
