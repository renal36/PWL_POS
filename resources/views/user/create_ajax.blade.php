<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
     <form id="formCreateUserAjax" method="POST" action="{{ route('user.ajax.store') }}">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Tambah User (AJAX)</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"> {{-- Ganti data-bs-dismiss jadi data-dismiss untuk Bootstrap 4 --}}
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
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- PERBAIKAN untuk error aria-hidden (ditempatkan di bawah modal, bisa juga di layout utama) -->
<script>
  $('#myModal')
    .on('show.bs.modal', function () {
      $(this).removeAttr('aria-hidden');
    })
    .on('hidden.bs.modal', function () {
      $(this).attr('aria-hidden', 'true');
    });
</script>

<script>
$(document).ready(function() {
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });

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
      var formData = new FormData(form);

      $.ajax({
        url: '/user/ajax/user',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
          if (response.status) {
            $('#myModal').modal('hide');
            $('#formCreateUserAjax')[0].reset();
            $('.form-text.text-danger').text('');
            $('.is-invalid').removeClass('is-invalid');

            Swal.fire({
              icon: 'success',
              title: 'Berhasil',
              text: response.message,
              timer: 2000,
              showConfirmButton: false
            });

            if (typeof tableUser !== 'undefined' && typeof tableUser.ajax !== 'undefined') {
              tableUser.ajax.reload(null, false);
            }
          } else {
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
          console.error("AJAX Error:", xhr.responseText, status, error);
          let errorMessage = "Terjadi kesalahan AJAX.";
          if (xhr.status === 405) {
              errorMessage = "Kesalahan: Metode tidak diizinkan. Periksa method dan URL route.";
          } else if (xhr.status === 422) {
              try {
                  let responseData = JSON.parse(xhr.responseText);
                  errorMessage = responseData.message || "Validasi gagal.";
              } catch (e) {
                  errorMessage = "Validasi gagal (format respons tidak sesuai).";
              }
          } else {
              errorMessage += ` Status: ${xhr.status} (${xhr.statusText})`;
          }
          Swal.fire({
            icon: 'error',
            title: 'AJAX Error',
            text: errorMessage
          });
        }
      });
    }
  });
});
</script>
