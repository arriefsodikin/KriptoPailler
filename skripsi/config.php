<?php
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "skripsi";

// Gunakan try-catch untuk menangani exception
try {    
    // Membuat koneksi PDO
    $db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
} catch(PDOException $e) {
    // Tampilkan pesan error
    die("Terjadi masalah: " . $e->getMessage());
}
?>