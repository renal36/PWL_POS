<form id="formCreateUserAjax" method="POST" action="{{ url('user/ajax') }}">
    @csrf
    <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Level</label>
        <select name="level" class="form-control" required>
            <option value="admin">Admin</option>
            <option value="user">User</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
</form>
