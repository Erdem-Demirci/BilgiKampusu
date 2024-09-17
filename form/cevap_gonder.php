<?php
session_start();

require_once "baglanti.php"; 

// Oturum kontrolü
if (!isset($_SESSION['username'])) {
    header("Location: giris.php");
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cevap']) && isset($_POST['soru_id'])) {

    $cevap = $_POST['cevap'];
    $username = $_SESSION['username'];
    $universite_id = $_POST['universite_id'];

    // Kullanıcı bilgisini al
    $bul = $baglanti->prepare("SELECT * FROM kullanicilar WHERE kullanici_adi = ?");
    $bul->execute([$username]);
    $row4 = $bul->fetch(PDO::FETCH_ASSOC);
    $kullanici_id = $row4['id'];

    $soru_id = $_POST['soru_id'];

    $puan = $baglanti->prepare("UPDATE kullanicilar SET puan = puan + 20 WHERE id = ?");
    $puan->execute([$kullanici_id]);

    // Soruyu veritabanına ekle
    $sorgu = $baglanti->prepare("INSERT INTO uni_cevaplar (soru_id, cevapveren_id,  cevap) VALUES (?, ?, ?)");
    $sorgu->execute([$soru_id, $kullanici_id, $cevap]);

    if ($sorgu) {
        header("Location: universite.php?id=$universite_id"); // Kullanıcıyı üniversite sayfasına yönlendir
        exit;
    } else {
        echo "Soru eklenirken bir hata oluştu. Lütfen tekrar deneyin.";
    }

    $sorgu->close();
}elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cevapbolum']) && isset($_POST['soru_id'])) {
    $cevap = $_POST['cevapbolum'];
    $username = $_SESSION['username'];
    $bolum_id = $_POST['bolum_id'];

    // Kullanıcı bilgisini al
    $bul = $baglanti->prepare("SELECT * FROM kullanicilar WHERE kullanici_adi = ?");
    $bul->execute([$username]);
    $row4 = $bul->fetch(PDO::FETCH_ASSOC);
    $kullanici_id = $row4['id'];

    $soru_id = $_POST['soru_id'];

    // Soruyu veritabanına ekle
    $sorgu = $baglanti->prepare("INSERT INTO bolum_cevaplar (soru_id, cevap_veren_id,  cevap) VALUES (?, ?, ?)");
    $sorgu->execute([$soru_id, $kullanici_id, $cevap]);

    if ($sorgu) {
        header("Location: bolum.php?id=$bolum_id"); // Kullanıcıyı üniversite sayfasına yönlendir
        exit;
    } else {
        echo "Soru eklenirken bir hata oluştu. Lütfen tekrar deneyin.";
    }
}else {
    header("Location: index.php"); // Kullanıcıyı anasayfaya yönlendir
    exit;
}




