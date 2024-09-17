<?php
    ob_start();
    session_start();
    if (isset($_SESSION["username"]) || !empty($_SESSION["username"])) {
   
    header("location: index.php");
    exit(); // İşlemi sonlandır
}

    require_once "baglanti.php";

    $kontrol = 0;
    $kontrol2 = 0;
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['kaydet']))
    {
        $name = $_POST['reg_username'];
        $email = $_POST['reg_mail'];
        $password = $_POST['reg_password']; 
        $cpassword = $_POST['reg_cpassword']; 
        $uni = $_POST['reg_uni'];
        $durum = $_POST['reg_durum'];

        if($password == $cpassword)
        {
            $password = password_hash($_POST['reg_password'], PASSWORD_DEFAULT); // Şifreyi hashle
            try {
                $sorgu = $baglanti->prepare("INSERT INTO kullanicilar SET kullanici_adi = ?, email = ? ,parola = ? ,universite =? ,durum= ?");
                $ekle = $sorgu->execute([$name, $email, $password,$uni,$durum]);

                if ($ekle) {
                    echo "<script>alert('kayıt başarılı ile gerçekleşti')</script>";
                } else {
                    echo "<script>alert('bir hata oluştu kontrol edin: " . $baglanti->errorInfo()[2] . "')</script>";
                    $son_hata = error_get_last();
                if ($son_hata !== null) {
                     echo "<script>alert('Bir hata oluştu: " . $son_hata['message'] . "')</script>";
                }
                }
            } catch (PDOException $e) {
                echo "<script>alert('Bir hata oluştu: " . $e->getMessage() . "')</script>";
            }

        }
        else
        {
            Header('Location: giris.php?eslesmiyor=1');
            exit;
        }    
        
    } 
   elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['giris'])) {
        $username = $_POST['log_username'];
        $password = $_POST['log_password'];

        $kullanici_sor = $baglanti->prepare('SELECT * FROM kullanicilar WHERE kullanici_adi=?');
        $kullanici_sor->execute([$username]);
        $user = $kullanici_sor->fetch(PDO::FETCH_ASSOC);

        if($user && password_verify($password, $user['parola'])) {
            // Parola doğru
            $_SESSION['username'] = $username;
            echo "<script>alert('başarılı ile giriş yaptınız, yönlendiriliyorsunuz.')</script>";
            header("location: index.php");
            exit();
        } else {
            
            Header('Location: giris.php?yanlis=1');
            exit;
        }
    }
?>


