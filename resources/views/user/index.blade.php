@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <a href="{{ url('user/create') }}" class="btn btn-primary btn-sm">Tambah User</a>
            <a href="{{ url('user/ajax/create') }}"
               class="btn btn-success btn-sm"
               onclick="event.preventDefault(); modalAction(this.href, 'Tambah User (AJAX)');">
               Tambah User (AJAX)
            </a>
        
            <a href="{{ url('barang/import') }}" 
               class="btn btn-info btn-sm"
               onclick="event.preventDefault(); modalAction(this.href, 'Import Data Barang');">
               <i class="fa fa-file-excel"></i> Import Barang (AJAX)
            </a>
            {{-- BARIS YANG DITAMBAHKAN SESUAI PERMINTAAN --}}
            <a href="{{ url('/barang/export_excel') }}" class="btn btn-warning btn-sm">
                <i class="fa fa-download"></i> Export Barang
            </a>
            {{-- AKHIR BARIS YANG DITAMBAHKAN --}}
        </div>
    </div>
    <div class="card-body">
        <table id="usersTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Nama</th>
                    <th>Level</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>


<div class="modal fade" id="modalAction" tabindex="-1" role="dialog" aria-labelledby="modalActionLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="modalContent">
            
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
let tableUser;

$(function () {
    tableUser = $('#usersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url('user/list') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'username', name: 'username' },
            { data: 'nama', name: 'nama' },
            { data: 'level_nama', name: 'level.level_nama' }, 
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
        ]
    });
});


function modalAction(url, title = null) { 
    
    $('#modalContent').html('<div class="text-center p-4">Loading...</div>');
    
    $('#modalAction').modal('show');

    $.ajax({
        url: url,
        type: 'GET', 
        dataType: 'html', 
        success: function (data) {
            $('#modalContent').html(data); 
            
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error loading modal:", xhr.responseText, status, error);
            $('#modalContent').html('<div class="text-danger p-4">Gagal memuat data: ' + error + '</div>');
        }
    });
}

// Submit form update via AJAX
$(document).on('submit', '#formEditUser', function(e){
    e.preventDefault();
    let form = $(this);
    let url = form.attr('action');

    $.ajax({
        url: url,
        method: 'POST', 
        data: form.serialize(),
        success: function(res) {
            // Bersihkan pesan error sebelumnya dari semua field
            form.find('.error-text').text('');

            if(res.status){
                $('#modalAction').modal('hide'); // Tutup modal dengan ID yang benar
                tableUser.ajax.reload(null, false); // Reload DataTables menggunakan instance tableUser
                Swal.fire({ // Menggunakan SweetAlert untuk notifikasi
                    icon: 'success',
                    title: 'Berhasil',
                    text: res.message,
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                let errors = res.msgField;
                // form.find('.text-danger').remove(); // Hapus baris ini jika Anda menggunakan `<small id="error-field">`
                $.each(errors, function(field, msg){
                    // form.find('[name="'+field+'"]').after('<small class="text-danger">'+msg[0]+'</small>'); // Ganti dengan baris di bawah
                    $('#error-' + field).text(msg[0]); // Menampilkan error pada elemen small yang spesifik
                });
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: res.message
                });
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", xhr.responseText, status, error);
            Swal.fire({
              icon: 'error',
              title: 'AJAX Error',
              text: 'Terjadi kesalahan saat menyimpan data.'
            });
        }
    });
});

$(document).on('submit', '#formImportBarang', function(e){
    e.preventDefault();
    const form = $(this);
    const formData = new FormData(form[0]); 

    $.ajax({
        url: form.attr('action'),
        type: form.attr('method'),
        data: formData,
        processData: false,
        contentType: false,
        success: function (res) {
            $('.error-text').text('');

            if (res.status) {
                $('#modalAction').modal('hide');    
                // Perhatian: tableBarang tidak didefinisikan di scope ini (halaman user).
                // Jika import ini berhasil dan Anda ingin merefresh tabel barang di halaman barang,
                // maka Anda perlu me-reload halaman barang atau mengirim event.
                // Untuk saat ini, saya biarkan kosong/komentar karena tidak ada tableBarang di halaman ini.
                // tableBarang.ajax.reload(null, false); 
                
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text:   res.message,
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                $.each(res.msgField, (field, msg) => {
                    $('#error-' + field).text(msg[0]);
                });
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text:   res.message
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
});


// Submit delete via AJAX
$(document).on('submit', '#formDeleteUser', function(e){
    e.preventDefault();
    // Gunakan SweetAlert untuk konfirmasi
    Swal.fire({
        title: 'Yakin ingin menghapus data ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                method: 'DELETE', 
                data: form.serialize(),
                success: function(res){
                    if(res.status){
                        $('#modalAction').modal('hide'); 
                        tableUser.ajax.reload(null, false); 
                        Swal.fire({
                            icon: 'success',
                            title: 'Dihapus!',
                            text: res.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Hapus',
                            text: res.message
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", xhr.responseText, status, error);
                    Swal.fire({
                      icon: 'error',
                      title: 'AJAX Error',
                      text: 'Terjadi kesalahan saat menghapus data.'
                    });
                }
            });
        }
    });
});
</script>
@endpush