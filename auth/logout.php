<?php
session_start(); // Memulai sesi
$_SESSION = []; // Mengosongkan data sesi
session_unset(); // Menghapus variabel sesi
session_destroy(); // Menghancurkan sesi

header("Location: login.php"); // Kembali ke login
exit;
?>