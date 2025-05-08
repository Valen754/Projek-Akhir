<?php
include '../../koneksi.php'; // Koneksi ke database
include '../../views/kasir/sidebar.php'; // Header file

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
        /* Container */
        .container {
            display: flex;
            height: 100vh;
            width: 100%;
            overflow: hidden;
        }

        /* Sidebar */
        .sidebar {
            background-color: #1c2431;
            width: 80px;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 16px 0;
            gap: 24px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
        }

        /* Main Content */
        main {
            flex: 1;
            padding: 32px;
            margin-left: 80px;
            margin-right: 384px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        /* Order Panel */
        .orders-panel {
            background-color: #2a3345;
            width: 384px;
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
            padding: 32px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100vh;
            position: fixed;
            right: 0;
            top: 0;
            overflow-y: auto;
            z-index: 1000;
        }

        /* Dishes Grid */
        .dishes-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
            max-height: calc(100vh - 250px);
            overflow-y: auto;
            padding-right: 16px;
        }

        /* Dish Card */
        .dish-card {
            background-color: #2a3345;
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            color: #9ca3af;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .dish-card img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 12px;
        }

        .dish-card h3 {
            color: white;
            font-weight: 600;
            margin: 0 0 8px 0;
            font-size: 14px;
        }

        .dish-card p.price {
            margin: 0 0 4px 0;
            font-size: 12px;
        }

        .dish-card p.available {
            margin: 0;
            font-size: 10px;
            color: #6b7280;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <?php include '../../views/kasir/sidebar.php'; ?>
        </aside>

        <!-- Main Content -->
        <main>
            <header>
                <h1>Tapal Kuda</h1>
                <p>Tuesday, 29 April 2025</p>
            </header>
            <nav class="tabs">
                <a href="kasir.php" class="<?php echo (!isset($_GET['type']) || empty($_GET['type'])) ? 'active' : ''; ?>">All</a>
                <a href="kasir.php?type=kopi" class="<?php echo (isset($_GET['type']) && $_GET['type'] == 'kopi') ? 'active' : ''; ?>">Coffe</a>
                <a href="kasir.php?type=minuman" class="<?php echo (isset($_GET['type']) && $_GET['type'] == 'minuman') ? 'active' : ''; ?>">Non Coffe</a>
                <a href="kasir.php?type=makanan_berat" class="<?php echo (isset($_GET['type']) && $_GET['type'] == 'makanan_berat') ? 'active' : ''; ?>">Foods</a>
                <a href="kasir.php?type=cemilan" class="<?php echo (isset($_GET['type']) && $_GET['type'] == 'cemilan') ? 'active' : ''; ?>">Snacks</a>
                <div class="search-container">
                    <input type="search" placeholder="Search for food, coffee, etc.." aria-label="Search for food, coffee, etc." />
                    <i class="fas fa-search icon-search"></i>
                </div>
            </nav>
            <section class="choose-dishes">
                <h2>Choose Dishes</h2>
                <div class="dishes-grid">
                    <?php
                    $sql = "SELECT * FROM menu WHERE 1=1";
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
                                <button class="add-to-order" 
                                        data-id="<?php echo $row['id']; ?>" 
                                        data-name="<?php echo $row['nama']; ?>" 
                                        data-price="<?php echo $row['price']; ?>">Add to Order</button>
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

        <!-- Order Panel -->
        <aside class="orders-panel">
            <header>
                <h2>Orders <span>#001</span></h2>
                <nav class="order-types">
                    <button class="active">Dine In</button>
                    <button>Take Away</button>
                    <button>Delivery</button>
                </nav>
            </header>
            <ul class="order-list">
                <!-- Pesanan akan ditambahkan di sini -->
            </ul>
            <footer>
                
                <div class="subtotal">
                    <span>Sub total</span>
                    <span>Total</span>
                </div>
                <button>Continue to Payment</button>
            </footer>
        </aside>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const orderList = document.querySelector('.order-list');
            const subtotalElement = document.querySelector('.subtotal span');

            // Event listener untuk tombol "Add to Order"
            document.querySelectorAll('.add-to-order').forEach(button => {
                button.addEventListener('click', () => {
                    const id = button.dataset.id;
                    const name = button.dataset.name;
                    const price = parseInt(button.dataset.price);

                    // Cek apakah item sudah ada di daftar pesanan
                    let existingItem = orderList.querySelector(`li[data-id="${id}"]`);
                    if (existingItem) {
                        // Jika sudah ada, tambahkan jumlahnya
                        let qtyElement = existingItem.querySelector('.qty');
                        qtyElement.textContent = parseInt(qtyElement.textContent) + 1;

                        // Perbarui total harga item
                        let itemTotalElement = existingItem.querySelector('.item-total');
                        itemTotalElement.textContent = `Rp. ${(price * parseInt(qtyElement.textContent)).toLocaleString()}`;
                    } else {
                        // Jika belum ada, tambahkan item baru
                        const orderItem = document.createElement('li');
                        orderItem.setAttribute('data-id', id);
                        orderItem.innerHTML = `
                            <img src="../../asset/default.jpg" alt="${name}" width="40" height="40" />
                            <div class="order-info">
                                <p class="name">${name}</p>
                                <p class="price">Rp. ${price.toLocaleString()}</p>
                                <p class="item-total">Rp. ${price.toLocaleString()}</p>
                            </div>
                            <div class="order-qty-delete">
                                <button class="decrease-qty">-</button>
                                <span class="qty">1</span>
                                <button class="increase-qty">+</button>
                                <button class="delete-item"><i class="fas fa-trash-alt"></i></button>
                            </div>
                        `;
                        orderList.appendChild(orderItem);

                        // Tambahkan event listener untuk tombol hapus dan kontrol kuantitas
                        orderItem.querySelector('.delete-item').addEventListener('click', () => {
                            orderItem.remove();
                            updateSubtotal();
                        });

                        orderItem.querySelector('.increase-qty').addEventListener('click', () => {
                            let qtyElement = orderItem.querySelector('.qty');
                            qtyElement.textContent = parseInt(qtyElement.textContent) + 1;
                            let itemTotalElement = orderItem.querySelector('.item-total');
                            itemTotalElement.textContent = `Rp. ${(price * parseInt(qtyElement.textContent)).toLocaleString()}`;
                            updateSubtotal();
                        });

                        orderItem.querySelector('.decrease-qty').addEventListener('click', () => {
                            let qtyElement = orderItem.querySelector('.qty');
                            if (parseInt(qtyElement.textContent) > 1) {
                                qtyElement.textContent = parseInt(qtyElement.textContent) - 1;
                                let itemTotalElement = orderItem.querySelector('.item-total');
                                itemTotalElement.textContent = `Rp. ${(price * parseInt(qtyElement.textContent)).toLocaleString()}`;
                                updateSubtotal();
                            }
                        });
                    }

                    // Perbarui subtotal
                    updateSubtotal();
                });
            });

            // Fungsi untuk memperbarui subtotal
            function updateSubtotal() {
                let subtotal = 0;
                orderList.querySelectorAll('li').forEach(item => {
                    const itemTotal = item.querySelector('.item-total').textContent.replace(/[^\d]/g, '');
                    subtotal += parseInt(itemTotal);
                });
                subtotalElement.textContent = `Rp. ${subtotal.toLocaleString()}`;
            }
        });
    </script>
</body>

</html>