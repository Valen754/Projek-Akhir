<?php
session_start();
require_once '../../koneksi.php'; // pastikan koneksi database sudah benar

$token_display = null; // untuk menyimpan token tampil

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Email tidak valid.";
        header("Location: forgot_password.php");
        exit;
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        $_SESSION['error'] = "Email tidak ditemukan.";
        header("Location: forgot_password.php");
        exit;
    }
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $stmt->close();

    $token = bin2hex(random_bytes(50));
    $expire = date("Y-m-d H:i:s", strtotime("+1 hour"));

    $del = $conn->prepare("DELETE FROM password_resets WHERE user_id = ?");
    $del->bind_param("i", $user_id);
    $del->execute();
    $del->close();

    $insert = $conn->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)");
    $insert->bind_param("iss", $user_id, $token, $expire);
    $insert->execute();
    $insert->close();

    $reset_link = "http://yourdomain.com/reset_password.php?token=" . $token;
    $subject = "Reset Password Akun Anda";
    $message = "Klik link berikut untuk mereset password Anda:\n\n" . $reset_link . "\n\nLink berlaku 1 jam.";
    $headers = "From: no-reply@yourdomain.com";

    if (mail($email, $subject, $message, $headers)) {
        $_SESSION['success'] = "Link reset password sudah dikirim ke email Anda.";
        $token_display = $token; // simpan token untuk ditampilkan
    } else {
        $_SESSION['error'] = "Gagal mengirim email. Silakan coba lagi nanti.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Lupa Password</title>
</head>
<body>
    <h2>Lupa Password</h2>

    <?php if (isset($_SESSION['error'])): ?>
        <p style="color: red;"><?php
            echo $_SESSION['error'];
            unset($_SESSION['error']);
        ?></p>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <p style="color: green;"><?php
            echo $_SESSION['success'];
            unset($_SESSION['success']);
        ?></p>
    <?php endif; ?>

    <?php if ($token_display !== null): ?>
        <p><strong>Token Reset Anda:</strong></p>
        <textarea rows="4" cols="80" readonly><?= htmlspecialchars($token_display) ?></textarea>
        <p>Gunakan token ini untuk reset password manual jika perlu.</p>
    <?php endif; ?>

    <form method="POST" action="forgot_password.php">
        <label for="email">Masukkan Email Terdaftar:</label><br />
        <input type="email" id="email" name="email" required /><br /><br />
        <button type="submit">Kirim Link Reset Password</button>
    </form>
    <p><a href="login.php">Kembali ke Login</a></p>
</body>
</html>
