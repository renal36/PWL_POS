<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
     <form id="formCreateUserAjax" method="POST" action="{{ url('user/ajax/user/ajax') }}">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Tambah User (AJAX)</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span>&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <div class="form-group">
            <label for="level_id">Level Pengguna</label>
            <select name="level_id" id="level_id" class="form-control">
              <option value="">- Pilih Level -</option>
              @foreach($level as $l)
                <option value="{{ $l->level_id }}">{{ $l->level_nama }}</option>
              @endforeach
            </select>
            <small id="error-level_id" class="form-text text-danger"></small>
          </div>

          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" class="form-control">
            <small id="error-username" class="form-text text-danger"></small>
          </div>

          <div class="form-group">
            <label for="nama">Nama</label>
            <input type="text" name="nama" id="nama" class="form-control">
            <small id="error-nama" class="form-text text-danger"></small>
          </div>

          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control">
            <small id="error-password" class="form-text text-danger"></small>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  $("#formCreateUserAjax").validate({
    rules: {
      level_id: { required: true },
      username: { required: true, minlength: 3 },
      nama: { required: true, minlength: 3 },
      password: { required: true, minlength: 6 }
    },
    messages: {
      level_id: { required: "Level pengguna wajib dipilih." },
      username: { required: "Username wajib diisi.", minlength: "Minimal 3 karakter." },
      nama: { required: "Nama wajib diisi.", minlength: "Minimal 3 karakter." },
      password: { required: "Password wajib diisi.", minlength: "Minimal 6 karakter." }
    },
    errorPlacement: function(error, element) {
      var id = element.attr('id');
      $('#error-' + id).text(error.text());
    },
    success: function(label, element) {
      var id = $(element).attr('id');
      $('#error-' + id).text('');
      $(element).removeClass('is-invalid');
    },
    highlight: function(element) {
      $(element).addClass('is-invalid');
    },
    unhighlight: function(element) {
      $(element).removeClass('is-invalid');
    },
    submitHandler: function(form) {
      // Debug: cek isi FormData
      var formData = new FormData(form);
      for (var pair of formData.entries()) {
        console.log(pair[0]+ ': ' + pair[1]);
      }

$.ajax({
  url: '/user/ajax/user',  // URL POST yang benar sesuai route
  type: 'POST',
  data: $('#form-id').serialize(),
  dataType: 'json',
  success: function(response) {
    if (response.status) {
      $('#myModal').modal('hide');
      $('#form-id')[0].reset();  // pastikan form di-reset
      $('.form-text.text-danger').text('');
      $('.is-invalid').removeClass('is-invalid');

      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: response.message,
        timer: 2000,
        showConfirmButton: false
      });

      if (typeof tableUser !== 'undefined') {
        tableUser.ajax.reload(null, false);
      }
    } else {
      // Tampilkan error validasi dari backend
      $('.form-text.text-danger').text('');
      $('.is-invalid').removeClass('is-invalid');
      $.each(response.msgField, function(field, errors) {
        $('#error-' + field).text(errors[0]);
        $('#' + field).addClass('is-invalid');
      });

      Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: response.message
      });
    }
  },
  error: function(xhr, status, error) {
    Swal.fire({
      icon: 'error',
      title: 'AJAX Error',
      text: error
    });
  }
});
