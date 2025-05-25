<?php

include '../../../koneksi.php';

// Query: Ambil nama menu dan total terjual (dari order_details)
$sql = "SELECT m.nama, SUM(od.quantity) as total_terjual
        FROM order_details od
        JOIN menu m ON od.menu_id = m.id
        GROUP BY od.menu_id
        ORDER BY total_terjual DESC
        LIMIT 10";
$result = mysqli_query($conn, $sql);

$labels = [];
$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $labels[] = $row['nama'];
    $data[] = (int)$row['total_terjual'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Chart Menu Terlaris</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h2>Menu Terlaris</h2>
    <canvas id="menuChart" width="600" height="350"></canvas>
    <script>
        const ctx = document.getElementById('menuChart').getContext('2d');
        const menuChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: 'Total Terjual',
                    data: <?php echo json_encode($data); ?>,
                    backgroundColor: 'rgba(108, 52, 31, 0.7)',
                    borderColor: 'rgba(108, 52, 31, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Jumlah Terjual' }
                    },
                    x: {
                        title: { display: true, text: 'Menu' }
                    }
                }
            }
        });
    </script>
</body>
</html>