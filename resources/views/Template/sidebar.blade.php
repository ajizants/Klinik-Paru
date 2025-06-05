<aside class="main-sidebar sidebar-dark-navy elevation-4">
    <!-- Brand Logo -->
    <a href="" class="brand-link">
        <img src="{{ asset('img/LOGO KKPM.jpg') }}" alt="KKPM Logo" class="bg bg-light brand-image img-circle elevation-3"
            width="30" height="30">
        <span class="brand-text font-weight-light text-md"><b>KKPM</b></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar font-weight-bold">
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
                <!-- Display Section -->
                <li class="nav-item">
                    <a href="" class="nav-link">
                        <i class="fa-solid fa-desktop nav-icon"></i>
                        <p>Display <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                        <li class="nav-item ml-4">
                            <a class="nav-link" target="_blank" href="{{ url('/verif/2') }}">
                                <i class="fa-solid fa-tv nav-icon"></i>
                                <p>Ambil Antrian</p>
                            </a>
                        </li>
                        <li class="nav-item ml-4">
                            <a class="nav-link" target="_blank" href="{{ url('display/loket') }}">
                                <i class="fa-solid fa-tv nav-icon"></i>
                                <p>Display Loket</p>
                            </a>
                        </li>

                        <li class="nav-item ml-4">
                            <a class="nav-link" target="_blank" href="{{ url('display/tensi') }}">
                                <i class="fa-solid fa-tv nav-icon"></i>
                                <p>Display Tensi</p>
                            </a>
                        </li>

                        <li class="nav-item ml-4">
                            <a class="nav-link" target="_blank" href="{{ url('display/lab') }}">
                                <i class="fa-solid fa-tv nav-icon"></i>
                                <p>Display Lab</p>
                            </a>
                        </li>

                        <li class="nav-item ml-4">
                            <a class="nav-link" target="_blank" href="{{ url('display/farmasi') }}">
                                <i class="fa-solid fa-tv nav-icon"></i>
                                <p>Display Farmasi</p>
                            </a>
                        </li>
                        <li class="nav-item ml-4">
                            <a class="nav-link" target="_blank" href="{{ url('display/dokter') }}">
                                <i class="fa-solid fa-tv nav-icon"></i>
                                <p>Jumlah Tiap Dokter</p>
                            </a>
                        </li>

                        <li class="nav-item ml-4">
                            <a href="" class="nav-link">
                                <i class="fa-solid fa-desktop nav-icon"></i>
                                <p>Display Poli
                                    <i class="fas fa-angle-left" style="margin-left: 58px;"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview" style="display: none;">
                                <li class="nav-item ml-4">
                                    <a class="nav-link" target="_blank" href="{{ url('/display/poli/agil') }}">
                                        <i class="fa-solid fa-tv nav-icon"></i>
                                        <p>dr. Agil</p>
                                    </a>
                                </li>
                                <li class="nav-item ml-4">
                                    <a class="nav-link" target="_blank" href="{{ url('/display/poli/nova') }}">
                                        <i class="fa-solid fa-tv nav-icon"></i>
                                        <p>dr. Cempaka</p>
                                    </a>
                                </li>
                                <li class="nav-item ml-4">
                                    <a class="nav-link" target="_blank" href="{{ url('/display/poli/filly') }}">
                                        <i class="fa-solid fa-tv nav-icon"></i>
                                        <p>dr. Filly</p>
                                    </a>
                                </li>
                                <li class="nav-item ml-4">
                                    <a class="nav-link" target="_blank" href="{{ url('/display/poli/sigit') }}">
                                        <i class="fa-solid fa-tv nav-icon"></i>
                                        <p>dr. Sigit</p>
                                    </a>
                                </li>

                            </ul>
                        </li>

                    </ul>
                </li>

                <!-- Transaksi Section -->
                <li class="nav-header">TRANSAKSI</li>

                <!-- IGD Section -->
                @php
                    $roleIGD = ['admin', '', 'igd', 'nurse', 'dokter', 'perawat'];
                @endphp
                <li class="nav-item @if (!in_array(Auth::user()->role, $roleIGD)) non-aktif @endif">
                    <a href="" class="nav-link">
                        <i class="fa-solid fa-truck-medical nav-icon"></i>
                        <p>Ruang Tindakan <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                        <li class="nav-item ml-4">
                            <a class="nav-link" href="{{ url('/Igd') }}" id="masukIGD">
                                <i class="nav-icon fas fa-edit"></i>
                                <p>Input Tindakan</p>
                            </a>
                        </li>
                        <li class="nav-item ml-4">
                            <a class="nav-link" href="{{ url('/Igd/Gudang') }}">
                                <i class="fa-solid fa-database nav-icon"></i>
                                <p>Master Tindakan</p>
                            </a>
                        </li>
                        <li class="nav-item ml-4">
                            <a class="nav-link" href="{{ url('/Askep') }}">
                                <i class="fa-solid fa-file-pen nav-icon"></i>
                                <p>ASKEP</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Dots Center Section -->
                @php
                    $roleDots = ['admin', '', 'dots', 'perawat'];
                @endphp
                <li class="nav-item @if (!in_array(Auth::user()->role, $roleDots)) non-aktif @endif">
                    <a href="" class="nav-link">
                        <i class="nav-icon fa-solid fa-hand-holding-medical"></i>
                        <p>Dots Center <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                        <li class="nav-item ml-4">
                            <a class="nav-link" href="{{ url('/dots') }}">
                                <i class="nav-icon fas fa-edit"></i>
                                <p>Input Dots Center</p>
                            </a>
                        </li>
                        <li class="nav-item ml-4">
                            <a class="nav-link" href="{{ url('/dots_master') }}">
                                <i class="fa-solid fa-database nav-icon"></i>
                                <p>Master Dots Center</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Gizi Section -->
                @php
                    $roleGizi = ['admin', '', 'gizi'];
                @endphp
                <li class="nav-item @if (!in_array(Auth::user()->role, $roleGizi)) non-aktif @endif">
                    <a href="" class="nav-link">
                        <i class="fa-brands fa-nutritionix nav-icon"></i>
                        <p>Gizi <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                        <li class="nav-item ml-4">
                            <a class="nav-link" href="{{ url('/Gizi') }}">
                                <i class="nav-icon fas fa-edit"></i>
                                <p>Input Gizi</p>
                            </a>
                        </li>
                        <li class="nav-item ml-4">
                            <a class="nav-link" href="{{ url('/Gizi/Master') }}">
                                <i class="fa-solid fa-database nav-icon"></i>
                                <p>Master Gizi</p>
                            </a>
                        </li>
                        <li class="nav-item  ml-4"">
                            <a class="nav-link" href="{{ url('/Gizi/Riwayat') }}">
                                <i class="fa-solid fa-chart-column nav-icon"></i>
                                <p>Riwayat Transaksi Gizi</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Farmasi Section -->
                @php
                    $roleFar = ['admin', '', 'farmasi'];
                @endphp
                <li class="nav-item @if (!in_array(Auth::user()->role, $roleFar)) non-aktif @endif">
                    <a href="" class="nav-link">
                        <i class="fa-solid fa-pills nav-icon"></i>
                        <p>Farmasi <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                        <li class="nav-item ml-4">
                            <a class="nav-link" href="{{ url('/farmasi') }}">
                                <i class="nav-icon fas fa-edit"></i>
                                <p>Input Farmasi</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Lab Section -->
                @php
                    $roleLab = ['admin', '', 'lab'];
                @endphp
                <li class="nav-item @if (!in_array(Auth::user()->role, $roleLab)) non-aktif @endif"> <a href=""
                        class="nav-link">
                        <i class="fa-solid fa-microscope nav-icon"></i>
                        <p>Laboratorium <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                        <li class="nav-item ml-4">
                            <a class="nav-link" href="{{ url('/Laboratorium/Pendaftaran') }}">
                                <i class="fa-solid fa-user-pen nav-icon"></i>
                                <p>Pendaftaran Lab</p>
                            </a>
                        </li>
                        <li class="nav-item ml-4">
                            <a class="nav-link" href="{{ url('/Laboratorium/Hasil') }}">
                                <i class="nav-icon fas fa-edit"></i>
                                <p>Input Hasil Lab</p>
                            </a>
                        </li>
                        <li class="nav-item ml-4">
                            <a class="nav-link" href="{{ url('/Laboratorium/TB04') }}">
                                <i class="nav-icon fas fa-edit"></i>
                                <p>Input TB-04 Lab</p>
                            </a>
                        </li>
                        <li class="nav-item ml-4">
                            <a class="nav-link" href="{{ url('/Laboratorium/Master') }}">
                                <i class="fa-solid fa-database nav-icon"></i>
                                <p>Master Lab</p>
                            </a>
                        </li>
                        <li class="nav-item ml-4">
                            <a class="nav-link" href="{{ url('/Laboratorium/Laporan') }}">
                                <i class="fa-solid fa-chart-column nav-icon"></i>
                                <p>Laporan Laboratorium</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Radiologi Section -->
                @php
                    $roleRO = ['admin', '', 'ro'];
                @endphp
                <li class="nav-item @if (!in_array(Auth::user()->role, $roleRO)) non-aktif @endif"> <a href=""
                        class="nav-link">
                        <i class="nav-icon fa-solid fa-circle-radiation"></i>
                        <p>Radiologi <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                        <li class="nav-item ml-4">
                            <a class="nav-link" href="{{ url('/Radiologi') }}">
                                <i class="fa-regular nav-icon fas fa-edit"></i>
                                <p>Input Radiologi</p>
                            </a>
                        </li>
                        <li class="nav-item ml-4">
                            <a class="nav-link" href="{{ url('/Radiologi/Master') }}">
                                <i class="fa-solid fa-database nav-icon"></i>
                                <p>Master Radiologi</p>
                            </a>
                        </li>
                        <li class="nav-item ml-4">
                            <a class="nav-link" href="{{ url('/Radiologi/Laporan') }}">
                                <i class="fa-solid fa-chart-column nav-icon"></i>
                                <p>Laporan Radiologi</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Kasir Section -->
                @php
                    $roleKasir = ['admin', '', 'kasir'];
                @endphp
                <li class="nav-item @if (!in_array(Auth::user()->role, $roleKasir)) non-aktif @endif">
                    <a href="" class="nav-link">
                        <i class="fa-solid fa-cash-register nav-icon"></i>
                        <p>Kasir <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                        <li class="nav-item ml-4">
                            <a class="nav-link" href="{{ url('/kasir') }}">
                                <i class="fa-regular nav-icon fas fa-edit"></i>
                                <p>Transaksi Kasir</p>
                            </a>
                        </li>
                        <li class="nav-item ml-4">
                            <a class="nav-link" href="{{ url('/kasir/pendapatan/lain') }}">
                                <i class="fa-regular nav-icon fas fa-edit"></i>
                                <p>Transaksi Lain</p>
                            </a>
                        </li>
                        <li class="nav-item ml-4">
                            <a class="nav-link" href="{{ url('/kasir/report') }}">
                                <i class="fa-solid fa-database nav-icon"></i>
                                <p>Laporan Kunjungan</p>
                            </a>
                        </li>
                        <li class="nav-item ml-4">
                            <a class="nav-link" href="{{ url('/kasir/master') }}">
                                <i class="fa-solid fa-database nav-icon"></i>
                                <p>Master Data Layanan</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- TU Section -->
                @php
                    $roleKasir = ['admin', 'tu', 'kasir', 'atk'];
                @endphp
                <li class="nav-item @if (!in_array(Auth::user()->role, $roleKasir)) non-aktif @endif">
                    <a href="" class="nav-link">
                        <i class="fa-solid fa-house-medical nav-icon"></i>
                        <p>Tata Usaha <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">
                        <li class="nav-item ml-4">
                            <a class="nav-link" href="{{ url('Gudang/ATK') }}">
                                <i class="fa-solid fa-warehouse nav-icon"></i>
                                <p>Gudang ATK</p>
                            </a>
                        </li>
                        <li class="nav-item ml-4">
                            <a class="nav-link" href="{{ url('TataUsaha/surat') }}">
                                <i class="fa-solid fa-envelope nav-icon"></i>
                                <p>Surat Umum</p>
                            </a>
                        </li>
                        <li class="nav-item ml-4">
                            <a class="nav-link" href="{{ url('/TataUsaha/belanja') }}">
                                <i class="fa-solid fa-money-bill-1-wave nav-icon"></i>
                                <p>Belanja</p>
                            </a>
                        </li>
                        <li class="nav-item ml-4">
                            <a class="nav-link" href="{{ url('TataUsaha/report') }}">
                                <i class="fa-solid fa-cart-flatbed nav-icon"></i>
                                <p>Laporan Belanja</p>
                            </a>
                        </li>
                        <li class="nav-item ml-4">
                            <a class="nav-link" href="{{ url('/kasir/master') }}">
                                <i class="fa-solid fa-database nav-icon"></i>
                                <p>Master Data Layanan</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Surat Medis -->
                @php
                    $roleSurat = ['admin', 'igd', 'nakes', 'dokter', 'perawat'];
                @endphp
                <li class="nav-item @if (!in_array(Auth::user()->role, $roleSurat)) non-aktif @endif">
                    <a class="nav-link" href="{{ url('/surat/medis') }}">
                        <i class="fa-solid fa-envelope nav-icon"></i>
                        <p>Surat Medis</p>
                    </a>
                </li>

                <li class="nav-item @if (!in_array(Auth::user()->role, $roleSurat)) non-aktif @endif">
                    <a class="nav-link" href="{{ url('/jadwal') }}">
                        <i class="fa-solid fa-calendar-days nav-icon"></i>
                        <p>Jadwal Karyawan</p>
                    </a>
                </li>

                <!-- Hasil Penunjang -->
                @php
                    $roleHasilPenunjang = ['tamu', 'igd', 'admin', 'nakes', 'dokter', 'perawat'];
                @endphp
                <li class="nav-item @if (!in_array(Auth::user()->role, $roleHasilPenunjang)) non-aktif @endif">
                    <a class="nav-link" href="{{ url('/RO/Hasil') }}">
                        <i class="fa-solid fa-x-ray nav-icon"></i>
                        <p>Hasil Penunjang</p>
                    </a>
                </li>
                <!-- Promkes -->
                @php
                    $roleHasilPenunjang = ['tamu', 'promkes', 'admin', 'nakes', 'dokter', 'perawat'];
                @endphp
                <li class="nav-item @if (!in_array(Auth::user()->role, $roleHasilPenunjang)) non-aktif @endif">
                    <a class="nav-link" href="{{ url('/Promkes') }}">
                        <i class="fa-solid fa-laptop-medical nav-icon"></i>
                        <p>Promkes</p>
                    </a>
                </li>

                <!-- Laporan Section -->
                <li class="nav-header">LAPORAN</li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/Pusat_Data') }}">
                        <i class="fa-solid fa-database nav-icon"></i>
                        <p>Pusat Data</p>
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link" href="{{ url('/Igd/Poin') }}">
                        <i class="fa-solid fa-chart-column nav-icon"></i>
                        <p>Poin Pegawai</p>
                    </a>
                </li> --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/E-kinerja') }}">
                        <i class="fa-solid fa-chart-line  nav-icon"></i>
                        <p>Laporan Kinerja Pegawai</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/Laporan/Pendaftaran') }}">
                        <i class="fa-solid fa-chart-column nav-icon"></i>
                        <p>Laporan Pendaftaran</p>
                    </a>
                </li>

                @php
                    $roleDokter = ['admin', 'igd', 'dokter', 'perawat'];
                @endphp
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/Riwayat/Pasien') }}">
                        <i class="fa-solid fa-book-medical nav-icon"></i>
                        <p>Riwayat Pasien</p>
                    </a>
                </li>
                <li class="nav-item @if (!in_array(Auth::user()->role, $roleDokter)) non-aktif @endif">
                    <a class="nav-link" href="{{ url('/Diagnosa/Mapping') }}">
                        <i class="fa-solid fa-book-medical nav-icon"></i>
                        <p>Mapping Dx Medis</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
