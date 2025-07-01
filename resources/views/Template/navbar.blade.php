        <nav class="main-header navbar navbar-expand navbar-dark font-weight-bold mobile-navbar"
            style="background-color: #343a40;height: 53px;">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="http://rsparu.kkpm.local" target="_blank"" class="nav-link">RS Paru</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="https://kkpm.banyumaskab.go.id/administrator/auth" target="_blank"" class="nav-link">APPS
                        KOMINFO</a>
                </li>

                <li class="nav-item d-none d-sm-inline-block">
                    <a type="button" class="nav-link" data-toggle="modal" data-target="#modal-jadwal"
                        onclick="getJadwalDokter()">
                        Jadwal Dokter
                    </a>
                </li>
                <li class="nav-item d-none d-sm-inline-block" style="display: none">
                    <input type="text" class="form-control form-control-navbar" placeholder="Search" id='roleUser'
                        value="{{ Auth::user()->role }}" hidden>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto" style="color: rgba(255,255,255,.75);hover:color: rgba(255,255,255,1)">
                <!-- Navbar Search -->
                <li class="nav-item d-none d-sm-inline-block">
                    <a class="nav-link" href="{{ url('/users') }}">
                        <i class="fa-solid fa-user"></i>
                    </a>
                </li>
                <li class="nav-item form-inline">
                    <label type="text" id="waktu" class="font-weight-bold mb-0 mr-2"></label>
                </li>
                <li class="nav-item">
                    <div class="navbar-search-block">
                        <form class="form-inline">
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-navbar" type="search" placeholder="Search"
                                    aria-label="Search">
                                <div class="input-group-append">
                                    <button class="btn btn-navbar" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </li>

                <!-- Messages Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="fa-solid fa-user"></i> <b class="mx-1">{{ Auth::user()->name }}</b>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="{{ asset('img/user1.webp') }}" alt="User Avatar"
                                    class="img-size-50 mr-3 img-circle">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        <b>{{ Auth::user()->name }}</b>
                                        @php
                                            $email = Auth::user()->email;
                                            $email = explode('@', $email);
                                            $nip = $email[0];
                                        @endphp
                                        <br>
                                        <input style="border: 0;" id="user_nip" value="{{ $nip }}" readonly>
                                        <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">Wis Rampung Lik...?</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Logout
                        </a>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modal-password">
                            <i class="fa-solid fa-lock mr-2 text-gray-400"></i>
                            Ubah Password
                        </a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
            </ul>
        </nav>


        <div class="modal fade" id="modal-jadwal" tabindex="-1" aria-labelledby="modal-jadwalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-jadwalLabel">Jadwal Dokter</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body px-4">
                        <table id="jadwal_ruang_dokter" class="table table-bordered table-striped">
                            <thead class="bg-orange table-bordered">
                                <tr>
                                    <th>NO</th>
                                    <th>Nama Dokter</th>
                                    <th>Ruangan</th>
                                </tr>
                            </thead>
                            <tbody id="jadwal-tbody"></tbody>
                        </table>
                        <div id="loadingSpinner2" class="badge bg-warning text-wrap text-center z-3 loadingSpinner">
                            <i class="fa fa-spinner fa-spin"></i> Sedang Mencari data...
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function getJadwalDokter() {
                $("#loadingSpinner2").show();
                fetch('/api/jadwal/dokter/poli')
                    .then(response => response.json())
                    .then(data => {
                        let tbody = document.getElementById("jadwal-tbody");
                        tbody.innerHTML = ""; // Kosongkan sebelum diisi ulang

                        data.forEach((item, index) => {
                            let row = document.createElement("tr");
                            row.innerHTML = `
                                <td>${index + 1}</td>
                                <td>${item.admin_nama}</td>
                                <td>${item.loket_nama}</td>
                            `;
                            tbody.appendChild(row);
                        });
                    })
                    .catch(error => console.error('Error:', error))
                    .finally(() => {
                        $("#loadingSpinner2").hide();
                    });
            }
        </script>
