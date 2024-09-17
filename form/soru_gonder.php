<?php
session_start();

require_once "baglanti.php"; 

// Oturum kontrolü
if (!isset($_SESSION['username'])) {
    header("Location: giris.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment']) && isset($_POST['university_id'])) {
    $soru = $_POST['comment'];
    $username = $_SESSION['username'];
    
    // Kullanıcı bilgisini al
    $bul = $baglanti->prepare("SELECT * FROM kullanicilar WHERE kullanici_adi = ?");
    $bul->execute([$username]);
    $row4 = $bul->fetch(PDO::FETCH_ASSOC);
    $kullanici_id = $row4['id'];

    $universite_id = $_POST['university_id']; // Formdan üniversite id'sini al

    $puan = $baglanti->prepare("UPDATE kullanicilar SET puan = puan + 10 WHERE id = ?");
    $puan->execute([$kullanici_id]);

    // Soruyu veritabanına ekle
    $sorgu = $baglanti->prepare("INSERT INTO uni_sorular (soran_id, universite_id,  soru) VALUES (?, ?, ?)");
    $sorgu->execute([$kullanici_id, $universite_id, $soru]);

    if ($sorgu) {
        header("Location: universite.php?id=$universite_id"); // Kullanıcıyı üniversite sayfasına yönlendir
        exit;
    } else {
        echo "Soru eklenirken bir hata oluştu. Lütfen tekrar deneyin.";
    }

    $sorgu->close();
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['commentbolum']) && isset($_POST['bolum_id'])) {

    $soru = $_POST['commentbolum'];
    $username = $_SESSION['username'];
    
    // Kullanıcı bilgisini al
    $bul = $baglanti->prepare("SELECT * FROM kullanicilar WHERE kullanici_adi = ?");
    $bul->execute([$username]);
    $row4 = $bul->fetch(PDO::FETCH_ASSOC);
    $kullanici_id = $row4['id'];

    $bolum_id = $_POST['bolum_id'];

    $sorgu = $baglanti->prepare("INSERT INTO bolum_sorular (soran_id, bolum_id,  soru) VALUES (?, ?, ?)");
    $sorgu->execute([$kullanici_id, $bolum_id, $soru]);

    if ($sorgu) {
        header("Location: bolum.php?id=$bolum_id"); // Kullanıcıyı üniversite sayfasına yönlendir
        exit;
    } else {
        echo "Soru eklenirken bir hata oluştu. Lütfen tekrar deneyin.";
    }

    $sorgu->close();
    
}else {
    header("Location: index.php"); // Kullanıcıyı anasayfaya yönlendir
    exit;
}
?>


