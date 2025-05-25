// PINDAH TAB
const tabs = document.querySelectorAll('.nav-link');
const panes = document.querySelectorAll('.tab-pane');

tabs.forEach(tab => {
    tab.addEventListener('click', (event) => {
        // Jika link adalah link biasa, bukan tab, biarkan default action
        if (event.target.getAttribute('href') && !event.target.dataset.tab) {
            return;
        }
        event.preventDefault(); // Hanya untuk tab

        tabs.forEach(t => t.classList.remove('active'));
        panes.forEach(p => p.classList.remove('active'));

        tab.classList.add('active');
        const targetPaneId = tab.dataset.tab;
        if (targetPaneId) {
            const targetPane = document.getElementById(targetPaneId);
            if (targetPane) {
                targetPane.classList.add('active');
            }
        }
    });
});

// Fungsionalitas tombol favorit
document.querySelectorAll('.btn-favorite').forEach(button => {
    button.addEventListener('click', function () {
        const menuId = this.dataset.menuId;
        const favoriteButton = this; // SVG element

        // Jika user tidak login (ditangani di PHP, tapi bisa juga dicek di JS jika perlu)
        // Untuk contoh ini, asumsikan PHP sudah menghandle link ke login jika tidak ada session

        fetch('logic/toggle_favorite.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `menu_id=${menuId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.action === 'added') {
                    favoriteButton.classList.add('active');
                    // CSS akan menangani perubahan warna fill melalui kelas .active
                } else { // 'removed'
                    favoriteButton.classList.remove('active');
                    // CSS akan menangani perubahan warna fill
                }
                // Notifikasi bisa ditambahkan di sini jika diinginkan
                // showNotification(data.message);
            } else {
                showNotification('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan saat memperbarui favorit.');
        });
    });
});


// MODAL UNTUK ADD TO CART (dan mungkin BUY NOW jika dipisah)
const modalOverlay = document.getElementById("modalOverlay");
const closeModalButton = document.getElementById("closeModal");
const cartButtons = document.querySelectorAll(".add-cart-button"); // Tombol SVG keranjang di card
const buyNowButtons = document.querySelectorAll(".openModal"); // Jika ada tombol "Buy Now" terpisah

const modalMenuIdInput = document.getElementById('modalMenuId');
const modalImage = document.getElementById('modalImage');
const modalName = document.getElementById('modalName');
const modalPrice = document.getElementById('modalPrice');
const modalStok = document.getElementById('modalStok');
const modalQuantityInput = document.getElementById('modalQuantityInput');
const modalCatatanInput = document.getElementById('modalCatatan');

// Fungsi untuk membuka modal dengan detail produk
function openProductModal(cardElement) {
    const form = cardElement.querySelector('.add-to-cart-form'); // Asumsi form ada di dalam card
    if (!form) {
        console.error("Form not found in card element:", cardElement);
        return;
    }

    modalImage.src = cardElement.querySelector('.image-wrapper img').src;
    modalName.textContent = cardElement.querySelector('.card-title:nth-of-type(1)').textContent;
    modalPrice.textContent = cardElement.querySelector('.card-title:nth-of-type(2)').textContent.replace('Rp ', '').replace(/\./g, ''); // Ambil angka saja
    modalStok.textContent = cardElement.querySelector('.card-title:nth-of-type(3)').textContent.replace('Tersedia: ', '');
    modalMenuIdInput.value = form.querySelector('input[name="menu_id"]').value;
    
    modalQuantityInput.value = 1; // Reset quantity
    modalQuantityInput.max = modalStok.textContent; // Set max quantity berdasarkan stok
    modalCatatanInput.value = ''; // Reset catatan
    modalOverlay.style.display = "flex";
}

// Event listener untuk tombol "Add to Cart" (SVG Keranjang di card)
cartButtons.forEach(button => {
    button.addEventListener('click', function(event) {
        event.preventDefault(); // Mencegah submit form jika ada
        const cardElement = this.closest('.card');
        openProductModal(cardElement);
    });
});

// Event listener untuk tombol "Buy Now" (jika ada dan berbeda)
// buyNowButtons.forEach(button => {
//     button.addEventListener('click', function() {
//         const cardElement = this.closest('.card'); // Sesuaikan jika struktur berbeda
//         openProductModal(cardElement);
//         // Mungkin ada logika tambahan untuk "Buy Now"
//     });
// });

// Event listener untuk tombol close modal
if (closeModalButton) {
    closeModalButton.addEventListener("click", () => {
        modalOverlay.style.display = "none";
    });
}

// Event listener untuk menutup modal jika klik di luar area modal
window.addEventListener('click', (event) => {
    if (event.target === modalOverlay) {
        modalOverlay.style.display = "none";
    }
});


// Update total di modal pembayaran saat modal dibuka (jika ada modal pembayaran terpisah)
// const paymentModal = document.getElementById('modalBayar');
// if (paymentModal) {
//     paymentModal.addEventListener('show.bs.modal', function () {
//         const currentSubtotalText = document.querySelector('#final-subtotal').textContent; // Jika ada subtotal di halaman utama
//         document.getElementById('modal-total-amount').textContent = currentSubtotalText;
//         document.getElementById('customerNameInput').value = '';
//         document.getElementById('paymentCash').checked = true;
//         document.getElementById('orderNotesInput').value = '';
//     });
// }

