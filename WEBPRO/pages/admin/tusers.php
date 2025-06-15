<?php
// bagian header
include '../../views/admin/header.php';

// bagian sidebar
include '../../views/admin/sidebar.php';

// Koneksi ke database
include '../../koneksi.php';

// Fetch all gender types for dropdowns
$gender_types_query = mysqli_query($conn, "SELECT id, gender_name FROM gender_types ORDER BY gender_name ASC");
$gender_types = [];
while ($row = mysqli_fetch_assoc($gender_types_query)) {
    $gender_types[] = $row;
}

// Fetch all user roles for dropdowns
$user_roles_query = mysqli_query($conn, "SELECT id, role_name FROM user_roles ORDER BY role_name ASC");
$user_roles = [];
while ($row = mysqli_fetch_assoc($user_roles_query)) {
    $user_roles[] = $row;
}

// Query SQL dasar - Updated to join with user_roles and gender_types
$sql = "SELECT u.*, ur.role_name, gt.gender_name 
        FROM users u
        LEFT JOIN user_roles ur ON u.role_id = ur.id
        LEFT JOIN gender_types gt ON u.gender_id = gt.id
        ORDER BY u.id DESC"; // Order by ID or created_at as needed

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
                $query = mysqli_query($conn, $sql); // Use the modified $sql query
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
                        <td><?= htmlspecialchars($row['gender_name'] ?: 'N/A') ?></td>
                        <td><?= htmlspecialchars($row['alamat']) ?></td>
                        <td><?= htmlspecialchars($row['role_name'] ?: 'N/A') ?></td>
                        <td><?= $row['created_at'] ?></td>
                        <td><?= $row['updated_at'] ?></td>
                        <td>
                            <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editModal<?= $row['id'] ?>">Edit</a>
                            <a href="logic/delete-user.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
                                onclick="return confirm('Yakin ingin hapus pengguna ini?')">Hapus</a>
                        </td>
                    </tr>

                    <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1"
                        aria-labelledby="editModalLabel<?= $row['id'] ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="logic/edit-user.php" method="POST" enctype="multipart/form-data">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel<?= $row['id'] ?>">Edit Pengguna</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <input type="hidden" name="profile_picture_lama"
                                            value="<?= $row['profile_picture'] ?>">

                                        <div class="mb-3">
                                            <label class="form-label">Username</label>
                                            <input type="text" name="username" class="form-control"
                                                value="<?= htmlspecialchars($row['username']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Nama Lengkap</label>
                                            <input type="text" name="nama" class="form-control"
                                                value="<?= htmlspecialchars($row['nama']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control"
                                                value="<?= htmlspecialchars($row['email']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">No. Telepon</label>
                                            <input type="text" name="no_telp" class="form-control"
                                                value="<?= htmlspecialchars($row['no_telp']) ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Gender</label>
                                            <select name="gender" class="form-select">
                                                <?php foreach ($gender_types as $gender_option): ?>
                                                    <option value="<?= htmlspecialchars($gender_option['gender_name']) ?>"
                                                        <?= (strtolower($row['gender_name']) === strtolower($gender_option['gender_name'])) ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($gender_option['gender_name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Alamat</label>
                                            <textarea name="alamat"
                                                class="form-control"><?= htmlspecialchars($row['alamat']) ?></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Foto Profil (Kosongkan jika tidak diubah)</label>
                                            <input type="file" name="profile_picture" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Role</label>
                                            <select name="role" class="form-select" required>
                                                <?php foreach ($user_roles as $role_option): ?>
                                                    <option value="<?= htmlspecialchars($role_option['role_name']) ?>"
                                                        <?= (strtolower($row['role_name']) === strtolower($role_option['role_name'])) ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($role_option['role_name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Password (Biarkan kosong jika tidak diubah)</label>
                                            <input type="password" name="password" class="form-control"
                                                placeholder="Isi untuk mengubah password">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Batal</button>
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
                            <?php foreach ($gender_types as $gender_option): ?>
                                <option value="<?= htmlspecialchars($gender_option['gender_name']) ?>">
                                    <?= htmlspecialchars($gender_option['gender_name']) ?>
                                </option>
                            <?php endforeach; ?>
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
                            <?php foreach ($user_roles as $role_option): ?>
                                <option value="<?= htmlspecialchars($role_option['role_name']) ?>">
                                    <?= htmlspecialchars($role_option['role_name']) ?>
                                </option>
                            <?php endforeach; ?>
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

<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
    crossorigin="anonymous"></script>
<script src="../../js/datatables-simple-demo.js"></script>
</body>
</html>