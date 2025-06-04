<?php
// forgot_password.php
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Lupa Password</title>
</head>
<body>
    <h2>Lupa Password</h2>
    <form action="send_reset_link.php" method="POST">
        <label for="email">Masukkan Email Terdaftar:</label><br />
        <input type="email" name="email" id="email" required /><br /><br />
        <button type="submit">Kirim Link Reset Password</button>
    </form>
    <p><a href="login.php">Kembali ke Login</a></p>
</body>
</html>
