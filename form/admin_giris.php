<?php
    ob_start();
    session_start();
    if (isset($_SESSION["admin"]) || !empty($_SESSION["admin"])) {
   
    header("location: admin.php");
    exit(); // İşlemi sonlandır
}

    require_once "baglanti.php";

    $kontrol = 0;
    $kontrol2 = 0;
    
   if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['giris'])) {
        $admin = $_POST['log_admin'];
        $password = $_POST['log_password'];

        $kullanici_sor = $baglanti->prepare('SELECT * FROM admin WHERE admin_ad=?');
        $kullanici_sor->execute([$admin]);
        $user = $kullanici_sor->fetch(PDO::FETCH_ASSOC);

        if($user && ($password == $user['admin_parola'])) {
            // Parola doğru
            $_SESSION['admin'] = $admin;
            echo "<script>alert('başarılı ile giriş yaptınız, yönlendiriliyorsunuz.')</script>";
            header("location: admin.php");
            exit();
        } else {
            
            header('Location: admin_giris.php?yanlis=1');
            exit;
        }
    }
?>


<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Giriş</title>
    
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/all.min.css">
    <link rel="stylesheet" type="text/css" href="css/stil1.css">


   
    
    
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-sm">
     <div class="container-fluid">
       <a class="navbar-brand me-auto" href="index.php">BilgiKampüsü</a> <!-me-auto sayesinde logo ile login kısmını ayırdım between yaptım->
             <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
         <div class="offcanvas-header">
           <h5 class="offcanvas-title" id="offcanvasNavbarLabel">BilgiKampüsü</h5>
           <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
         </div>
         <div class="offcanvas-body">
           <ul class="navbar-nav justify-content-center flex-grow-1 pe-3"> <!-justify-content-center sayesinde home fln kısmı ortaya aldım->
             <li class="nav-item">
               <a class="nav-link mx-lg-2" href="index.php">Anasayfa</a>
             </li>
             
           </ul>
          
         </div>
       </div>
       <a href="Admin_giris.php" class="login-button">Giriş</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
       </button>

     </div>
   </nav>
    <div class="login"> 
       <div class="row mt-5">
           <div class="col-lg-4 col-md-6 col-sm-8 bg-white m-auto rounded-top wrapper">
               <h2 class="text-center pt-3 mb-4">Giriş</h2>
                
                <!-- Form start -->
                <form method="POST"  action="admin_giris.php">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fa fa-user"> </i></span>
                        <input type="text" class="form-control" placeholder="Kullanıcı Adı" name="log_admin" required>
                    </div>
                    
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fa fa-lock"> </i></span>
                        <input type="password" class="form-control" placeholder="Şifre" name="log_password" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" name="giris" class="btn btn-success mb-3">Giriş Yap</button>
                    </div>
                    
                        <?php if( isset($_GET['yanlis']) ): ?> 
                            <div class="alert alert-danger">Giriş başarısız</div>
                        <?php endif ?>
                </form>

                <!-- Form end -->
           </div>
       </div>
    </div>



<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>


