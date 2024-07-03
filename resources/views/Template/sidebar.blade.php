            <aside class="main-sidebar sidebar-dark-navy elevation-4">
                <!-- Brand Logo -->
                <a href="" class="brand-link">
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
                            <li class="nav-header">TRANSAKSI</li>
                            <!-- IGD Section -->
                            <li class="nav-item">
                                <a href="" class="nav-link">
                                    <i class="fa-solid fa-truck-medical nav-icon"></i>
                                    <p>
                                        Ruang Tindakan
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview" style="display: none;">
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ url('/igd') }}" id="masukIGD">
                                            <i class="nav-icon fas fa-edit"></i>
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
                                </ul>
                            </li>

                            {{-- @if (auth()->user()->role === 'igd' || auth()->user()->role === 'admin' || auth()->user()->role === 'dots')
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
                            @endif --}}
                            <li class="nav-item">
                                <a href="" class="nav-link">
                                    <i class="nav-icon fa-solid fa-hand-holding-medical"></i>
                                    <p>
                                        Dots Center
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview" style="display: none;">
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ url('/dots') }}">
                                            <i class="fa-solid fa-notes-medical nav-icon"></i>
                                            <p>Transaksi Dots Center</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ url('/dots_master') }}">
                                            <i class="fa-solid fa-notes-medical nav-icon"></i>
                                            <p>Master Dots Center</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            {{-- @if (auth()->user()->role === 'admin' || auth()->user()->role === 'dots')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/dots') }}">
                                        <i class="fa-solid fa-notes-medical nav-icon"></i>
                                        <p>Dots Center</p>
                                    </a>
                                </li>
                            @endif --}}

                            <!-- Farmasi Section -->
                            <li class="nav-item">
                                <a href="" class="nav-link">
                                    <i class="fa-solid fa-pills nav-icon"></i>
                                    <p>
                                        Farmasi
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview" style="display: none;">
                                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Farmasi">
                                        <a class="nav-link" href="{{ url('/farmasi') }}">
                                            <i class="nav-icon fas fa-edit"></i>
                                            <p>Farmasi</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ url('/gudangFarmasi') }}">
                                            <i class="fa-solid fa-database nav-icon"></i>
                                            <p>Master Farmasi</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            {{-- @if (auth()->user()->role === 'farmasi' || auth()->user()->role === 'admin')
                                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Farmasi">
                                    <a class="nav-link" href="{{ url('/farmasi') }}">
                                        <i class="nav-icon fas fa-edit"></i>
                                        <p>Farmasi</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/gudangFarmasi') }}">
                                        <i class="fa-solid fa-database nav-icon"></i>
                                        <p>Master Farmasi</p>
                                    </a>
                                </li>
                            @endif --}}
                            <!-- lab Section -->
                            <li class="nav-item">
                                <a href="" class="nav-link">
                                    <i class="fa-solid fa-microscope nav-icon"></i>
                                    <p>
                                        Laboratorium
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview" style="display: none;">
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ url('/lab') }}">
                                            <i class="fa-regular fa-id-card nav-icon"></i>
                                            <p>Pendaftaran Laboratorium</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ url('/hasilLab') }}">
                                            <i class="nav-icon fas fa-edit"></i>
                                            <p>Input Hasil Laboratorium</p>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ url('/masterLab') }}">
                                            <i class="fa-solid fa-database nav-icon"></i>
                                            <p>Master Laboratorium</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            {{-- @if (auth()->user()->role === 'lab' || auth()->user()->role === 'admin')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/lab') }}">
                                        <i class="fa-regular fa-id-card nav-icon"></i>
                                        <p>Pendaftaran Laboratorium</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/hasilLab') }}">
                                        <i class="nav-icon fas fa-edit"></i>
                                        <p>Input Hasil Laboratorium</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/masterLab') }}">
                                        <i class="fa-solid fa-database nav-icon"></i>
                                        <p>Master Laboratorium</p>
                                    </a>
                                </li>
                            @endif --}}
                            <!-- RO Section -->
                            <li class="nav-item">
                                <a href="" class="nav-link">
                                    <i class="nav-icon fa-solid fa-x-ray"></i>
                                    <p>
                                        Radiologi
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview" style="display: none;">
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ url('/ro') }}">
                                            {{-- <i class="fa-regular fa-id-card nav-icon"></i> --}}
                                            <i class="fa-regular nav-icon fas fa-edit"></i>
                                            <p>Input Radiologi</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ url('/masterRo') }}">
                                            <i class="fa-solid fa-database nav-icon"></i>
                                            <p>Master Radiologi</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            {{-- @if (auth()->user()->role === 'ro' || auth()->user()->role === 'admin')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/ro') }}">
                                        <i class="fa-regular fa-id-card nav-icon"></i>
                                        <i class="fa-regular nav-icon fas fa-edit"></i>
                                        <p>Input Radiologi</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/masterRo') }}">
                                        <i class="fa-solid fa-database nav-icon"></i>
                                        <p>Master Radiologi</p>
                                    </a>
                                </li>
                            @endif --}}
                            <!-- Kasir Section -->
                            @if (auth()->user()->role === 'kasir' || auth()->user()->role === 'admin')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/kasir') }}">
                                        <i class="fa-solid fa-cash-register nav-icon"></i>
                                        <p>Kasir</p>
                                    </a>
                                </li>
                            @endif

                            {{-- LAPORAN --}}
                            <li class="nav-header">LAPORAN</li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/report') }}">
                                    <i class="fa-solid fa-chart-column nav-icon"></i>
                                    <p>Report Petugas</p>
                                </a>
                            </li>
                            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Farmasi">
                                <a class="nav-link" href="{{ url('/logFarmasi') }}">
                                    <i class="fa-solid fa-chart-column nav-icon"></i>
                                    <p>Riwayat Transaksi Farmasi</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/riwayatRo') }}">
                                    <i class="fa-solid fa-chart-column nav-icon"></i>
                                    <p>Laporan Radiologi</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/riwayatLab') }}">
                                    <i class="fa-solid fa-chart-column nav-icon"></i>
                                    <p>Laporan Laboratorium</p>
                                </a>
                            </li>
                        </ul>
                    </nav>

                    <!-- /.sidebar-menu -->
                </div>
                <!-- /.sidebar -->
            </aside>
