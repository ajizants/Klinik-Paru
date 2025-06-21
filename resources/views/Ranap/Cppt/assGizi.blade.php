 {{-- Diagnosa Gizi --}}
 <div class="row mb-2">
     <div class="col-md-4 pr-1 pl-0 pb-1">
         <select type="select" id="dxGizi" class="form-control select2bs4 mb-2 border border-primary" required>
             <option value="">--Pilih Diagnosa Gizi--</option>
             @foreach ($sub as $dx)
                 <option value="{{ $dx->kode }}">{{ $dx->sub_kelas }}</option>
             @endforeach
         </select>
     </div>
     <div class="col-12 col-md-4 pr-1 pl-0 pb-1">
         <div class="input-group input-group-sm pb-1">
             <input type="text" id="etiologi" class="form-control" aria-describedby="inputGroup-sizing-sm"
                 placeholder="Etiologi Diagnosa" />
         </div>
     </div>
 </div>
 <div class="row mb-2">
     <div class="input-group input-group-sm pb-1">
         <textarea type="text" id="evaluasi" class="form-control" aria-describedby="inputGroup-sizing-sm"
             placeholder="Evaluasi ahli gizi" required rows="2"></textarea>
     </div>
 </div>
