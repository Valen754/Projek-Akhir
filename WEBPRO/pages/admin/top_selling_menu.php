<?php
// Pastikan jalur include ke header dan sidebar admin sudah benar
// File ini ada di WEBPRO/pages/admin/
// header.php dan sidebar.php ada di WEBPRO/views/admin/
include '../../views/admin/header.php';
include '../../views/admin/sidebar.php';
?>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Analisis Penjualan</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Menu Terlaris</li>
        </ol>

        <!-- Filter Waktu -->
        <div class="mb-3">
            <label for="filter_waktu" class="form-label">Filter Waktu:</label>
            <select id="filter_waktu" class="form-select" style="width:auto;display:inline-block;">
                <option value="hari">Hari Ini</option>
                <option value="bulan">Bulan Ini</option>
                <option value="tahun">Tahun Ini</option>
                <option value="semua">Semua</option>
            </select>
        </div>

        <div class="row">
            <div class="col-xl-12"> <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-pie me-1"></i>
                        Diagram Lingkaran Penjualan Menu Terlaris
                    </div>
                    <div class="card-body" style="height: 400px; display: flex; justify-content: center; align-items: center;">
                        <canvas id="myPieChart" width="100%" height="100%"></canvas>
                    </div>
                    <div class="card-footer small text-muted">Data berdasarkan total kuantitas terjual</div>
                </div>
            </div>

            <div class="col-xl-12"> <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Data Tabel Penjualan Menu Terlaris
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered" id="pieChartDataTable">
                            <thead>
                                <tr>
                                    <th>Nama Menu</th>
                                    <th>Total Terjual (Kuantitas)</th>
                                    <th>Persentase (%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="3" style="text-align: center;">Memuat data...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer small text-muted">Data berdasarkan total kuantitas terjual</div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
// Pastikan jalur include ke footer admin sudah benar
include '../../views/admin/footer.php';
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="../../js/scripts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<script src="../../js/datatables-simple-demo.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>

<script>
    // Pastikan skrip berjalan setelah DOM sepenuhnya dimuat
    document.addEventListener('DOMContentLoaded', function() {
        const filterSelect = document.getElementById('filter_waktu');
        function loadChartData() {
            const filter = filterSelect.value;
            let url = 'logic/get_top_selling_menu.php?filter=' + filter;
            if (filter === 'tanggal') {
                url += '&tanggal=' + tanggalInput.value;
            }
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => { throw new Error(text); });
                    }
                    return response.json();
                })
                .then(data => {
                    // Log data yang diterima untuk debugging di konsol browser
                    console.log('Data yang diterima dari PHP:', data); // Untuk debugging

                    // Tangani jika ada error yang dikirim dari PHP
                    if (data.error) {
                        console.error('Error dari PHP:', data.error);
                        alert('Terjadi kesalahan saat memuat data diagram: ' + data.error);
                        return; // Hentikan eksekusi lebih lanjut
                    }

                    // Inisialisasi Pie Chart
                    var ctx = document.getElementById("myPieChart");
                    // Pastikan elemen canvas ditemukan dan ada data yang bisa digambar
                    // data.labels.length > 0 memastikan ada menu untuk digambar
                    if (ctx && data.labels && data.labels.length > 0) {
                        if (window.myPieChartInstance) {
                            window.myPieChartInstance.destroy();
                        }
                        window.myPieChartInstance = new Chart(ctx, {
                            type: 'pie',
                            data: {
                                labels: data.labels, // Nama menu dari PHP
                                datasets: [{
                                    data: data.data, // Persentase penjualan dari PHP
                                    // Array warna untuk irisan diagram. Tambahkan jika ada lebih dari 9 menu.
                                    backgroundColor: [
                                        '#007bff', '#dc3545', '#ffc107', '#28a745', '#6f42c1',
                                        '#17a2b8', '#fd7e14', '#6c757d', '#e83e8c', '#20c997',
                                        '#4d5656', '#a9cce3', '#f9e79f', '#d2b4de'
                                    ],
                                }],
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false, // Memungkinkan kontrol ukuran canvas yang lebih baik
                                tooltips: {
                                    callbacks: {
                                        // Mengatur format tooltip agar menampilkan label dan persentase
                                        label: function(tooltipItem, chartData) {
                                            var label = chartData.labels[tooltipItem.index] || '';
                                            if (label) {
                                                label += ': ';
                                            }
                                            label += chartData.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] + '%';
                                            return label;
                                        }
                                    }
                                }
                            }
                        });
                    } else {
                        // Pesan jika elemen canvas tidak ditemukan atau tidak ada data untuk diagram
                        console.warn('Elemen canvas "myPieChart" tidak ditemukan atau tidak ada data menu terlaris untuk ditampilkan.');
                        // Anda bisa menampilkan pesan di UI jika perlu
                        // Lokasi untuk pesan "Tidak ada data" jika diagram tidak terbentuk
                        var pieChartCardBody = document.querySelector('.col-xl-12 .card-body canvas').parentNode;
                        if (pieChartCardBody) {
                            pieChartCardBody.innerHTML = '<p style="text-align: center; padding: 20px;">Tidak ada data penjualan menu terlaris untuk diagram.</p>';
                        }
                    }

                    // Mengisi data ke dalam tabel HTML
                    const dataTableBody = document.querySelector('#pieChartDataTable tbody');
                    dataTableBody.innerHTML = ''; // Kosongkan baris yang ada (termasuk "Memuat data...")

                    if (data.table_data && data.table_data.length > 0) {
                        // Jika ada data tabel, masukkan ke dalam baris tabel
                        data.table_data.forEach(item => {
                            const row = dataTableBody.insertRow();
                            row.insertCell().textContent = item.menu_name; // Menggunakan 'menu_name'
                            row.insertCell().textContent = item.sold;
                            row.insertCell().textContent = item.percentage;
                        });
                    } else {
                        // Jika tidak ada data tabel, tampilkan pesan "Tidak ada data"
                        const row = dataTableBody.insertRow();
                        const cell = row.insertCell();
                        cell.colSpan = 3; // Rentang kolom sesuai jumlah kolom tabel
                        cell.textContent = 'Tidak ada data penjualan menu terlaris.';
                        cell.style.textAlign = 'center';
                    }
                })
                .catch(error => {
                    // Menangani error yang terjadi selama proses fetch atau penguraian JSON
                    console.error('Terjadi kesalahan saat mengambil atau memproses data diagram:', error);
                    // Menampilkan pesan error kepada pengguna
                    alert('Gagal memuat data diagram. Silakan cek konsol browser untuk detail.');
                    // Mengisi tabel dengan pesan error atau "tidak ada data"
                    const dataTableBody = document.querySelector('#pieChartDataTable tbody');
                    dataTableBody.innerHTML = '<tr><td colspan="3" style="text-align: center; color: red;">Error memuat data: ' + error.message + '</td></tr>';
                });
        }
        filterSelect.addEventListener('change', loadChartData);
        loadChartData();
    });
</script>