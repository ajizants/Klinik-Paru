<p class="mb-0">Assalamu'alaikum wr. wb</p>
<p class="mb-0">Izin melaporkan
    <strong>{{ \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('dddd, D MMMM Y') }}</strong>
</p>
----------------------------------------
<ul class="list-unstyled">
    @forelse($dataCutiWa as $index => $cuti)
        <li><strong>*{{ $index + 1 }}. {{ $cuti->pegawai->nama }}*</strong>
            <br> <span class="ml-4">{{ $cuti->alasan }}</span>
        </li>
    @empty
        <li>Tidak ada data cuti untuk hari ini.</li>
    @endforelse
</ul>
----------------------------------------
<p class="mb-0">Terima kasih 🙏</p>
<p class="mb-0">Wassalamu'alaikum wr. wb.</p>
