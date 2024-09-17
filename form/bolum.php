<?php 
session_start();

require_once "baglanti.php"; 
    $id = $_GET['id'];
    $username = $_SESSION['username'];

    $sorgu = "SELECT * FROM bolumler b INNER JOIN universiteler u
    ON u.u_id = b.universite_id WHERE id=?";
    $state = $baglanti->prepare($sorgu);
    $state->execute([$id]);

    $sorgu2 = "SELECT * FROM bolum_sorular bs
    INNER JOIN kullanicilar k ON k.id = bs.soran_id
    WHERE bolum_id = ?";
    $state2 = $baglanti->prepare($sorgu2);
    $state2->execute([$id]);

    
    $sorgu7 = "SELECT * FROM kullanicilar WHERE kullanici_adi = ?";
    $state7 = $baglanti->prepare($sorgu7);
    $state7->execute([$username]);

    $kul = $state7->fetch(PDO::FETCH_ASSOC);
    $kul_uni = $kul['universite'];

    $sorgu4 = "SELECT * FROM bolumler WHERE id=?";
    $state4 = $baglanti->prepare($sorgu4);
    $state4->execute([$id]);

    $bul = $state4->fetch(PDO::FETCH_ASSOC);
    $bul_uni = $bul['universite_id'];


?>


