<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/reservasi.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
</head>

<body>
    <?php
    include '../../views/header.php';
    include 'logic/logic_reservasi.php';
    include '../../koneksi.php';
    

    // Ambil data user dari database
    $user_id = $_SESSION['user_id'] ?? null;
    $user = [
        'nama' => '',
        'email' => '',
        'no_telp' => ''
    ];
    if ($user_id) {
        $query = $conn->query("SELECT nama, email, no_telp FROM users WHERE id = '$user_id'");
        if ($query && $query->num_rows > 0) {
            $user = $query->fetch_assoc();
        }
    }
    ?>

    <div class="container">
        <div class="form-section" data-aos="fade-right">
            <h1>MAKE A RESERVATION</h1>
            <p>
                "Selamat datang di halaman reservasi Tapal Kuda Kedai Kopi!
                Nikmati pengalaman ngopi terbaik dengan pemandangan yang memukau.
                Silakan isi detail reservasi Anda di bawah ini untuk memastikan tempat Anda tersedia."
            </p>

            <!-- Pesan sukses atau error -->
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php elseif (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <input name="name" placeholder="Name" type="text" style="color: black;" required value="<?php echo htmlspecialchars($user['nama']); ?>" readonly />
                <input name="phone" placeholder="Phone" type="text" style="color: black;" required value="<?php echo htmlspecialchars($user['no_telp']); ?>" readonly />
                <input name="email" placeholder="Email" type="email" style="color: black;" required value="<?php echo htmlspecialchars($user['email']); ?>" readonly />
                <input name="number_of_people" placeholder="Number Of People" type="number" style="color: black;" min="1" required value="<?php echo $_POST['number_of_people'] ?? ''; ?>" />
                <input name="date" placeholder="Date" type="date"  style="color: black;" required value="<?php echo $_POST['date'] ?? ''; ?>" />
                <input name="hour" placeholder="Hour" type="time" style="color: black;" required value="<?php echo $_POST['hour'] ?? ''; ?>" />
                <textarea name="message" placeholder="Write Your Message" style="color: black;"><?php echo $_POST['message'] ?? ''; ?></textarea>
                <button type="submit" name="submit_reservation" class="btn btn-primary mt-3">
                    RESERVE A TABLE
                </button>
            </form>
        </div>

        <div class="image-section" data-aos="fade-left">
            <img alt="A hand preparing a dish with fresh ingredients" height="400" src="menu1.jpg">
        </div>

        <div class="map-section" data-aos="fade-right">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3961.1687459860887!2d107.88790757592116!3d-6.870373967223285!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68d1b8e9ca8793%3A0x786b963c3e8cf075!2sKedai%20Kopi%20Tapal%20Kuda%20Sumedang!5e0!3m2!1sid!2sid!4v1736263089220!5m2!1sid!2sid"
                width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>

        <div class="info-section" data-aos="fade-left">
            <h1>HOW TO GET HERE</h1>
            <p>
                Tapal Kuda Kedai Kopi terletak di kawasan yang mudah diakses dari berbagai titik di Sumedang.
                Kami ingin memastikan Anda dapat menemukan kami dengan mudah, baik Anda menggunakan kendaraan pribadi,
                transportasi umum, atau bahkan berjalan kaki dari beberapa lokasi terdekat.
            </p>
        </div>
    </div>

    <?php include '../../views/footer.php'; ?>

    <script>
        AOS.init();
    </script>
</body>

</html>