<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giris ve Kayıt</title>
    
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
             
             <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle mx-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Üniversiteler
                </a>
                <ul class="dropdown-menu">
                  <?php
                        $sec = "SELECT * FROM sehirler";
                        $sonuc = $baglanti->query($sec);
                        if ($sonuc === false) {
                        die("Hata: " . $baglanti->error);
                         }

                      if ($sonuc->rowCount() > 0) {
                          while ($cek = $sonuc->fetch(PDO::FETCH_ASSOC)) {
                            echo '
                           <li class="nav-item dropend">
                              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                  ' . $cek['sehir_ad'] . '
                              </a>
                        <ul class="dropdown-menu">';
                                
                        $sec2 = "SELECT * FROM sehirler INNER JOIN universiteler ON universiteler.sehir_id = sehirler.s_id WHERE sehirler.s_id = " . $cek['s_id'];
                        $sonuc2 = $baglanti->query($sec2);
                        if ($sonuc2 === false) {
                            die("Hata: " . $baglanti->error);
                        }

                        if ($sonuc2->rowCount() > 0) {
                            while ($cek2 = $sonuc2->fetch(PDO::FETCH_ASSOC)) {
                                echo '
                                <li><a class="dropdown-item" href="universite.php?id='.$cek2['u_id'].'">'.$cek2['universite_ad'].'</a></li>';
                            }
                        }

                        echo '
                            </ul>
                        </li>';
                      }
                   }
                ?>
                </ul>
              </li>
           </ul>
          
         </div>
       </div>
       <a href="giris.php" class="login-button">Giriş/Kayıt</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
       </button>

     </div>
   </nav>

   <div class="container giris-kayit">
    <div class="register"> 
       <div class="row mt-5">
           <div class="col-lg-4 col-md-6 col-sm-8 bg-white m-auto rounded-top wrapper">
               <h2 class="text-center pt-3 mb-4">Kayıt</h2>
                
                <!-- Form start -->
                <form method="POST"  action="">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fa fa-user"> </i></span>
                        <input type="text" class="form-control" placeholder="Kullanıcı Adı" name="reg_username" required>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fa fa-envelope"> </i></span>
                        <input type="Email" class="form-control" placeholder="Email" name="reg_mail" required>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fa fa-lock"> </i></span>
                        <input type="password" class="form-control" placeholder="Şifre" name="reg_password" required>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fa fa-lock"> </i></span>
                        <input type="password" class="form-control" placeholder="Şifreyi Onayla" name="reg_cpassword" required>
                    </div>
                    <div class="input-group mb-3">
                        <div class="col-7">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-university"> </i></span>
                                <select class="form-select" id="universite" name="reg_uni" required>
                                    <option value="" selected disabled>Üniversite seçin</option>
                                    <?php
                                    $universiteler_sorgu = $baglanti->query("SELECT * FROM universiteler");
                                    while ($universite = $universiteler_sorgu->fetch(PDO::FETCH_ASSOC)) {
                                        echo '<option value="' . $universite['u_id'] . '">' . $universite['universite_ad'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-5 ps-2 text-end">
                            <select class="form-select" id="durum" name="reg_durum" required>
                                <option value="" selected disabled>Durum seçin</option>
                                <option value="aktif">Aktif</option>
                                <option value="mezun">Mezun</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" name="kaydet" class="btn btn-success mb-3">Kayıt Ol</button>
                        <p class="text-center">
                             Zaten hesabın var mı? <a href="#" id="loginLink">Giriş yap</a>
                        </p>
                    </div>

                    <?php if( isset($_GET['eslesmiyor']) ): ?> 
                        <div class="alert alert-danger">Parolalar eşleşmiyor</div>
                    <?php endif ?>
                </form>

                <!-- Form end -->
           </div>
       </div>
    </div>

    <div class="login"> 
       <div class="row mt-5">
           <div class="col-lg-4 col-md-6 col-sm-8 bg-white m-auto rounded-top wrapper">
               <h2 class="text-center pt-3 mb-4">Giriş</h2>
                
                <!-- Form start -->
                <form method="POST"  action="giris.php">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fa fa-user"> </i></span>
                        <input type="text" class="form-control" placeholder="Kullanıcı Adı" name="log_username" required>
                    </div>
                    
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fa fa-lock"> </i></span>
                        <input type="password" class="form-control" placeholder="Şifre" name="log_password" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" name="giris" class="btn btn-success mb-3">Giriş Yap</button>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label>
                                <input type="checkbox"> Beni Hatırla
                            </label>
                        </div>
                        <div class="col-6  text-end">
                             <a href="unuttum.php">Şifreni mi unuttun?</a>
                        </div>
                    </div>

                    <div class="d-grid mt-3">
                        <p class="text-center">
                          Hesabın yok mu? <a href="#" id="registerLink">Kayıt ol</a>
                        </p>
                    </div>
                        <?php if( isset($_GET['yanlis']) ): ?> 
                            <div class="alert alert-danger">Giriş başarısız</div>
                        <?php endif ?>
                </form>

                <!-- Form end -->
           </div>
       </div>
    </div>
   </div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if(isset($_GET['eslesmiyor']) && $_GET['eslesmiyor'] == 1): ?>
            document.querySelector('.login').style.display = 'none'; // Giriş formunu gizle
            document.querySelector('.register').style.display = 'block'; // Kayıt formunu göster
        <?php endif; ?>
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var dropdowns = document.querySelectorAll('.dropdown');
    dropdowns.forEach(function(dropdown) {
        var menu = dropdown.querySelector('.dropdown-menu');
        dropdown.addEventListener('mouseenter', function() {
            menu.style.display = 'block';
            menu.style.opacity = '1';
        });
        dropdown.addEventListener('mouseleave', function() {
            menu.style.display = 'none';
            menu.style.opacity = '0';
        });
    });
});
</script>


<script type="text/javascript" src="js/script2.js"></script>   
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>


