@empty($user)
<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Kesalahan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger">
          <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
          Data yang anda cari tidak ditemukan
        </div>
        <a href="{{ url('/user') }}" class="btn btn-warning">Kembali</a>
      </div>
    </div>
  </div>
</div>
@else

<form action="{{ url('/user/' . $user->user_id . '/delete_ajax') }}" method="POST" id="form-delete">
  @csrf
  @method('DELETE')

  <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Hapus Data User</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <div class="alert alert-warning">
            <h5><i class="icon fas fa-ban"></i> Konfirmasi !!!</h5>
            Apakah Anda ingin menghapus data seperti di bawah ini?
          </div>
          <table class="table table-sm table-bordered table-striped">
            <tr>
              <th class="text-right col-3">Level Pengguna :</th>
              <td class="col-9">{{ $user->level->level_nama }}</td>
            </tr>
            <tr>
              <th class="text-right col-3">Username :</th>
              <td class="col-9">{{ $user->username }}</td>
            </tr>
            <tr>
              <th class="text-right col-3">Nama :</th>
              <td class="col-9">{{ $user->nama }}</td>
            </tr>
          </table>
        </div>

        <div class="modal-footer">
          <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
          <button type="submit" class="btn btn-primary">Ya, Hapus</button>
        </div>
      </div>
    </div>
  </div>
</form>
@push('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.19.5/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
  $("#form-delete").validate({
    submitHandler: function(form) {
      $.ajax({
        url: form.action,
        type: form.method,
        data: $(form).serialize(),
        success: function(response) {
          if(response.status){
            $('#myModal').modal('hide');
            Swal.fire('Berhasil', response.message, 'success');
            if(typeof tableUser !== 'undefined') {
              tableUser.ajax.reload(null, false);
            }
          } else {
            Swal.fire('Error', response.message, 'error');
          }
        },
        error: function() {
          Swal.fire('Error', 'Gagal menghapus data', 'error');
        }
      });
      return false;
    }
  });
});
</script>
@endpush
