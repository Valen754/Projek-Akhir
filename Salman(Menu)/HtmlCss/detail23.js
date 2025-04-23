// KUANTITAS
let quantity = 1;
const pricePerItem = 18000; // Ganti dengan harga produk yang sesuai

// Fungsi untuk memperbarui tampilan jumlah dan harga
function updateDisplay() {
    document.getElementById("quantity").textContent = quantity;
    document.getElementById("price").textContent = `Rp ${(quantity * pricePerItem).toLocaleString("id-ID")}`;
}

// Event listener untuk tombol tambah
document.getElementById("increase").addEventListener("click", () => {
    quantity++;
    updateDisplay();
});

// Event listener untuk tombol kurang
document.getElementById("decrease").addEventListener("click", () => {
    if (quantity > 1) {
        quantity--;
        updateDisplay();
    }
});

// Inisialisasi tampilan awal
updateDisplay();

// BINTANG
const stars = document.querySelectorAll('.bintangf');
let selectedRating = 0;

// Menambahkan event listener untuk setiap bintang
stars.forEach(star => {
    star.addEventListener('click', () => {
        selectedRating = star.getAttribute('data-value');
        updateStars();
    });
});

// Fungsi untuk memperbarui tampilan bintang
function updateStars() {
    stars.forEach(star => {
        if (star.getAttribute('data-value') <= selectedRating) {
            star.classList.add('selected');
        } else {
            star.classList.remove('selected');
        }
    });
}

// MODAL 1
const modal1 = document.getElementById("modalOverlay");
const openModal = document.getElementById("openModal");
const closeModal = document.getElementById("closeModal");
const cart = document.getElementById("cartModal");
const cartCount = document.getElementById("cart-count"); // Ambil elemen span untuk jumlah cart
let itemCount = 0; // Variabel untuk menyimpan jumlah item di cart

// BUKA MODAL
openModal.addEventListener("click", () => {
    updateModal(); // Memperbarui modal sebelum ditampilkan
    modal1.style.display = "flex";
});

// Fungsi untuk memperbarui konten modal
function updateModal() {
    const modalQuantity = document.getElementById("modalQuantity");
    const modalTotalPrice = document.getElementById("totalPrice");
    modalQuantity.textContent = `x${quantity}`; // Update jumlah di modal
    modalTotalPrice.textContent = `Rp ${(quantity * pricePerItem).toLocaleString("id-ID")}`; // Update total harga di modal
}

// CART 
cart.addEventListener('click', () => {
    itemCount++; // Tambah jumlah item
    cartCount.textContent = itemCount; // Update span dengan jumlah item
    showNotification('Success! Item added to cart!'); // Tampilkan notifikasi
    modal1.style.display = 'none'; // Menutup modal
});

// TOMBOL X
closeModal.addEventListener("click", () => {
    modal1.style.display = "none";
});

// Fungsi notifikasi
function showNotification(message) {
    const notification = document.getElementById('notification');
    notification.textContent = message;
    notification.style.display = 'block';
    notification.style.opacity = '1'; // Tampilkan notifikasi

    // Sembunyikan notifikasi setelah 3 detik
    setTimeout(() => {
        notification.style.opacity = '0'; // Sembunyikan dengan animasi
        setTimeout(() => {
            notification.style.display = 'none'; // Sembunyikan elemen
        }, 500); // Waktu untuk menyembunyikan elemen setelah animasi
    }, 3000);
}

// MODAL 2
const modal2 = document.getElementById('modalBayar');
const tutupModal = document.getElementById('tutupModal');
const bukaModal = document.getElementById('bukaModal');
const tombolBayar = document.getElementById('tombolBayar');

// UNTUK MEMBUKA MODAL
bukaModal.addEventListener('click', () => {
    modal2.style.display = 'flex';
});

// UNTUK MENUTUP MODAL (TOMBOL X)
tutupModal.addEventListener('click', () => {
    modal2.style.display = 'none';
    modal1.style.display = "none";
});

// UNTUK MENUTUP MODAL KETIKA SUDAH BAYAR
tombolBayar.addEventListener('click', () => {
    showNotification('Payment successful!');
    modal2.style.display = 'none';
    modal1.style.display = "none";
});

// Ambil semua opsi bank
const bankOptions = document.querySelectorAll('input[name="bank-method"]');
const qrCodes = document.querySelectorAll('.qrbank-summary'); // Ambil semua QR code

// Sembunyikan semua QR code saat modal dibuka
qrCodes.forEach(qr => qr.style.display = 'none');

// Event listener untuk bank options
bankOptions.forEach(option => {
    option.addEventListener('change', () => {
        // Sembunyikan semua QR code
        qrCodes.forEach(qr => qr.style.display = 'none');

        // Tampilkan QR code yang sesuai dengan bank yang dipilih
        const selectedBank = document.querySelector('input[name="bank-method"]:checked');
        if (selectedBank) {
            const qrCode = document.getElementById(`qr-code-bank-${selectedBank.value}`);
            if (qrCode) {
                qrCode.style.display = 'block'; // Tampilkan QR code yang sesuai
            }
        }
    });
});

// Ambil semua opsi e-wallet
const eWalletOptions = document.querySelectorAll('input[name="ewallet-method"]');
const qrCode = document.querySelectorAll('.qrbank-summary'); // Ambil semua QR code

// Sembunyikan semua QR code saat modal dibuka
qrCode.forEach(qr => qr.style.display = 'none');

// Event listener untuk e-wallet options
eWalletOptions.forEach(option => {
    option.addEventListener('change', () => {
        // Sembunyikan semua QR code
        qrCode.forEach(qr => qr.style.display = 'none');

        // Tampilkan QR code yang sesuai dengan e-wallet yang dipilih
        const selectedEWallet = document.querySelector('input[name="ewallet-method"]:checked');
        if (selectedEWallet) {
            const qrCode = document.getElementById(`qr-code-ewallet-${selectedEWallet.value}`);
            if (qrCode) {
                qrCode.style.display = 'block'; // Tampilkan QR code yang sesuai
            }
        }
    });
});

function copyToClipboard(elementId) {
    const inputElement = document.getElementById(elementId);
    inputElement.select(); // Pilih teks dalam input
    document.execCommand('copy'); // Salin teks ke clipboard
    showNotification('The code has been copied! : ' + inputElement.value); // Tampilkan pesan konfirmasi
}