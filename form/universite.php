<?php 
session_start();

require_once "baglanti.php"; 
    
    $id = $_GET['id'];
    $username = $_SESSION['username'];


    $sorgu = "SELECT * FROM universiteler WHERE u_id=?";
    $state = $baglanti->prepare($sorgu);
    $state->execute([$id]);

    $sorgu2 = "SELECT * FROM bolumler WHERE universite_id=?";
    $statement = $baglanti->prepare($sorgu2);
    $statement->execute([$id]);
         

    $sorgu4 = "SELECT * FROM universiteler WHERE u_id=?";
    $state4 = $baglanti->prepare($sorgu4);
    $state4->execute([$id]);

    $sorgu3 = "SELECT * FROM sehirler s
            INNER JOIN universiteler u ON s.s_id = u.sehir_id
            WHERE u_id = ?";  
    $durum = $baglanti->prepare($sorgu3);
    $durum->execute([$id]);

    $sorgu5 = "SELECT * FROM uni_sorular u 
                INNER JOIN kullanicilar k ON u.soran_id = k.id
                WHERE universite_id = ?";
    $state5 = $baglanti->prepare($sorgu5);
    $state5->execute([$id]);

    $sorgu7 = "SELECT * FROM kullanicilar WHERE kullanici_adi = ?";
    $state7 = $baglanti->prepare($sorgu7);
    $state7->execute([$username]);

    $kul = $state7->fetch(PDO::FETCH_ASSOC);
    $kul_uni = $kul['universite'];




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
<body style="background-color:rgb(112,128,144);">

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


   <section class="pad-6">
        <div class="row">
            <div class="col-md">
            <?php while ($row = $state->fetch(PDO::FETCH_ASSOC)) : ?>
            <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
                <div class="carousel-inner" data-bs-interval="3000">
                    <div class="carousel-item active">
                    <center> <img src="<?php echo $row['uni_resimler1']; ?>" class="d-block" alt="..."> </center>
                    </div>
                    <div class="carousel-item" data-bs-interval="3000">
                        <center> <img src="<?php echo $row['uni_resimler2']; ?>" class="d-block" alt="..."> </center>
                    </div>
                    <div class="carousel-item" data-bs-interval="3000">
                        <center> <img src="<?php echo $row['uni_resimler3']; ?>" class="d-block" alt="..."> </center>
                    </div>
                </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
            </div>
        <?php endwhile; ?> 
       </div>
        <div class="col-md pt-3">
          
            <div class="row">
              <h2 class="text-center" style="color: darkorange;">
                <i class="fas fa-university"></i>
                <?php
                  $row4 = $state4->fetch(PDO::FETCH_ASSOC);
                  echo $row4['universite_ad'];
                ?>
              </h2>  
                <span class="text-dark pt-3">
                    <i class="fa fa-building me-1 me-sm-3" style="color: darkorange;"></i> 
                    Şehir:
                      <?php $row3 = $durum->fetch(PDO::FETCH_ASSOC);
                        echo $row3['sehir_ad'];

                       ?>
                </span>

                <span class="text-dark pt-3">
                    <i class="fa fa-mouse me-1 me-sm-3" style="color: darkorange;"></i>
                        Website: 
                    <a href="<?php echo $row3['universite_website']; ?>" target="_blank" style="text-decoration: none; color: darkorange;">
                     <?php echo $row3['website_ad']; ?> </a> 

                </span>

            </div>

            <div class="row pt-2"> 
                <h4 class="" style="color: darkorange;">Hakkımızda</h4>
                <p class="pt-2 text-white"><?php echo nl2br($row3['hakkımızda']); ?></p> <!- nl2br paragraflar icin ->
            </div>



        </div>
   </div>
   <hr>
   </section>

   
  
  <section class="">
      <div class="container pt-3 pb-3">
          <center> <h2 class="text-white">Bölümleri Keşfet</h2> </center>
            <div class="slider slider-1">
            <?php while ($row2 = $statement->fetch(PDO::FETCH_ASSOC)) : ?>

                <div class="card">
                    <img src="<?php echo $row2['bolum_resimler']; ?>" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h6 class="card-title text-center"><?php echo $row2['bolum_ad']; ?></h6>

                        <a class="btn" style="background-color: darkorange; color: white;" href="bolum.php?id=<?php echo $row2['id']; ?>">İncele</a>

                        
                    </div>
                </div>
            <?php endwhile; ?>
    
            </div>
      </div>
      <hr>
  </section>

