<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tapal Kuda | Menu</title>
    <link href="../../css/menu.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <?php
    include '../../views/header.php';
    include '../../koneksi.php';

    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    // Query untuk menghitung jumlah menu berdasarkan kategori
    $countQuery = "SELECT type, COUNT(*) as total FROM menu GROUP BY type";
    $countResult = $conn->query($countQuery);

    $menuCounts = [];
    if ($countResult->num_rows > 0) {
        while ($countRow = $countResult->fetch_assoc()) {
            $menuCounts[$countRow['type']] = $countRow['total'];
        }
    }

    // Ambil daftar menu yang difavoritkan oleh user yang sedang login
    $favorite_menus = [];
    if ($user_id) {
        $query_favorites = "SELECT menu_id FROM favorit WHERE user_id = $user_id";
        $result_favorites = $conn->query($query_favorites);
        if ($result_favorites) {
            while ($row_fav = $result_favorites->fetch_assoc()) {
                $favorite_menus[] = $row_fav['menu_id'];
            }
        }
    }

    // Ambil daftar menu
    if (isset($_GET['type']) && $_GET['type'] == 'favorit' && $user_id) {
        $sql = "SELECT * FROM menu WHERE id IN (" . implode(',', $favorite_menus ?: [0]) . ") AND quantity > 0";
    } else if (isset($_GET['type']) && !empty($_GET['type'])) {
        $type = $conn->real_escape_string($_GET['type']);
        $sql = "SELECT * FROM menu WHERE type = '$type' AND quantity > 0";
    } else {
        $sql = "SELECT * FROM menu WHERE quantity > 0";
    }

    $menu_result = $conn->query($sql);
    ?>

    <div class="container-banner">
        <div class="overlay"></div>
        <div class="judul">Menu</div>
    </div>

    <div class="container">
        <ul class="nav-pills">
            <div class="kategori">PRODUCT CATEGORIES</div>
            <li>
                <a class="nav-link <?php echo (!isset($_GET['type']) || empty($_GET['type'])) ? 'active' : ''; ?>" href="menu.php">
                    All <span>(<?php echo array_sum($menuCounts); ?>)</span>
                </a>
            </li>
            <li>
                <a class="nav-link <?php echo (isset($_GET['type']) && $_GET['type'] == 'kopi') ? 'active' : ''; ?>" href="menu.php?type=kopi">
                    Coffe <span>(<?php echo isset($menuCounts['kopi']) ? $menuCounts['kopi'] : 0; ?>)</span>
                </a>
            </li>
            <li>
                <a class="nav-link <?php echo (isset($_GET['type']) && $_GET['type'] == 'minuman') ? 'active' : ''; ?>" href="menu.php?type=minuman">
                    Non Coffe <span>(<?php echo isset($menuCounts['minuman']) ? $menuCounts['minuman'] : 0; ?>)</span>
                </a>
            </li>
            <li>
                <a class="nav-link <?php echo (isset($_GET['type']) && $_GET['type'] == 'makanan_berat') ? 'active' : ''; ?>" href="menu.php?type=makanan_berat">
                    Foods <span>(<?php echo isset($menuCounts['makanan_berat']) ? $menuCounts['makanan_berat'] : 0; ?>)</span>
                </a>
            </li>
            <li>
                <a class="nav-link <?php echo (isset($_GET['type']) && $_GET['type'] == 'cemilan') ? 'active' : ''; ?>" href="menu.php?type=cemilan">
                    Snacks <span>(<?php echo isset($menuCounts['cemilan']) ? $menuCounts['cemilan'] : 0; ?>)</span>
                </a>
            </li>
            <li>
                <a class="nav-link <?php echo (isset($_GET['type']) && $_GET['type'] == 'favorit') ? 'active' : ''; ?>" href="menu.php?type=favorit">
                    Favorit <span>(<?php echo count($favorite_menus); ?>)</span>
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="semua">
                <div class="row">
                    <?php
                    if ($menu_result && $menu_result->num_rows > 0) {
                        while ($row = $menu_result->fetch_assoc()) {
                            echo '<div class="col">';
                            echo '<div class="card">';
                            echo '<div class="image-wrapper">';
                            echo '<img src="../../asset/' . htmlspecialchars($row['url_foto']) . '" alt="' . htmlspecialchars($row['nama']) . '">';
                            echo '</div>';
                            echo '<div class="card-body">';
                            echo '<div class="card-title">' . htmlspecialchars($row['nama']) . '</div>';
                            echo '<div class="card-title">Rp ' . number_format($row['price'], 0, ',', '.') . '</div>';
                            echo '<div class="card-title" style="color:#6d4c2b;">Tersedia: ' . htmlspecialchars($row['quantity']) . '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo "<p style='margin-top:1rem;'>Menu tidak tersedia.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php include '../../views/footer.php'; ?>
</body>
</html>


    <!-- Modal Notifikasi -->
    <div id="notifModal"
        style="display:none;position:fixed;z-index:9999;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.2);">
        <div
            style="background:#fff;padding:24px 32px;border-radius:8px;max-width:350px;margin:120px auto 0;box-shadow:0 2px 8px rgba(0,0,0,0.15);text-align:center;position:relative;">
            <span id="notifModalClose"
                style="position:absolute;top:8px;right:16px;cursor:pointer;font-size:22px;">&times;</span>
            <div id="notifModalMsg" style="color:#155724;font-size:16px;"></div>
        </div>
    </div>
    <?php if (isset($_SESSION['favorit_message'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var notifModal = document.getElementById('notifModal');
                var notifMsg = document.getElementById('notifModalMsg');
                notifMsg.innerHTML = <?php echo json_encode($_SESSION['favorit_message']); ?>;
                notifModal.style.display = 'block';
                document.getElementById('notifModalClose').onclick = function () {
                    notifModal.style.display = 'none';
                };
                notifModal.onclick = function (e) {
                    if (e.target === notifModal) notifModal.style.display = 'none';
                };
                setTimeout(function () {
                    notifModal.style.display = 'none';
                }, 2000);
            });
        </script>
        <?php unset($_SESSION['favorit_message']); endif; ?>

    <?php
    include '../../views/footer.php';
    ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Contoh jQuery untuk dropdown sederhana
        $(document).ready(function () {
            $('.dropdown-toggle').click(function () {
                $(this).next('.dropdown-menu').toggle(); // Atau .slideToggle()
            });

            // Tutup dropdown jika klik di luar
            $(document).click(function (event) {
                if (!$(event.target).closest('.dropdown').length) {
                    $('.dropdown-menu').hide();
                }
            });
        });
        document.addEventListener('DOMContentLoaded', function () {
            // Logika untuk modal yang sudah ada
            document.querySelectorAll('.openModal').forEach(button => {
                button.addEventListener('click', function () {
                    document.getElementById('modalOverlay').style.display = 'block';
                    document.getElementById('modalImage').src = this.getAttribute('data-foto');
                    document.getElementById('modalName').textContent = this.getAttribute('data-nama');
                    document.getElementById('modalPrice').textContent = parseInt(this.getAttribute('data-harga')).toLocaleString('id-ID');
                    document.getElementById('modalStok').textContent = this.getAttribute('data-stok');
                    document.getElementById('modalMenuId').value = this.getAttribute('data-id');
                    document.getElementById('modalQuantityInput').value = 1;
                });
            });

            document.getElementById('closeModal').addEventListener('click', () => {
                document.getElementById('modalOverlay').style.display = 'none';
            });

            // --- Logika untuk Fitur Favorit ---
            const favoriteButtons = document.querySelectorAll('.favorite-btn');

            favoriteButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault(); // Mencegah link berpindah halaman

                    const menuId = this.dataset.menuId;
                    const icon = this.querySelector('i');

                    // Jika tombol dinonaktifkan (misal karena belum login), tampilkan alert
                    if (this.classList.contains('disabled')) {
                        alert('Anda harus login untuk menambahkan ke favorit.');
                        return;
                    }

                    fetch('logic/toggle_favorit.php', { // Path relatif ke file logic Anda
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'menu_id=' + menuId
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                if (data.action === 'added') {
                                    icon.classList.remove('far');
                                    icon.classList.add('fas');
                                    alert(data.message); // Opsional: tampilkan notifikasi
                                } else if (data.action === 'removed') {
                                    icon.classList.remove('fas');
                                    icon.classList.add('far');
                                    alert(data.message); // Opsional: tampilkan notifikasi
                                }
                            } else {
                                alert('Error: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat memproses permintaan favorit.');
                        });
                });
            });
        });
    </script>
</body>

</html>