 <!-- Modal -->
 <div class="modal fade" id="spirometriModal">
     <div class="modal-dialog modal-lg">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="spirometriModalLabel">Input Hasi Spirometri</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <form id="spirometriForm">
                     <div class="row">
                         <!-- norm -->
                         <div class="form-group col-md-2">
                             <label for="norm">No RM</label>
                             <input type="text" class="form-control" id="normSpiro" name="norm"
                                 placeholder="No RM" readonly>
                             <input type="text" class="form-control" id="idSpiro" name="id"
                                 placeholder="No RM" readonly>
                         </div>

                         <!-- notrans -->
                         <div class="form-group col-md-3">
                             <label for="notrans">No Trans</label>
                             <input type="text" class="form-control" id="notransSpiro" name="notrans"
                                 placeholder="No Trans" readonly>
                         </div>

                         <!-- tgl -->
                         <div class="form-group col-md-3">
                             <label for="tgl">Tanggal</label>
                             <input type="date" class="form-control" id="tglSpiro" name="tgl"
                                 placeholder="Tanggal">
                         </div>

                         <!-- nama -->
                         <div class="form-group col-md-4">
                             <label for="nama">Nama</label>
                             <input type="text" class="form-control" id="namaSpiro" name="nama" placeholder="Nama"
                                 readonly>
                         </div>

                     </div>
                     <div class="row">

                         <!-- petugas -->
                         <div class="form-group col-md-6">
                             <label for="petugas">Petugas</label>
                             <select class="form-control select2bs4" id="petugasSpiro" name="petugas">
                                 <option value="">-- Pilih Petugas --</option>
                                 @foreach (collect($perawat)->sortBy('nama') as $item)
                                     <!-- Convert to collection and sort by 'nama' -->
                                     <option value="{{ $item->nip }}">{{ $item->gelar_d }}
                                         {{ $item->nama }} {{ $item->gelar_b }}</option>
                                 @endforeach
                                 <!-- Tambahkan opsi sesuai data -->
                             </select>
                         </div>

                         <!-- dokter -->
                         <div class="form-group col-md-6">
                             <label for="dokter">Dokter</label>
                             <select class="form-control select2bs4" id="dokterSpiro" name="dokter">
                                 <option value="">-- Pilih Dokter --</option>
                                 @foreach (collect($dokter)->sortBy('nama') as $item)
                                     <!-- Convert to collection and sort by 'nama' -->
                                     <option value="{{ $item->nip }}">{{ $item->gelar_d }}
                                         {{ $item->nama }} {{ $item->gelar_b }}</option>
                                 @endforeach
                                 <!-- Tambahkan opsi sesuai data -->
                             </select>
                         </div>
                     </div>
                     <div class="row">

                         <!-- pred_fvc -->
                         <div class="form-group col-md-4">
                             <label for="pred_fvc">Prediksi FVC</label>
                             <input type="text" class="form-control" id="pred_fvc" name="pred_fvc">
                         </div>

                         <!-- value_fvc -->
                         <div class="form-group col-md-4">
                             <label for="value_fvc">Nilai FVC</label>
                             <input type="text" class="form-control" id="value_fvc" name="value_fvc">
                         </div>

                         <!-- pred_fvc_2 -->
                         <div class="form-group col-md-4">
                             <label for="pred_fvc_2">Prediksi FVC (%)</label>
                             <input type="text" class="form-control" id="pred_fvc_2" name="pred_fvc_2">
                         </div>

                         <!-- pred_fev1 -->
                         <div class="form-group col-md-4">
                             <label for="pred_fev1">Prediksi FEV1</label>
                             <input type="text" class="form-control" id="pred_fev1" name="pred_fev1">
                         </div>

                         <!-- value_fev1 -->
                         <div class="form-group col-md-4">
                             <label for="value_fev1">Nilai FEV1</label>
                             <input type="text" class="form-control" id="value_fev1" name="value_fev1">
                         </div>

                         <!-- pred_fev1_2 -->
                         <div class="form-group col-md-4">
                             <label for="pred_fev1_2">Prediksi FEV1 (%)</label>
                             <input type="text" class="form-control" id="pred_fev1_2" name="pred_fev1_2">
                         </div>

                         <!-- pred_fev1_fvc -->
                         <div class="form-group col-md-4">
                             <label for="pred_fev1_fvc">Prediksi FEV1/FVC</label>
                             <input type="text" class="form-control" id="pred_fev1_fvc" name="pred_fev1_fvc">
                         </div>

                         <!-- value_fev1_fvc -->
                         <div class="form-group col-md-4">
                             <label for="value_fev1_fvc">Nilai FEV1/FVC</label>
                             <input type="text" class="form-control" id="value_fev1_fvc" name="value_fev1_fvc">
                         </div>

                         <!-- pred_fev1_fvc_2 -->
                         <div class="form-group col-md-4">
                             <label for="pred_fev1_fvc_2">Prediksi FEV1/FVC (%)</label>
                             <input type="text" class="form-control" id="pred_fev1_fvc_2" name="pred_fev1_fvc_2">
                         </div>
                     </div>
                 </form>
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-primary" onclick="valideate()">Simpan</button>
             </div>
         </div>
     </div>
 </div>


 <script>
     $(function() {
         $(".select2bs4").select2({
             theme: "bootstrap4",
         });
     });

     function setSpiroForm(norm, nama, notrans, petugas, dokter, tgltrans) {
         $("#normSpiro").val(norm);
         $("#namaSpiro").val(nama);
         $("#notransSpiro").val(notrans);
         $("#tglSpiro").val(tgltrans);
         $("#petugasSpiro").val(petugas);
         $("#petugasSpiro").trigger("change");
         $("#dokterSpiro").val(dokter);
         $("#dokterSpiro").trigger("change");
     }

     function setSpiroFormEdit(data, tgl) {
         console.log("🚀 ~ setSpiroFormEdit ~ data:", data);
         const hasilSpiro = data.hasilSpiro;
         console.log("🚀 ~ setSpiroFormEdit ~ hasilSpiro:", hasilSpiro);

         // Isi nama pasien
         $('#namaSpiro').val(data.pasien.nama);
         $('#tglSpiro').val(tgl);

         const form = document.getElementById("spirometriForm");

         // Isi semua input, select, dan textarea berdasarkan name
         Object.entries(hasilSpiro).forEach(([key, value]) => {
             const field = form.querySelector(`[name="${key}"]`);
             if (field) {
                 field.value = value;
                 // Jika field adalah Select2, trigger change agar UI dan event sinkron
                 if ($(field).hasClass("select2-hidden-accessible")) {
                     $(field).val(value).trigger("change");
                 }
             }
         });
     }

     function valideate() {
         if ($('#idSpiro').val() == '') {
             simpanSpiro();
         } else {
             updateSpiro();
         }
     }

     function updateSpiro() {
         const form = $("#spirometriForm").serialize();
         $.ajax({
             url: "api/spiro/update/" + $('#idSpiro').val(),
             type: "POST",
             data: form,
             success: function(response) {
                 if (response.status == "success") {
                     Toast.fire({
                         icon: "success",
                         title: response.message,
                     });
                 } else {
                     Toast.fire({
                         icon: "error",
                         title: response.message,
                     });
                 }
                 $("#spirometriModal").modal("hide");
                 $("#divAntrianSpiro").html(response.table);
                 $("#tabelAntrianSpiro").DataTable().columns.adjust().draw();

             },
             error: function(xhr, status, error) {
                 tampilkanEror(xhr.responseText);
             }
         });
     }

     function simpanSpiro() {
         const form = $("#spirometriForm").serialize();
         $.ajax({
             url: "api/spiro/simpan",
             type: "POST",
             data: form,
             success: function(response) {
                 if (response.status == "success") {
                     Toast.fire({
                         icon: "success",
                         title: response.message,
                     });
                 } else {
                     Toast.fire({
                         icon: "error",
                         title: response.message,
                     });
                 }
                 $("#spirometriModal").modal("hide");
                 $("#divAntrianSpiro").html(response.table);
                 $("#tabelAntrianSpiro").DataTable().columns.adjust().draw();
             },
             error: function(xhr, status, error) {
                 Toast.fire({
                     icon: "error",
                     title: error,
                 });
             },
         });
     }
 </script>
