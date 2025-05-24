<?php
// bagian header
include '../../views/admin/header.php';

// bagian sidebar
include '../../views/admin/sidebar.php';

// Koneksi ke database
include '../../koneksi.php';

// Query SQL untuk mengambil data ulasan
// Melakukan JOIN dengan tabel users dan menu untuk mendapatkan detail
$sql = "SELECT 
            r.id, 
            r.user_id, 
            u.username, 
            r.menu_id, 
            m.nama AS menu_nama,
            r.rating, 
            r.comment, 
            r.created_at
        FROM 
            reviews r
        JOIN 
            users u ON r.user_id = u.id
        JOIN 
            menu m ON r.menu_id = m.id
        ORDER BY 
            r.created_at DESC";

$result = mysqli_query($conn, $sql);
?>

<div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4><i class="fas fa-star me-1"></i> Data Ulasan</h4>
    </div>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'updated'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Ulasan berhasil diperbarui!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Ulasan berhasil dihapus!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <table id="datatablesSimple">
        <thead>
            <tr>
                <th>No</th>
                <th>User</th>
                <th>Menu</th>
                <th>Rating</th>
                <th>Komentar</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['username']) ?> (ID: <?= $row['user_id'] ?>)</td>
                        <td><?= htmlspecialchars($row['menu_nama']) ?> (ID: <?= $row['menu_id'] ?>)</td>
                        <td><?= htmlspecialchars($row['rating']) ?> / 5</td>
                        <td><?= htmlspecialchars($row['comment']) ?></td>
                        <td><?= $row['created_at'] ?></td>
                        <td>
                            <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>">Edit</a>
                            <a href="logic/delete-review.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin hapus ulasan ini?')">Hapus</a>
                        </td>
                    </tr>

                    <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $row['id'] ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="logic/edit-review.php" method="POST">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel<?= $row['id'] ?>">Edit Ulasan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <div class="mb-3">
                                            <label class="form-label">User</label>
                                            <input type="text" class="form-control" value="<?= htmlspecialchars($row['username']) ?>" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Menu</label>
                                            <input type="text" class="form-control" value="<?= htmlspecialchars($row['menu_nama']) ?>" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Rating</label>
                                            <select name="rating" class="form-select" required>
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <option value="<?= $i ?>" <?= $row['rating'] == $i ? 'selected' : '' ?>><?= $i ?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Komentar</label>
                                            <textarea name="comment" class="form-control" rows="3" required><?= htmlspecialchars($row['comment']) ?></textarea>
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
                <?php
                }
            } else {
                echo "<tr><td colspan='7' class='text-center'>Tidak ada ulasan.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</main>
<?php include '../../views/admin/footer.php'; ?>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../js/table-menu.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<script src="../../js/datatables-simple-demo.js"></script>