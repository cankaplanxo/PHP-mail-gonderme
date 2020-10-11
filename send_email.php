<?php

session_start();
error_reporting((E_ERROR));

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "vendor/autoload.php";

if (isset($_POST)){

    if($_POST ["to_email"] && $_POST["sender"]&& $_POST["subject"]&& $_POST["message"]) {

        //mail gönderme işlemi gerçekleştir...
        $file = $_FILES["attachment"];

        if (move_uploaded_file($file["tmp_name"],"files/".$file["name"])){

            $mail =new PHPMailer(true);

            try{

                //server ayarları
                $mail->SMTPDebug=0;
                $mail->isSMTP();
                $mail->Host="ssl://smtp.gmail.com";
                $mail->SMTPAuth=true;
                $mail->Username = "BURAYA KENDİ MAİL ADRESİNİZİ YAZINIZ";                 // SMTP username
                $mail->Password = "BURAYA KENDİ MAİL ADRESİNİZİN ŞİFRESİNİ YAZINIZ";                           // SMTP password
                $mail->CharSet="utf8";
                $mail->SMTPSecure="tls";
                $mail->Port=465;

                //alıcı ayarları
                $mail->setFrom("BURAYA KENDİ MAİL ADRESİNİZİ YAZINIZ",$_POST ["sender"]);
                $mail-> addAddress($_POST["to_email"],"");
                $mail->addAttachment("files/" .$file["name"]);


                //gönderi ayarları
                $mail->isHTML();
                $mail->Subject=$_POST["subject"];
                $mail->Body=$_POST["message"];

                if ($mail->send()){

                    $alert = array(
                        "message" => "Mail başarılı bir şekilde gönderildi!",
                        "type" => "success"
                    );

                }else{
                    $alert = array(
                        "message" => "Mail gönderirken bir hata oluştu!",
                        "type" => "danger"
                    );

                }
                header("location:index.php");

            } catch (Exception $e){

                $alert = array(
                    "message" => $e ->getMessage(),
                    "type" => "danger"
                );

            }


        }else {
            $alert = array(
                "message" => "Dosya yüklenirken bir hata oluştu!",
                "type" => "danger"
            );
        }

    } else{

    $alert = array(
        "message" => "Lütfen tüm alanları doldurunuz!",
        "type" => "danger"
    );
    }

$_SESSION["alert"]= $alert;
header("location:index.php");

}