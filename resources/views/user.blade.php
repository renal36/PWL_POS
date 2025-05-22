<!DOCTYPE html>
<html>
<head>
    <title>Data Kategori Barang</title>
</head>
<body>
    <h1>Data User dengan Level 2</h1>
    <table border="1" cellpadding="2" cellspacing="0">
        <tr>
            <td>ID</td>
            <td>Username</td>
            <td>Nama</td>
            <td>ID Level Pengguna</td>
            <td>Kode Level</td>
            <td>Nama Level</td>
            <th>Aksi</th>
        </tr>
        @foreach ($data as $data) 
        <tr>
            <td>{{ $data->user_id }}</td> 
            <td>{{ $data->username }}</td>
            <td>{{ $data->nama }}</td>-
            <td>{{ $data->level_id }}</td>
            <td>{{ $data->level->level_kode }}</td>
            <td>{{ $data->level->level_nama }}</td>
                <a href="/user/ubah/{{ $data->user_id }}">Ubah</a> | 
                <a href="/user/hapus/{{ $data->user_id }}">Hapus</a>
            </td>
        </tr>
        @endforeach
    </table>
</body>
</html>
