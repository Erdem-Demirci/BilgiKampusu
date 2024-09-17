<?php
session_start();
require_once "baglanti.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bolum_id = $_POST['bolum_id'];
    $bolum_ad = $_POST['bolum_ad'];
    $bilgi = $_POST['bilgi'];

    // Varsayılan resim yolu
    $resim_yolu = $_POST['existing_resim'];

    // Eğer yeni bir resim yüklenmişse, eski resmi güncelle
    if (isset($_FILES['bolum_resim']) && $_FILES['bolum_resim']['error'] === UPLOAD_ERR_OK) {
        $hedef_klasor = 'img/bolumler/';
        $dosya_adi = basename($_FILES['bolum_resim']['name']);
        $hedef_dosya = $hedef_klasor . $dosya_adi;
        if (move_uploaded_file($_FILES['bolum_resim']['tmp_name'], $hedef_dosya)) {
            $resim_yolu = $hedef_dosya;
        }
    }

    // Veritabanında güncelleme
    $stmt = $baglanti->prepare("UPDATE bolumler SET bolum_ad = ?, bilgi = ?, bolum_resimler = ? WHERE id = ?");
    $stmt->execute([$bolum_ad, $bilgi, $resim_yolu, $bolum_id]);

    if ($stmt->rowCount()) {
        header("Location: admin_bolum.php?bolum_id=$bolum_id&status=success");
        exit();
    } else {
        echo '<div class="alert alert-danger">Bölüm güncellenirken bir hata oluştu.</div>';
    }
}
?>

