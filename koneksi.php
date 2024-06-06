<?php
$host = 'localhost'; 
$db = 'motor_club'; 
$user = 'root'; 
$pass = 'root'; 

// Membuat koneksi
$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
