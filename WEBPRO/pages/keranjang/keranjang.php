<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tapal Kuda | Keranjang</title>
    <link rel="stylesheet" href="../../css/keranjang.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <!--BAGIAN NAVBAR-->
    <?php
        include '../views/header.php';
    ?>

    <!-- BREADCRUMB -->
    <div class="wadah-breadcrumb">
        <nav class="navigasi-breadcrumb" aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li><a href="menu.html">Menu</a></li>
                <li class="aktif">Cart</li>
            </ul>
        </nav>
    </div>

    <!-- Table -->
    <div class="wadah">
        <h3 class="judul">Shopping Cart</h3>

        <div class="tabel-wadah">
            <table class="tabel">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" id="selectAll" style="width: 20px; height: 20px;">
                        </th>
                        <th>#</th>
                        <th>Order</th>
                        <th>Memo</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <input type="checkbox" class="item-checkbox" data-price="12000"
                                style="width: 20px; height: 20px;">
                        </td>
                        <td><img src="Foto/Kopi/Real/KOPI TUBRUK ROBUSTA.jpg" alt="Produk" class="gambar-produk"></td>
                        <td>Kopi Tubruk Robusta</td>
                        <td class="memo">-</td>
                        <td class="quantity-cell">
                            <span class="quantity">1</span>
                            <input type="number" class="quantity-input" value="1" min="1"
                                style="width: 50px; display: none;">
                        </td>
                        <td class="price-cell" data-price="12000">Rp 12.000</td>
                        <td>
                            <div class="edit-table">Edit</div>
                            <div class="save-table" style="display: none;">Save</div>
                            <div class="delete-table">Delete</div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" class="item-checkbox" data-price="20000"
                                style="width: 20px; height: 20px;">
                        </td>
                        <td><img src="Foto/Makanan/AyamTeriyaki.jpg" alt="Produk" class="gambar-produk"></td>
                        <td>Chicken Teriyaki</td>
                        <td class="memo">-</td>
                        <td class="quantity-cell">
                            <span class="quantity">1</span>
                            <input type="number" class="quantity-input" value="1" min="1"
                                style="width: 50px; display: none;">
                        </td>
                        <td class="price-cell" data-price="20000">Rp 20.000</td>
                        <td>
                            <div class="edit-table">Edit</div>
                            <div class="save-table" style="display: none;">Save</div>
                            <div class="delete-table">Delete</div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" class="item-checkbox" data-price="16000"
                                style="width: 20px; height: 20px;">
                        </td>
                        <td><img src="Foto/Cemilan/Roti.jpg" alt="Produk" class="gambar-produk"></td>
                        <td>Roti Bakar</td>
                        <td class="memo">-</td>
                        <td class="quantity-cell">
                            <span class="quantity">1</span>
                            <input type="number" class="quantity-input" value="1" min="1"
                                style="width: 50px; display: none;">
                        </td>
                        <td class="price-cell" data-price="16000">Rp 16.000</td>
                        <td>
                            <div class="edit-table">Edit</div>
                            <div class="save-table" style="display: none;">Save</div>
                            <div class="delete-table">Delete</div>
                        </td>
                    </tr>
                    <tr class="footer-judul">
                        <td colspan="5" class="total-judul">Total</td>
                        <td colspan="1" class="total-judul" id="totalPrice">Rp 0</td>
                        <td>
                            <button class="tombol-checkout" id="bukaModal"
                                style="font-family: inherit;">Checkout</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal-overlay" id="confirmDeleteModal" style="display: none;">
        <div class="modal-container">
            <div class="modal-header">
                <h1 class="modal-title">Konfirmasi Penghapusan</h1>
                <button class="close-button" id="closeDeleteModal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus item ini?</p>
                <div class="modal-item">
                    <img src="" id="modalItemImage" width="100px" alt="Item Image">
                    <div class="modal-item-details">
                        <p id="modalItemName">Nama Item</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-success" id="confirmDelete">Ya, Hapus</button>
                <button type="button" class="btn-cancel" id="cancelDelete">Batal</button>
            </div>
        </div>
    </div>

    <!-- Modal2 -->
    <div class="modal2" id="modalBayar" aria-hidden="true">
        <div class="modal-dialog2">
            <div class="modal-content2">
                <div class="modal-header2">
                    <h1 class="modal-title2" id="modalBayarLabel">Metode Pembayaran</h1>
                    <button type="button" class="tutup-btn" id="tutupModal" aria-label="Tutup">&times;</button>
                </div>
                <div class="modal-body2">
                    <div class="payment-option">
                        <input type="radio" id="bank" name="payment" value="bank">
                        <label for="bank" class="payment-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" style="color: black"
                                fill="currentColor" class="bi bi-bank" viewBox="0 0 16 16">
                                <path
                                    d="m8 0 6.61 3h.89a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5H15v7a.5.5 0 0 1 .485.38l.5 2a.498.498 0 0 1-.485.62H.5a.498.498 0 0 1-.485-.62l.5-2A.5.5 0 0 1 1 13V6H.5a.5.5 0 0 1-.5-.5v-2A.5.5 0 0 1 .5 3h.89zM3.777 3h8.447L8 1zM2 6v7h1V6zm2 0v7h2.5V6zm3.5 0v7h1V6zm2 0v7H12V6zM13 6v7h1V6zm2-1V4H1v1zm-.39 9H1.39l-.25 1h13.72z" />
                            </svg>
                            <div class="span">Bank Transfer</div>
                        </label>
                        <div class="payment-summary" id="bank-summary">
                            <p>Choose Bank Transfer</p>
                            <div class="payment-methods">
                                <input type="radio" id="bank-method-1" name="bank-method" value="BCA">
                                <label for="bank-method-1" class="payment-method-btn"><img src="Foto/BCA.png"
                                        width="50px" height="50px"> BCA</label>
                                <div class="qrbank-summary" id="qr-code-bank-BCA" style="display: none;">
                                    <p>QR Code</p>
                                    <div class="qr-metod">
                                        <img src="Foto/QRIS.png" alt="QR Code BCA" style="width: 200px; height: 200px;">
                                    </div>
                                    <div style="position: relative;">
                                        <input type="text" id="code-ewallet-BCA" value="33339143029727145703291"
                                            readonly style="text-align: center; width: 100%; border-radius: 5px;">
                                        <button onclick="copyToClipboard('code-ewallet-BCA')"
                                            style="position: absolute; right: 10px; top: 50%; transform: translateY(-40%); background: transparent; border: none;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13"
                                                fill="currentColor" class="bi bi-copy" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd"
                                                    d="M4 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 5a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1h1v1a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h1v1z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <input type="radio" id="bank-method-2" name="bank-method" value="BRI">
                                <label for="bank-method-2" class="payment-method-btn"><img src="Foto/BRI.png"
                                        width="50px" height="50px"> BRI</label>
                                <div class="qrbank-summary" id="qr-code-bank-BRI" style="display: none;">
                                    <p>QR Code</p>
                                    <div class="qr-metod">
                                        <img src="Foto/QRIS.png" alt="QR Code BRI" style="width: 200px; height: 200px;">
                                    </div>
                                    <div style="position: relative;">
                                        <input type="text" id="code-ewallet-BRI" value="33339178430083163553394"
                                            readonly style="text-align: center; width: 100%; border-radius: 5px;">
                                        <button onclick="copyToClipboard('code-ewallet-BRI')"
                                            style="position: absolute; right: 10px; top: 50%; transform: translateY(-40%); background: transparent; border: none;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13"
                                                fill="currentColor" class="bi bi-copy" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd"
                                                    d="M4 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 5a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1h1v1a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h1v1z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <input type="radio" id="bank-method-3" name="bank-method" value="Mandiri">
                                <label for="bank-method-3" class="payment-method-btn"><img src="Foto/MANDIRI.png"
                                        width="50px" height="50px"> Mandiri</label>
                                <div class="qrbank-summary" id="qr-code-bank-Mandiri" style="display: none;">
                                    <p>QR Code</p>
                                    <div class="qr-metod">
                                        <img src="Foto/QRIS.png" alt="QR Code Mandiri"
                                            style="width: 200px; height: 200px;">
                                    </div>
                                    <div style="position: relative;">
                                        <input type="text" id="code-ewallet-Mandiri" value="33339178430083163553394"
                                            readonly style="text-align: center; width: 100%; border-radius: 5px;">
                                        <button onclick="copyToClipboard('code-ewallet-Mandiri')"
                                            style="position: absolute; right: 10px; top: 50%; transform: translateY(-40%); background: transparent; border: none;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13"
                                                fill="currentColor" class="bi bi-copy" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd"
                                                    d="M4 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 5a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1h1v1a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h1v1z" />
                                            </svg>
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="payment-option">
                        <input type="radio" id="ewallet" name="payment" value="ewallet">
                        <label for="ewallet" class="payment-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" style="color: black;"
                                fill="currentColor" class="bi bi-wallet2" viewBox="0 0 16 16">
                                <path
                                    d="M12.136.326A1.5 1.5 0 0 1 14 1.78V3h.5A1.5 1.5 0 0 1 16 4.5v9a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 13.5v-9a1.5 1.5 0 0 1 1.432-1.499zM5.562 3H13V1.78a.5.5 0 0 0-.621-.484zM1.5 4a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z" />
                            </svg>
                            <div class="span">E-Wallet</div>
                        </label>
                        <div class="payment-summary" id="ewallet-summary">
                            <p>Select E-Wallet</p>
                            <div class="payment-methods">
                                <input type="radio" id="ewallet-method-1" name="ewallet-method" value="Gopay">
                                <label for="ewallet-method-1" class="payment-method-btn"><img src="Foto/LOGO.jpg"
                                        width="50px" height="50px"> Gopay</label>
                                <div class="qrbank-summary" id="qr-code-ewallet-Gopay" style="display: none;">
                                    <p>QR Code</p>
                                    <div class="qr-metod">
                                        <img src="Foto/QRIS.png" alt="QR Code Gopay"
                                            style="width: 200px; height: 200px;">
                                    </div>
                                    <div style="position: relative;">
                                        <input type="text" id="code-ewallet-Gopay" value="33339143029727145703297"
                                            readonly style="text-align: center; width: 100%; border-radius: 5px;">
                                        <button onclick="copyToClipboard('code-ewallet-Gopay')"
                                            style="position: absolute; right: 10px; top: 50%; transform: translateY(-40%); background: transparent; border: none;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13"
                                                fill="currentColor" class="bi bi-copy" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd"
                                                    d="M4 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 5a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1h1v1a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h1v1z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <input type="radio" id="ewallet-method-2" name="ewallet-method" value="Dana">
                                <label for="ewallet-method-2" class="payment-method-btn"><img src="Foto/DANA.jpg"
                                        width="50px" height="50px"> Dana</label>
                                <div class="qrbank-summary" id="qr-code-ewallet-Dana" style="display: none;">
                                    <p>QR Code</p>
                                    <div class="qr-metod">
                                        <img src="Foto/QRIS.png" alt="QR Code Dana"
                                            style="width: 200px; height: 200px;">
                                    </div>
                                    <div style="position: relative;">
                                        <input type="text" id="code-ewallet-Dana" value="33339178430083163553389"
                                            readonly style="text-align: center; width: 100%; border-radius: 5px;">
                                        <button onclick="copyToClipboard('code-ewallet-Dana')"
                                            style="position: absolute; right: 10px; top: 50%; transform: translateY(-40%); background: transparent; border: none;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13"
                                                fill="currentColor" class="bi bi-copy" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd"
                                                    d="M4 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 5a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1h1v1a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h1v1z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <input type="radio" id="ewallet-method-3" name="ewallet-method" value="Ovo">
                                <label for="ewallet-method-3" class="payment-method-btn"><img src="Foto/OVO.jpg"
                                        width="50px" height="50px"> Ovo</label>
                                <div class="qrbank-summary" id="qr-code-ewallet-Ovo" style="display: none;">
                                    <p>QR Code</p>
                                    <div class="qr-metod">
                                        <img src="Foto/QRIS.png" alt="QR Code Ovo" style="width: 200px; height: 200px;">
                                    </div>
                                    <div style="position: relative;">
                                        <input type="text" id="code-ewallet-Ovo" value="33339578475169030060901"
                                            readonly style="text-align: center; width: 100%; border-radius: 5px;">
                                        <button onclick="copyToClipboard('code-ewallet-Ovo')"
                                            style="position: absolute; right: 10px; top: 50%; transform: translateY(-40%); background: transparent; border: none;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13"
                                                fill="currentColor" class="bi bi-copy" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd"
                                                    d="M4 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 5a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1h1v1a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h1v1z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn2" id="tombolBayar" style="font-family: inherit;">Buy</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Notification -->
    <div id="notification" class="notification" style="display: none;"></div>

    <script src="../../js/keranjang.js"></script>
</body>

</html>