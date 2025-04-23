// Event listener untuk checkbox "select all"
document.getElementById('selectAll').addEventListener('change', function () {
    const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    updateTotal();
});

// Ambil semua checkbox item
const checkboxes = document.querySelectorAll('.item-checkbox');

// Tambahkan event listener untuk setiap checkbox
checkboxes.forEach(checkbox => {
    checkbox.addEventListener('change', updateTotal);
});

// Fungsi untuk menghitung total
function updateTotal() {
    let total = 0;
    checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
            const row = checkbox.closest('tr');
            const priceCell = row.querySelector('.price-cell');
            const price = parseInt(priceCell.textContent.replace(/[^0-9]/g, ''));
            total += price;
        }
    });
    // Update total harga di elemen dengan ID totalPrice
    document.getElementById('totalPrice').innerText = 'Rp ' + total.toLocaleString();
}

// Ambil semua tombol edit dan save
const editButtons = document.querySelectorAll('.edit-table');
const saveButtons = document.querySelectorAll('.save-table');

editButtons.forEach((editButton) => {
    editButton.addEventListener('click', () => {
        const row = editButton.closest('tr');
        const memoCell = row.querySelector('.memo');
        const quantityCell = row.querySelector('.quantity-cell');
        const quantitySpan = quantityCell.querySelector('.quantity');
        const quantityInput = quantityCell.querySelector('.quantity-input');

        // Tampilkan input dan sembunyikan teks
        memoCell.contentEditable = true; // Membuat memo bisa diedit
        memoCell.focus(); // Fokus pada input memo
        quantitySpan.style.display = 'none'; // Sembunyikan span quantity
        quantityInput.style.display = 'inline'; // Tampilkan input quantity

        // Sembunyikan tombol edit dan tampilkan tombol save
        editButton.style.display = 'none';
        const saveButton = row.querySelector('.save-table');
        saveButton.style.display = 'block';
    });
});

// Event listener untuk tombol save
saveButtons.forEach((saveButton) => {
    saveButton.addEventListener('click', () => {
        const row = saveButton.closest('tr');
        const memoCell = row.querySelector('.memo');
        const quantityCell = row.querySelector('.quantity-cell');
        const quantitySpan = quantityCell.querySelector('.quantity');
        const quantityInput = quantityCell.querySelector('.quantity-input');

        // Ambil nilai dari input dan perbarui tampilan
        memoCell.contentEditable = false; // Nonaktifkan edit memo
        const newQuantity = quantityInput.value;
        quantitySpan.textContent = newQuantity; // Perbarui span quantity
        quantityInput.style.display = 'none'; // Sembunyikan input quantity
        quantitySpan.style.display = 'inline'; // Tampilkan span quantity

        // Sembunyikan tombol save dan tampilkan tombol edit
        saveButton.style.display = 'none';
        const editButton = row.querySelector('.edit-table');
        editButton.style.display = 'block';

        // Panggil fungsi untuk memperbarui total jika diperlukan
        updatePrice(row); // Panggil updatePrice dengan baris saat ini
    });
});

// Ambil semua input quantity
const quantityInputs = document.querySelectorAll('.quantity-input');

// Tambahkan event listener untuk setiap input quantity
quantityInputs.forEach(input => {
    input.addEventListener('change', function () {
        updatePrice(this.closest('tr')); // Panggil updatePrice dengan baris terkait
    });
});

// Fungsi untuk memperbarui harga
function updatePrice(row) {
    const priceCell = row.querySelector('.price-cell'); // Ambil sel harga
    const pricePerUnit = parseInt(priceCell.getAttribute('data-price')); // Ambil harga per unit dari atribut data-price
    const quantityInput = row.querySelector('.quantity-input'); // Ambil input quantity
    const quantity = parseInt(quantityInput.value); // Ambil nilai quantity

    // Pastikan quantity valid
    if (isNaN(quantity) || quantity < 1) {
        quantityInput.value = 1; // Set ke 1 jika invalid
        quantity = 1; // Set quantity ke 1
    }

    // Hitung total harga untuk item ini
    const totalPrice = pricePerUnit * quantity;

    // Update sel harga dengan total harga
    priceCell.textContent = 'Rp ' + totalPrice.toLocaleString();

    // Panggil fungsi untuk memperbarui total keseluruhan
    updateTotal();
}


// Fungsi untuk mengatur event listener pada tombol delete
function setupDeleteButtons() {
    const deleteButtons = document.querySelectorAll('.delete-table');

    deleteButtons.forEach((deleteButton) => {
        deleteButton.addEventListener('click', () => {
            const row = deleteButton.closest('tr'); // Ambil baris terkait
            row.remove(); // Hapus baris dari tabel

            // Panggil fungsi untuk memperbarui total setelah menghapus item
            updateTotal();
        });
    });
}

// Panggil fungsi setupDeleteButtons setelah halaman dimuat
document.addEventListener('DOMContentLoaded', setupDeleteButtons);


let rowToDelete; // Variabel untuk menyimpan baris yang akan dihapus

// Fungsi untuk mengatur event listener pada tombol delete
function setupDeleteButtons() {
    const deleteButtons = document.querySelectorAll('.delete-table');

    deleteButtons.forEach((deleteButton) => {
        deleteButton.addEventListener('click', () => {
            rowToDelete = deleteButton.closest('tr'); // Simpan baris yang akan dihapus
            const itemName = rowToDelete.querySelector('td:nth-child(3)').textContent; // Ambil nama item
            const itemImage = rowToDelete.querySelector('img').src; // Ambil gambar item

            // Update modal dengan informasi item
            document.getElementById('modalItemName').textContent = itemName;
            document.getElementById('modalItemImage').src = itemImage;

            // Tampilkan modal
            document.getElementById('confirmDeleteModal').style.display = 'block';
        });
    });
}

// Event listener untuk tombol konfirmasi di modal
document.getElementById('confirmDelete').addEventListener('click', () => {
    if (rowToDelete) {
        rowToDelete.remove(); // Hapus baris dari tabel
        updateTotal(); // Perbarui total
        rowToDelete = null; // Reset variabel
    }
    document.getElementById('confirmDeleteModal').style.display = 'none'; // Sembunyikan modal
});

// Event listener untuk tombol batal di modal
document.getElementById('cancelDelete').addEventListener('click', () => {
    document.getElementById('confirmDeleteModal').style.display = 'none'; // Sembunyikan modal
});

// Event listener untuk menutup modal ketika mengklik tanda silang
document.getElementById('closeDeleteModal').addEventListener('click', () => {
    document.getElementById('confirmDeleteModal').style.display = 'none'; // Sembunyikan modal
});

// Panggil fungsi setupDeleteButtons setelah halaman dimuat
document.addEventListener('DOMContentLoaded', setupDeleteButtons);

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


const cart = document.getElementById("confirmDelete");
const cartCount = document.getElementById("cart-count"); // Ambil elemen span untuk jumlah cart
let itemCount = 3; // Variabel untuk menyimpan jumlah item di cart

// CART 
cart.addEventListener('click', () => {
    itemCount--; // Tambah jumlah item
    cartCount.textContent = itemCount; // Update span dengan jumlah item
    showNotification('Item has been successfully deleted.'); // Tampilkan notifikasi
});