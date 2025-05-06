// PINDAH TAB
const tabs = document.querySelectorAll('.nav-link');
const panes = document.querySelectorAll('.tab-pane');

tabs.forEach(tab => {
    tab.addEventListener('click', () => {
        tabs.forEach(t => t.classList.remove('active'));
        panes.forEach(p => p.classList.remove('active'));
        tab.classList.add('active');
        document.getElementById(tab.dataset.tab).classList.add('active');
    });
});

// MODAL 1
const modal1 = document.getElementById("modalOverlay");
const openModal = document.getElementById("openModal");
const closeModal = document.getElementById("closeModal");
const cart = document.getElementById("cartModal");
const quantityElement = document.getElementById("quantity");
const totalPriceElement = document.getElementById("totalPrice");
const cartCount = document.getElementById("cart-count"); // Ambil elemen span untuk jumlah cart
let quantity = 1;
const pricePerItem = 12000;
let itemCount = 0; // Variabel untuk menyimpan jumlah item di cart

// BUKA MODAL
openModal.addEventListener("click", () => {
    modal1.style.display = "flex";
});

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

// TAMBAH
document.getElementById("increase").addEventListener("click", () => {
    quantity++;
    updatePrice();
});

// KURANG
document.getElementById("decrease").addEventListener("click", () => {
    if (quantity > 1) {
        quantity--;
        updatePrice();
    }
});

// UPDET TOTAL
function updatePrice() {
    quantityElement.textContent = quantity;
    totalPriceElement.textContent = (quantity * pricePerItem).toLocaleString("id-ID");
}

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

// FILTER PRICE
// Ambil elemen dari DOM
const minPriceInput = document.getElementById('min-price');
const maxPriceInput = document.getElementById('max-price');
const priceValue = document.getElementById('price-value');
const filterButton = document.getElementById('filter-btn');

// Update tampilan harga saat slider berubah
function updatePriceValue() {
    const minPrice = minPriceInput.value;
    const maxPrice = maxPriceInput.value;
    priceValue.textContent = `Price: Rp${minPrice} - Rp${maxPrice}`;
}

// Fungsi untuk memfilter produk berdasarkan harga
function filterProducts() {
    const minPrice = parseInt(minPriceInput.value);
    const maxPrice = parseInt(maxPriceInput.value);

    // Ambil semua produk (misalnya, dengan class 'product')
    const products = document.querySelectorAll('.card'); // Ganti dengan selector yang sesuai

    products.forEach(product => {
        const productPrice = parseInt(product.querySelector('.card-title:last-child').textContent.replace('Rp ', '').replace('.', '')); // Ambil harga produk
        if (productPrice >= minPrice && productPrice <= maxPrice) {
            product.style.display = 'block'; // Tampilkan produk
        } else {
            product.style.display = 'none'; // Sembunyikan produk
        }
    });
}

// Fungsi untuk membatasi slider dan menggerakkan slider lainnya
function limitSliderValues() {
    const minPrice = parseInt(minPriceInput.value);
    const maxPrice = parseInt(maxPriceInput.value);

    // Jika minPrice lebih besar dari maxPrice, set maxPrice ke minPrice
    if (minPrice > maxPrice) {
        maxPriceInput.value = minPrice; // Set nilai max ke nilai min
    }

    // Jika maxPrice lebih kecil dari minPrice, set minPrice ke maxPrice
    if (maxPrice < minPrice) {
        minPriceInput.value = maxPrice; // Set nilai min ke nilai max
    }
}

// Event listener untuk slider
minPriceInput.addEventListener('input', () => {
    limitSliderValues(); // Batasi nilai slider
    updatePriceValue(); // Update tampilan harga
});

maxPriceInput.addEventListener('input', () => {
    limitSliderValues(); // Batasi nilai slider
    updatePriceValue(); // Update tampilan harga
});

// Event listener untuk tombol filter
filterButton.addEventListener('click', filterProducts);

// Inisialisasi tampilan harga saat halaman dimuat
updatePriceValue();

// untuk warna
const miPriceInput = document.getElementById('min-price');
const maPriceInput = document.getElementById('max-price');
const pricValue = document.getElementById('price-value');

function updateSliderBackground() {
    const minValue = parseInt(minPriceInput.value);
    const maxValue = parseInt(maxPriceInput.value);
    const minRange = parseInt(minPriceInput.min);
    const maxRange = parseInt(maxPriceInput.max);

    // Hitung persentase untuk latar belakang
    const minPercent = ((minValue - minRange) / (maxRange - minRange)) * 100;
    const maxPercent = ((maxValue - minRange) / (maxRange - minRange)) * 100;

    // Mengubah warna latar belakang slider
    const background = `linear-gradient(to right, #ddd ${minPercent}%, var(--main-color) ${minPercent}%, var(--main-color) ${maxPercent}%, #ddd ${maxPercent}%)`;

    minPriceInput.style.background = background;
    maxPriceInput.style.background = background;
}

// Event listener untuk mengupdate saat slider digeser
miPriceInput.addEventListener('input', updateSliderBackground);
maPriceInput.addEventListener('input', updateSliderBackground);

// Inisialisasi warna saat halaman dimuat
updateSliderBackground();

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




