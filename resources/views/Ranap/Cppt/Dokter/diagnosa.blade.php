 {{-- Diagnosa Medis --}}
 <div class="form-row">
     <div class="col mx-1">
         <div class="card card-outline card-info">
             <div class="card-header">
                 <h3 class="card-title">
                     Diagnosa Medis
                 </h3>
             </div>
             <div class="card-body pb-0 pt-1 px-1">
                 <div class="form-row mb-2">
                     <div class="col-md-6 px-1 pb-1">
                         <select type="select" id="dx1" name="dx1"
                             class="form-control select2bs4 mb-2 border border-primary" required>
                             <option value="">--Pilih Diagnosa Medis 1--</option>
                             {{-- @foreach ($dxMed as $dx)
                                 <option value="{{ $dx->kdDiag }}">{{ $dx->diagnosa }}</option>
                             @endforeach --}}
                         </select>
                         <input type="text" id="ket_dx1" name="ket_dx1" class="form-control"
                             placeholder="Keterangan Diagnosa 1">
                     </div>
                     <div class="col-md-6 px-1 pb-1">
                         <select type="select" id="dx2" name="dx2"
                             class="form-control select2bs4 mb-2 border border-primary" required>
                             <option value="">--Pilih Diagnosa Medis 2--</option>
                             @foreach ($dxMed as $dx)
                                 <option value="{{ $dx->kdDiag }}">{{ $dx->diagnosa }}</option>
                             @endforeach
                         </select>
                         <input type="text" id="ket_dx2" name="ket_dx2" class="form-control"
                             placeholder="Keterangan Diagnosa 2">
                     </div>
                 </div>
                 <div class="form-row mb-2">
                     <div class="col-md-6 px-1 pb-1">
                         <select type="select" id="dx3" name="dx3"
                             class="form-control select2bs4 mb-2 border border-primary" required>
                             <option value="">--Pilih Diagnosa Medis 3--</option>
                             @foreach ($dxMed as $dx)
                                 <option value="{{ $dx->kdDiag }}">{{ $dx->diagnosa }}</option>
                             @endforeach
                         </select>
                         <input type="text" id="ket_dx3" name="ket_dx3" class="form-control"
                             placeholder="Keterangan Diagnosa 3">
                     </div>
                     <div class="col-md-6 px-1 pb-1">
                         <select type="select" id="dx4" name="dx4"
                             class="form-control select2bs4 mb-2 border border-primary" required>
                             <option value="">--Pilih Diagnosa Medis 4--</option>
                             @foreach ($dxMed as $dx)
                                 <option value="{{ $dx->kdDiag }}">{{ $dx->diagnosa }}</option>
                             @endforeach
                         </select>
                         <input type="text" id="ket_dx4" name="ket_dx4" class="form-control"
                             placeholder="Keterangan Diagnosa 4">
                     </div>
                 </div>
             </div>
         </div>
     </div>
     <div class="col">
         <div class="col-md mx-1">
             <div class="card card-outline card-info">
                 <div class="card-header">
                     <h3 class="card-title">
                         Assesment (deskriptif)
                     </h3>
                 </div>
                 <!-- /.card-header -->
                 <div class="card-body p-0">
                     <textarea id="assesment" name="assesment" placeholder="Tuliskan secara deskriptif di sini"></textarea>
                 </div>
             </div>
         </div>
     </div>
 </div>
