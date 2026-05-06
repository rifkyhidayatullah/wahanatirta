<?php
/** @var mysqli $conn */
$id = $_GET['id'];
$q = "DELETE FROM penjualan WHERE id = '$id'";

if (mysqli_query($conn, $q)) {
    echo "<script>alert('Data Berhasil Dihapus!'); window.location='?page=penjualan';</script>";
}
?>