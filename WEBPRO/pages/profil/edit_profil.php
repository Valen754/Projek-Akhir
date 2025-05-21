<?php
include '../../views/header.php';
include '../../koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($query);
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $no_telp = mysqli_real_escape_string($conn, $_POST['no_telp']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);

    // Handle upload foto
    $foto = $user['profile_picture'];
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($ext, $allowed)) {
            $newName = 'user_' . $user_id . '_' . time() . '.' . $ext;
            $uploadPath = '../../asset/user_picture/' . $newName;
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadPath)) {
                $foto = $newName;
            } else {
                $error = "Gagal upload foto.";
            }
        } else {
            $error = "Format foto tidak didukung.";
        }
    }

    // Update data user
    $update = "UPDATE users SET nama='$nama', email='$email', no_telp='$no_telp', alamat='$alamat', profile_picture='$foto' WHERE id=$user_id";
    if (empty($error) && $conn->query($update)) {
        echo "<script>alert('Profil berhasil diperbarui!');window.location='profil.php';</script>";
        exit();
    } elseif (empty($error)) {
        $error = "Gagal memperbarui profil.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil</title>
    <link href="../../css/menu.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .edit-profile-container {
            max-width: 50%;
            margin: 40px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            padding: 32px 24px;
        }
        .edit-profile-container h2 {
            text-align: center;
            margin-bottom: 24px;
            color: #6d4c2b;
        }
        .edit-profile-container label {
            font-weight: bold;
            color: #6d4c2b;
        }
        .edit-profile-container input,
        .edit-profile-container textarea {
            width: 100%;
            padding: 8px 10px;
            margin-bottom: 16px;
            border: 1px solid #a67c52;
            border-radius: 6px;
            font-size: 1em;
        }
        .edit-profile-container button {
            width: 100%;
            padding: 10px;
            background: #a67c52;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1.1em;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
        }
        .edit-profile-container button:hover {
            background: #6d4c2b;
        }
        .edit-profile-container .error {
            color: red;
            text-align: center;
            margin-bottom: 12px;
        }
        .edit-profile-container .profile-preview {
            display: flex;
            justify-content: center;
            margin-bottom: 16px;
        }
        .edit-profile-container .profile-preview img {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #a67c52;
        }
    </style>
</head>
<body>
    <div class="edit-profile-container">
        <h2>Edit Profil</h2>
        <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="profile-preview">
                <img src="../../asset/user_picture/<?php echo $user['profile_picture'] ? $user['profile_picture'] : 'default-avatar.png'; ?>" alt="Foto Profil">
            </div>
            <label for="foto">Foto Profil</label>
            <input type="file" name="foto" id="foto" accept="image/*">

            <label for="nama">Nama</label>
            <input type="text" name="nama" id="nama" required value="<?php echo htmlspecialchars($user['nama']); ?>">

            <label for="email">Email</label>
            <input type="email" name="email" id="email" required value="<?php echo htmlspecialchars($user['email']); ?>">

            <label for="no_telp">No. Telepon</label>
            <input type="text" name="no_telp" id="no_telp" value="<?php echo htmlspecialchars($user['no_telp']); ?>">

            <label for="alamat">Alamat</label>
            <textarea name="alamat" id="alamat" rows="3"><?php echo htmlspecialchars($user['alamat']); ?></textarea>

            <button type="submit">Simpan Perubahan</button>
        </form>
    </div>
    <?php include '../../views/footer.php'; ?>
</body>
</html>