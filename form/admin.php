<?php 
session_start();
require_once "baglanti.php"; 
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sayfası</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/all.min.css">
    <link rel="stylesheet" type="text/css" href="css/stil1.css">
</head>
<body class="bg-light">
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
                           <a class="nav-link mx-lg-2 active" aria-current="page" href="admin.php">Universite Sil</a>
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
    <div class="container mt-5 pt-5">
        <form method="POST" action="" id="universityForm">
            <div class="input-group mb-3">
                <div class="col-7">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-university"></i></span>
                        <select class="form-select" id="universite" name="universite" required onchange="submitForm()">
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
            </div>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['universite'])) {
            $uniID = $_POST['universite'];

            if (isset($_POST['delete_uni'])) {
                $stmt = $baglanti->prepare("DELETE FROM universiteler WHERE u_id = ?");
                $stmt->execute([$uniID]);
                if ($stmt->rowCount()) {
                    echo '<div class="alert alert-success">Üniversite başarıyla silindi.</div>';
                } else {
                    echo '<div class="alert alert-danger">Üniversite silinemedi.</div>';
                }
            } else {
                $stmt = $baglanti->prepare("SELECT * FROM universiteler WHERE u_id = ?");
                $stmt->execute([$uniID]);
                $universite = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($universite) {
                    echo '<div id="uniDetails">';
                    echo '<h3>Üniversite Bilgileri</h3>';
                    echo '<table class="table table-bordered">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>ID</th>';
                    echo '<th>Üniversite Adı</th>';
                    echo '<th>Website</th>';
                    echo '<th>Website Adı</th>';
                    echo '<th>Şehir ID</th>';
                    echo '<th>Hakkımızda</th>';
                    echo '<th>Resim</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    echo '<tr>';
                    echo '<td>' . $universite['u_id'] . '</td>';
                    echo '<td>' . $universite['universite_ad'] . '</td>';
                    echo '<td>' . $universite['universite_website'] . '</td>';
                    echo '<td>' . $universite['website_ad'] . '</td>';
                    echo '<td>' . $universite['sehir_id'] . '</td>';
                    echo '<td>' . $universite['hakkımızda'] . '</td>';
                    echo '<td><img height="100px" width="150px" src="' . $universite['uni_resimler1'] . '"></td>';
                    echo '</tr>';
                    echo '</tbody>';
                    echo '</table>';
                    echo '</div>';

                    echo '<form method="POST" action="">';
                    echo '<input type="hidden" name="universite" value="' . $uniID . '">';
                    echo '<button type="submit" name="delete_uni" class="btn btn-danger mt-3">Üniversiteyi Sil</button>';
                    echo '</form>';
                } else {
                    echo '<div class="alert alert-danger">Üniversite bulunamadı.</div>';
                }
            }
        }
        ?>
        <?php if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['universite'])) : ?>
            <form method="POST" action="universite_guncelle.php" class="mt-3">
                <input type="hidden" name="universite_id" value="<?php echo $uniID; ?>">
                <button type="submit" name="update_uni" class="btn btn-primary">Üniversiteyi Güncelle</button>
            </form>
        <?php endif; ?>
    </div>
    
<script>
    function submitForm() {
        document.getElementById('universityForm').submit();
    }
</script>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
