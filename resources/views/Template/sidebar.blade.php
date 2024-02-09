            <aside class="main-sidebar sidebar-dark-navy elevation-4">
                <!-- Brand Logo -->
                <a href="#" class="brand-link">
                    <img src="{{ asset('img/LOGO KKPM.jpg') }}" alt="KKPM Logo"
                        class="bg bg-light brand-image img-circle elevation-3" width="30" height="30">
                    <span class="brand-text font-weight-light text-md"><b>KKPM</b></span>
                </a>

                <!-- Sidebar -->
                <div class="sidebar bg-navy font-weight-bold">
                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                            data-accordion="false">
                            <!-- Dashboard -->
                            <li class="nav-item">
                                <a href="{{ url('home') }}" class="nav-link">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p>Dashboard</p>
                                </a>
                            </li>

                            <!-- IGD Section -->
                            @if (auth()->user()->role === 'igd' || auth()->user()->role === 'admin' || auth()->user()->role === 'dots')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/igd') }}" id="masukIGD">
                                        <i class="fa-solid fa-truck-medical nav-icon"></i>
                                        <p>IGD</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/gudangIGD') }}">
                                        <i class="fa-solid fa-database nav-icon"></i>
                                        <p>Master IGD</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/askep') }}">
                                        <i class="fa-solid fa-file-pen nav-icon"></i>
                                        <p>ASKEP</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/report') }}">
                                        <i class="fa-solid fa-chart-column nav-icon"></i>
                                        <p>Report Petugas IGD</p>
                                    </a>
                                </li>
                            @endif
                            <!-- Dots Section -->
                            @if (auth()->user()->role === 'admin' || auth()->user()->role === 'dots')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/dots') }}">
                                        <i class="fa-solid fa-notes-medical nav-icon"></i>
                                        <p>Dots Center</p>
                                    </a>
                                </li>
                            @endif

                            <!-- Farmasi Section -->
                            @if (auth()->user()->role === 'farmasi' || auth()->user()->role === 'admin')
                                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Farmasi">
                                    <a class="nav-link" href="{{ url('/farmasi') }}">
                                        <i class="fa-solid fa-pills nav-icon"></i>
                                        <p>Farmasi</p>
                                    </a>
                                </li>
                                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Farmasi">
                                    <a class="nav-link" href="{{ url('/logFarmasi') }}">
                                        <i class="fa-solid fa-chart-column nav-icon"></i>
                                        <p>Riwayat Transaksi Farmasi</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/gudangFarmasi') }}">
                                        <i class="fa-solid fa-database nav-icon"></i>
                                        <p>Master Farmasi</p>
                                    </a>
                                </li>
                            @endif

                            <!-- lab Section -->
                            @if (auth()->user()->role === 'lab' || auth()->user()->role === 'admin')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/lab') }}">
                                        <i class="fa-regular fa-id-card nav-icon"></i>
                                        <p>Pendaftaran Laboratorium</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/hasilLab') }}">
                                        <i class="fa-solid fa-microscope nav-icon"></i>
                                        <p>Input Hasil Laboratorium</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/riwayatlab') }}">
                                        <i class="fa-solid fa-chart-column nav-icon"></i>
                                        <p>Laporan Laboratorium</p>
                                    </a>
                                </li>
                            @endif
                            <!-- Kasir Section -->
                            @if (auth()->user()->role === 'kasir' || auth()->user()->role === 'admin')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/kasir') }}">
                                        <i class="fa-solid fa-cash-register nav-icon"></i>
                                        <p>Kasir</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </nav>

                    <!-- /.sidebar-menu -->
                </div>
                <!-- /.sidebar -->
            </aside>
