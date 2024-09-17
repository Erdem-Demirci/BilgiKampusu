<?php
if( !isset($_GET['g']) &&  $_GET['g'] != 1 && !isset($_GET['e']) &&  $_GET['e'] != 1){

        Header('Location: giris.php');
        exit;
    }

session_start();
if (!isset($_SESSION["email"]) || empty($_SESSION["email"])) {
                // Kullanıcı giriş yapmamışsa çıkış sayfasına yönlendir
                header("location: giris.php");
                exit(); // İşlemi sonlandır
}
require_once "baglanti.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['yenile']))
{
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $email = $_SESSION['email'];

    if($password == $cpassword)
    {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sorgu = $baglanti->prepare('UPDATE kullanicilar SET parola = ? WHERE email = ?');
        $sorgu->execute([$password, $email]);

        header('Location: index.php');
        $_SESSION['email'] = "";
        exit();
    }
    else
    {
        
        header('Location: sifre_yenile.php?e=1');
        exit();
    }
}
?>



<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Şifre yenile</title>
    
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/all.min.css">
    <link rel="stylesheet" type="text/css" href="css/stil1.css">

   
    
    
</head>
<body class="bg-light">
   <div class="container giris-kayit">
       <div class="row" style="min-height: 100vh;justify-content: center;align-items: center;">
           <div class="col-lg-4 col-md-6 col-sm-8 bg-white m-auto rounded-top wrapper">
               <h2 class="text-center pt-3 mb-4">Şifre Yenile</h2>
                <!-- Form start -->
                <form method="POST"  action="">
                    
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fa fa-lock"> </i></span>
                        <input type="password" class="form-control" placeholder="Şifre" name="password" required>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fa fa-lock"> </i></span>
                        <input type="password" class="form-control" placeholder="Şifreyi onayla" name="cpassword" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" name="yenile" class="btn btn-success mb-3">Şifre yenile</button>
            
                    </div>
                    <?php if( isset($_GET['e']) ): ?> 
                    <div class="alert alert-danger">Şifre değiştirilmedi</div>
                    <?php endif ?>

   
                </form>
           </div>
       
    </div>

   
<script type="text/javascript" src="js/script2.js"></script>   
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>


