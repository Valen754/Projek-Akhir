<?php
// Mengaktifkan pelaporan error untuk debugging (Hapus di produksi)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Jalur relatif ke koneksi.php dari file ini.
// File ini ada di WEBPRO/pages/admin/logic/
// koneksi.php ada di WEBPRO/
// Jadi perlu naik 3 tingkat direktori: ../../../
include '../../../koneksi.php';

// Menentukan header agar browser tahu responsnya adalah JSON
header('Content-Type: application/json');

$data = [
    'labels' => [],       // Nama menu untuk label diagram
    'data' => [],         // Persentase penjualan untuk data diagram
    'table_data' => []    // Data detail untuk tabel HTML
];

// Query SQL untuk mengambil menu terlaris berdasarkan total kuantitas terjual
// Bergabung dengan tabel 'menu' untuk mendapatkan nama menu
// Mengelompokkan berdasarkan nama menu dan menjumlahkan kuantitas
// Mengurutkan hasilnya dari kuantitas terbanyak ke terendah
// Membatasi hasilnya hanya 9 menu teratas (Anda bisa mengubah angka ini)
$sql = "SELECT
            m.nama AS menu_name,
            SUM(od.quantity) AS total_sold
        FROM
            order_details od
        JOIN
            menu m ON od.menu_id = m.id
        GROUP BY
            m.nama
        ORDER BY
            total_sold DESC
        LIMIT 7"; // Mengambil hingga 9 menu terlaris

$result = $conn->query($sql);

// Memeriksa apakah ada error dalam eksekusi kueri
if ($result === FALSE) {
    echo json_encode(['error' => 'Query database gagal: ' . $conn->error]);
    $conn->close();
    exit(); // Hentikan eksekusi skrip
}

// Memproses hasil kueri jika ada baris data
if ($result->num_rows > 0) {
    $total_all_items_sold = 0;
    $temp_items_for_calculation = [];

    // Langkah 1: Kumpulkan semua data menu dan hitung total penjualan keseluruhan
    while ($row = $result->fetch_assoc()) {
        $temp_items_for_calculation[] = $row;
        $total_all_items_sold += $row['total_sold'];
    }

    // Langkah 2: Hitung persentase dan siapkan data untuk output JSON
    foreach ($temp_items_for_calculation as $item) {
        // Menghitung persentase, menghindari pembagian dengan nol
        $percentage = ($total_all_items_sold > 0) ? round(($item['total_sold'] / $total_all_items_sold) * 100, 2) : 0;

        $data['labels'][] = $item['menu_name'];
        $data['data'][] = $percentage;
        $data['table_data'][] = [
            'menu_name' => $item['menu_name'],
            'sold' => $item['total_sold'],
            'percentage' => $percentage . '%'
        ];
    }
}

// Mengembalikan data dalam format JSON
echo json_encode($data);

// Menutup koneksi database
$conn->close();
?>