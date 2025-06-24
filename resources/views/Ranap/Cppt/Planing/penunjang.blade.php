 {{-- Diagnosa Medis --}}
 <div class="form-row">
     <div class="col-sm mx-1">
         <div class="card card-outline card-info">
             <div class="card-header">
                 <h3 class="card-title">
                     Input Permintaan Laboratorium
                 </h3>
             </div>
             <div class="card-body pb-0 pt-1 px-1">
                 @csrf
                 <form class="form-group col">
                     <div>
                         <input type="text" id="penunjang_norm" name="norm">
                         <input type="text" id="penunjang_notrans" name="notrans">
                         <input type="text" id="penunjang_form_id" name="form_id">
                     </div>
                     <table id="tablePenunjang" class="table table-striped table-tight" cellspacing="0">
                         <thead>
                             <tr>
                                 <th><input type="checkbox" id="pilih-semua"
                                         style="width: 15px; height: 15px; margin-top: 12px;"></th>
                                 <th>Item Pemeriksaan</th>
                                 <th>Keterangan</th>
                             </tr>
                         </thead>
                     </table>
                     <br>
                     <a id="addKasir" class="btn btn-success d-flex justify-content-center mb-4"
                         onclick="orderPenunjang();">Tambah
                         Order Penunjang</a>
                 </form>
             </div>
         </div>
     </div>
     <div class="col-sm mx-1">
         <div class="card card-outline card-info">
             <div class="card-header">
                 <h3 class="card-title">
                     Daftar Permintaan Laboratorium
                 </h3>
             </div>
             <div class="card-body pb-0 pt-1 px-1">
                 <table id="daftarOrderPenunjang" class="table table-striped table-tight" cellspacing="0">
                     <thead>
                         <tr>
                             <th class="col-1">Aksi</th>
                             <th class="col">Pemeriksaan</th>
                             <th class="col">Keterangan</th>
                         </tr>
                 </table>
             </div>
         </div>
     </div>
 </div>

 <script>
     function orderPenunjang() {
         const norm = $('#penunjang_norm').val();
         const notrans = $('#penunjang_notrans').val();
         const form_id = $('#penunjang_form_id').val();
         const petugas = $('#petugas').val();

         let itemTerpilih = [];

         $('#tablePenunjang .data-checkbox:checked').each(function() {
             const idLayanan = $(this).attr('id');
             const kdFoto = $(this).attr('kdFoto');
             const kdTind = $(this).attr('kdTInd');
             const ket = $(`#ket_${idLayanan}`).val();

             itemTerpilih.push({
                 idLayanan: idLayanan,
                 kdFoto: kdFoto,
                 kdTind: kdTind,
                 ket: ket
             });
         });

         if (itemTerpilih.length === 0) {
             alert('Pilih setidaknya satu item pemeriksaan.');
             return;
         }

         const data = {
             norm: norm,
             notrans: notrans,
             form_id: form_id,
             petugas: petugas,
             items: itemTerpilih
         };

         // Kirim data via AJAX (contoh POST)
         $.ajax({
             url: '/api/ranap/order_penunjang', // Ganti dengan route sesuai backend kamu
             method: 'POST',
             data: JSON.stringify(data),
             contentType: 'application/json',
             headers: {
                 'X-CSRF-TOKEN': $('input[name="_token"]').val()
             },
             success: function(response) {
                 console.log('Berhasil:', response);
                 tampilkanSukses(response.message);
             },
             error: function(xhr) {
                 console.error('Gagal:', xhr.responseText);
                 tampilkanEror(xhr.responseText);
             }
         });
     }


     function isiTabelPenunjang(data) {
         console.log(data);
         $("#daftarOrderPenunjang").DataTable({
             data: data,
             columns: [{
                     data: "id",
                     render: function(data) {
                         return `
                    <button class="btn btn-sm btn-danger" onclick="deletePenunjang('${data}')">
                        <i class="fa fa-trash"></i>
                    </button>
                `;
                     },
                 },
                 {
                     data: "nmLayanan",
                     defaultContent: "-"
                 },
                 {
                     data: "ket",
                     defaultContent: "-",
                 },
             ],
             createdRow: function(row, data) {
                 $(row).attr("id", "row_" + data.id);
             },
             order: [1, "asc"],
             scrollY: "300px",
             paging: false,
         });
     }
 </script>
