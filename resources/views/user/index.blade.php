@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <!-- Tombol Tambah User Biasa -->
            <a href="{{ url('user/create') }}" class="btn btn-primary btn-sm">Tambah User</a>

            <!-- Tombol Tambah User AJAX -->
       <a href="{{ url('user/ajax/create') }}"
   class="btn btn-success btn-sm"
   onclick="event.preventDefault(); modalAction(this.href);">
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

<!-- Modal untuk edit, detail, konfirmasi hapus -->
<div class="modal fade" id="modalAction" tabindex="-1" role="dialog" aria-labelledby="modalActionLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" id="modalContent">
      <!-- Konten modal di-load lewat AJAX -->
    </div>
  </div>
</div>
@endsection

@push('js')
<script>
$(function () {
    var table = $('#usersTable').DataTable({
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

function modalAction(url) {
    $('#modalContent').html('<div class="text-center p-4">Loading...</div>');
    $('#modalAction').modal('show');
    $.ajax({
        url: url,
        success: function (data) {
            $('#modalContent').html(data);
        },
        error: function () {
            $('#modalContent').html('<div class="text-danger p-4">Gagal memuat data</div>');
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
            if(res.status){
                $('#modalAction').modal('hide');
                $('#usersTable').DataTable().ajax.reload();
                alert(res.message);
            } else {
                let errors = res.msgField;
                form.find('.text-danger').remove();
                $.each(errors, function(field, msg){
                    form.find('[name="'+field+'"]').after('<small class="text-danger">'+msg[0]+'</small>');
                });
            }
        }
    });
});

// Submit delete via AJAX
$(document).on('submit', '#formDeleteUser', function(e){
    e.preventDefault();
    if(!confirm('Yakin ingin menghapus data ini?')) return false;

    let form = $(this);
    let url = form.attr('action');

    $.ajax({
        url: url,
        method: 'DELETE',
        data: form.serialize(),
        success: function(res){
            if(res.status){
                $('#modalAction').modal('hide');
                $('#usersTable').DataTable().ajax.reload();
                alert(res.message);
            } else {
                alert(res.message);
            }
        }
    });
});
</script>
@endpush
