 <div class="form-row d-flex justify-content-end">
     <label for="petugas" class="col-auto col-form-label font-weight-bold">Pembuat CPPT
         :</label>
     <select id="petugas" name="petugas" class="form-control col-3 mx-2 select2bs4" required>
         <option value="">--Pilih Pembuat CPPT--</option>
         @foreach ($petugas as $item)
             <option value="{{ $item->nip }}">{{ $item->gelar_d }}
                 {{ $item->nama }} {{ $item->gelar_b }}</option>
         @endforeach
     </select>
     <div class="form-group form-row mx-2">
         <div class="col-auto">
             <a class="btn btn-sm btn-primary" id="tombol_selesai" onclick="simpanCppt();">Simpan CPPT</a>
         </div>
         <div class="col-auto">
             <a class="btn btn-sm btn-warning" id="tblBatal" onclick="resetCppt();">Batal</a>
         </div>
         <div class="col-auto">
             <a class="btn btn-sm btn-warning" id="tblSelesai" onclick="resetCppt();" style="display: none;">Selesai</a>
         </div>
     </div>
 </div>

 <script>
     function resetCppt() {
         document.getElementById('form_identitas').reset();
         document.getElementById('form_cppt').reset();
         $('#form_identitas select').trigger('change');
         $("#subjektif").summernote("code", "");
         $("#objektif").summernote("code", "");
         $("#assesment").summernote("code", "");
         $("#planing").summernote("code", "");
         isiDiagnosaSelect("#dx1", "");
         isiDiagnosaSelect("#dx2", "");
         isiDiagnosaSelect("#dx3", "");
         isiDiagnosaSelect("#dx4", "");
         $('#tblBatal').show();
         $('#tblSelesai').hide();
         //  window.location.reload();
         scrollToTop();
     }
 </script>
