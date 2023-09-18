<?php 
session_start();

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
// Function to generate a unique code
function generateKode() {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < 10; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

if(isset($_POST['signaturesubmit'])){
 
    // Get signature data from the form
    $signature = $_POST['signature'];
    $signatureFileName = uniqid().'.png';
    $signature = str_replace('data:image/png;base64,', '', $signature);
    $signature = str_replace(' ', '+', $signature);
    $data = base64_decode($signature);
    $temp = 'signature/'.$signatureFileName;
    file_put_contents($temp, $data);

    $image = imagecreatefrompng($temp);

    // Dimensi x dan y
    $width = imagesx($image);
    $height = imagesy($image);

    // panggil file paillier
    require_once("paillier.php");
    // panggil  privatekey dan publickey untuk  di simpan database
    // $publicKey = $paillier->getPublicKey();
    // $privateKey = $paillier->getPrivateKey();

    $paillier = new Paillier(71, 53);
// Looping untuk membaca data RGB dari setiap pixel
    $encryptedRGBData = array();
    $encryptedData = array('red' => array(), 'green' => array(), 'blue' => array());
    for ($x = 0; $x < $width; $x++) {
      for ($y = 0; $y < $height; $y++) {
        // Nilai RGB setiap pixel
        $rgb = imagecolorat($image, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
      // Simpan nilai RGB yang sebelum dienkripsi ke dalam array
      $RGBData[$x][$y] = array(
        'red' => $r,
        'green' => $g,
        'blue' => $b
      );
      $Data['red'][$x][$y] = $r;
      $Data['green'][$x][$y] = $g;
      $Data['blue'][$x][$y] = $b;
      
      // Enkripsi nilai RGB dengan Paillier
        $encrypted_r = gmp_strval($paillier->Enkripsi($r));
        $encrypted_g = gmp_strval($paillier->Enkripsi($g));
        $encrypted_b = gmp_strval($paillier->Enkripsi($b));

        // Simpan nilai RGB yang sudah dienkripsi ke dalam array
        $encryptedRGBData[$x][$y] = array(
          'red' => $encrypted_r,
          'green' => $encrypted_g,
          'blue' => $encrypted_b
        );
            // // Tambahkan nilai RGB yang sudah dienkripsi ke dalam array yang akan disimpan ke database
            $encryptedData['red'][$x][$y] = $encrypted_r;
            $encryptedData['green'][$x][$y] = $encrypted_g;
            $encryptedData['blue'][$x][$y] = $encrypted_b;
      }
    }
    imagedestroy($image);
    //SAVING DATA ARRAY RGB 
    $file = json_encode($encryptedData);

    // Filter and sanitize form input
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    // Encrypt password
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Generate unique code
    $kode = generateKode();

    // Prepare query to save data to database
    $sql = 'INSERT INTO users (kode, username, email, password, signature, file, data) 
            VALUES (:kode, :username, :email, :password, :signature, :file, :data)';
    $stmt = $db->prepare($sql);

    // Execute query
    $stmt->execute([
      'kode' => $kode,
      'username' => $username,
      'email' => $email,
      'password' => $password,
      'signature' => $temp,
      'file' => $data,
      'data' => $file,
    ]);

    // Display success message
    $msg = "<div class='alert alert-success'>Signature Uploaded</div>";

    header("Location: test.php?code=$kode");  
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <title>Form Registrasi</title>
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-5 mt-5">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title">Form Registrasi</h5>
          </div>
          <div class="card-body">
            <form id="signatureform" action="register.php" method="post">
              <div class="mb-3">
                <label for="username" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="username" name="username" required>
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Alamat Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
              </div>
              <div id="canvasDiv"></div>
              <br>
              <button type="button" class="btn btn-danger" id="reset-btn">Clear</button>
              <button type="button" class="btn btn-success" id="btn-save">Simpan Datadiri</button>
              <input type="hidden" id="signature" name="signature" required>
              <input type="hidden" name="signaturesubmit" value="1">
            </form>
            <?php if (isset($msg)) { echo $msg; } ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha256-pasqAKBDmFT4eHoN2ndd6lN370kFiGUFyTiUHWhU7k8=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>
  <script src="js/bootstrap.js"></script>
  <script src="js/signature.js"></script>
</body>
</html>
