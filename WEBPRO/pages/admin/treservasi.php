<?php
// bagian header
include '../../views/admin/header.php';

// bagian sidebar
include '../../views/admin/sidebar.php';

// Koneksi ke database
include '../../koneksi.php';

// Query SQL dasar
$sql = "SELECT r.*, u.username AS user_username, u.nama AS user_nama 
        FROM reservasi r 
        JOIN users u ON r.user_id = u.id";

?>

<div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Tambah Reservasi</a>
        </div>
        </div>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'updated'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Reservasi berhasil diperbarui!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Reservasi berhasil dihapus!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'added'): ?>
        <div class="alert alert-primary alert-dismissible fade show" role="alert">
            Reservasi berhasil ditambah!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>


    <table id="datatablesSimple">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Reservasi</th>
                <th>Pengguna</th>
                <th>Tanggal Reservasi</th>
                <th>Jumlah Orang</th>
                <th>Email</th>
                <th>No. Telp</th>
                <th>Pesan</th>
                <th>Status</th>
                <th>Dibuat Pada</th>
                <th>Diperbarui Pada</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $query = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($query)) {
                $statusBadge = '';
                switch ($row['status']) {
                    case 'pending':
                        $statusBadge = 'warning';
                        break;
                    case 'dikonfirmasi':
                        $statusBadge = 'success';
                        break;
                    case 'dibatalkan':
                        $statusBadge = 'danger';
                        break;
                }
                ?>
                <tr>
                    <td><?= $no ?></td>
                    <td><?= htmlspecialchars($row['kode_reservasi']) ?></td>
                    <td><?= htmlspecialchars($row['user_nama']) ?> (<?= htmlspecialchars($row['user_username']) ?>)</td>
                    <td><?= $row['tanggal_reservasi'] ?></td>
                    <td><?= htmlspecialchars($row['jumlah_orang']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['no_telp']) ?></td>
                    <td><?= htmlspecialchars($row['message']) ?></td>
                    <td><span class="badge bg-<?= $statusBadge ?>"><?= htmlspecialchars(ucfirst($row['status'])) ?></span></td>
                    <td><?= $row['created_at'] ?></td>
                    <td><?= $row['updated_at'] ?></td>
                    <td>
                        <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>">Edit</a>
                        <a href="logic/delete-reservasi.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin hapus reservasi ini?')">Hapus</a>
                    </td>
                </tr>

                <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $row['id'] ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="logic/edit-reservasi.php" method="POST">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel<?= $row['id'] ?>">Edit Reservasi</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">

                                    <div class="mb-3">
                                        <label class="form-label">Kode Reservasi</label>
                                        <input type="text" name="kode_reservasi" class="form-control" value="<?= htmlspecialchars($row['kode_reservasi']) ?>" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Pengguna (ID)</label>
                                        <input type="text" class="form-control" value="<?= htmlspecialchars($row['user_username']) ?>" readonly>
                                        <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Reservasi</label>
                                        <input type="date" name="tanggal_reservasi_date" class="form-control" value="<?= date('Y-m-d', strtotime($row['tanggal_reservasi'])) ?>" required>
                                        <input type="time" name="tanggal_reservasi_time" class="form-control mt-2" value="<?= date('H:i', strtotime($row['tanggal_reservasi'])) ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Jumlah Orang</label>
                                        <input type="number" name="jumlah_orang" class="form-control" value="<?= htmlspecialchars($row['jumlah_orang']) ?>" min="1" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($row['email']) ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">No. Telp</label>
                                        <input type="text" name="no_telp" class="form-control" value="<?= htmlspecialchars($row['no_telp']) ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Pesan</label>
                                        <textarea name="message" class="form-control"><?= htmlspecialchars($row['message']) ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-select" required>
                                            <option value="pending" <?= $row['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="dikonfirmasi" <?= $row['status'] === 'dikonfirmasi' ? 'selected' : '' ?>>Dikonfirmasi</option>
                                            <option value="dibatalkan" <?= $row['status'] === 'dibatalkan' ? 'selected' : '' ?>>Dibatalkan</option>
                                        </select>
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
            <form action="logic/add-reservasi.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Tambah Reservasi Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="addUserId" class="form-label">Pengguna (ID User)</label>
                        <input type="number" class="form-control" id="addUserId" name="user_id" placeholder="Masukkan ID Pengguna" required>
                    </div>
                    <div class="mb-3">
                        <label for="addTanggalReservasiDate" class="form-label">Tanggal Reservasi</label>
                        <input type="date" class="form-control" id="addTanggalReservasiDate" name="tanggal_reservasi_date" required>
                        <input type="time" class="form-control mt-2" id="addTanggalReservasiTime" name="tanggal_reservasi_time" required>
                    </div>
                    <div class="mb-3">
                        <label for="addJumlahOrang" class="form-label">Jumlah Orang</label>
                        <input type="number" class="form-control" id="addJumlahOrang" name="jumlah_orang" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="addEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="addEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="addNoTelp" class="form-label">No. Telepon</label>
                        <input type="text" class="form-control" id="addNoTelp" name="no_telp" required>
                    </div>
                    <div class="mb-3">
                        <label for="addMessage" class="form-label">Pesan</label>
                        <textarea class="form-control" id="addMessage" name="message" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="addStatus" class="form-label">Status</label>
                        <select class="form-select" id="addStatus" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="dikonfirmasi">Dikonfirmasi</option>
                            <option value="dibatalkan">Dibatalkan</option>
                        </select>
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
<script src="../../js/table-menu.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<script src="../../js/datatables-simple-demo.js"></script>
</body>
</html>