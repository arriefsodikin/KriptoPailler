<?php require_once("auth.php"); 
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
            <p>Dekripsi =L(C^lambda(mod n^2) * Mu(mod n)</p>
          </div>
          </div>
          <div class="card-body">
          <h3><?php echo  $_SESSION["user"]["username"] ?></h3>
          <p><?php echo $_SESSION["user"]["email"] ?></p>
          <?php echo'<img src="data:image/jpeg;base64,'.base64_encode( $_SESSION["user"]["file"] ).'"/>'."<br>";?>
            <div class="col-md-6 text-center">
                <a href="login.php" class="btn btn-danger ">logout</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
<script src="js/bootstrap.js"></script>
</html>