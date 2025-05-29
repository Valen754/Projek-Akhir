<?php
include '../../../koneksi.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Hapus foto profil jika ada
    $result = $conn->query("SELECT profile_picture FROM users WHERE id = $id");
    if ($result && $row = $result->fetch_assoc()) {
        if (!empty($row['profile_picture'])) {
            $file = '../../../asset/user_picture/' . $row['profile_picture'];
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }

    // Hapus user dari database
    $conn->query("DELETE FROM users WHERE id = $id");
    header("Location: ../tusers.php?msg=deleted");
    exit();
} else {
    header("Location: ../tusers.php?msg=error");
    exit();
}
?>