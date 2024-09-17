<?php 
session_start();

require_once "baglanti.php"; 

?>
<!DOCTYPE html>
<html>
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title></title>
   <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
   <link rel="stylesheet" type="text/css" href="css/stil1.css">
</head>
<body class="bg-light">

   <nav class="navbar navbar-expand-sm fixed-top">
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
               <a class="nav-link mx-lg-2 active" aria-current="page" href="index.php">Anasayfa</a>
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
       <?php
            if(isset($_SESSION['username']))
            {
        ?>
            <div class="dropdown">
            <a class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="text-decoration: none;color: #666777;">
             Kullanıcı Adı: <?php echo $_SESSION['username']; ?>
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="cikis.php">Çıkış</a></li>
              
            </ul>
          </div>
            
        <?php    
            }
            else
            {
         ?>   
       <a href="giris.php" class="login-button">Giriş/Kayıt</a>
        <?php 
            }
        ?>

        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
       </button>

     </div>
   </nav>

   <section class="py-5 bg-dark-subtle">

    <div class="container pb-4">
        
        <div class="row g-lg-5 mt-5">
            
            <div class="col-md-6 col-lg-4 text-center position-relative">
                
                 <div class="px-4 mb-3">
                    
                        <div class="icon-xxl mx-auto rounded-3 mb-3">
                            <img src="img/sorucevap1.png" class="rounded-3 lazy entered loaded" alt="Üniversite Yorumları" width="80" height="56" data-ll-status="loaded">
                        </div>
                        <h4 class="text-dark">Soru Cevap</h4>

                        <p class="text-secondary">Üniversite ya da Bölümler hakkında sorular sor veya soruları cevaplayarak puan kazan</p>
                   
                </div>
            </div>

            <div class="col-md-6 col-lg-4 text-center pt-0 pt-lg-5 position-relative">

                
                <div class="px-4 mb-3">

                <div class="icon-xxl mx-auto rounded-3 mb-3">
                    <img src="img/university.png" class="rounded-3 lazy entered loaded" alt="Türkiye'deki En İyi Üniversiteler" width="63" height="56" data-ll-status="loaded">
                </div>

                <h4 class="text-dark">Üniversiteleri Keşfet</h4>
                <p class="text-secondary">Türkiyedeki Üniversiteleri incele ve sorular sorarak bilgi edin</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 text-center">
                <div class="px-4 mb-3">

                <div class="icon-xxl mx-auto rounded-3 mb-3">
                <img src="img/bolum.png" class="rounded-3 lazy entered loaded" alt="Üniversite Bölümleri" width="63" height="56" data-ll-status="loaded">
                </div>

                <h4 class="text-dark">Üniversite Bölümleri Keşfet</h4>
                <p class="text-secondary">Üniversite bölümlerini incele, bölümler hakkında
                bilgi edin!</p>
                </div>
            </div>


        </div>

    </div>
    
</section>


   <section class="py-5 bg-dark">
    <div class="container text-white">
        <h3 class="my-3 text-center">Şehirlerdeki Üniversiteleri Keşfet</h3>
        <?php $sorgu = "SELECT * FROM sehirler";
        $statement = $baglanti->prepare($sorgu);
        $statement->execute();
         ?>
        <div class="row  mt-4">
        <?php while ($row = $statement->fetch(PDO::FETCH_ASSOC)) : ?>
         <div class="col-sm-3">
            <div class="card">
                <img src="<?php echo $row['sehir_resim']; ?>" class="card-img-top" alt="...">
                <div class="card-body">
                 <h5 class="card-title text-center"><?php echo $row['sehir_ad']; ?></h5>
                 <?php
                            // Her şehir için üniversitelerin sayısını hesaplamak için sorgu
                            $universite_sayisi_sorgu = "SELECT COUNT(*) AS uni_sayisi FROM universiteler WHERE sehir_id = :sehir_id";
                            $universite_statement = $baglanti->prepare($universite_sayisi_sorgu);
                            $universite_statement->bindParam(':sehir_id', $row['s_id']);
                            $universite_statement->execute();
                            $uni_sayisi = $universite_statement->fetch(PDO::FETCH_ASSOC);
                            ?>
                            <p class="text-center"><?php echo $uni_sayisi['uni_sayisi']; ?> Üniversite</p>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
         
    </div>
   </section>


     
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

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>