<section class="p-5">
    
      <div class="container col-10">
        <div class="col-12 border border-dark border-1 rounded-1 p-4">
            <?php
            if(isset($_SESSION['username']))
            {
            ?>
            <form action="soru_gonder.php" method="post">
                    <input type="hidden" name="university_id" value="<?php echo $id; ?>"> 
                    <textarea name="comment" class="form-control mb-2" placeholder="Sorunuzu sorun" id="exampleFormControlTextarea1 rounded-3" rows="2"></textarea>
                <div class="d-flex justify-content-end">
                    <input type="submit" class="btn btn-primary mb-0" name="Gönder">
                </div>
            </form>
            <?php 
            }
            else
            {
            ?>
            <div class="row justify-content-center text-center text-white">
                <p>Soru sorabilmeniz için giriş yapmanız lazım</p>
                <a href="giris.php" class="" style="text-decoration:none; color: darkorange;">Giriş/Kayıt</a>
            </div>
                
            <?php 
            }
            ?>
        </div>
        <?php while ($row = $state5->fetch(PDO::FETCH_ASSOC)) : ?>
            <div class="col-12 border border-dark border-1 rounded-1 p-4 text-white">
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
                    <a href="#" class="" style="text-decoration:none; color:darkblue;" onclick="toggleCevap(this); return false;">Cevapla</a>
                
                    <a href="#" class="vote ms-3" style="text-decoration:none;" data-type="up" data-id="<?php echo $row['soru_id']; ?>">
                        <i class="fa-solid fa-thumbs-up"></i></a>
                    <span class="votes ms-1"><?php echo $row['upvotes'] ?></span>
                    <a href="#" class="vote ms-2" style="text-decoration:none;" data-type="down" data-id="<?php echo $row['soru_id']; ?>">
                        <i class="fa-solid fa-thumbs-down"></i></a>
                    <span class="votes ms-1"><?php echo $row['downvotes'] ?></span>
                
                </div>
                <div class="cevapla mt-2" style="display: none;">
                    <?php if(!isset($_SESSION['username'])) { ?>
                        <div class="row justify-content-center">
                            <p class="text-center" style="color:orangered;">Cevap yazmanız için giriş yapmanız lazım</p>
                            <center><a href="giris.php" style="text-decoration:none; color: darkorange;">Giriş/Kayıt</a></center>
                        </div>
                        

                    <?php } elseif ($id == $kul_uni) { ?>
                        <form action="cevap_gonder.php" method="post">
                            <textarea name="cevap" class="form-control mb-2" placeholder="Cevabınızı yazın" rows="2"></textarea>
                            <div class="d-flex justify-content-end">
                                <input type="hidden" name="universite_id" value="<?php echo $id; ?>">
                                <input type="hidden" name="soru_id" value="<?php echo $row['soru_id']; ?>">
                                <input type="submit" class="btn btn-dark mb-0" value="Cevapla">
                            </div>
                        </form>
                    <?php } else { ?>
                                
                           <p class="text-center" style="color:darkred;">Cevap verebilmek için <?php echo $row['universite_ad']; ?>'den mezun yada okuyor olmak zorundasınız</p>     

                    <?php } ?>

                </div>

                <?php  $sorgu6 = "SELECT * FROM uni_cevaplar uc 
                                INNER JOIN uni_sorular us ON uc.soru_id = us.soru_id
                                INNER JOIN kullanicilar k ON k.id = uc.cevapveren_id
                                WHERE us.universite_id = ? and uc.soru_id = ?";
                        $state6 = $baglanti->prepare($sorgu6);
                        $state6->execute([$id,$row['soru_id']]);

                ?>
                <?php while ($row2 = $state6->fetch(PDO::FETCH_ASSOC)) : ?>
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



     
<script type="text/javascript" src="js/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function() {
        // Soru formunu işle
        $('#soruForm').submit(function(e) {
            e.preventDefault();
            var soruText = $('#soruText').val();
            $.ajax({
                type: "POST",
                url: "soru_gonder.php",
                data: { 
                    university_id: <?php echo $id; ?>,
                    comment: soruText
                },
                success: function(response) {
                    // Başarılı bir şekilde gönderildiğinde sayfayı yenile
                    location.reload();
                },
                error: function(xhr, status, error) {
                    // Hata durumunda kullanıcıya bilgi ver
                    alert("Bir hata oluştu: " + error);
                }
            });
        });

        $('.vote').click(function(e) {
            e.preventDefault();
            var postId = $(this).data('id');
            var type = $(this).data('type');
            var votesElement = $(this).next('.votes'); // Oy sayısını içeren elementi al
            $.ajax({
                type: "POST",
                url: "vote.php",
                data: {
                    postId: postId,
                    type: type
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.status === 'success') {
                        // Oy sayısını güncelle
                        if (type === 'up') {
                            votesElement.text(data.upvotes);
                        } else if (type === 'down') {
                            // Beğenmeme sayısını güncelle
                            votesElement.text(data.downvotes);
                        }
                    } else {
                        alert('Oy verme işlemi başarısız oldu: ' + data.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert("Bir hata oluştu: " + error);
                }
            });
        });

    });
</script>

<script>
    function toggleCevap(button) {
    var cevaplaDiv = button.parentElement.nextElementSibling;
    if (cevaplaDiv.style.display === 'none' || cevaplaDiv.style.display === '') {
        cevaplaDiv.style.display = 'block';
    } else {
        cevaplaDiv.style.display = 'none';
    }
}
</script>

<script type="text/javascript" src="js/jquery-migrate-3.4.0.min.js"></script>
<script type="text/javascript" src="slick/slick.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
      $('.slider-1').slick({
        dots: true,
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 4,
        responsive: [
        {
          breakpoint: 1024,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 3,
            dots: true,
            infinite: true
          }
        },
        {
          breakpoint: 600,
          settings: {
            dots: true,
            slidesToShow: 2,
            slidesToScroll: 2
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1
          }
        }
        // You can unslick at a given breakpoint now by adding:
        // settings: "unslick"
        // instead of a settings object
      ]

      });
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

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>