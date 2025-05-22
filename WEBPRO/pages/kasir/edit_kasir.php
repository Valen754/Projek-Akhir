<?php

include '../../koneksi.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'kasir') {
    header("Location: ../../login/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    header("Location: ../../login/login.php");
    exit();
}

// Proses update jika form disubmit
if (isset($_POST['save_profile'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $no_telp = $_POST['no_telp'];
    $gender = $_POST['gender'];
    $alamat = $_POST['alamat'];

    $stmt = $conn->prepare("UPDATE users SET nama=?, email=?, no_telp=?, gender=?, alamat=? WHERE id=?");
    $stmt->bind_param("sssssi", $nama, $email, $no_telp, $gender, $alamat, $user_id);
    $stmt->execute();
    $stmt->close();

    header("Location: profile.php?success=1");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile Kasir</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link href="../../css/kasir.css" rel="stylesheet">
    <style>
        body { background: #1c2431; font-family: Arial, sans-serif; }
        .edit-container { max-width: 500px; margin: 40px auto; background: #222b3a; border-radius: 12px; padding: 32px; color: #fff; }
        .edit-container h2 { text-align: center; margin-bottom: 24px; }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; margin-bottom: 6px; color: #e07b6c; }
        .form-group input, .form-group select { width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #e07b6c; background: #1c2431; color: #fff; }
        .form-group input:focus, .form-group select:focus { outline: none; border-color: #f7b267; }
        .btn-save { background: linear-gradient(90deg, #e07b6c 0%, #f7b267 100%); color: #fff; border: none; padding: 10px 24px; border-radius: 6px; cursor: pointer; font-weight: 500; }
        .btn-save:hover { background: linear-gradient(90deg, #f7b267 0%, #e07b6c 100%); }
    </style>
</head>
<body>
    <div class="edit-container">
        <h2>Edit Profile Kasir</h2>
        <form method="POST">
            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama" value="<?php echo $user['nama']; ?>" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
            </div>
            <div class="form-group">
                <label>No Telepon</label>
                <input type="text" name="no_telp" value="<?php echo $user['no_telp']; ?>" required>
            </div>
            <div class="form-group">
                <label>Gender</label>
                <select name="gender" required>
                    <option value="Laki-laki" <?php if($user['gender']=='Laki-laki') echo 'selected'; ?>>Laki-laki</option>
                    <option value="Perempuan" <?php if($user['gender']=='Perempuan') echo 'selected'; ?>>Perempuan</option>
                </select>
            </div>
            <div class="form-group">
                <label>Alamat</label>
                <input type="text" name="alamat" value="<?php echo $user['alamat']; ?>" required>
            </div>
            <button type="submit" name="save_profile" class="btn-save"><i class="fas fa-save"></i> Simpan</button>
        </form>
    </div>
</body>
</html>