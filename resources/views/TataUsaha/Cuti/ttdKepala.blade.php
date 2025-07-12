 <table class="w-full text-sm">
     <tbody>
         <tr class="bg-white ">
             <td class="px-2 py-[0.15rem] border border-t-0 border-black" colspan="4">
                 <h2 class="font-bold">
                     @if ($data->pegawai->stat_pns == 'PNS' || $data->pegawai->stat_pns == 'PPPK')
                         VII. PERTIMBANGAN ATASAN LANGSUNG
                     @else
                         VIII. KEPUTUSAN PEJABAT YANG BERWENANG MEMBERIKAN CUTI**
                     @endif
                 </h2>
             </td>
         </tr>
         <tr class="bg-white border border-black">
             <td class="px-2 py-[0.15rem] border border-black" width="25%">DISETUJUI</td>
             <td class="px-2 py-[0.15rem] border border-black" width="25%">PERUBAHAN</td>
             <td class="px-2 py-[0.15rem] border border-black" width="25%">DITANGGUHKAN</td>
             <td class="px-2 py-[0.15rem] border border-black" width="25%">DISETUJUI</td>
         </tr>
         <tr class="bg-white border border-black">
             <td class="px-2 py-[0.15rem] border border-black" width="25%">
                 @if ($data->persetujuan == 1)
                     <strong class="text-red-600">✓</strong>
                 @endif
             </td>
             <td class="px-2 py-[0.15rem] border border-black" width="25%"></td>
             <td class="px-2 py-[0.15rem] border border-black" width="25%">
                 @if ($data->persetujuan == 0)
                     <strong class="text-red-600">✓</strong>
                 @endif
             </td>
             <td class="px-2 py-[0.15rem] border border-black" width="25%">
                 @if ($data->persetujuan == 2)
                     <strong class="text-red-600">✓</strong>
                 @endif
             </td>
         </tr>
         <tr class="bg-white @if ($data->pegawai->stat_pns == 'PNS' || $data->pegawai->stat_pns == 'PPPK') border-b border-black @endif">
             <td class="px-2 py-[0.15rem]" colspan="2">
                 <textarea class="w-full resize-none" rows="6"></textarea>
             </td>

             <td class="px-2 py-[0.15rem] text-center border  border-black" colspan="2">
                 <p>{{ $pimpinan['gelar'] }} <br> Klinik Utama Kesehatan Paru Masyarakat Kelas A</p>
                 <div class="h-[4rem]"></div>
                 <p><u>{{ $pimpinan['kepala'] }}</u></p>
                 <p>NIP: {{ $pimpinan['nipKepala'] }}</p>
             </td>
         </tr>
     </tbody>
 </table>
