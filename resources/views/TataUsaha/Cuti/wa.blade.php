{{-- <p class="mb-0">Assalamu'alaikum wr. wb</p>
<p class="mb-0">Izin melaporkan
    <strong>{{ \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('dddd, D MMMM Y') }}</strong></p>
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
<p class="mb-0">Wassalamu'alaikum wr. wb.</p> --}}


<div class="mb-2">
    <button class="btn btn-sm btn-primary mb-2" onclick="copyCutiText()">Copy</button>
</div>

<div id="cutiTextToCopy">
    <p class="mb-0">Assalamu'alaikum wr. wb</p>
    <p class="mb-0">Izin melaporkan cuti pegawai:<br>
        <strong
            class="mx-2">*{{ \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('dddd, D MMMM Y') }}*</strong>
    </p>
    ----------------------------------------
    <ul class="list-unstyled">
        @forelse($dataCutiWa as $index => $cuti)
            <li><strong> *{{ $index + 1 }}. {{ $cuti->pegawai->nama }}*</strong>
                <br> <span class="ml-4">{{ $cuti->alasan }}</span>
            </li>
        @empty
            <li>Tidak ada pegawai yang cuti untuk hari ini.</li>
        @endforelse
    </ul>
    ----------------------------------------
    <p class="mb-0">Terima kasih 🙏</p>
    <p class="mb-0">Wassalamu'alaikum wr. wb.</p>
</div>

<script>
    function copyCutiText() {
        const element = document.getElementById('cutiTextToCopy');
        const range = document.createRange();
        range.selectNode(element);
        window.getSelection().removeAllRanges();
        window.getSelection().addRange(range);
        try {
            document.execCommand('copy');
            Toast.fire({
                icon: 'success',
                title: 'Berhasil menyalin teks'
            })
        } catch (err) {
            alert('Gagal menyalin teks');
        }
        window.getSelection().removeAllRanges();
    }
</script>
