<?php
//upload.php
if($_FILES["file"]["name"] != '') {
    $test = explode('.', $_FILES["file"]["name"]);
    $ext = end($test);
    $name = rand(100, 999) . '.' . $ext;
    $location = './upload/' . $name;  
    move_uploaded_file($_FILES["file"]["tmp_name"], $location);
    echo '<img src="'.$location.'" height="150" width="225" class="img-thumbnail" />';
    
    // Membuka gambar
    // Load the PNG image
    $image = imagecreatefrompng($location);

    // Dimensi x dan y
    $width = imagesx($image);
    $height = imagesy($image);

    // panggil file paillier
    require_once("paillier.php");
    $paillier = new Paillier(71, 53);
       // Looping untuk bacat data RGB dari  pixel
   for ($x = 0; $x < $width; $x++) {
    for ($y = 0; $y < $height; $y++) {
       // nilai  RGB setiap pixel
        $rgb = imagecolorat($image, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        
        // Enkripsi data  RGB values dengan Paillier
        $encrypted_r = $paillier->Enkripsi($r);
        $encrypted_g = $paillier->Enkripsi($g);
        $encrypted_b = $paillier->Enkripsi($b);
        
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
    imagedestroy($image);
}
}


?>