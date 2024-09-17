<?php 
    if( !isset($_GET['success']) &&  $_GET['success'] != 1 && !isset($_GET['eslesmiyor']) &&  $_GET['eslesmiyor'] != 1){

        Header('Location: index.php');
        exit;
    }

    require_once "baglanti.php";
    session_start();
    if (!isset($_SESSION["email"]) || empty($_SESSION["email"])) {
                // Kullanıcı giriş yapmamışsa çıkış sayfasına yönlendir
                header("location: index.php");
                exit(); // İşlemi sonlandır
    }


    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['kod']))
    {
        $kod = $_POST['kod'];
        $email = $_SESSION['email'];

        $sorgu = $baglanti->prepare('SELECT * FROM kullanicilar WHERE email=:email');
        $sorgu->bindParam(':email', $email);
        $sorgu->execute();
        $user = $sorgu->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($kod, $user['unuttum'])) 
        {
            // Kod doğru
            header('Location: sifre_yenile.php?g=1');
            exit(); // Yönlendirme yapıldıktan sonra scriptin devam etmemesi için exit kullanılmalı.
        }
        else
        {
            header('Location: kodgir.php?eslesmiyor=1');
            exit(); // Yönlendirme yapıldıktan sonra scriptin devam etmemesi için exit kullanılmalı.
        }
    }
?>


<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kod gir</title>
    
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/all.min.css">
    <link rel="stylesheet" type="text/css" href="css/stil1.css">

   
    
    
</head>
<body class="bg-light">
   <div class="container giris-kayit">
       <div class="row" style="min-height: 100vh;justify-content: center;align-items: center;">
           <div class="col-lg-4 col-md-6 col-sm-8 bg-white m-auto rounded-top wrapper">
               <h2 class="text-center pt-3 mb-4">Kodu Giriniz</h2>
                
                <!-- Form start -->
                <form method="POST"  action="">
                    
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fa fa-user"> </i></span>
                        <input type="text" class="form-control" placeholder="Kodu giriniz" name="kod" required>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" name="Kod" class="btn btn-success mb-3">Doğrulama</button>
            
                    </div>
                    <?php if( isset($_GET['eslesmiyor']) ): ?> 
                    <div class="alert alert-danger">Kod eşleşmedi</div>
                <?php endif ?>
   
                </form>
           </div>
       
    </div>

   
<script type="text/javascript" src="js/script2.js"></script>   
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>