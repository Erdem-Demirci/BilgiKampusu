<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);


    ob_start();
    session_start();
    require_once "baglanti.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['Kod']))
    {
    $email = $_POST['mail'];
    require 'phpmailer/src/Exception.php';
    require 'phpmailer/src/PHPMailer.php';
    require 'phpmailer/src/SMTP.php';
    $kod = rand(1000, 9999);
    
    $mail = new PHPMailer(true);

    if($email !="")
    {
        $email_sor = $baglanti->prepare('SELECT * FROM kullanicilar WHERE email=?');
        $email_sor->execute([$email]);
        $sonuc = $email_sor->rowCount();
        if($sonuc != 0)
        {   
            $hashed_kod = password_hash($kod, PASSWORD_DEFAULT);

            $sorgu = $baglanti->prepare('UPDATE kullanicilar SET unuttum=? WHERE email=?');
            $calis = $sorgu->execute([$hashed_kod, $email]);
            $mesaj = 'Merhaba değerli kullanıcımız, şifrenizi unuttuğunuza dair mesaj aldık. Tek kullanımlık kodunuz: ' . $kod;
            try {
                //Server settings
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = 'demircierdem14@gmail.com';                     //SMTP username
                $mail->Password   = 'mqenrrohzmoxawyx';                               //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                $mail->SMTPOptions = array(
                'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
                )
                );

                //Recipients
                $mail->setFrom('demircierdem14@gmail.com', 'Şifre Yenileme');
                $mail->addAddress($email, 'Erdem Demirci');     //Add a recipient
                
                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->CharSet = 'UTF-8';
                $mail->Subject = 'Doğrulama Kodu';
                $mail->Body    = $mesaj;
                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                $mail->send();
                //echo 'Message has been sent';

                $_SESSION['email'] = $email;
                Header('Location: kodgir.php?success=1');
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }

        }
        else
        {
            Header('Location: unuttum.php?danger=1');
            exit;
        }

    }
    
    
}
?>


<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unuttum</title>
    
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/all.min.css">
    <link rel="stylesheet" type="text/css" href="css/stil1.css">

   
    
    
</head>
<body class="bg-light">
   <div class="container giris-kayit">
       <div class="row" style="min-height: 100vh;justify-content: center;align-items: center;">
           <div class="col-lg-4 col-md-6 col-sm-8 bg-white m-auto rounded-top wrapper">
               <h2 class="text-center pt-3 mb-4">Şifremi Unuttum</h2>
                <?php if( isset($_GET['danger']) ): ?> 
                    <div class="alert alert-danger">Mesaj iletilmedi</div>
                <?php endif ?>
                <!-- Form start -->
                <form method="POST"  action="">
                    
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fa fa-envelope"> </i></span>
                        <input type="Email" class="form-control" placeholder="Email" name="mail" required>

                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" name="Kod" class="btn btn-success mb-3">Kod Gönder</button>
            
                    </div>
   
                </form>
           </div>
       
    </div>

   
<script type="text/javascript" src="js/script2.js"></script>   
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>


