<?php
//The script takes input from user to reset password & send a confirmation email back to user when done.
 require "../PHPMailer/PHPMailerAutoload.php";
 $mail = new PHPMailer(true);
session_start();
include "../resources/dbconfig.php";

if (isset($_POST["action"]) && ($_POST["action"] == "update")) {
    $pass1 = mysqli_real_escape_string($link, $_POST["pass1"]);
    $pass2 = mysqli_real_escape_string($link, $_POST["pass2"]);
    $email = $_SESSION["email"];

    //If passwords dont match show error message on page.
    if ($pass1 != $pass2) {
        $error = "Lösenorden matchar ej. Båda lösenorden måste stämma överens";
        $html = file_get_contents("resetPassword.html");
        $html = str_replace("---errors---", $error, $html);
        echo $html;
    }else{ 

        $pass1 = hash("sha512", $pass1); //Enctrypt password with sha512 to store password more safely.

        try {
          
          // Start transaction.
            $link->begin_transaction();

            //Update the password.
            mysqli_query($link, "UPDATE `users` SET `password`='" . $pass1 . "'WHERE `email`='$email';");

            //Delete the temporary record for reseting password.
            mysqli_query($link, "DELETE FROM password_reset_temp WHERE email='$email';");

            //End transaction if no errors.
            $link->commit();
            header("Location: ..Home/index.html");
        } catch (mysqli_sql_exception $exception) {
            mysqli_rollback($link);

            throw $exception;
        }

        /* $mail->isSMTP();    */ /* <--This is commented away as its not working on dsv apache server only on localhost. Tried every port. */
        //Send user confirmation email that password has been reset.
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->Username = "emmyveriksson@gmail.com";
        $mail->Password = "Depechie92";
        $mail->SMTPSecure = "tls";
        $output = "<p>Hej användare,</p>";
        $output .= "<p>Ditt lösenord har nu blivit återställt!.</p>";
        $output.="<p>Om det inte var du kan det vara bra att byta lösenordet på ditt konto.</p>"; 
        $output .= '<p>-------------------------------------------------------------</p>';
        $output .= "<p>Vänligen,</p>";
        $output .= "<p>Skin deluxe.</p>";
        $subject = "Bekräftelsemejl återställning av lösenord.";
        $body = $output;
        $email_to = $email;
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->IsHTML(true);
        $mail->AddAddress($email);
        if (!$mail->Send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            //Redirect user to homepage when email sent.
            header("Location: https://people.dsv.su.se/~emer4231/Webbutv%202/Prov/Home/index.html");
        }

    }
}
?>
