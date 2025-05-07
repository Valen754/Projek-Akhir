<?php
include '../../views/header.php'; // Sesuaikan dengan struktur direktori Anda
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout Berhasil</title>
    <link rel="stylesheet" href="../../css/style.css"> <!-- Jika ada style umum -->
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
            background-color: #f9f9f9;
        }
        .success-container {
            background: #fff;
            border: 1px solid #ddd;
            padding: 30px;
            border-radius: 10px;
            display: inline-block;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .success-container h1 {
            color: green;
        }
        .success-container a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
        .success-container a:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <h1>✅ Checkout Berhasil!</h1>
        <p>Terima kasih telah melakukan pemesanan.</p>
        <a href="../menu/menu.php">Kembali ke Menu</a>
    </div>
</body>
</html>
