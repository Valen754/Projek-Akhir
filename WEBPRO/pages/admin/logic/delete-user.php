<?php
include '../../../koneksi.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Hapus foto profil jika ada
    $stmt = $conn->prepare("SELECT profile_picture FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $row = $result->fetch_assoc()) {
        if (!empty($row['profile_picture'])) {
            $file = '../../../asset/user_picture/' . $row['profile_picture'];
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }
    $stmt->close();

    // Hapus user dari database
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: ../tusers.php?msg=deleted");
    exit();
} else {
    header("Location: ../tusers.php?msg=error");
    exit();
}
?>