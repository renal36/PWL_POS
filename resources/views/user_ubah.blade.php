<!DOCTYPE html>
<html>
<head>
    <title>Form Ubah Data User</title>
</head>
<body>
    <h1>Form Ubah Data User</h1>
    <a href="/user">Kembali</a>
    <br><br>

    <form method="POST" action="/user/ubah_simpan/{{ $data->id }}">
        {{ csrf_field() }}
        {{ method_field('PUT') }} 

        <label>Username</label>
        <input type="text" name="username" placeholder="Masukan Username" value="{{ $data->username }}" required>
        <br>

        <label>Nama</label>
        <input type="text" name="nama" placeholder="Masukan Nama" value="{{ $data->nama }}" required>
        <br>

        <label>Password (kosongkan jika tidak diubah)</label>
        <input type="password" name="password" placeholder="Masukan Password baru (opsional)">
        <br>

        <label>Level ID</label>
        <input type="number" name="level_id" placeholder="Masukan Level ID" value="{{ $data->level_id }}" required>
        <br><br>

        <input type="submit" class="btn btn-success" value="Ubah">
    </form>
</body>
</html>
