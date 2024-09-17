<?php
  session_start();

 // Oturumu sonlandır ve kullanıcıyı giriş sayfasına yönlendir
  $_SESSION = array();
  session_destroy();
  Header("location:admin_giris.php");
  exit();
?>