<?php
session_start();
include '../../../koneksi.php'; // Pastikan jalur ini benar
include '../../views/header.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login/login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Ambil user_id dari session
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tapal Kuda | Keranjang</title>
    <link rel="stylesheet" href="../../css/keranjang.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <!-- BREADCRUMB -->
    <div class="wadah-breadcrumb">
        <nav class="navigasi-breadcrumb" aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li><a href="menu.html">Menu</a></li>
                <li class="aktif">Cart</li>
            </ul>
        </nav>
    </div>

    <!-- Table -->
    <div class="wadah">
        <h3 class="judul">Shopping Cart</h3>

        <div class="tabel-wadah">
            <table class="tabel">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Order</th>
                        <th>Memo</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Isi tabel keranjang -->
                    <?php
                    $query = "SELECT k.*, m.nama, m.url_foto FROM keranjang k 
                              JOIN menu m ON k.menu_id = m.id 
                              WHERE k.user_id = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            ?>
                            <tr>
                                <td><img src="../../asset/<?php echo $row['url_foto']; ?>" alt="<?php echo $row['nama']; ?>" width="50"></td>
                                <td><?php echo $row['nama']; ?></td>
                                <td><?php echo $row['catatan']; ?></td>
                                <td><?php echo $row['quantity']; ?></td>
                                <td>Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></td>
                                <td>Rp <?php echo number_format($row['quantity'] * $row['price'], 0, ',', '.'); ?></td>
                                <td>
                                    <form method="POST" action="../keranjang/logic/remove_keranjang.php">
                                        <input type="hidden" name="menu_id" value="<?php echo $row['menu_id']; ?>">
                                        <button type="submit">Remove</button>
                                    </form>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='7'>Keranjang Anda kosong.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="keranjang.js"></script>
</body>

</html>