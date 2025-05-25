<?php
include 'koneksi.php'; // Path ke WEBPRO/koneksi.php

header('Content-Type: application/json');

$sql = "SELECT
            m.nama AS menu_name,
            SUM(od.quantity) AS total_quantity_sold
        FROM
            order_details od
        JOIN
            menu m ON od.menu_id = m.id
        GROUP BY
            m.nama
        ORDER BY
            total_quantity_sold DESC
        LIMIT 5"; // Ambil 5 menu terlaris (Anda bisa sesuaikan batasnya)

$result = $conn->query($sql);

$top_selling_data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $top_selling_data[] = [
            'menu_name' => $row['menu_name'],
            'quantity_sold' => (int) $row['total_quantity_sold']
        ];
    }
}

// Jika tidak ada data, kembalikan array kosong agar Chart.js tetap bisa digambar
// Atau Anda bisa menambahkan data placeholder jika mau, contohnya:
/*
if (empty($top_selling_data)) {
    $top_selling_data[] = ['menu_name' => 'No Sales Yet', 'quantity_sold' => 0];
}
*/

echo json_encode($top_selling_data);

$conn->close();
?>