<!DOCTYPE html>
<html>
<head>
    <title>Data Kategori Barang</title>
</head>
<body>
    <h1>Data Kategori Barang</h1>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Nama Kategori</th>
            <th>Deskripsi</th>
        </tr>
        @foreach ($data as $d)
        <tr>
            <td>{{ $d->id }}</td>
            <td>{{ $d->nama_kategori }}</td>
            <td>{{ $d->deskripsi }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
