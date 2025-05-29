<?php
// bagian header
include '../../views/admin/header.php';

// bagian sidebar
include '../../views/admin/sidebar.php';

// Koneksi ke database
include '../../koneksi.php';

// Query SQL dasar
$sql = "SELECT * FROM users";
?>

<h1 class="mt-4">Table User</h1>
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-table me-1"></i>
                            Data User
                        </div>
                    </div>

<div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Tambah Pengguna</a>
        </div>
        </div>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'updated'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Pengguna berhasil diperbarui!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Pengguna berhasil dihapus!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'added'): ?>
        <div class="alert alert-primary alert-dismissible fade show" role="alert">
            Pengguna berhasil ditambah!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>


    <table id="datatablesSimple">
        <thead>
            <tr>
                <th>No</th>
                <th>Foto Profil</th>
                <th>Username</th>
                <th>Nama Lengkap</th>
                <th>Email</th>
                <th>No. Telp</th>
                <th>Gender</th>
                <th>Alamat</th>
                <th>Role</th>
                <th>Dibuat Pada</th>
                <th>Diperbarui Pada</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $query = mysqli_query($conn, "SELECT * FROM users");
            while ($row = mysqli_fetch_assoc($query)) {
                $profile_picture_path = !empty($row['profile_picture']) ? '../../asset/user_picture/' . $row['profile_picture'] : '../../asset/user_picture/default-avatar.png';
                ?>
                <tr>
                    <td><?= $no ?></td>
                    <td><img src="<?= $profile_picture_path ?>" width="50" alt="Foto Profil"></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['nama']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['no_telp']) ?></td>
                    <td><?= htmlspecialchars($row['gender']) ?></td>
                    <td><?= htmlspecialchars($row['alamat']) ?></td>
                    <td><?= htmlspecialchars($row['role']) ?></td>
                    <td><?= $row['created_at'] ?></td>
                    <td><?= $row['updated_at'] ?></td>
                    <td>
                        <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>">Edit</a>
                        <a href="logic/delete-user.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin hapus pengguna ini?')" >Hapus</a>
                    </td>
                </tr>

                <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $row['id'] ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="logic/edit-user.php" method="POST" enctype="multipart/form-data">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel<?= $row['id'] ?>">Edit Pengguna</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <input type="hidden" name="profile_picture_lama" value="<?= $row['profile_picture'] ?>">

                                    <div class="mb-3">
                                        <label class="form-label">Username</label>
                                        <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($row['username']) ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nama Lengkap</label>
                                        <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($row['nama']) ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($row['email']) ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">No. Telepon</label>
                                        <input type="text" name="no_telp" class="form-control" value="<?= htmlspecialchars($row['no_telp']) ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Gender</label>
                                        <select name="gender" class="form-select">
                                            <option value="male" <?= $row['gender'] === 'male' ? 'selected' : '' ?>>Male</option>
                                            <option value="female" <?= $row['gender'] === 'female' ? 'selected' : '' ?>>Female</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Alamat</label>
                                        <textarea name="alamat" class="form-control"><?= htmlspecialchars($row['alamat']) ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Foto Profil (Kosongkan jika tidak diubah)</label>
                                        <input type="file" name="profile_picture" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Role</label>
                                        <select name="role" class="form-select" required>
                                            <option value="admin" <?= $row['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                            <option value="kasir" <?= $row['role'] === 'kasir' ? 'selected' : '' ?>>Kasir</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Password (Biarkan kosong jika tidak diubah)</label>
                                        <input type="password" name="password" class="form-control" placeholder="Isi untuk mengubah password">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php $no++;
            } ?>
        </tbody>
    </table>
</div>

</main>
<?php include '../../views/admin/footer.php'; ?>
</div>
</div>

<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="logic/add-user.php" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Tambah Pengguna Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="addUsername" class="form-label">Username</label>
                        <input type="text" class="form-control" id="addUsername" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="addNama" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="addNama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="addEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="addEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="addNoTelp" class="form-label">No. Telepon</label>
                        <input type="text" class="form-control" id="addNoTelp" name="no_telp">
                    </div>
                    <div class="mb-3">
                        <label for="addGender" class="form-label">Gender</label>
                        <select class="form-select" id="addGender" name="gender">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="addAlamat" class="form-label">Alamat</label>
                        <textarea class="form-control" id="addAlamat" name="alamat" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="addProfilePicture" class="form-label">Foto Profil</label>
                        <input type="file" class="form-control" id="addProfilePicture" name="profile_picture">
                    </div>
                    <div class="mb-3">
                        <label for="addRole" class="form-label">Role</label>
                        <select class="form-select" id="addRole" name="role" required>
                            <option value="admin">Admin</option>
                            <option value="kasir">Kasir</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="addPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="addPassword" name="password" required>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<script src="../../js/datatables-simple-demo.js"></script>
</body>
</html>