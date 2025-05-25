-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 24 Bulan Mei 2025 pada 18.13
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tapal_kuda`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `checkout`
--

CREATE TABLE `checkout` (
  `checkout_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keranjang`
--

CREATE TABLE `keranjang` (
  `order_id` int(11) NOT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `catatan` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `url_foto` varchar(255) DEFAULT NULL,
  `type` enum('makanan_berat','minuman','cemilan','kopi') DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `status` enum('tersedia','habis') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `menu`
--

INSERT INTO `menu` (`id`, `nama`, `url_foto`, `type`, `price`, `quantity`, `deskripsi`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Kopi Tubruk Arabika', 'arabika.jpg', 'kopi', 14000.00, 1000, 'Nikmati kenikmatan sejati dari secangkir Kopi Tubruk Arabika, dibuat dari 100% biji arabika pilihan yang disangrai sempurna. Diseduh dengan cara tradisional khas Indonesia, kopi ini menawarkan rasa yang kaya, pekat, dan berkarakter, dengan aroma yang membangkitkan semangat sejak tegukan pertama.', 'tersedia', '2025-05-06 04:52:58', '2025-05-24 11:20:27'),
(2, 'Cappucino', 'cappucino.jpg', 'kopi', 22000.00, 999, 'Butuh pelukan hangat dalam bentuk kopi? Coba Cappuccino kami perpaduan sempurna antara espresso arabika berkualitas, susu steamed yang creamy, dan buih halus di atasnya. Setiap cangkir dibuat dengan cinta dan keseimbangan rasa yang bikin nagih!', 'tersedia', '2025-05-06 04:52:58', '2025-05-22 06:47:48'),
(3, 'ES Kopi Susu', 'kosu.jpg', 'kopi', 22000.00, 1000, 'Minuman favorit semua kalangan! Es Kopi Susu kami adalah kombinasi sempurna antara espresso arabika yang bold, susu segar yang lembut, dan sentuhan manis yang pas disajikan dingin untuk kesegaran maksimal.', 'tersedia', '2025-05-06 04:52:58', '2025-05-21 02:23:13'),
(4, 'Espresso', 'espresso.jpg', 'kopi', 14000.00, 1000, 'Espresso kami dibuat dari biji arabika pilihan yang disangrai dengan sempurna untuk menghasilkan rasa kuat, pekat, dan aromatik. Cocok untuk kamu yang butuh dorongan energi tanpa basa-basi—langsung to the point!', 'tersedia', '2025-05-06 04:52:58', '2025-05-21 02:23:16'),
(5, 'Espresso Double', 'espresso1.jpg', 'kopi', 17000.00, 1000, 'Siap hadapi hari yang panjang? Espresso Double kami adalah jawaban untuk kamu yang butuh ekstra tenaga dan ekstra rasa. Dua shot espresso dari biji arabika premium, disajikan pekat dan panas, dengan cita rasa tajam dan aroma yang membangkitkan semangat.', 'tersedia', '2025-05-06 04:52:58', '2025-05-21 02:23:19'),
(6, 'Japanase Flavour', 'JAPAN.jpg', 'kopi', 21000.00, 1000, 'Rasakan kelembutan dan keunikan rasa Jepang lewat varian Japanese Flavour kami. Mulai dari Matcha yang earthy, Hojicha yang smoky, hingga Yuzu yang segar setiap rasa dipilih dengan cermat untuk menghadirkan pengalaman yang menenangkan dan autentik.', 'tersedia', '2025-05-06 04:52:58', '2025-05-21 02:23:22'),
(7, 'Latte', 'Latte.jpg', 'kopi', 25000.00, 1000, 'Butuh momen tenang di tengah hari yang sibuk? Latte kami hadir dengan perpaduan sempurna antara espresso arabika yang halus dan susu steamed yang creamy. Rasanya ringan, aromanya menenangkan, dan cocok dinikmati kapan saja—pagi, siang, atau sore.', 'tersedia', '2025-05-06 04:52:58', '2025-05-21 02:23:24'),
(8, 'Sukomon', 'SUKOMON.jpg', 'kopi', 21000.00, 1000, 'Perpaduan yang tidak biasa, tapi luar biasa!\nSukomon adalah minuman kopi susu lemon yang menyegarkan dan unik. Menggabungkan espresso arabika yang bold, susu yang creamy, dan sentuhan asam segar dari lemon, menciptakan sensasi rasa baru yang bikin penasaran dan langsung jatuh cinta.', 'tersedia', '2025-05-06 04:52:58', '2025-05-21 02:23:27'),
(9, 'V60', 'V60.jpg', 'kopi', 19000.00, 1000, 'Nikmati kelezatan kopi dengan cara yang lebih personal dan presisi! V60 kami menggunakan metode manual pour-over, di mana air panas disiram perlahan melalui biji kopi segar pilihan. Hasilnya? Kopi yang lebih clean, dengan rasa yang jelas dan penuh karakter. Setiap tegukan membawa kenikmatan yang seimbang—cocok untuk pecinta kopi sejati yang ingin merasakan setiap nuansa rasa.', 'tersedia', '2025-05-06 04:52:58', '2025-05-21 02:23:30'),
(10, 'Vietname Drip', 'VIETNAME.jpg', 'kopi', 19000.00, 1000, 'Rasakan kenikmatan kopi ala Vietnam dengan Vietnamese Drip kami! Diseduh dengan metode drip tradisional, kopi ini memiliki rasa yang kental, kaya, dan sedikit manis dari campuran susu kental manis. Setiap tetesnya memberikan sensasi kopi yang lembut namun penuh rasa, menjadikannya pilihan sempurna untuk menemani aktivitas santai Anda.', 'tersedia', '2025-05-06 04:52:58', '2025-05-21 02:23:35'),
(11, 'Matcha', 'maca.jpg', 'minuman', 17000.00, 1000, 'Rasakan kekayaan rasa dari Matcha kami, terbuat dari bubuk teh hijau kualitas terbaik yang diimpor langsung dari Jepang. Dipadukan dengan susu segar, matcha ini memberikan sensasi creamy yang lembut dengan rasa teh hijau yang khas—segar, sedikit pahit, dan menenangkan. Selain rasanya yang nikmat, matcha juga kaya akan antioksidan dan manfaat kesehatan lainnya!', 'tersedia', '2025-05-06 04:52:58', '2025-05-21 02:23:39'),
(12, 'Red Velvet Latte', 'red.jpg', 'minuman', 17000.00, 1000, 'Nikmati kelezatan Red Velvet Latte kami kombinasi sempurna antara espresso yang kaya, susu steamed yang creamy, dan sirup red velvet yang manis dan lembut. Dengan warna merah yang memikat dan rasa yang indulgent, setiap tegukan terasa seperti momen spesial. Menyajikan sensasi rasa manis yang tidak terlalu berat, pas untuk menemani hari Anda!', 'tersedia', '2025-05-06 04:52:58', '2025-05-21 02:23:43'),
(13, 'Es Teh Manis', 'TehManis.jpg', 'minuman', 8000.00, 998, 'Nikmati kesegaran Es Teh Manis kami yang sempurna untuk menghilangkan dahaga! Dibuat dari teh pilihan yang diseduh dengan hati-hati dan dicampur dengan gula yang pas, minuman ini memberikan rasa manis yang menyegarkan di setiap tegukan. Disajikan dingin, cocok untuk menemani hari-hari panas atau kapan saja kamu butuh kesegaran.', 'tersedia', '2025-05-06 04:52:58', '2025-05-22 06:47:56'),
(14, 'Wedang', 'wedang.jpg', 'minuman', 8000.00, 1000, 'Nikmati kehangatan Wedang kami minuman tradisional yang penuh rasa dan manfaat. Terbuat dari rempah-rempah pilihan seperti jahe, serai, dan gula merah, Wedang ini memberikan rasa hangat dan menyegarkan yang cocok dinikmati di segala suasana. Ideal untuk menghangatkan tubuh dan menenangkan pikiran, seperti momen santai di rumah.', 'tersedia', '2025-05-06 04:52:58', '2025-05-21 02:23:50'),
(15, 'Chicken Teriyaki', 'AyamTeriyaki.jpg', 'makanan_berat', 20000.00, 1000, 'Nikmati kelezatan Chicken Teriyaki kami, ayam yang dipanggang dengan sempurna dan dilapisi dengan saus teriyaki manis dan gurih. Setiap potong ayam dipadukan dengan rasa yang seimbang, memberikan sensasi kenikmatan yang tak terlupakan. Dihidangkan dengan sayuran segar dan nasi putih hangat, menjadikan hidangan ini pilihan sempurna untuk santapan yang memuaskan.', 'tersedia', '2025-05-06 04:52:58', '2025-05-21 02:23:55'),
(16, 'Cuanki', 'cuanki.png', 'makanan_berat', 15000.00, 1000, 'Rasakan kenikmatan Cuanki kami, hidangan khas Bandung yang penuh cita rasa. Mie kenyal dipadukan dengan kuah kaldu panas yang gurih, serta berbagai topping seperti bakso, tahu, dan siomay yang menggugah selera. Disajikan dalam kondisi panas, Cuanki ini cocok untuk menghangatkan tubuh dan memuaskan perut. Sempurna untuk makan siang atau malam yang penuh kenikmatan.', 'tersedia', '2025-05-06 04:52:58', '2025-05-21 02:23:58'),
(17, 'indomie goreng', 'indomieGoreng.jpg', 'makanan_berat', 15000.00, 1000, 'Indomie Goreng kami adalah pilihan sempurna untuk memuaskan selera dalam sekejap. Mie yang kenyal, digoreng dengan bumbu khas yang kaya rasa dan disajikan dengan pelengkap sederhana namun menggoda. Nikmati sensasi gurih dan pedas dari bumbu yang meresap sempurna ke dalam mie, menjadikannya hidangan yang selalu bisa diandalkan untuk mengatasi rasa lapar kapan saja.', 'tersedia', '2025-05-06 04:52:58', '2025-05-21 02:24:02'),
(18, 'indomie kuah', 'indomieKuah.jpeg', 'makanan_berat', 15000.00, 1000, 'Nikmati kehangatan Indomie Kuah kami, mie kenyal yang disajikan dengan kuah kaldu gurih yang kaya rasa. Dipadukan dengan bumbu khas yang meresap sempurna, hidangan ini memberikan sensasi kenikmatan yang hangat dan mengenyangkan. Cocok untuk teman santai atau menghangatkan tubuh di hari yang dingin.', 'tersedia', '2025-05-06 04:52:58', '2025-05-21 02:24:05'),
(19, 'Nasi Tutug Onceom', 'nasiTutug.webp', 'makanan_berat', 27000.00, 999, 'Nikmati kelezatan Nasi Tutug Oncom kami, hidangan khas Sunda yang menggugah selera. Nasi putih hangat ditumbuk dengan oncom yang dibumbui dengan rempah-rempah pilihan, menciptakan rasa gurih yang unik dan kaya. Disajikan dengan pelengkap seperti ayam goreng atau sambal terasi, menjadikan hidangan ini pilihan sempurna untuk makan siang atau makan malam yang memuaskan.', 'tersedia', '2025-05-06 04:52:58', '2025-05-22 06:47:56'),
(20, 'Bala-bala', 'balabala.jpg', 'cemilan', 13000.00, 999, 'Nikmati kelezatan Bala-Bala kami, camilan gorengan khas Indonesia yang renyah di luar dan lembut di dalam. Terbuat dari campuran sayuran segar seperti wortel, kol, dan bumbu pilihan yang dibalut dalam adonan tepung yang gurih, menjadikannya pilihan sempurna untuk ngemil atau sebagai teman makan. Disajikan panas, siap memanjakan lidah Anda dengan kelezatannya.', 'tersedia', '2025-05-06 04:52:58', '2025-05-22 07:17:08'),
(21, 'Kentang Sosis', 'kentangSosis.jpg', 'cemilan', 16000.00, 1000, 'Nikmati kelezatan Kentang Sosis kami—kentang goreng yang renyah dipadukan dengan sosis yang juicy dan penuh rasa. Setiap gigitan memberikan sensasi gurih yang memuaskan, dengan rasa kenyal dari sosis dan kerenyahan kentang yang tak terlupakan. Hidangan ringan yang sempurna untuk menemani waktu santai Anda, baik sebagai camilan atau teman makan.', 'tersedia', '2025-05-06 04:52:58', '2025-05-21 02:24:14'),
(22, 'Risoles', 'risol.jpg', 'cemilan', 18000.00, 999, 'Nikmati Risoles kami yang lembut di luar dan kaya rasa di dalam! Terbuat dari kulit tepung yang renyah, diisi dengan perpaduan ragout daging atau sayuran yang gurih dan creamy. Setiap gigitan memberikan sensasi kenikmatan yang memuaskan, cocok untuk camilan sore atau teman makan. Risoles ini sempurna dinikmati dengan saus sambal atau mayones sebagai pelengkap.', 'tersedia', '2025-05-06 04:52:58', '2025-05-22 06:45:54'),
(23, 'Roti Bakar', 'Roti.jpg', 'cemilan', 16000.00, 1000, 'Nikmati kelezatan Roti Bakar kami yang hangat dan renyah! Roti yang dipanggang dengan sempurna, diolesi dengan mentega dan diberi berbagai pilihan topping manis atau gurih—mulai dari cokelat, keju, selai, hingga kacang. Setiap gigitan menghadirkan sensasi gurih dan manis yang pas, menjadikannya teman santai yang sempurna untuk menikmati kopi atau teh.', 'tersedia', '2025-05-06 04:52:58', '2025-05-21 02:24:20'),
(24, 'Seblak Juragan', 'seblak.jpg', 'makanan_berat', 15000.00, 1000, 'Rasakan sensasi pedas dan gurih yang menggugah selera dengan Seblak Juragan kami! Terbuat dari kerupuk basah yang dimasak dengan bumbu rempah khas, dipadukan dengan sayuran segar, mie, dan topping pilihan seperti aya, bakso, atau sosis. Setiap suapan memberikan rasa pedas yang mantap, dijamin bikin ketagihan dan ingin nambah terus!', 'tersedia', '2025-05-06 04:52:58', '2025-05-21 02:24:23'),
(25, 'Tempe Mendoan', 'tempeMendoan.jpg', 'cemilan', 13000.00, 1000, 'Nikmati kelezatan Tempe Mendoan kami, tempe tipis yang dibalut dengan tepung crispy, digoreng sempurna hingga renyah di luar namun tetap lembut di dalam. Setiap potongan tempe mendoan ini memiliki rasa gurih yang khas, cocok untuk camilan atau teman makan nasi. Disajikan dengan sambal kecap atau sambal pedas, tempe mendoan ini selalu bikin nagih!', 'tersedia', '2025-05-06 04:52:58', '2025-05-21 02:24:27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `reservation_id` int(11) DEFAULT NULL,
  `type` enum('order','reservasi') DEFAULT NULL,
  `pesan` text DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` datetime NOT NULL DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('completed','pending','cancelled') NOT NULL DEFAULT 'completed',
  `customer_name` varchar(255) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_date`, `total_amount`, `status`, `customer_name`, `payment_method`, `notes`) VALUES
(2, 5, '2025-05-22 13:47:48', 36000.00, 'completed', 'vemas', 'cash', ''),
(3, 5, '2025-05-22 13:47:56', 35000.00, 'completed', 'vemas', 'cash', ''),
(4, 9, '2025-05-22 14:17:08', 13000.00, 'completed', '', 'cash', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price_per_item` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `item_notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `menu_id`, `quantity`, `price_per_item`, `subtotal`, `item_notes`) VALUES
(3, 2, 1, 1, 14000.00, 14000.00, ''),
(4, 2, 2, 1, 22000.00, 22000.00, ''),
(5, 3, 13, 1, 8000.00, 8000.00, ''),
(6, 3, 19, 1, 27000.00, 27000.00, ''),
(7, 4, 20, 1, 13000.00, 13000.00, '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `reservasi`
--

CREATE TABLE `reservasi` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `kode_reservasi` varchar(20) DEFAULT NULL,
  `tanggal_reservasi` datetime DEFAULT NULL,
  `jumlah_orang` int(11) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL,
  `no_telp` varchar(20) NOT NULL,
  `status` enum('pending','dikonfirmasi','dibatalkan') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `reservasi`
--

INSERT INTO `reservasi` (`id`, `user_id`, `kode_reservasi`, `tanggal_reservasi`, `jumlah_orang`, `email`, `message`, `no_telp`, `status`, `created_at`, `updated_at`) VALUES
(1, 6, 'RSV20250521054156700', '2025-05-21 10:42:00', 24, 'salmanisal24@gmail.com', 'test', '076789', 'dikonfirmasi', '2025-05-21 03:41:56', '2025-05-21 03:44:37'),
(2, 6, 'RSV20250521054555141', '2025-05-21 10:42:00', 24, 'salmanisal24@gmail.com', 'test', '076789', 'dikonfirmasi', '2025-05-21 03:45:55', '2025-05-21 04:30:30'),
(3, 5, 'RSV20250521063607727', '2025-05-22 13:35:00', 6, 'stwncssk@gmail.com', 'masak yang enak', '0987654345', 'dibatalkan', '2025-05-21 04:36:07', '2025-05-21 04:45:16'),
(4, 5, 'RSV20250521065534445', '2025-05-22 13:55:00', 2, 'vemas@gmail.com', 'qpq', '00976', 'dikonfirmasi', '2025-05-21 04:55:34', '2025-05-21 07:04:45'),
(5, 5, 'RSV20250521083747657', '2025-05-22 15:37:00', 6, 'vemas@gmail.com', 'iya', '00976', 'dikonfirmasi', '2025-05-21 06:37:47', '2025-05-21 06:53:08'),
(6, 5, 'RSV20250524151316244', '2025-05-29 03:56:00', 2, 'vemas@gmail.com', 'wleeee', '00976', 'pending', '2025-05-24 13:13:16', '2025-05-24 13:13:16');

-- --------------------------------------------------------

--
-- Struktur dari tabel `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `menu_id` int(11) NOT NULL,
  `rating` tinyint(1) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `menu_id`, `rating`, `comment`, `created_at`) VALUES
