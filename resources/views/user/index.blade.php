@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">{{ $page->title }}</h3>
        <a class="btn btn-sm btn-primary" href="{{ url('user/create') }}">Tambah</a>
    </div>
    <div class="card-body">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        {{-- Filter Level Pengguna --}}
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="level_id" class="form-label">Filter Level:</label>
                <select class="form-control" id="level_id" name="level_id" >
                    <option value="">-- Semua --</option>
                    @foreach($level as $item)
                        <option value="{{ $item->level_id }}">{{ $item->level_nama }}</option>
                    @endforeach
                </select>
                <small class="form-text text-muted">Level Pengguna</small>
            </div>
        </div>

        {{-- Tabel User --}}
        <table class="table table-bordered table-striped table-hover table-sm" id="table_user" style="width:100%">
            <thead>
                <tr>
                    <th style="width: 5%;">ID</th>
                    <th>Username</th>
                    <th>Nama</th>
                    <th>Level Pengguna</th>
                    <th style="width: 15%;">Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('css')
<!-- DataTables Bootstrap4 CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css" />
@endpush

@push('js')
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function () {
    var table = $('#table_user').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ url('user/list') }}",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: function(d) {
                d.level_id = $('#level_id').val(); // Kirim filter level ke server
            }
        },
        columns: [
            {
                data: "DT_RowIndex",
                name: "DT_RowIndex",
                className: "text-center",
                orderable: false,
                searchable: false
            },
            { data: "username", name: "username" },
            { data: "nama", name: "nama" },
            { 
                data: "level.level_nama", 
                name: "level.level_nama",
                orderable: false,
                searchable: false
            },
            {
                data: "aksi",
                name: "aksi",
                orderable: false,
                searchable: false
            }
        ],
        order: [[1, 'asc']],
        lengthMenu: [10, 25, 50],
        language: {
            processing: "Loading..."
        }
    });

    // Reload tabel saat filter level diubah
    $('#level_id').on('change', function() {
        table.ajax.reload();
    });
});
</script>
@endpush