// Fungsi notifikasi
function showNotification(message) {
    const notification = document.getElementById('notification');
    if (!notification) {
        // Buat elemen notifikasi jika belum ada
        const newNotification = document.createElement('div');
        newNotification.id = 'notification';
        newNotification.className = 'notification'; // Pastikan kelas ini ada di CSS Anda
        document.body.appendChild(newNotification);
        notification = newNotification;
    }

    notification.textContent = message;
    notification.classList.add('show'); // Tambah kelas untuk animasi tampil

    setTimeout(() => {
        notification.classList.remove('show'); // Hapus kelas untuk animasi hilang
    }, 3000);
}

// Penyesuaian kuantitas di modal
const quantityInputModal = document.getElementById("modalQuantityInput");
const decreaseBtnModal = document.getElementById("decrease"); // Pastikan ID ini ada di modal Anda
const increaseBtnModal = document.getElementById("increase"); // Pastikan ID ini ada di modal Anda

if (decreaseBtnModal && increaseBtnModal && quantityInputModal) {
    decreaseBtnModal.addEventListener("click", () => {
        let currentVal = parseInt(quantityInputModal.value);
        if (currentVal > 1) {
            quantityInputModal.value = currentVal - 1;
        }
    });

    increaseBtnModal.addEventListener("click", () => {
        let currentVal = parseInt(quantityInputModal.value);
        let maxStock = parseInt(document.getElementById('modalStok').textContent);
        if (currentVal < maxStock) {
            quantityInputModal.value = currentVal + 1;
        } else {
            showNotification('Jumlah melebihi stok yang tersedia.');
        }
    });

    quantityInputModal.addEventListener('input', function() {
        let currentVal = parseInt(this.value);
        let maxStock = parseInt(document.getElementById('modalStok').textContent);
        if (isNaN(currentVal) || currentVal < 1) {
            this.value = 1;
        } else if (currentVal > maxStock) {
            this.value = maxStock;
            showNotification('Jumlah melebihi stok yang tersedia.');
        }
    });
}


// Logika lain seperti filter harga dan tab kategori dari menu.js Anda bisa tetap di sini
// FILTER PRICE (jika masih relevan dan ada elemennya di menu.php)
const minPriceInput = document.getElementById('min-price');
const maxPriceInput = document.getElementById('max-price');
const priceValueDisplay = document.getElementById('price-value'); // Ganti nama variabel agar tidak konflik
const filterButton = document.getElementById('filter-btn');

function updatePriceValueDisplay() { // Ganti nama fungsi
    if (minPriceInput && maxPriceInput && priceValueDisplay) {
        const minPrice = minPriceInput.value;
        const maxPrice = maxPriceInput.value;
        priceValueDisplay.textContent = `Price: Rp${minPrice} - Rp${maxPrice}`;
    }
}

function filterProductsByPrice() { // Ganti nama fungsi
    if (minPriceInput && maxPriceInput) {
        const minPrice = parseInt(minPriceInput.value);
        const maxPrice = parseInt(maxPriceInput.value);
        const products = document.querySelectorAll('.card');

        products.forEach(product => {
            const priceElement = product.querySelector('.card-title:nth-of-type(2)');
            if (priceElement) {
                const productPrice = parseInt(priceElement.textContent.replace('Rp ', '').replace(/\./g, ''));
                if (productPrice >= minPrice && productPrice <= maxPrice) {
                    product.closest('.col').style.display = 'flex'; // Tampilkan kolomnya
                } else {
                    product.closest('.col').style.display = 'none'; // Sembunyikan kolomnya
                }
            }
        });
    }
}

function limitSliderValues() {
    if (minPriceInput && maxPriceInput) {
        const minPrice = parseInt(minPriceInput.value);
        const maxPrice = parseInt(maxPriceInput.value);
        if (minPrice > maxPrice) {
            maxPriceInput.value = minPrice;
        }
        if (maxPrice < minPrice) {
            minPriceInput.value = maxPrice;
        }
    }
}
if (minPriceInput) {
    minPriceInput.addEventListener('input', () => {
        limitSliderValues();
        updatePriceValueDisplay();
        updateSliderBackground(); // Panggil fungsi update background
    });
}
if (maxPriceInput) {
    maxPriceInput.addEventListener('input', () => {
        limitSliderValues();
        updatePriceValueDisplay();
        updateSliderBackground(); // Panggil fungsi update background
    });
}
if (filterButton) {
    filterButton.addEventListener('click', filterProductsByPrice);
}


function updateSliderBackground() {
    if (minPriceInput && maxPriceInput) {
        const minValue = parseInt(minPriceInput.value);
        const maxValue = parseInt(maxPriceInput.value);
        const minRange = parseInt(minPriceInput.min);
        const maxRange = parseInt(maxPriceInput.max);

        const minPercent = ((minValue - minRange) / (maxRange - minRange)) * 100;
        const maxPercent = ((maxValue - minRange) / (maxRange - minRange)) * 100;

        const background = `linear-gradient(to right, #ddd ${minPercent}%, var(--main-color) ${minPercent}%, var(--main-color) ${maxPercent}%, #ddd ${maxPercent}%)`;

        minPriceInput.style.background = background;
        // Jika kedua slider adalah elemen yang sama atau Anda ingin keduanya memiliki background yang sama
        maxPriceInput.style.background = background;
    }
}

// Panggil saat halaman dimuat
document.addEventListener('DOMContentLoaded', () => {
    updatePriceValueDisplay();
    updateSliderBackground();
});