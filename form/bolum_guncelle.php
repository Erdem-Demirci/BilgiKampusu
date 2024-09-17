<?php 
session_start();
require_once "baglanti.php"; 

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['bolum_id'])) {
    $bolum_id = $_POST['bolum_id'];

    // Bölüm bilgilerini veritabanından al
    $stmt = $baglanti->prepare("SELECT * FROM bolumler WHERE id = ?");
    $stmt->execute([$bolum_id]);
    $bolum = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$bolum) {
        echo "Bölüm bulunamadı.";
        exit();
    }

    // Bölüm bilgileri
    $bolum_ad = $bolum['bolum_ad'];
    $bilgi = $bolum['bilgi'];
    $bolum_resim = $bolum['bolum_resimler'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bölüm Güncelleme</title>
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
    
    <div class="container col-8 mt-5 pt-5">
        <h2>Bölüm Güncelleme Formu</h2>
        <form method="POST" action="bolum_guncelle_kaydet.php" enctype="multipart/form-data">
            <input type="hidden" name="bolum_id" value="<?php echo $bolum_id; ?>">
            <div class="mb-3">
                <label class="form-label">Bölüm Adı:</label>
                <input type="text" class="form-control" name="bolum_ad" value="<?php echo $bolum_ad; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Bilgi:</label>
                <textarea class="form-control" name="bilgi" rows="4" required><?php echo $bilgi; ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Bölüm Resmi:</label><br>
                <img src="<?php echo $bolum_resim; ?>" alt="Bölüm Resmi" style="max-width: 200px;"><br>
                <input type="file" class="form-control" name="bolum_resim">
            </div>
            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary">Güncelle</button>
            </div>
            
        </form>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
