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
             <a class="btn btn-sm btn-warning" id="tblBatal" onclick="reset();">Batal</a>
         </div>
     </div>
 </div>
