<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Tapal Kuda</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter&display=swap');

        * {
            box-sizing: border-box;
        }

        html,
        body {
            width: 100%;
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Inter', sans-serif;
            background-color: #1c2431;
        }


        .container {
            display: flex;
            /* Menggunakan flexbox untuk menata elemen dalam satu baris */
            height: 100vh;
            /* Memastikan tinggi kontainer mengisi seluruh layar */
        }

        /* Sidebar */
        .sidebar {
            background-color: #222b3a;
            width: 80px;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 32px 0;
            gap: 40px;
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
            height: 100vh;
            /* Mengisi seluruh tinggi layar */
            position: relative;
        }

        .sidebar button {
            background: none;
            border: none;
            color: #e07b6c;
            font-size: 20px;
            cursor: pointer;
        }

        /* Main content */
        main {
            flex: 1;
            padding: 32px;
            display: flex;
            flex-direction: column;
            gap: 24px;
            height: 100vh;
            /* Membuat main mengambil seluruh tinggi layar */
            min-height: 100vh;
            /* Pastikan elemen ini minimal mengisi layar */
        }

        main header {
            margin-bottom: 24px;
        }

        main header h1 {
            color: white;
            font-weight: 600;
            font-size: 20px;
            margin: 0 0 4px 0;
        }

        main header p {
            font-size: 14px;
            color: #6b7280;
            margin: 0;
        }

        nav.tabs {
            display: flex;
            align-items: center;
            gap: 24px;
            font-size: 14px;
            font-weight: 600;
        }

        .tab-link {
            background: none;
            border: none;
            color: #6b7280;
            padding-bottom: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            margin-right: 18px;
        }

        .tab-link.active {
            color: #e07b6c;
            border-bottom: 2px solid #e07b6c;
        }

        .tab-link span {
            color: #f7b267;
            font-weight: 400;
            font-size: 13px;
            margin-left: 2px;
        }

        .search-container {
            margin-left: auto;
            position: relative;
            width: 240px;
        }

        .search-container input[type="search"] {
            width: 100%;
            padding: 8px 12px 8px 36px;
            border-radius: 6px;
            border: none;
            background-color: #2a3345;
            color: #9ca3af;
            font-size: 14px;
        }

        .search-container input::placeholder {
            color: #6b7280;
        }

        .search-container .icon-search {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            font-size: 14px;
            pointer-events: none;
        }

        select.dine-in {
            margin-left: 16px;
            background-color: #2a3345;
            border: none;
            border-radius: 6px;
            color: #9ca3af;
            font-size: 14px;
            padding: 8px 12px;
            cursor: pointer;
        }

        /* Choose Dishes */
        section.choose-dishes h2 {
            font-weight: 700;
            color: white;
            margin-bottom: 16px;
        }

        .dishes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 24px;
        }

        .dish-card {
            background-color: #2a3345;
            border-radius: 12px;
            padding: 24px 24px 32px 24px;
            text-align: center;
            color: #9ca3af;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
        }

        .dish-card img {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 16px;
        }

        .dish-card h3 {
            color: white;
            font-weight: 600;
            margin: 0 0 8px 0;
            font-size: 16px;
        }

        .dish-card p.price {
            margin: 0 0 4px 0;
            font-size: 14px;
        }

        .dish-card p.available {
            margin: 0;
            font-size: 12px;
            color: #6b7280;
        }

        .dish-card button.add-to-order {
            position: absolute;
            top: 12px;
            right: 12px;
            background: #e07b6c;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.2s;
            z-index: 2;
        }

        .dish-card button.add-to-order:hover {
            background: #f7b267;
            color: #222b3a;
        }

        /* Orders panel */
        aside.orders-panel {
            background-color: #2a3345;
            width: 384px;
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
            padding: 32px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100vh;
            /* Mengisi seluruh tinggi layar */
            position: relative;
        }

        aside.orders-panel header h2 {
            color: white;
            font-weight: 600;
            font-size: 16px;
            margin: 0 0 12px 0;
        }

        aside.orders-panel header h2 span {
            color: #6b7280;
            font-weight: 400;
            font-size: 14px;
        }

        nav.order-types {
            display: flex;
            gap: 12px;
            font-size: 12px;
            font-weight: 700;
        }

        nav.order-types button {
            border-radius: 8px;
            border: none;
            padding: 6px 16px;
            cursor: pointer;
            color: #9ca3af;
            background-color: #3f4556;
        }

        nav.order-types button.active {
            background-color: #e07b6c;
            color: white;
        }

        ul.order-list {
            list-style: none;
            padding: 0;
            margin: 24px 0 0 0;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        ul.order-list li {
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }

        ul.order-list li img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }

        ul.order-list li .order-info {
            flex: 1;
        }

        ul.order-list li .order-info p.name {
            margin: 0 0 4px 0;
            font-weight: 600;
            font-size: 14px;
            color: white;
        }

        ul.order-list li .order-info p.price {
            margin: 0 0 6px 0;
            font-size: 12px;
            color: #6b7280;
        }

        ul.order-list li .order-info input[type="text"] {
            width: 100%;
            background-color: #1c2431;
            border: none;
            border-radius: 6px;
            padding: 6px 12px;
            font-size: 12px;
            color: #9ca3af;
        }

        ul.order-list li .order-info input::placeholder {
            color: #6b7280;
        }

        ul.order-list li .order-qty-delete {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        ul.order-list li .order-qty-delete span.qty {
            background-color: #1c2431;
            color: #9ca3af;
            font-size: 12px;
            border-radius: 8px;
            padding: 2px 8px;
            user-select: none;
        }

        ul.order-list li .order-qty-delete button {
            background: none;
            border: none;
            color: #e07b6c;
            cursor: pointer;
            font-size: 14px;
        }

        ul.order-list li .order-qty-delete button:hover {
            color: #f28a7a;
        }

        /* Footer */
        aside.orders-panel footer {
            border-top: 1px solid #374151;
            padding-top: 24px;
        }

        aside.orders-panel footer .discount,
        aside.orders-panel footer .subtotal {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            margin-bottom: 8px;
        }

        aside.orders-panel footer .subtotal {
            font-weight: 700;
            color: white;
            font-size: 16px;
            margin-bottom: 24px;
        }

        aside.orders-panel footer button {
            width: 100%;
            background-color: #e07b6c;
            border: none;
            border-radius: 12px;
            padding: 12px 0;
            font-weight: 700;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        aside.orders-panel footer button:hover {
            background-color: #d46a5a;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                min-height: auto;
                border-radius: 0;
            }

            .sidebar {
                flex-direction: row;
                width: 100%;
                padding: 16px 0;
                gap: 24px;
                border-radius: 0;
                justify-content: center;
            }

            main {
                padding: 16px;
            }

            aside.orders-panel {
                width: 100%;
                border-radius: 0;
                padding: 16px;
                margin-top: 24px;
            }

            .dishes-grid {
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
                gap: 16px;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
</head>

<body>
    <div class="container" role="main">
        <?php
            include '../../views/kasir/sidebar.php';
        ?>
        <main>
            <header>
                <h1>Tapal Kuda</h1>
                <p>Tuesday, 29 April 2025</p>
            </header>
            <nav class="tabs" aria-label="Dish categories">
                <a href="kasir.php" class="tab-link <?php echo (!isset($_GET['type']) || empty($_GET['type'])) ? 'active' : ''; ?>">
                    Coffe <span>(<?php echo isset($menuCounts['kopi']) ? $menuCounts['kopi'] : 0; ?>)</span>
                </a>
                <a href="kasir.php?type=minuman" class="tab-link <?php echo (isset($_GET['type']) && $_GET['type'] == 'minuman') ? 'active' : ''; ?>">
                    Non Coffe <span>(<?php echo isset($menuCounts['minuman']) ? $menuCounts['minuman'] : 0; ?>)</span>
                </a>
                <a href="kasir.php?type=makanan_berat" class="tab-link <?php echo (isset($_GET['type']) && $_GET['type'] == 'makanan_berat') ? 'active' : ''; ?>">
                    Foods <span>(<?php echo isset($menuCounts['makanan_berat']) ? $menuCounts['makanan_berat'] : 0; ?>)</span>
                </a>
                <a href="kasir.php?type=cemilan" class="tab-link <?php echo (isset($_GET['type']) && $_GET['type'] == 'cemilan') ? 'active' : ''; ?>">
                    Snacks <span>(<?php echo isset($menuCounts['cemilan']) ? $menuCounts['cemilan'] : 0; ?>)</span>
                </a>
                <div class="search-container">
                    <input type="search" placeholder="Search for food, coffe, etc.." aria-label="Search for food, coffee, etc." />
                    <i class="fas fa-search icon-search" aria-hidden="true"></i>
                </div>
            </nav>
            <?php
            include '../../koneksi.php';

            // Query dasar
            $sql = "SELECT * FROM menu WHERE quantity > 0";
            if (isset($_GET['type']) && !empty($_GET['type'])) {
                $type = $_GET['type'];
                $sql .= " AND type = '$type'";
            }
            $result = $conn->query($sql);
            ?>
            <section class="choose-dishes" aria-label="Choose Dishes">
                <h2>Choose Dishes</h2>
                <div class="dishes-grid">
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                    ?>
                    <article class="dish-card" data-id="<?php echo $row['id']; ?>" data-name="<?php echo htmlspecialchars($row['nama']); ?>" data-price="<?php echo $row['price']; ?>" data-img="<?php echo $row['url_foto']; ?>">
                        <img src="../../asset/<?php echo $row['url_foto']; ?>" alt="<?php echo $row['nama']; ?>" width="96" height="96" />
                        <h3><?php echo $row['nama']; ?></h3>
                        <p class="price">Rp. <?php echo number_format($row['price'], 0, ',', '.'); ?></p>
                        <p class="available"><?php echo $row['quantity']; ?> available</p>
                        <button class="add-to-order" type="button" title="Tambah ke pesanan">
                            <i class="fas fa-plus"></i>
                        </button>
                    </article>
                    <?php
                        }
                    } else {
                        echo "<p>Menu tidak tersedia.</p>";
                    }
                    ?>
                </div>
            </section>
        </main>
        <aside class="orders-panel" aria-label="Orders panel">
            <header>
                <h2>Orders <span>#001</span></h2>
            </header>
            <ul class="order-list">
            </ul>
            <footer>
                <div class="subtotal">
                    <span>Sub total</span>
                    <span>Rp. 0</span>
                </div>
                <button type="button">Continue to Payment</button>
            </footer>
        </aside>
    </div>
    <script>
        let orders = {};

        function renderOrders() {
            const orderList = document.querySelector('.order-list');
            orderList.innerHTML = '';
            let subtotal = 0;
            for (const key in orders) {
                const item = orders[key];
                subtotal += item.price * item.qty;
                orderList.innerHTML += `
                    <li>
                        <img src="../../asset/${item.img}" alt="${item.name}" width="40" height="40" />
                        <div class="order-info">
                            <p class="name">${item.name}</p>
                            <p class="price">Rp. ${item.price.toLocaleString('id-ID')}</p>
                            <input type="text" placeholder="Order Note..." aria-label="Order note for ${item.name}" />
                        </div>
                        <div class="order-qty-delete">
                            <span class="qty">${item.qty}</span>
                            <button aria-label="Delete ${item.name} order" type="button" onclick="removeOrder('${key}')"><i class="fas fa-trash-alt"></i></button>
                        </div>
                    </li>
                `;
            }
            document.querySelector('.subtotal span:last-child').textContent = 'Rp. ' + subtotal.toLocaleString('id-ID');
        }

        function addOrder(id, name, price, img) {
            if (orders[id]) {
                orders[id].qty += 1;
            } else {
                orders[id] = { name, price: Number(price), img, qty: 1 };
            }
            renderOrders();
        }

        function removeOrder(id) {
            delete orders[id];
            renderOrders();
        }

        // Event listener untuk tombol +
        document.querySelectorAll('.add-to-order').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const card = this.closest('.dish-card');
                const id = card.getAttribute('data-id');
                const name = card.getAttribute('data-name');
                const price = card.getAttribute('data-price');
                const img = card.getAttribute('data-img');
                addOrder(id, name, price, img);
            });
        });
    </script>
</body>

</html>