<?php
// Mengaktifkan pelaporan error untuk debugging (Hapus di produksi)

header('Content-Type: application/json');
include '../../../koneksi.php';

$tanggal_awal = isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : '';
$tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : '';

$where = '';
if (!empty($tanggal_awal) && !empty($tanggal_akhir)) {
    $where = "AND DATE(p.order_date) BETWEEN '" . 
             mysqli_real_escape_string($conn, $tanggal_awal) . "' AND '" . 
             mysqli_real_escape_string($conn, $tanggal_akhir) . "'";
} elseif (!empty($tanggal_awal)) {
    $where = "AND DATE(p.order_date) >= '" . mysqli_real_escape_string($conn, $tanggal_awal) . "'";
} elseif (!empty($tanggal_akhir)) {
    $where = "AND DATE(p.order_date) <= '" . mysqli_real_escape_string($conn, $tanggal_akhir) . "'";
}

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
            detail_pembayaran od
        JOIN
            menu m ON od.menu_id = m.id
        JOIN
            pembayaran p ON od.pembayaran_id = p.id
        WHERE 1 $where
        GROUP BY
            m.id, m.nama
        ORDER BY
            total_sold DESC
        LIMIT 5"; // Mengambil hingga 9 menu terlaris

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
} else {
    // Data dummy jika tidak ada data
    $data['labels'] = ['Tidak ada data'];
    $data['data'] = [100];
    $data['table_data'][] = [
        'menu_name' => 'Tidak ada data',
        'sold' => 0,
        'percentage' => '100%'
    ];
}

// Mengembalikan data dalam format JSON
echo json_encode($data);

// Menutup koneksi database
$conn->close();
exit;
?>