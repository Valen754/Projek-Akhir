<?php
include '../../../koneksi.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $no_telp = $_POST['no_telp'];
    $gender_name = $_POST['gender']; // Changed variable name
    $alamat = $_POST['alamat'];
    $role_name = $_POST['role'];     // Changed variable name
    $password = md5($_POST['password']);

    // Handle upload foto profil
    $profile_picture = '';
    if (isset($_FILES['profile_picture'])) {
        $ext = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $ext;
        $upload_path = '../../../asset/user_picture/' . $filename;
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_path)) {
            $profile_picture = $filename;
        }
    }

    // Get gender_id from gender_types table
    $query_gender_id = "SELECT id FROM gender_types WHERE gender_name = '$gender_name'";
    $result_gender_id = mysqli_query($conn, $query_gender_id);
    if ($result_gender_id && mysqli_num_rows($result_gender_id) > 0) {
        $row_gender = mysqli_fetch_assoc($result_gender_id);
        $gender_id = $row_gender['id'];
    } else {
        echo "Error: Gender type not found.";
        exit;
    }

    // Get role_id from user_roles table
    $query_role_id = "SELECT id FROM user_roles WHERE role_name = '$role_name'";
    $result_role_id = mysqli_query($conn, $query_role_id);
    if ($result_role_id && mysqli_num_rows($result_role_id) > 0) {
        $row_role = mysqli_fetch_assoc($result_role_id);
        $role_id = $row_role['id'];
    } else {
        echo "Error: User role not found.";
        exit;
    }

    // Query insert user baru, using role_id and gender_id
    $query = "INSERT INTO users (username, nama, email, no_telp, gender_id, alamat, role_id, password, profile_picture, created_at, updated_at) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param(
        "sssssisss", // 's' for string, 'i' for integer (for gender_id and role_id)
        $username,
        $nama,
        $email,
        $no_telp,
        $gender_id, // Now using ID
        $alamat,
        $role_id,   // Now using ID
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
}
?>