(14, 5, 1, 5, 'enak buangetttt', '2025-05-24 13:21:56');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `role` enum('admin','kasir','member') DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `no_telp` varchar(20) DEFAULT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `role`, `username`, `password`, `nama`, `email`, `no_telp`, `gender`, `alamat`, `profile_picture`, `created_at`, `updated_at`) VALUES
(5, 'member', 'vemas', 'c345037fb938ae1a5b19630c704a4682', 'vemas aja', 'vemas@gmail.com', '00976', 'male', 'jlmhhj', NULL, '2025-05-06 05:43:30', '2025-05-06 05:43:30'),
(6, 'member', 'salman', '97502267ac1b12468f69c14dd70196e9', 'Salman Ridhwan', 'salman@gmail.com', '0892', 'male', 'jl. kebon jeruk', 'user_6_1747795998.jpg', '2025-05-07 07:17:05', '2025-05-21 02:53:18'),
(8, 'admin', 'admin', '0192023a7bbd73250516f069df18b500', 'haykal aja', 'haykal@gmail.com', '00976', 'male', 'jl. aceh', NULL, '2025-05-07 08:44:18', '2025-05-07 08:44:18'),
(9, 'kasir', 'kasir', 'de28f8f7998f23ab4194b51a6029416f', 'vemas qomarudin lazuardy prasetyo', 'vemas@gmail.com', '0812345678909', '', 'JL. Aljabar', 'user_9_1747811146.jpg', '2025-05-07 08:45:46', '2025-05-21 07:37:48'),
(10, 'member', 'dwiky', '9182ddda98d655c44ec373fc5444a64e', 'Dwiky mandalika', 'dwiky@gmail.com', '0887654321', 'male', 'JL. Telekomunikasi', NULL, '2025-05-24 14:27:46', '2025-05-24 14:27:46');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `checkout`
--
ALTER TABLE `checkout`
  ADD PRIMARY KEY (`checkout_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Indeks untuk tabel `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`menu_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Indeks untuk tabel `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Indeks untuk tabel `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `reservation_id` (`reservation_id`);

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Indeks untuk tabel `reservasi`
--
ALTER TABLE `reservasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `reviews_ibfk_1` (`menu_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `checkout`
--
ALTER TABLE `checkout`
  MODIFY `checkout_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT untuk tabel `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `reservasi`
--
ALTER TABLE `reservasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `checkout`
--
ALTER TABLE `checkout`
  ADD CONSTRAINT `checkout_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `checkout_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`);

--
-- Ketidakleluasaan untuk tabel `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `keranjang`
--
ALTER TABLE `keranjang`
  ADD CONSTRAINT `keranjang_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`);

--
-- Ketidakleluasaan untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD CONSTRAINT `notifikasi_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
