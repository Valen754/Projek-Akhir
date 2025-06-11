<?php
include '../../views/header.php';
include '../../koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $query = "SELECT password FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);    
    if (md5($current_password) === $user['password']) {
        // Check if new password matches confirmation
        if ($new_password === $confirm_password) {
            if (strlen($new_password) >= 6) {
                $hashed_password = md5($new_password);
                $update_query = "UPDATE users SET password = ? WHERE id = ?";
                $update_stmt = mysqli_prepare($conn, $update_query);
                mysqli_stmt_bind_param($update_stmt, "si", $hashed_password, $user_id);
                
                if (mysqli_stmt_execute($update_stmt)) {
                    $success_message = "Password berhasil diubah!";
                } else {
                    $error_message = "Gagal mengubah password. Silakan coba lagi.";
                }
                mysqli_stmt_close($update_stmt);
            } else {
                $error_message = "Password baru minimal 6 karakter!";
            }
        } else {
            $error_message = "Password baru dan konfirmasi password tidak cocok!";
        }
    } else {
        $error_message = "Password saat ini tidak sesuai!";
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | Tapal Kuda</title>
    <link href="../../css/menu.css" rel="stylesheet">
    <style>
        .reset-password-container {
            max-width: 500px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .reset-password-header {
            text-align: center;
            margin-bottom: 20px;
            color: #8d6748;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
        }

        .error-message {
            color: #dc3545;
            margin-bottom: 15px;
            text-align: center;
        }

        .success-message {
            color: #28a745;
            margin-bottom: 15px;
            text-align: center;
        }

        .btn-reset {
            background: #8d6748;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            width: 100%;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-reset:hover {
            background: #6d4c2b;
        }

        .btn-back {
            display: inline-block;
            text-decoration: none;
            color: #8d6748;
            margin-top: 15px;
            text-align: center;
            width: 100%;
        }

        .btn-back:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="reset-password-container">
        <div class="reset-password-header">
            <h2>Reset Password</h2>
        </div>

        <?php if ($error_message): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="current_password">Password Saat Ini</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>

            <div class="form-group">
                <label for="new_password">Password Baru</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Konfirmasi Password Baru</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <button type="submit" class="btn-reset">Ubah Password</button>
        </form>

        <a href="profil.php" class="btn-back">Kembali ke Profil</a>
    </div>

    <?php include '../../views/footer.php'; ?>
</body>
</html>