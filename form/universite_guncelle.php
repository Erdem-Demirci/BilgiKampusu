<?php 
session_start();
require_once "baglanti.php"; 

// Üniversite ID'sini al
$uni_id = isset($_GET['uni_id']) ? $_GET['uni_id'] : $_POST['universite_id'];

// Üniversite bilgilerini veritabanından al
$stmt = $baglanti->prepare("SELECT * FROM universiteler WHERE u_id = ?");
$stmt->execute([$uni_id]);
$universite = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$universite) {
    // Eğer veritabanında üniversite bulunamazsa, hata mesajı göster
    echo "Üniversite bulunamadı.";
    exit(); // Kodun devamını çalıştırmamak için çık
}

// Üniversite bilgileri
$universite_ad = $universite['universite_ad'];
$universite_website = $universite['universite_website'];
$website_ad = $universite['website_ad'];
$sehir_id = $universite['sehir_id'];
$hakkimizda = $universite['hakkımızda'];
$uni_resim = $universite['uni_resimler1'];





?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Üniversite Güncelleme</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/all.min.css">
    <link rel="stylesheet" type="text/css" href="css/stil1.css">
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
        <h2>Üniversite Güncelleme Formu</h2>

        <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
            <div class="alert alert-success">Üniversite başarıyla güncellendi.</div>
        <?php endif; ?>

        <form method="POST" action="universite_guncelle_kaydet.php" enctype="multipart/form-data">
            <input type="hidden" name="universite_id" value="<?php echo $uni_id; ?>">
            <div class="mb-3">
                <label class="form-label">Üniversite Adı:</label>
                <input type="text" class="form-control" name="universite_ad" value="<?php echo $universite_ad; ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Üniversite Websitesi:</label>
                <input type="text" class="form-control" name="universite_website" value="<?php echo $universite_website; ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Website Adı:</label>
                <input type="text" class="form-control" name="website_ad" value="<?php echo $website_ad; ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Şehir ID:</label>
                <input type="text" class="form-control" name="sehir_id" value="<?php echo $sehir_id; ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Hakkında:</label>
                <textarea class="form-control" name="hakkinda" rows="4"><?php echo $hakkimizda; ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Üniversite Resmi:</label><br>
                <img src="<?php echo $uni_resim; ?>" alt="Üniversite Resmi" style="max-width: 200px;"><br>
                <input type="file" class="form-control" name="uni_resimler1">
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Güncelle</button>
            </div>
            
        </form>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>

