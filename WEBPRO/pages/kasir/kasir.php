<?php
include '../../koneksi.php'; // Koneksi ke database

// Query untuk menghitung jumlah menu berdasarkan kategori
$countQuery = "SELECT type, COUNT(*) as total FROM menu GROUP BY type";
$countResult = $conn->query($countQuery);

$menuCounts = [];
if ($countResult->num_rows > 0) {
    while ($countRow = $countResult->fetch_assoc()) {
        $menuCounts[$countRow['type']] = $countRow['total'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tapal Kuda | Kasir</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link href="../../css/kasir.css" rel="stylesheet">
    <style>
        /* Gunakan style yang sudah ada di file Anda */
        /* Tidak ada perubahan pada desain */
        <?php include 'style.css'; ?>
    </style>
</head>

<body>
    <div class="container" role="main">
        <aside class="sidebar" aria-label="Sidebar navigation">
            <button aria-label="Hot Dishes"><i class="fas fa-utensils"></i></button>
            <button aria-label="Home"><i class="fas fa-home"></i></button>
            <button aria-label="Settings"><i class="fas fa-cog"></i></button>
            <button aria-label="Clock"><i class="fas fa-clock"></i></button>
            <button aria-label="Mail"><i class="fas fa-envelope"></i></button>
            <button aria-label="Notification"><i class="fas fa-bell"></i></button>
            <button aria-label="User"><i class="fas fa-user"></i></button>
        </aside>
        <main>
            <header>
                <h1>Tapal Kuda</h1>
                <p>Tuesday, 29 April 2025</p>
            </header>
            <nav class="tabs" aria-label="Dish categories">
                <a href="kasir.php" class="<?php echo (!isset($_GET['type']) || empty($_GET['type'])) ? 'active' : ''; ?>">All</a>
                <a href="kasir.php?type=kopi" class="<?php echo (isset($_GET['type']) && $_GET['type'] == 'kopi') ? 'active' : ''; ?>">Coffe</a>
                <a href="kasir.php?type=minuman" class="<?php echo (isset($_GET['type']) && $_GET['type'] == 'minuman') ? 'active' : ''; ?>">Non Coffe</a>
                <a href="kasir.php?type=makanan_berat" class="<?php echo (isset($_GET['type']) && $_GET['type'] == 'makanan_berat') ? 'active' : ''; ?>">Foods</a>
                <a href="kasir.php?type=cemilan" class="<?php echo (isset($_GET['type']) && $_GET['type'] == 'cemilan') ? 'active' : ''; ?>">Snacks</a>
                <div class="search-container">
                    <input type="search" placeholder="Search for food, coffee, etc.." aria-label="Search for food, coffee, etc." />
                    <i class="fas fa-search icon-search" aria-hidden="true"></i>
                </div>
            </nav>
            <section class="choose-dishes" aria-label="Choose Dishes">
                <h2>Choose Dishes</h2>
                <div class="dishes-grid">
                    <?php
                    // Query dasar
                    $sql = "SELECT * FROM menu WHERE 1=1";

                    // Filter berdasarkan kategori
                    if (isset($_GET['type']) && !empty($_GET['type'])) {
                        $type = $_GET['type'];
                        $sql .= " AND type = '$type'";
                    }

                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            ?>
                            <article class="dish-card">
                                <img src="../../asset/<?php echo $row['url_foto']; ?>" alt="<?php echo $row['nama']; ?>" />
                                <h3><?php echo $row['nama']; ?></h3>
                                <p class="price">Rp. <?php echo number_format($row['price'], 0, ',', '.'); ?></p>
                                <p class="available"><?php echo $row['quantity']; ?> available</p>
                            </article>
                            <?php
                        }
                    } else {
                        echo "<p>No dishes available.</p>";
                    }
                    ?>
                </div>
            </section>
        </main>
        <aside class="orders-panel" aria-label="Orders panel">
            <header>
                <h2>Orders <span>#001</span></h2>
                <nav class="order-types" aria-label="Order types">
                    <button class="active" type="button">Dine In</button>
                    <button type="button">Take Away</button>
                    <button type="button">Delivery</button>
                </nav>
            </header>
            <ul class="order-list">
                <li>
                    <img src="https://storage.googleapis.com/a1aa/image/8294678e-e685-4081-dce2-181e85ee96b9.jpg" alt="Cappuccino" width="40" height="40" />
                    <div class="order-info">
                        <p class="name">Cappuccino</p>
                        <p class="price">Rp. 15.000</p>
                        <input type="text" placeholder="Order Note..." aria-label="Order note for Cappuccino" />
                    </div>
                    <div class="order-qty-delete">
                        <span class="qty">1</span>
                        <button aria-label="Delete Cappuccino order" type="button"><i class="fas fa-trash-alt"></i></button>
                    </div>
                </li>
            </ul>
            <footer>
                <div class="discount">
                    <span>Discount</span>
                    <span>Rp. 10.000</span>
                </div>
                <div class="subtotal">
                    <span>Sub total</span>
                    <span>Rp. 105.000</span>
                </div>
                <button type="button">Continue to Payment</button>
            </footer>
        </aside>
    </div>
</body>

</html>