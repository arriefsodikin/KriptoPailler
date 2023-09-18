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

	$code = $_GET['code'];
	$sql  = "SELECT * FROM users WHERE kode='$code'";
	$records = $db->query($sql);
	$records->setFetchMode(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <title>Document</title>
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 mt-5">
        <div class="card">
          <div class="card-header text-center">
          <img src="img/logo.png" class="" style="width: 150px; height: 150px;">
          <h6>Implementasi Algoritma Paillier Cryptosystem pada fitur E-signature</h6>
          <hr>
          <div class="">
            <p>Enkripsi= g^m * r^n (mod n^2)</p>
          </div>
          <div class="col-md-6 ">
            <a href="login.php" class="btn btn-danger ">logout</a>
          </div>
          </div>
          <div class="card-body">
          <?php 
        while ($row = $records->fetch()){
          // echo "File : ".'<img src="data:image/jpeg;base64,'.base64_encode( $row['file'] ).'"/>'."<br>";
          echo $row['data'];
        }
        ?>
          
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
<script src="js/bootstrap.js"></script>
</html>