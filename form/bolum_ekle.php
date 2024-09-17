<?php 
session_start();
require_once "baglanti.php"; 

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['admin'])) {
    header('Location: admin_giris.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bol_ad = $_POST['bolum_ad'];
    $uni_id = $_POST['uni_id'];
    $bilgi = $_POST['bilgi'];

    $resim_yolu = ''; // varsayılan resim yolu
    if (isset($_FILES['bolum_resim']) && $_FILES['bolum_resim']['error'] === UPLOAD_ERR_OK) {
        // Resim yüklendi, hedef klasöre kaydet
        $hedef_klasor = 'img/bolumler/';
        $dosya_adi = basename($_FILES['bolum_resim']['name']);
        $hedef_dosya = $hedef_klasor . $dosya_adi;
        if (move_uploaded_file($_FILES['bolum_resim']['tmp_name'], $hedef_dosya)) {
            $resim_yolu = $hedef_dosya;
        }
    }
    
    $stmt = $baglanti->prepare("INSERT INTO bolumler (bolum_ad, universite_id, bilgi, bolum_resimler) VALUES (?, ?, ?, ?)");
    $stmt->execute([$bol_ad, $uni_id, $bilgi, $resim_yolu]);
    if ($stmt->rowCount()) {
        $success_message = "Bölüm başarıyla eklendi.";
    } else {
        $error_message = "Bölüm eklenirken bir hata oluştu.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Üniversite Ekleme Sayfası</title>
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
                           <a class="nav-link mx-lg-2 active" href="bolum_ekle.php">Bölüm Ekle</a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link mx-lg-2" href="rapor.php">Rapor Sayfası</a>
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
        <h2 class="mb-3">Bölüm Ekle</h2>
        <?php if (isset($success_message)) : ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if (isset($error_message)) : ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="bolum_ad" class="form-label">Bölüm Adı</label>
                <input type="text" class="form-control" id="bolum_ad" name="bolum_ad" required>
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text"><i class="fas fa-university"> </i></span>
                <select class="form-select" id="uni_id" name="uni_id" required>
                    <option value="" selected disabled>Universite seçin</option>
                    <?php
                    $uni_sorgu = $baglanti->query("SELECT * FROM universiteler");
                    while ($universite = $uni_sorgu->fetch(PDO::FETCH_ASSOC)) {
                        echo '<option value="' . $universite['u_id'] . '">' . $universite['universite_ad'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="bilgi" class="form-label">Bilgi</label>
                <textarea class="form-control" id="bilgi" name="bilgi" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="bolum_resim" class="form-label">Bölüm Resmi</label>
                <input type="file" class="form-control" id="bolum_resim" name="bolum_resim" accept="image/*">
            </div>
            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary">Ekle</button>
            </div>
            
        </form>
    </div>
</body>
</html>
