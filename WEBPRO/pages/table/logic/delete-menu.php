<?php
include '../../koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "DELETE FROM menu WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        header("Location: table-menu.php?msg=deleted");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
