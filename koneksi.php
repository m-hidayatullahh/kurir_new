<?php
$host = "localhost";
$user = "root";
$pass = "root";
$db   = "db_kurir_paket"; 

$conn = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>