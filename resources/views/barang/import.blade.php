{{-- resources/views/barang/import_ajax.blade.php --}}
<form  id="formImportBarang"
       action="{{ url('/barang/import_ajax') }}"
       method="POST"
       enctype="multipart/form-data">
    @csrf

    <div class="modal-dialog modal-lg" role="document" id="modal-master">
        <div class="modal-content">
           
            <div class="modal-header">
                <h5 class="modal-title">Import Data Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

         
            <div class="modal-body">

                <div class="form-group">
                    <label>Download Template</label>
                    <a href="{{ asset('template_barang.xlsx') }}"
                       class="btn btn-info btn-sm"
                       download>
                       <i class="fa fa-file-excel"></i> Download
                    </a>
                </div>

               
                <div class="form-group">
                    <label>Pilih File</label>
                    <input  type="file"
                            name="file_barang"
                            id="file_barang"
                            class="form-control"
                            required>
                    <small id="error-file_barang"
                           class="error-text text-danger"></small>
                </div>

            </div>

            {{-- Footer --}}
            <div class="modal-footer">
                {{-- TOMBOL EXPORT PDF DITAMBAHKAN DI SINI --}}
                <a href="{{ url('/barang/export-pdf') }}" class="btn btn-warning">
                    <i class="fa fa-file-pdf"></i> Export Barang PDF
                </a>
                {{-- AKHIR TOMBOL EXPORT PDF --}}

                <button type="button"
                        class="btn btn-secondary" {{-- Ubah warna tombol Batal menjadi secondary agar lebih kontras --}}
                        data-dismiss="modal">
                        Batal
                </button>
                <button type="submit" class="btn btn-primary">
                    Upload
                </button>
            </div>
        </div>
    </div>
</form>

@push('js')
<script>
$(function () {

   
    $('#formImportBarang').validate({
        rules: {
            file_barang: {
                required: true,
                extension: 'xlsx'  // hanya .xlsx
            }
        },
        errorElement: 'span',
        errorClass: 'invalid-feedback',
        highlight: el => $(el).addClass('is-invalid'),
        unhighlight: el => $(el).removeClass('is-invalid'),

     
        submitHandler: function (form) {
            const formData = new FormData(form);  

            $.ajax({
                url:  form.action,
                type: form.method,       // POST
                data: formData,
                processData: false,      // wajib untuk FormData
                contentType: false,      // wajib untuk FormData
                success: function (res) {

                    $('.error-text').text('');

                    if (res.status) {
                        $('#modalAction').modal('hide');   // tutup modal
                        tableBarang.ajax.reload(null, false); // reload DataTables
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text:  res.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        // tampilkan error validasi dari server
                        $.each(res.msgField, (field, msg) => {
                            $('#error-' + field).text(msg[0]);
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text:  res.message
                        });
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', status, error, xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'AJAX Error',
                        text: 'Terjadi kesalahan saat mengunggah file.'
                    });
                }
            });

            return false; // cegah submit normal
        }
    });

});
</script>
@endpush