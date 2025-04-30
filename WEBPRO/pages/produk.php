<?php
include '../koneksi.php';
$produk = mysqli_query($conn, "SELECT * FROM produk");
?>

<h2>Data Produk</h2>
<a href="tambah.php">Tambah Produk</a>
<table border="1" cellpadding="10">
    <tr>
        <th>No</th>
        <th>Nama Produk</th>
        <th>Foto</th>
        <th>Harga</th>
        <th>Stok</th>
        <th>Deskripsi</th>
        <th>Aksi</th>
    </tr>
    <?php $no=1; foreach ($produk as $p): ?>
    <tr>
        <td><?= $no++ ?></td>
        <td><?= $p['Nama_Produk'] ?></td>
        <td><img src="uploads/<?= $p['Foto'] ?>" width="100"></td>
        <td><?= $p['Harga'] ?></td>
        <td><?= $p['Stok'] ?></td>
        <td><?= $p['Deskripsi'] ?></td>
        <td>
            <a href="edit.php?id=<?= $p['ID_Produk'] ?>">Edit</a> |
            <a href="hapus.php?id=<?= $p['ID_Produk'] ?>" onclick="return confirm('Hapus data ini?')">Hapus</a>
        </td>
    </tr>
    <?php endforeach ?>
</table>
