<?php

require_once 'paillier.php';

// Nama file gambar
$file = 'img/logo.png';

// Membuka gambar
$image = imagecreatefrompng($file);

// Ukuran gambar
$width = imagesx($image);
$height = imagesy($image);

// P & Q bilangan prima

// Generate a Paillier key pair
$paillier = new Paillier(71,53);

// Store the public key in a database
// ...

// Loop to read the RGB data of each pixel
for ($x = 0; $x < $width; $x++) {
    for ($y = 0; $y < $height; $y++) {
        $rgb = imagecolorat($image, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        
        // Encrypt the RGB data of the pixel using Paillier
        $encrypted_r = $paillier->Enkripsi($r);
        $encrypted_g = $paillier->Enkripsi($g);
        $encrypted_b = $paillier->Enkripsi($b);
        
        // Store the encrypted RGB data of the pixel in a database
        // Dekripsi data RGB setiap piksel
        $m_r = $paillier->Dekripsi($encrypted_r);
        $m_g = $paillier->Dekripsi($encrypted_g);
        $m_b = $paillier->Dekripsi
        ($encrypted_b);
        
        echo "<hr>";
        echo "Pixel ($x, $y) memiliki warna RGB ($r, $g, $b)\n";
        echo "<hr>";
        echo "Pixel ($x, $y) memiliki warna RGB yang terenkripsi ($encrypted_r, $encrypted_g, $encrypted_b)\n";
        echo "<hr>";
        echo "Pixel ($x, $y) memiliki warna RGB yang dekripsi ($m_r, $m_g, $m_b)\n";
    }
}

// Menutup gambar
imagedestroy($image);

?>