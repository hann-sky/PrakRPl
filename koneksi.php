<?php
$servername = "127.0.0.1:3307";
$username = "root";
$password = "root";
$database = "wisata";

// Membuat koneksi
$koneksi = mysqli_connect($servername, $username, $password, $database);

// Memeriksa koneksi
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
