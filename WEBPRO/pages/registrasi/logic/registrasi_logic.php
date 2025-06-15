<?php
session_start();
include '../../../koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $no_telp = trim($_POST['no_telp']);
    $gender_name = trim($_POST['gender']); // Renamed variable
    $alamat = trim($_POST['alamat']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $role_name = "member"; // Default role for new registrations

    // Validasi apakah username sudah ada
    $check_username = $conn->prepare("SELECT username FROM users WHERE username = ?");
    $check_username->bind_param("s", $username);
    $check_username->execute();
    $result = $check_username->get_result();
    if ($result->num_rows > 0) {
        echo "<script>alert('Username sudah digunakan!'); window.location.href = '../registrasi.php';</script>";
        exit();
    }
    $check_username->close();

    // Validasi password
    if ($password !== $confirm_password) {
        echo "<script>alert('Password dan Konfirmasi Password tidak cocok!'); window.location.href = '../registrasi.php';</script>";
        exit();
    }

    // Hash password
    $hashed_password = md5($password);
    
    // Handle profile picture
    $profile_picture = "default-avatar.png"; // Consistent default profile picture name

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

    // Get gender_id from gender_types table
    $gender_id = null;
    $stmt_gender = $conn->prepare("SELECT id FROM gender_types WHERE gender_name = ?");
    if ($stmt_gender) {
        $stmt_gender->bind_param("s", $gender_name);
        $stmt_gender->execute();
        $result_gender = $stmt_gender->get_result();
        if ($row_gender = $result_gender->fetch_assoc()) {
            $gender_id = $row_gender['id'];
        }
        $stmt_gender->close();
    }
    if ($gender_id === null) {
        echo "<script>alert('Error: Gender type not found.'); window.location.href = '../registrasi.php';</script>";
        exit();
    }

    // Get role_id from user_roles table
    $role_id = null;
    $stmt_role = $conn->prepare("SELECT id FROM user_roles WHERE role_name = ?");
    if ($stmt_role) {
        $stmt_role->bind_param("s", $role_name);
        $stmt_role->execute();
        $result_role = $stmt_role->get_result();
        if ($row_role = $result_role->fetch_assoc()) {
            $role_id = $row_role['id'];
        }
        $stmt_role->close();
    }
    if ($role_id === null) {
        echo "<script>alert('Error: User role not found.'); window.location.href = '../registrasi.php';</script>";
        exit();
    }


    // Prepare SQL statement using foreign key IDs
    $sql = "INSERT INTO users (username, nama, email, no_telp, gender_id, alamat, password, role_id, profile_picture) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    // Bind parameters: ssss (username, nama, email, no_telp), i (gender_id), s (alamat), s (password), i (role_id), s (profile_picture)
    $stmt->bind_param("sssssisss", $username, $nama, $email, $no_telp, $gender_id, $alamat, $hashed_password, $role_id, $profile_picture);

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