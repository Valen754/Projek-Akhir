<?php
include '../../../koneksi.php';

$username = $_POST['username'];
$nama = $_POST['nama'];
$email = $_POST['email'];
$no_telp = $_POST['no_telp'];
$gender = $_POST['gender'];
$alamat = $_POST['alamat'];
$role = $_POST['role'];
$password = md5($_POST['password']);

// Handle upload foto profil
$profile_picture = '';
if (isset($_FILES['profile_picture']) ) {
    $ext = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $ext;
    $upload_path = '../../../asset/user_picture/' . $filename;
    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_path)) {
        $profile_picture = $filename;
    }
}

// Query insert user baru
$query = "INSERT INTO users (username, nama, email, no_telp, gender, alamat, role, password, profile_picture, created_at, updated_at) 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
$stmt = $conn->prepare($query);
$stmt->bind_param(
    "sssssssss",
    $username,
    $nama,
    $email,
    $no_telp,
    $gender,
    $alamat,
    $role,
    $password,
    $profile_picture
);

if ($stmt->execute()) {
    header("Location: ../tusers.php?msg=added");
    exit();
} else {
    header("Location: ../tusers.php?msg=error");
    exit();
}
?>