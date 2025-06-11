<?php
include '../../views/header.php';
include '../../koneksi.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Tapal Kuda</title>
    <link href="../../css/menu.css" rel="stylesheet">
    <style>
        .checkout-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 32px;
            background: #222b3a;
            border-radius: 12px;
            color: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .checkout-form {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .checkout-preview {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .checkout-preview-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .checkout-totals {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px solid rgba(255, 255, 255, 0.2);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 15px;
            color: #fff;
        }

        .form-group input[type="text"],
        .form-group select {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
            font-size: 15px;
            box-sizing: border-box;
        }

        .form-group select {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1em;
        }

        .radio-group {
            display: flex;
            gap: 24px;
            align-items: center;
            margin: 15px 0;
        }

        .radio-group input[type="radio"] {
            margin-right: 8px;
        }

        #qrisImageContainer {
            display: none;
            text-align: center;
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            margin: 15px 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        #qrisImageContainer img {
            max-width: 250px;
            margin: 10px auto;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .qris-text {
            color: #333;
            margin-top: 12px;
            font-size: 14px;
            font-weight: 500;
        }

        .btn-bayar {
            width: 100%;
            background: #e07b6c;
            padding: 14px 0;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            color: #fff;
            font-size: 16px;
            margin-top: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-bayar:hover {
            background: #d45a4c;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(224, 123, 108, 0.3);
        }

        .btn-kembali {
            width: 100%;
            margin-top: 12px;
            padding: 12px 0;
            border: 1px solid rgba(224, 123, 108, 0.3);
            border-radius: 10px;
            background: transparent;
            color: #e07b6c;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
            display: block;
        }

        .btn-kembali:hover {
            background: rgba(224, 123, 108, 0.1);
        }

        .error-message {
            color: #ff4444;
            background: rgba(255, 68, 68, 0.1);
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 14px;
            display: none;
        }
    </style>
</head>

<body>
    <div class="checkout-container">
        <form class="checkout-form" action="logic/checkout.php" method="post">
            <h2 style="text-align: center; margin-bottom: 20px;">Pembayaran</h2>

            <div class="error-message" id="errorMessage"></div>

            <div id="checkoutPreview" class="checkout-preview">
                <!-- Items will be displayed here via JavaScript -->
            </div>

            <div class="form-group">
                <label>Nama Customer:</label>
                <input type="text" name="customer_name" required placeholder="Masukkan nama anda">
            </div>

            <div class="form-group">
                <label>Jenis Pesanan:</label>
                <div class="radio-group">
                    <label>
                        <input type="radio" name="jenis_order" value="dine_in" checked> Dine In
                    </label>
                    <label>
                        <input type="radio" name="jenis_order" value="take_away"> Take Away
                    </label>
                    <label>
                        <input type="radio" name="jenis_order" value="delivery"> Delivery
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label>Metode Pembayaran:</label>
                <select name="payment_method" id="paymentMethodSelect" required>
                    <option value="cash" style="color: black;">Cash</option>
                    <option value="e-wallet" style="color: black;">E-Wallet</option>
                    <option value="qris" style="color: black;">QRIS</option>
                </select>
            </div>

            <div id="qrisImageContainer">
                <img id="qrisImg" src="../../asset/pembayaran/qr.jpg" alt="QRIS" />
                <div id="qrisImgError" style="display:none;color:red;font-size:13px;">
                    QRIS tidak ditemukan! Pastikan file qr.jpg ada di folder asset/pembayaran/.
                </div>
                <div class="qris-text">Scan QRIS untuk pembayaran</div>
            </div>

            <input type="hidden" name="items" id="checkoutItemsInput">
            <button type="submit" class="btn-bayar">Bayar & Cetak Struk</button>
            <a href="menu.php" class="btn-kembali">Kembali ke Menu</a>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Load items from sessionStorage
            const checkoutItems = JSON.parse(sessionStorage.getItem('checkout_items') || '[]');
            const previewDiv = document.getElementById('checkoutPreview');

            if (checkoutItems.length === 0) {
                document.getElementById('errorMessage').style.display = 'block';
                document.getElementById('errorMessage').textContent = 'Tidak ada item yang dipilih.';
                setTimeout(() => {
                    window.location.href = 'menu.php';
                }, 2000);
                return;
            }

            // Calculate totals
            let subtotal = 0;
            let html = '';

            // Add items
            checkoutItems.forEach(item => {
                const itemTotal = (item.harga || item.price) * (item.qty || item.quantity);
                subtotal += itemTotal;

                html += `
                <div class="checkout-preview-item">
                    <div>
                        ${item.nama || item.name} x ${item.qty || item.quantity}
                        ${item.note ? `<br><small style="color: #aaa;">Note: ${item.note}</small>` : ''}
                    </div>
                    <div>Rp ${itemTotal.toLocaleString('id-ID')}</div>
                </div>`;
            });

            // Add totals
            const tax = Math.round(subtotal * 0.10);
            const total = subtotal + tax;

            html += `
            <div class="checkout-totals">
                <div class="checkout-preview-item">
                    <div>Subtotal</div>
                    <div>Rp ${subtotal.toLocaleString('id-ID')}</div>
                </div>
                <div class="checkout-preview-item">
                    <div>Pajak (10%)</div>
                    <div>Rp ${tax.toLocaleString('id-ID')}</div>
                </div>
                <div class="checkout-preview-item" style="border-bottom: none;">
                    <div><strong>Total</strong></div>
                    <div><strong>Rp ${total.toLocaleString('id-ID')}</strong></div>
                </div>
            </div>`;

            previewDiv.innerHTML = html;

            // Set items to hidden input
            document.getElementById('checkoutItemsInput').value = JSON.stringify(checkoutItems);

            // Handle QRIS display
            const paymentSelect = document.getElementById('paymentMethodSelect');
            const qrisContainer = document.getElementById('qrisImageContainer');

            paymentSelect.addEventListener('change', function () {
                qrisContainer.style.display = this.value === 'qris' ? 'block' : 'none';
            });

            // Handle form submission
            document.querySelector('.checkout-form').addEventListener('submit', function (e) {
                if (paymentSelect.value === 'qris') {
                    const img = document.getElementById('qrisImg');
                    if (img.naturalWidth === 0) {
                        e.preventDefault();
                        document.getElementById('qrisImgError').style.display = 'block';
                    }
                }
            });
        });
    </script>

    <?php include '../../views/footer.php'; ?>
</body>

</html>