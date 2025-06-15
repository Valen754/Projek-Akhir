<?php

include '../../koneksi.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'kasir') {
    header("Location: ../../login/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
// Updated query to join with gender_types to get gender_name
$sql = "SELECT u.*, gt.gender_name 
        FROM users u 
        LEFT JOIN gender_types gt ON u.gender_id = gt.id 
        WHERE u.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    header("Location: ../../login/login.php");
    exit();
}

// Fetch all gender types for the dropdown
$gender_types_query = mysqli_query($conn, "SELECT id, gender_name FROM gender_types ORDER BY gender_name ASC");
$gender_options = [];
while ($row = mysqli_fetch_assoc($gender_types_query)) {
    $gender_options[] = $row;
}

// Proses update jika form disubmit
if (isset($_POST['save_profile'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $no_telp = $_POST['no_telp'];
    $gender_name = $_POST['gender']; // Use gender_name from form
    $alamat = $_POST['alamat'];
    
    $current_profile_picture = $user['profile_picture']; // Use the correct column name

    // Get gender_id from gender_types table
    $gender_id = null;
    $stmt_gender_id = $conn->prepare("SELECT id FROM gender_types WHERE gender_name = ?");
    if ($stmt_gender_id) {
        $stmt_gender_id->bind_param("s", $gender_name);
        $stmt_gender_id->execute();
        $result_gender_id = $stmt_gender_id->get_result();
        if ($row_gender_id = $result_gender_id->fetch_assoc()) {
            $gender_id = $row_gender_id['id'];
        }
        $stmt_gender_id->close();
    }
    if ($gender_id === null) {
        // Handle error if gender not found, or set to default
        // For simplicity, we'll just throw an exception or redirect
        echo "Error: Gender type not found in database.";
        exit();
    }


    // Proses upload foto jika ada file baru
    $new_profile_picture_name = $current_profile_picture; // Default to current picture
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($ext, $allowed)) {
            $newName = 'kasir_' . $user_id . '_' . time() . '.' . $ext;
            // Corrected upload path
            $uploadPath = __DIR__ . '/../../asset/user_picture/' . $newName; 
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadPath)) {
                // Hapus foto lama jika ada dan bukan default
                if (!empty($current_profile_picture) && 
                    file_exists(__DIR__ . '/../../asset/user_picture/' . $current_profile_picture) && 
                    $current_profile_picture != 'default.png') {
                    unlink(__DIR__ . '/../../asset/user_picture/' . $current_profile_picture);
                }
                $new_profile_picture_name = $newName;
            }
        }
    }

    // Update query using gender_id and new_profile_picture_name
    $stmt = $conn->prepare("UPDATE users SET nama=?, email=?, no_telp=?, gender_id=?, alamat=?, profile_picture=? WHERE id=?");
    // Bind parameters: s (nama), s (email), s (no_telp), i (gender_id), s (alamat), s (profile_picture), i (user_id)
    $stmt->bind_param("sssisss", $nama, $email, $no_telp, $gender_id, $alamat, $new_profile_picture_name, $user_id);
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
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group" style="text-align:center;">
                <?php 
                // Use profile_picture column for display
                $display_profile_picture = !empty($user['profile_picture']) 
                                        ? '../../asset/user_picture/' . htmlspecialchars($user['profile_picture']) 
                                        : '../../asset/user_picture/default-avatar.png'; 
                ?>
                <img src="<?= $display_profile_picture ?>" alt="Foto Profil" style="width:90px;height:90px;border-radius:50%;object-fit:cover;margin-bottom:10px;">
                <br>
                <input type="file" name="foto" accept="image/*">
                <small style="color:#aaa;">(Kosongkan jika tidak ingin mengubah foto)</small>
            </div>
            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama" value="<?php echo htmlspecialchars($user['nama']); ?>" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label>No Telepon</label>
                <input type="text" name="no_telp" value="<?php echo htmlspecialchars($user['no_telp']); ?>" required>
            </div>
            <div class="form-group">
                <label>Gender</label>
                <select name="gender" required>
                    <?php foreach ($gender_options as $gender_option): ?>
                        <option value="<?= htmlspecialchars($gender_option['gender_name']) ?>" 
                            <?php if(isset($user['gender_name']) && $user['gender_name'] === $gender_option['gender_name']) echo 'selected'; ?>>
                            <?= htmlspecialchars($gender_option['gender_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Alamat</label>
                <input type="text" name="alamat" value="<?php echo htmlspecialchars($user['alamat']); ?>" required>
            </div>
            <button type="submit" name="save_profile" class="btn-save"><i class="fas fa-save"></i> Simpan</button>
        </form>
    </div>
</body>
</html>