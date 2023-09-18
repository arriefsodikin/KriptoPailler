<?php
//upload.php
if($_FILES["file"]["name"] != '') {
    $test = explode('.', $_FILES["file"]["name"]);
    $ext = end($test);
    $name = rand(100, 999) . '.' . $ext;
    $location = './upload/' . $name;  
    move_uploaded_file($_FILES["file"]["tmp_name"], $location);
    
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
    $encryptedRGBData = array();
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

            // Array RGB jadi gambar
            $encryptedRGBData[$x][$y] = array(
                'red' => $encrypted_r,
                'green' => $encrypted_g,
                'blue' => $encrypted_b
            );
        }
    }

    // buat gambar baru hasil  enkripsi 
    $newImage1 = imagecreatetruecolor($width, $height);
    
    // simpan  data dengan format  png
    imagepng($newImage1, 'encrypted_image.png');

    // buat gambar baru hasil  dekripsi
    $newImage2 = imagecreatetruecolor($width, $height);

    // loopingdata enkripsi pada  setiap  pixel untuk  di dekripsi
    for ($x = 0; $x < $width; $x++) {
        for ($y = 0; $y < $height; $y++) {
            // baca nilai RGB enkripisi dari data  array rgb atau  bisa dari  database
            $encrypted_r = $encryptedRGBData[$x][$y]['red'];
            $encrypted_g = $encryptedRGBData[$x][$y]['green'];
            $encrypted_b = $encryptedRGBData[$x][$y]['blue'];

            // Dekripsi  data  array menggunakan pailller
            $decrypted_r = intval($paillier->Dekripsi($encrypted_r));
            $decrypted_g = intval($paillier->Dekripsi($encrypted_g));
            $decrypted_b = intval($paillier->Dekripsi($encrypted_b));
          // Buat nilai baru dari hasil dekripsi menjadi nilai rgb 0-255
        $color = imagecolorallocate($newImage2, 
            min(max($decrypted_r, 0), 255), 
            min(max($decrypted_g, 0), 255), 
            min(max($decrypted_b, 0), 255)
        );

        // Set warna pada setiap  pixel
        imagesetpixel($newImage2, $x, $y, $color);
    }
}

    // simpan gambar hasil dekripsi  dengan format PNG
    imagepng($newImage2, 'decrypted_image.png');

    // Tampilkan data PlainImage - Cipher Imager - Dekrip Image
    echo '<h3>Original Image:</h3>';
    echo '<img src="'.$location.'" height="150" width="225" class="img-thumbnail" />';
    echo '<h3>Encrypted Image:</h3>';
    echo '<img src="encrypted_image.png" height="150" width="225" class="img-thumbnail" />';
    echo '<h3>Decrypted Image:</h3>';
    echo '<img src="decrypted_image.png" height="150" width="225" class="img-thumbnail" />';

    // Destroy the images to free up memory
    imagedestroy($image);
    imagedestroy($newImage1);
    imagedestroy($newImage2);
}


?>