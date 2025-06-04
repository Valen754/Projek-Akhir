<?php
session_start();
require_once 'configdb.php';

if (!isset($_GET['token'])) {
    die("Token tidak ditemukan.");
}

$token = $_GET['token'];

// Cek token valid dan belum expired
$stmt = $conn->prepare("SELECT user_id, expires_at FROM password_resets WHERE token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 0) {
    die("Token tidak valid atau sudah kadaluarsa.");
}

$stmt->bind_result($user_id, $expires_at);
$stmt->fetch();

if (strtotime($expires_at) < time()) {
    die("Token sudah kadaluarsa.");
}
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    if ($password !== $password_confirm) {
        $error = "Password dan konfirmasi password tidak cocok.";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter.";
    } else {
        // Hash password
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        // Update password di tabel users
        $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $update->bind_param("si", $hashed, $user_id);
        if ($update->execute()) {
            // Hapus token reset
            $del = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
            $del->bind_param("s", $token);
            $del->execute();
            $del->close();

            $_SESSION['success'] = "Password berhasil diubah. Silakan login.";
            header("Location: login.php");
            exit;
        } else {
            $error = "Gagal memperbarui password.";
        }
        $update->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>

    <?php if (!empty($error)): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="password">Password Baru:</label><br />
        <input type="password" name="password" id="password" required /><br /><br />

        <label for="password_confirm">Konfirmasi Password:</label><br />
        <input type="password" name="password_confirm" id="password_confirm" required /><br /><br />

        <button type="submit">Ubah Password</button>
    </form>
</body>
</html>
