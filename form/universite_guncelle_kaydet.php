<?php
session_start();
require_once "baglanti.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uni_id = $_POST['universite_id'];
    $universite_ad = $_POST['universite_ad'];
    $universite_website = $_POST['universite_website'];
    $website_ad = $_POST['website_ad'];
    $sehir_id = $_POST['sehir_id'];
    $hakkinda = $_POST['hakkinda'];

    // Varsayılan resim yolu
    $resim_yolu = $_POST['existing_resim'];

    // Eğer yeni bir resim yüklenmişse, eski resmi güncelle
    if (isset($_FILES['uni_resimler1']) && $_FILES['uni_resimler1']['error'] === UPLOAD_ERR_OK) {
        $hedef_klasor = 'img/universiteler/';
        $dosya_adi = basename($_FILES['uni_resimler1']['name']);
        $hedef_dosya = $hedef_klasor . $dosya_adi;
        if (move_uploaded_file($_FILES['uni_resimler1']['tmp_name'], $hedef_dosya)) {
            $resim_yolu = $hedef_dosya;
        }
    }

    // Veritabanında güncelleme
    $stmt = $baglanti->prepare("UPDATE universiteler SET universite_ad = ?, universite_website = ?, website_ad = ?, sehir_id = ?, hakkımızda = ?, uni_resimler1 = ? WHERE u_id = ?");
    $stmt->execute([$universite_ad, $universite_website, $website_ad, $sehir_id, $hakkinda, $resim_yolu, $uni_id]);

    if ($stmt->rowCount()) {
        header("Location: universite_guncelle.php?uni_id=$uni_id&status=success");
        exit();
    } else {
        echo '<div class="alert alert-danger">Üniversite güncellenirken bir hata oluştu.</div>';
    }
}
?>

