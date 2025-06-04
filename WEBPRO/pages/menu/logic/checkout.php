<?php
// File: pages/menu/logic/checkout.php

session_start();

// 1. Include koneksi ke database
// --------------------------------------------------
// Dari file ini (__DIR__ = pages/menu/logic),
// kita naik dua tingkat untuk menemukan koneksi.php di root:
include '../../../koneksi.php';

// 2. Pastikan request-nya metode POST
// --------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../menu.php');
    exit;
}

// 3. Ambil user_id dari session (harus sudah login)
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    // Jika belum ada session user, redirect ke login
    header('Location: ../../login.php');
    exit;
}

// 4. Ambil & validasi data form
// --------------------------------------------------
$customer_name_raw   = $_POST['customer_name']  ?? '';
$order_type_raw      = $_POST['jenis_order']    ?? 'dine_in';
$payment_method_raw  = $_POST['payment_method'] ?? 'cash';
$items_json          = $_POST['items']          ?? '[]';

// Escape untuk teks customer_name
$customer_name = mysqli_real_escape_string($conn, trim($customer_name_raw));

// Default catatan order (notes) kita set jadi string kosong
// Jika kelak Anda menambahkan <textarea name="notes"> di menu.php, ganti '' menjadi $_POST['notes'] yang sudah di-sanitasi.
$order_notes = ''; 

// Validasi order_type
$allowed_order_types = ['dine_in', 'take_away'];
$order_type = in_array($order_type_raw, $allowed_order_types)
    ? $order_type_raw
    : 'dine_in';

// Validasi payment_method
$allowed_payment_methods = ['cash', 'e-wallet', 'qris'];
$payment_method = in_array($payment_method_raw, $allowed_payment_methods)
    ? $payment_method_raw
    : 'cash';

// Decode JSON items (harusnya array of {id, price, quantity})
$items = json_decode($items_json, true);
if (!is_array($items) || count($items) === 0) {
    echo '<script>
            alert("Tidak ada item yang dipilih untuk checkout!");
            window.history.back();
          </script>';
    exit;
}

// 5. Hitung total_amount dari setiap item
// --------------------------------------------------
$total_amount = 0.0;
foreach ($items as $row) {
    // Pastikan key tersedia
    $menu_id  = intval($row['id'] ?? 0);
    $price    = floatval($row['price'] ?? 0);
    $quantity = intval($row['quantity'] ?? 0);

    if ($menu_id <= 0 || $quantity <= 0 || $price < 0) {
        // Lewati jika data tidak valid
        continue;
    }

    $subtotal = $price * $quantity;
    $total_amount += $subtotal;
}

// Jika jumlah akhir 0 (misal semua price/qty invalid), batalkan
if ($total_amount <= 0) {
    echo '<script>
            alert("Total pembayaran tidak valid.");
            window.history.back();
          </script>';
    exit;
}

// 6. Simpan ke tabel pembayaran (master)
// --------------------------------------------------
// Struktur tabel `pembayaran` berdasarkan screenshot:
//   id (PK, AUTO_INCREMENT),
//   user_id (INT),
//   order_date (DATETIME, default current_timestamp()),
//   total_amount (DECIMAL(10,2)),
//   status     (ENUM('completed','pending','cancelled') default 'completed'),
//   customer_name (VARCHAR(255)),
//   payment_method (ENUM('cash','e-wallet','qris')),
//   order_type (ENUM('dine_in','take_away')),
//   notes (TEXT)

// Karena kita tidak ingin meng‐insert NULL ke kolom notes,
// maka kita bind nilai $order_notes (string kosong) ke kolom tersebut.
$sql_master = "
    INSERT INTO pembayaran
        (user_id, total_amount, customer_name, payment_method, order_type, notes)
    VALUES
        (?, ?, ?, ?, ?, ?)
";
$stmt_master = $conn->prepare($sql_master);
if (!$stmt_master) {
    die("Prepare master pembayaran gagal: " . $conn->error);
}

// Bind parameter:
//   1) user_id       → INT (“i”)
//   2) total_amount  → DOUBLE (“d”)
//   3) customer_name → STRING (“s”)
//   4) payment_method→ STRING (“s”)
//   5) order_type    → STRING (“s”)
//   6) notes         → STRING (“s”)
$stmt_master->bind_param(
    "idssss",
    $user_id,
    $total_amount,
    $customer_name,
    $payment_method,
    $order_type,
    $order_notes
);

if (!$stmt_master->execute()) {
    die("Eksekusi insert master pembayaran gagal: " . $stmt_master->error);
}

// Ambil ID dari pembayaran yang baru saja di‐insert
$order_id = $stmt_master->insert_id;
stmt_master->close();

// 7. Simpan detail per item ke tabel detail_pembayaran
// --------------------------------------------------
// Struktur tabel `detail_pembayaran` berdasarkan screenshot:
//   id (PK, AUTO_INCREMENT),
//   order_id (INT),
//   menu_id  (INT),
//   quantity (INT),
//   price_per_item (DECIMAL(10,2)),
//   subtotal      (DECIMAL(10,2)),
//   item_notes    (TEXT, nullable)

// Kita tidak ingin meng‐insert NULL ke kolom item_notes,
// maka kita pakai string kosong ('') sebagai default.
$item_notes_default = '';

$sql_detail = "
    INSERT INTO detail_pembayaran
        (order_id, menu_id, quantity, price_per_item, subtotal, item_notes)
    VALUES
        (?, ?, ?, ?, ?, ?)
";
$stmt_detail = $conn->prepare($sql_detail);
if (!$stmt_detail) {
    die("Prepare detail_pembayaran gagal: " . $conn->error);
}

// Loop tiap elemen di $items dan insert satu per satu
foreach ($items as $row) {
    $menu_id  = intval($row['id'] ?? 0);
    $price    = floatval($row['price'] ?? 0);
    $quantity = intval($row['quantity'] ?? 0);

    if ($menu_id <= 0 || $quantity <= 0 || $price < 0) {
        // Lewati jika data invalid
        continue;
    }

    $subtotal = $price * $quantity;

    // Bind parameter:
    //   1) order_id      → INT (“i”)
    //   2) menu_id       → INT (“i”)
    //   3) quantity      → INT (“i”)
    //   4) price_per_item→ DOUBLE (“d”)
    //   5) subtotal      → DOUBLE (“d”)
    //   6) item_notes    → STRING (“s”)
    $stmt_detail->bind_param(
        "iiidds",
        $order_id,
        $menu_id,
        $quantity,
        $price,
        $subtotal,
        $item_notes_default
    );

    if (!$stmt_detail->execute()) {
        // Jika salah satu detail gagal, kita log error dan tetap lanjutkan sisanya
        error_log("Gagal insert detail_pembayaran (order_id={$order_id}, menu_id={$menu_id}): " . $stmt_detail->error);
    }
}
$stmt_detail->close();

// 8. Redirect ke halaman cetak struk (atau tampilkan notifikasi sukses)
// --------------------------------------------------
// Misalnya Anda punya halaman cetak struk di: pages/kasir/cetak_struk.php?id=<order_id>
header("Location: ../../kasir/cetak_struk.php?id=" . $order_id);
exit;
