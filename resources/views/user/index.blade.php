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
            { data: 'level_nama', name: 'level.level_nama' }, // name harus sesuai dengan kolom di database untuk sorting/search
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
        ]
    });
});

// Fungsi modalAction harus di luar $(document).ready() agar bisa diakses
function modalAction(url, title = null) { // Tambahkan parameter title
    // Tampilkan indikator loading saat memuat
    $('#modalContent').html('<div class="text-center p-4">Loading...</div>');
    // Tampilkan modal
    $('#modalAction').modal('show');

    $.ajax({
        url: url,
        type: 'GET', // Pastikan ini GET untuk memuat form
        dataType: 'html', // Karena responsnya adalah HTML dari form_ajax.blade.php
        success: function (data) {
            $('#modalContent').html(data); // Masukkan konten modal ke dalam div modalContent
            // Anda bisa tambahkan ini jika ada elemen untuk judul di dalam data yang dimuat
            // if (title) {
            //     $('#modalActionLabel').text(title); // Mengatur judul modal jika ada label
            // }
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
        method: 'POST', // Atau PUT/PATCH jika route Anda diset PUT/PATCH
        data: form.serialize(),
        success: function(res) {
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
                form.find('.text-danger').remove();
                $.each(errors, function(field, msg){
                    form.find('[name="'+field+'"]').after('<small class="text-danger">'+msg[0]+'</small>');
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
                method: 'DELETE', // Pastikan ini DELETE sesuai route
                data: form.serialize(),
                success: function(res){
                    if(res.status){
                        $('#modalAction').modal('hide'); // Tutup modal dengan ID yang benar
                        tableUser.ajax.reload(null, false); // Reload DataTables menggunakan instance tableUser
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