<!DOCTYPE html>
<html>
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title></title>
   <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
   <link rel="stylesheet" type="text/css" href="css/all.min.css">
   <link rel="stylesheet" type="text/css" href="css/stil1.css">
   <link rel="stylesheet" type="text/css" href="slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="slick/slick-theme.css"/>


   <style>
       .carousel-item img {
           width: 100%; /* veya istediğiniz bir genişlik değeri */
           height: 400px; /* veya istediğiniz bir yükseklik değeri */
       }

          .pad-6 {
              padding: 56px 12px 0px 0px; /* üst sol alt sağ */
            }
            
        .dropdown-toggle { white-space: nowrap; }  /* sonrada ekledim */

        /* sliderdaki cardlar arası boşuk için   */
        .slick-slider {
            margin:0 -15px;
        }
        .slick-slide {
            padding:5px;
            text-align:center;
            margin-right:5px;
            margin-left:5px;
        }
        

   </style>

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


    <section class="bg-dark pad-6">
        <?php while ($row = $state->fetch(PDO::FETCH_ASSOC)) : ?>
        <div class="row">
            <div class="col-md">                
                <img src="<?php echo $row['bolum_resimler']; ?>" class="img-fluid">

            
            </div>
        <div class="col-md text-white pt-3">

                <h4 class="text-center" style="color: darkorange;"><?php echo $row['universite_ad']  ?></h4>
                <h5 class="text-center pt-2" style="color: darkred;"> <?php echo $row['bolum_ad']; ?></h5>

                 <p class="pt-2"><?php echo nl2br($row['bilgi']); ?></p>


        </div>
        <?php endwhile; ?> 
   </section>

  <section class="p-5" style="background-color: ghostwhite;">
    <div class="container col-10">
        <div class="col-12 border border-dark border-1 rounded-1 p-4">
            <?php if(isset($_SESSION['username'])): ?>
                <form action="soru_gonder.php" method="post">
                    <input type="hidden" name="bolum_id" value="<?php echo $id; ?>"> 
                    <textarea name="commentbolum" class="form-control mb-2" placeholder="Sorunuzu sorun" id="exampleFormControlTextarea1 rounded-3" rows="2"></textarea>
                    <div class="d-flex justify-content-end">
                        <input type="submit" class="btn btn-primary mb-0" name="Gönder">
                    </div>
                </form>
            <?php else: ?>
                <div class="row justify-content-center text-center">
                    <p>Soru sorabilmeniz için giriş yapmanız lazım</p>
                    <a href="giris.php" class="" style="text-decoration:none; color: darkorange;">Giriş/Kayıt</a>
                </div>
            <?php endif; ?>
        </div>
        <?php while ($row = $state2->fetch(PDO::FETCH_ASSOC)) : ?>
            <div class="col-12 border border-dark border-1 rounded-1 p-4">
                <?php $unvan_sorgu = $baglanti->prepare("
                        SELECT u.unvan
                        FROM kullanicilar k
                        JOIN unvan u ON k.puan BETWEEN u.min_puan AND u.max_puan
                        WHERE k.kullanici_adi = ?
                    ");
                    $unvan_sorgu->execute([$row['kullanici_adi']]);
                    $unv = $unvan_sorgu->fetch(PDO::FETCH_ASSOC);

                    // Unvanın mevcut olup olmadığını kontrol edin
                    $unvan = $unv ? $unv['unvan'] : 'Unvan bulunamadı';
                    ?>
                <div class="row">
                    <div class="d-flex justify-content-start">
                        <h6 class=""><?php echo $row['kullanici_adi'] ?></h6>
                        <h6 class="ms-3" style="color:darkorange;">(<?php echo $unvan ?>)</h6>
                    </div>
                </div>
                <div class="row">
                    <p class=""><?php echo $row['soru'] ?></p>
                </div>
                <div class="d-flex justify-content-start">
                    <a href="#" class="" style="text-decoration:none;" onclick="toggleCevap(this); return false;">Cevapla</a>
                </div>
                <div class="cevapla mt-2" style="display: none;">
                    <?php if(!isset($_SESSION['username'])): ?>
                        <div class="row justify-content-center">
                            <p class="text-center" style="color:orangered;">Cevap yazmanız için giriş yapmanız lazım</p>
                            <a href="giris.php" style="text-decoration:none; color: darkorange;">Giriş/Kayıt</a>
                        </div>
                    <?php elseif ($bul_uni == $kul_uni): ?>
                        <form action="cevap_gonder.php" method="post">
                            <textarea name="cevapbolum" class="form-control mb-2" placeholder="Cevabınızı yazın" rows="2"></textarea>
                            <div class="d-flex justify-content-end">
                                <input type="hidden" name="bolum_id" value="<?php echo $id; ?>">
                                <input type="hidden" name="soru_id" value="<?php echo $row['bs_id']; ?>">
                                <input type="submit" class="btn btn-dark mb-0" value="Cevapla">
                            </div>
                        </form>
                    <?php else: ?>
                        <p class="text-center" style="color:darkred;">Cevap verebilmek için üniversiteden mezun yada okuyor olmak zorundasınız</p>
                    <?php endif; ?>
                </div>

                <?php  
                $sorgu3 = "SELECT * FROM bolum_cevaplar bc 
                    INNER JOIN kullanicilar k ON k.id = bc.cevap_veren_id
                    INNER JOIN bolum_sorular bs ON bc.soru_id = bs.bs_id
                    WHERE bs.bolum_id = ? and bc.soru_id = ?";
                $state3 = $baglanti->prepare($sorgu3);
                $state3->execute([$id,$row['bs_id']]);
                ?>
                <?php while ($row2 = $state3->fetch(PDO::FETCH_ASSOC)) : ?>
                    <div class="col-12 border border-dark border-1 rounded-1 p-4 mt-2">
                        <?php $unvan_sorgu = $baglanti->prepare("
                            SELECT u.unvan
                            FROM kullanicilar k
                            JOIN unvan u ON k.puan BETWEEN u.min_puan AND u.max_puan
                            WHERE k.kullanici_adi = ?
                        ");
                        $unvan_sorgu->execute([$row2['kullanici_adi']]);
                        $unv = $unvan_sorgu->fetch(PDO::FETCH_ASSOC);

                        // Unvanın mevcut olup olmadığını kontrol edin
                        $unvan = $unv ? $unv['unvan'] : 'Unvan bulunamadı';
                        ?>
                        <div class="row">
                            <div class="d-flex justify-content-start">
                                <h6 class=""><?php echo $row2['kullanici_adi'] ?></h6>
                                <h6 class="ms-3" style="color:darkorange;">(<?php echo $unvan ?>)</h6>
                            </div>
                        </div>
                        <div class="row">
                            <p class=""><?php echo $row2['cevap'] ?></p>
                        </div>
                    </div>
                <?php endwhile; ?> 
            </div>
        <?php endwhile; ?>  
    </div>
</section>


  <script>
    function toggleCevap(button) {
        var cevaplaDiv = button.parentElement.nextElementSibling;
        cevaplaDiv.style.display = cevaplaDiv.style.display === 'none' ? 'block' : 'none';
    }
</script>


   <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>