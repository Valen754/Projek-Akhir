<?php
session_start();
include '../../../koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $no_telp = trim($_POST['no_telp']);
    $gender = trim($_POST['gender']);
    $alamat = trim($_POST['alamat']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $role = "member"; 

    // Validasi apakah username sudah ada
    $check_username = $conn->prepare("SELECT username FROM users WHERE username = ?");
    $check_username->bind_param("s", $username);
    $check_username->execute();
    $result = $check_username->get_result();
    if ($result->num_rows > 0) {
        echo "<script>alert('Username sudah digunakan!'); window.location.href = '../registrasi.php';</script>";
        exit();
    }

    // Validasi password
    if ($password !== $confirm_password) {
        echo "<script>alert('Password dan Konfirmasi Password tidak cocok!'); window.location.href = '../registrasi.php';</script>";
        exit();
    }

    // Hash password
    $hashed_password = md5($password);
    
    // Handle profile picture
    $profile_picture = "default.jpg"; // Default profile picture

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "../../../asset/user_picture/";
        $file_extension = strtolower(pathinfo($_FILES["profile_picture"]["name"], PATHINFO_EXTENSION));
        $new_filename = uniqid('profile_') . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        // Check if image file is actual image
        $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
        if ($check !== false && in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                $profile_picture = $new_filename;
            }
        }
    }

    // Prepare SQL statement
    $sql = "INSERT INTO users (username, nama, email, no_telp, gender, alamat, password, role, profile_picture) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", $username, $nama, $email, $no_telp, $gender, $alamat, $hashed_password, $role, $profile_picture);

    try {
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Registrasi berhasil! Silakan login.";
            header("Location: ../../login/login.php");
            exit();
        } else {
            throw new Exception($stmt->error);
        }
    } catch (Exception $e) {
        echo "<script>alert('Terjadi kesalahan: " . $e->getMessage() . "'); window.location.href = '../registrasi.php';</script>";
    }

    $stmt->close();
}

$conn->close();
?>