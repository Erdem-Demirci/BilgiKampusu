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
                           <a class="nav-link mx-lg-2"  href="admin.php">Universite Sil</a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link mx-lg-2 active" aria-current="page" href="admin_bolum.php">Bölüm Sil</a>
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
        <!-- Üniversite seçimi formu -->
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
            ?>
            <!-- Bölüm seçimi formu -->
            <form method="POST" action="" id="bolumForm">
                <div class="input-group mb-3">
                    <div class="col-7">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-book"></i></span>
                            <select class="form-select" id="bolum" name="bolum" required onchange="submitForm2()">
                                <option value="" selected disabled>Bölüm seçin</option>
                                <?php
                                $bolum_sorgu = $baglanti->prepare("SELECT * FROM bolumler WHERE universite_id = ?");
                                $bolum_sorgu->execute([$uniID]);
                                while ($bolum = $bolum_sorgu->fetch(PDO::FETCH_ASSOC)) {
                                    echo '<option value="' . $bolum['id'] . '">' . $bolum['bolum_ad'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                        <input type="hidden" name="universite" value="<?php echo $uniID; ?>">
                </div>
            </form>
            <?php
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['bolum'])) {
            $bolID = $_POST['bolum'];

            if (isset($_POST['delete_bolum'])) {
                $stmt = $baglanti->prepare("DELETE FROM bolumler WHERE id = ?");
                $stmt->execute([$bolID]);
                if ($stmt->rowCount()) {
                    echo '<div class="alert alert-success">Bölüm başarıyla silindi.</div>';
                } else {
                    echo '<div class="alert alert-danger">Bölüm silinemedi.</div>';
                }
            } else {
                $stmt = $baglanti->prepare("SELECT * FROM bolumler b
                    INNER JOIN universiteler u ON b.universite_id = u.u_id
                 WHERE id = ?");
                $stmt->execute([$bolID]);
                $bolum = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($bolum) {
                    echo '<div id="bolumDetails">';
                    echo '<h3>Bölüm Bilgileri</h3>';
                    echo '<table class="table table-bordered">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>ID</th>';
                    echo '<th>Bölüm Adı</th>';
                    echo '<th>Üniversite Adı</th>';
                    echo '<th>Bilgileri</th>';
                    echo '<th>Resim</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    echo '<tr>';
                    echo '<td>' . $bolum['id'] . '</td>';
                    echo '<td>' . $bolum['bolum_ad'] . '</td>';
                    echo '<td>' . $bolum['universite_ad'] . '</td>';
                    echo '<td>' . $bolum['bilgi'] . '</td>';
                    echo '<td><img height="100px" width="150px" src="' . $bolum['bolum_resimler'] . '"></td>';
                    echo '</tr>';
                    echo '</tbody>';
                    echo '</table>';
                    echo '</div>';

                    echo '<form method="POST" action="">';
                    echo '<input type="hidden" name="bolum" value="' . $bolID . '">';
                    echo '<input type="hidden" name="universite" value="' . $_POST['universite'] . '">';
                    echo '<button type="submit" name="delete_bolum" class="btn btn-danger mt-3">Bölümü Sil</button>';
                    echo '</form>';
                } else {
                    echo '<div class="alert alert-danger">Bölüm bulunamadı.</div>';
                }
            }
        }
        ?>
        <?php if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['bolum'])) : ?>
            <form method="POST" action="bolum_guncelle.php" class="mt-3">
                <input type="hidden" name="bolum_id" value="<?php echo $bolID; ?>">
                <button type="submit" name="update_bol" class="btn btn-primary">Bölüm Güncelle</button>
            </form>
        <?php endif; ?>
    </div>

    <script>
        function submitForm() {
            document.getElementById('universityForm').submit();
        }
        function submitForm2() {
            document.getElementById('bolumForm').submit();
        }
    </script>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
