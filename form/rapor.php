<?php
session_start();
require_once "baglanti.php";

// İlk 5'te yer alan üniversiteleri sorgulamak için SQL sorgusu
$sorgu = "SELECT u.universite_ad, COUNT(us.soru_id) as toplam_soru 
          FROM uni_sorular us
          INNER JOIN universiteler u ON us.universite_id = u.u_id
          GROUP BY u.universite_ad
          ORDER BY toplam_soru DESC
          LIMIT 5";

$statement = $baglanti->prepare($sorgu);
$statement->execute();
$sonuclar = $statement->fetchAll(PDO::FETCH_ASSOC);

// İlk 5'te yer alan bölümleri sorgulamak için SQL sorgusu
$sorgu2 = "SELECT b.bolum_ad, COUNT(bs.bs_id) as toplam_soru 
          FROM bolum_sorular bs
          INNER JOIN bolumler b ON bs.bolum_id = b.id
          GROUP BY b.bolum_ad
          ORDER BY toplam_soru DESC
          LIMIT 5";

$statement2 = $baglanti->prepare($sorgu2);
$statement2->execute();
$sonuclar2 = $statement2->fetchAll(PDO::FETCH_ASSOC);

$sorgu3 = "SELECT us.soru, us.upvotes, u.universite_ad, k.kullanici_adi
           FROM uni_sorular us
           INNER JOIN universiteler u ON us.universite_id = u.u_id
           INNER JOIN kullanicilar k ON us.soran_id = k.id
           WHERE upvotes > 0
           ORDER BY us.upvotes DESC
           LIMIT 5";

$statement3 = $baglanti->prepare($sorgu3);
$statement3->execute();
$sonuclar3 = $statement3->fetchAll(PDO::FETCH_ASSOC);

$sorgu4 = "SELECT us.soru, us.downvotes, u.universite_ad, k.kullanici_adi
           FROM uni_sorular us
           INNER JOIN universiteler u ON us.universite_id = u.u_id
           INNER JOIN kullanicilar k ON us.soran_id = k.id
           WHERE downvotes > 0
           ORDER BY us.downvotes DESC
           LIMIT 5";

$statement4 = $baglanti->prepare($sorgu4);
$statement4->execute();
$sonuclar4 = $statement4->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>En Fazla Soru Sorulan Üniversiteler ve Bölümler</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/all.min.css">
    <link rel="stylesheet" type="text/css" href="css/stil1.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

    <nav class="navbar navbar-expand-sm fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand me-auto" href="index.php">BilgiKampüsü</a>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">BilgiKampüsü</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-center flex-grow-1 pe-3">
                        <li class="nav-item">
                           <a class="nav-link mx-lg-2" href="admin.php">Universite Sil</a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link mx-lg-2" href="admin_bolum.php">Bölüm Sil</a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link mx-lg-2" href="universite_ekle.php">Universite Ekle</a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link mx-lg-2" href="bolum_ekle.php">Bölüm Ekle</a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link mx-lg-2 active" href="rapor.php">Rapor Sayfası</a>
                        </li>
                    </ul>
                </div>
            </div>
            <?php
            if (isset($_SESSION['admin'])) {
            ?>
                <div class="dropdown">
                    <a class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="text-decoration: none;color: #666777;">
                        Admin Adı: <?php echo $_SESSION['admin']; ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="admin_cikis.php">Çıkış</a></li>
                    </ul>
                </div>
            <?php
            } else {
                header('Location: admin_giris.php');
            }
            ?>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <div class="container mt-5 pt-5">
        <div class="row">
            <div class="col-md-6">
                <h3 class="mt-5 mb-3" style="color:orangered;">En Fazla Soru Sorulan Üniversiteler</h3>
                <canvas id="universiteChart"></canvas>
            </div>
            <div class="col-md-6">
                <h3 class="mt-5 mb-3" style="color:orangered;">En Fazla Soru Sorulan Bölümler</h3>
                <canvas id="bolumChart"></canvas>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <h3 class="mt-5 mb-3" style="color:orangered;">En Çok Beğenilen Sorular</h3>
                <canvas id="likeChart"></canvas>
            </div>
            <div class="col-md-6">
                <h3 class="mt-5 mb-3" style="color:orangered;">En Çok Beğenilmeyen Sorular</h3>
                <canvas id="dislikeChart"></canvas>
            </div>
        </div>
    </div>

<script src="js/bootstrap.bundle.min.js"></script>
<script>
// Üniversite Grafiği
const ctx1 = document.getElementById('universiteChart').getContext('2d');
const universiteChart = new Chart(ctx1, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode(array_column($sonuclar, 'universite_ad')); ?>,
        datasets: [{
            label: 'Toplam Soru Sayısı',
            data: <?php echo json_encode(array_column($sonuclar, 'toplam_soru')); ?>,
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// Bölüm Grafiği
const ctx2 = document.getElementById('bolumChart').getContext('2d');
const bolumChart = new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode(array_column($sonuclar2, 'bolum_ad')); ?>,
        datasets: [{
            label: 'Toplam Soru Sayısı',
            data: <?php echo json_encode(array_column($sonuclar2, 'toplam_soru')); ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// En Çok Beğenilen Sorular Grafiği
const ctx3 = document.getElementById('likeChart').getContext('2d');
const likeChart = new Chart(ctx3, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode(array_column($sonuclar3, 'soru')); ?>,
        datasets: [{
            label: 'Beğeni Sayısı',
            data: <?php echo json_encode(array_column($sonuclar3, 'upvotes')); ?>,
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// En Çok Beğenilmeyen Sorular Grafiği
const ctx4 = document.getElementById('dislikeChart').getContext('2d');
const dislikeChart = new Chart(ctx4, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode(array_column($sonuclar4, 'soru')); ?>,
        datasets: [{
            label: 'Dislike Sayısı',
            data: <?php echo json_encode(array_column($sonuclar4, 'downvotes')); ?>,
            backgroundColor: 'rgba(255, 206, 86, 0.2)',
            borderColor: 'rgba(255, 206, 86, 1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});
</script>
S
</body>
</html>
