{{-- <div class="text-xs flex justify-between">
    <div class="w-1/2 text-center">
        <p>Mengetahui,</p>
        <p>Plt. Kepala KKPM PURWOKERTO</p>
        <div class="h-16"></div>
        <p><u>dr. RENDI RETISSU</u></p>
        <p>NIP: 19881016 201902 1 002</p>
    </div>
    <div class="w-1/2 text-center">
        <p>Purwokerto, {{ $tglAkhir }}</p>
        <p>Bendahara Penerimaan / Kasir</p>
        <div class="h-16"></div>
        <p><u>NASIRIN</u></p>
        <p>NIP: 196906022007011039</p>
    </div>
</div> --}}

<div class="text-xs flex justify-between">
    <div class="w-1/2 text-center">
        <p>Mengetahui,</p>
        <p>Plt. Kepala KKPM PURWOKERTO</p>
        <div class="h-16"></div>
        {{-- <p><u>dr. RENDI RETISSU</u></p>
        <p>NIP: 19881016 201902 1 002</p> --}}
        <p><u>{{ $kepala }}</u></p>
        <p>NIP: {{ $nipKepala }}</p>
    </div>
    <div class="w-1/2 text-center">
        <p>Purwokerto, {{ $tglAkhir }}</p>
        <p>Bendahara Penerimaan / Kasir</p>
        <div class="h-16"></div>
        <p><u>NASIRIN</u></p>
        <p>NIP: 196906022007011039</p>
    </div>
</div>
