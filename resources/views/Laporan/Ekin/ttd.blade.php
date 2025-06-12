<div class="flex justify-between mt-20">
    <div class="w-1/2 text-center">
        <p>Purwokerto, {{ $tglAkhir }}</p>
        <p>Pegawai yang Dinilai</p>
        <div class="h-16"></div>
        <p><u>{{ $biodata['nama'] ?? '-' }}</u></p>
        <p>NIP: {{ $biodata['nip'] ?? '-' }}</p>
    </div>
    <div class="w-1/2 text-center">
        <p>Mengetahui,</p>
        <p>Plt. Kepala KKPM PURWOKERTO</p>
        <div class="h-16"></div>
        <p><u>{{ $kepala }}</u></p>
        <p>NIP: {{ $nipKepala }}</p>
    </div>
</div>
