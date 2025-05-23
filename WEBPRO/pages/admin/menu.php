    <?php
    //bagian header
    include '../../views/admin/header.php';

    //bagian sidebar
    include '../../views/admin/sidebar.php';

    //Koneksi ke database
    include '../../koneksi.php';

    //query sql dasar
    $sql = "SELECT * FROM menu";

    ?>

    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Tambah Data</a>
            </div>
            <!-- <div>
                <button class="btn btn-success" id="downloadPdf">Download PDF</button>
            </div> -->
        </div>

        <?php
        //notifikasi berhasil edit menu
        if (isset($_GET['msg']) && $_GET['msg'] === 'updated'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                menu berhasil diperbarui!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php
        //notifikasi berhasil hapus menu
        if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                menu berhasil dihapus!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php
        //notifikasi berhasil tambah menu
        if (isset($_GET['msg']) && $_GET['msg'] === 'added'): ?>
            <div class="alert alert-primary alert-dismissible fade show" role="alert">
                menu berhasil ditambah!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>




        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Menu</th>
                    <th>Foto</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Kategori</th>
                    <th>Deskripsi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $query = mysqli_query($conn, "SELECT * FROM menu");
                while ($row = mysqli_fetch_assoc($query)) {
                    $statusBadge = ($row['status'] === 'tersedia') ? 'primary' : 'danger';
                    ?>
                    <tr>
                        <td><?= $no ?></td>
                        <td><?= $row['nama'] ?></td>
                        <td><img src="../../asset/<?= $row['url_foto'] ?>" width="60" alt="Foto Menu"></td>
                        <td>Rp <?= number_format($row['price'], 0, ',', '.') ?></td>
                        <td><?= $row['quantity'] ?></td>
                        <td><?= $row['type'] ?></td>
                        <td><?= $row['deskripsi'] ?></td>
                        <td><span class="badge bg-<?= $statusBadge ?>"><?= $row['status'] ?></span></td>
                        <td>
                            <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editModal<?= $row['id'] ?>">Edit</a>
                            <a href="logic/delete-menu.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
                                onclick="return confirm('Yakin ingin hapus?')">Delete</a>
                        </td>
                    </tr>

                    <!-- Modal Edit -->
                    <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1"
                        aria-labelledby="editModalLabel<?= $row['id'] ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="logic/edit-menu.php" method="POST" enctype="multipart/form-data">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel<?= $row['id'] ?>">Edit Menu</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <input type="hidden" name="url_foto_lama" value="<?= $row['url_foto'] ?>">

                                        <div class="mb-3">
                                            <label class="form-label">Nama</label>
                                            <input type="text" name="nama" class="form-control" value="<?= $row['nama'] ?>"
                                                required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Foto (Kosongkan jika tidak diubah)</label>
                                            <input type="file" name="foto" class="form-control">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Jenis</label>
                                            <select name="type" class="form-select" required>
                                                <?php
                                                $types = ['makanan_berat', 'minuman', 'cemilan', 'kopi'];
                                                foreach ($types as $type) {
                                                    $selected = $row['type'] === $type ? 'selected' : '';
                                                    echo "<option value='$type' $selected>$type</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Harga</label>
                                            <input type="text" name="price" class="form-control"
                                                value="<?= $row['price'] ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Quantity</label>
                                            <input type="number" name="quantity" class="form-control"
                                                value="<?= $row['quantity'] ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Deskripsi</label>
                                            <textarea name="deskripsi" class="form-control"
                                                required><?= $row['deskripsi'] ?></textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Status</label>
                                            <select name="status" class="form-select" required>
                                                <option value="tersedia" <?= $row['status'] === 'tersedia' ? 'selected' : '' ?>>Tersedia</option>
                                                <option value="habis" <?= $row['status'] === 'habis' ? 'selected' : '' ?>>Habis
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- End Modal -->
                    <?php $no++;
                } ?>
            </tbody>
        </table>

    </div>
    </div>
    </div>
    </main>

    <?php include '../../views/admin/footer.php'; ?>
    </div>
    </div>

    <?php
    if (isset($_GET['edit_id'])) {
        $id = $_GET['edit_id'];
        $query = mysqli_query($conn, "SELECT * FROM menu WHERE id = $id");
        $data = mysqli_fetch_assoc($query);
        if ($data) {
            ?>
            <!-- Modal Edit -->
            <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1"
                aria-labelledby="editModalLabel<?= $row['id'] ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="edit-menu.php" method="POST" enctype="multipart/form-data" class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit Menu</h5>
                            <a href="menu.php" class="btn-close"></a>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" value="<?= $data['id'] ?>">
                            <input type="hidden" name="url_foto_lama" value="<?= $data['url_foto'] ?>">

                            <div class="mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text" name="nama" class="form-control" value="<?= $data['nama'] ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Foto (Kosongkan jika tidak diubah)</label>
                                <input type="file" name="foto" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Jenis</label>
                                <select name="type" class="form-select" required>
                                    <?php
                                    $types = ['makanan_berat', 'minuman', 'cemilan', 'kopi'];
                                    foreach ($types as $type) {
                                        echo "<option value='$type' " . ($data['type'] == $type ? 'selected' : '') . ">$type</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Harga</label>
                                <input type="text" name="price" class="form-control" value="<?= $data['price'] ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Quantity</label>
                                <input type="number" name="quantity" class="form-control" value="<?= $data['quantity'] ?>"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control"><?= $data['deskripsi'] ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="tersedia" <?= $data['status'] == 'tersedia' ? 'selected' : '' ?>>Tersedia
                                    </option>
                                    <option value="habis" <?= $data['status'] == 'habis' ? 'selected' : '' ?>>Habis</option>
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <a href="menu.php" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>

            <?php
        }
    }
    ?>


    <!-- Modal Tambah Menu -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="logic/add-menu.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Tambah Menu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="addNama" class="form-label">Nama Menu</label>
                            <input type="text" class="form-control" id="addNama" name="nama" required>
                        </div>

                        <div class="mb-3">
                            <label for="addFoto" class="form-label">Foto</label>
                            <input type="file" class="form-control" id="addFoto" name="url_foto" required>
                        </div>

                        <div class="mb-3">
                            <label for="addType" class="form-label">Tipe</label>
                            <select class="form-select" id="addType" name="type" required>
                                <option value="makanan_berat">Makanan Berat</option>
                                <option value="minuman">Minuman</option>
                                <option value="cemilan">Cemilan</option>
                                <option value="kopi">Kopi</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="addPrice" class="form-label">Harga</label>
                            <input type="number" step="0.01" class="form-control" id="addPrice" name="price" required>
                        </div>

                        <div class="mb-3">
                            <label for="addQuantity" class="form-label">Stok</label>
                            <input type="number" class="form-control" id="addQuantity" name="quantity" required>
                        </div>

                        <div class="mb-3">
                            <label for="addDeskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="addDeskripsi" name="deskripsi" rows="3"
                                required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="addStatus" class="form-label">Status</label>
                            <select class="form-select" id="addStatus" name="status" required>
                                <option value="tersedia">Tersedia</option>
                                <option value="habis">Habis</option>
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Delete -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus data ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmDownloadModal" tabindex="-1" aria-labelledby="confirmDownloadModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDownloadModalLabel">Confirm Download</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to download the PDF?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmDownload">Download</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../js/table-menu.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="../../js/datatables-simple-demo.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>
</body>

</html>