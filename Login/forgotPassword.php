<?php
require "../PHPMailer/PHPMailerAutoload.php";
$mail = new PHPMailer(true);
include "../resources/dbconfig.php";
$error = "";

if (isset($_POST["submit"])) {
    // Remove all illegal characters from email.
    $email = $_POST["email"];
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    // Validate e-mail.
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "$email är inte en korrekt email. Försök igen";
    } else {
        $query = "SELECT * FROM `users` WHERE email='" . $email . "'";
        $results = mysqli_query($link, $query);
        $row = mysqli_num_rows($results);
        if ($row == "") {
            $error .= "Ingen registrerad användare finns på denna e-postadress!";
        }
    }

    //If errors exists echo errors.
    if ($error != "") {
        $show_error = file_get_contents("forgotPassword.html");
        $show_error = str_replace("---errors---", $error, $show_error);
        echo $show_error;
    } else {
        //Set timezone & date.
        date_default_timezone_set("Europe/Stockholm");

        //Set expiration date for now + 1 day(24 hours).
        $formatDate = mktime(
            date("H"), date("i"), date("s"), date("m"), date("d") + 1, date("Y")
        );
        $expDate = date("Y-m-d H:i:s", $formatDate);

        //Creates a random unique key.
        $random = random_bytes(20);
        $key = bin2hex($random);

        // Insert into a temporary table.
        mysqli_query($link,
            "INSERT INTO `password_reset_temp` (`email`, `key`, `expDate`)
VALUES ('" . $email . "', '" . $key . "', '" . $expDate . "');");

//Send reset email to users mail.
        /* $mail->isSMTP(); *///* <--This is commented away as its not working on dsv apache server only on localhost. Tried every port. */
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->Username = 'emmyveriksson@gmail.com';
        $mail->Password = 'Depechie92';
        $mail->SMTPSecure = 'tls';
        $output = '<p>Hej användare!</p>';
        $output .= '<p>Vänligen klicka på följande länk för att återställa ditt lösenord.</p>';
        $output .= '<p>-------------------------------------------------------------</p>';
        $output .= '<p><a href="https://people.dsv.su.se/~emer4231/Webbutv%202/Prov/Login/resetPassword.php?key=' . $key . '&email=' . $email . '&action=reset"target="_blank">https://people.dsv.su.se/~emer4231/Webbutv%202/Prov/Login/resetPassword.php?key=' . $key . '&email=' . $email . '&action=reset</a></p>';
        $output .= '<p>-------------------------------------------------------------</p>';
        $output .= '<p>Vänligen se till att kopiera hela länken till din browser.
            Länken kommer att bli inaktiv efter ett dygn pågrund av säkerhetsskäl.</p>';
        $output .= '<p>Om du inte utförde denna åtgärd behöver du inte göra något. Ditt lösenord kommer ej att återställas.
            Det kan dock vara bra att byta lösenordet utifall någon har kommit över ditt lösenord.</p>';
        $output .= '<p>Tackar vänligen,</p>';
        $output .= '<p>Skin deluxe.</p>';
        $subject = 'Lösenordsåterställning.';
        $body = $output;
        $email_to = $email;
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->IsHTML(true);
        $mail->AddAddress($email_to);
        if (!$mail->Send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            //Redirect user to homepage after success of sending email.
            header("Location: https://people.dsv.su.se/~emer4231/Webbutv%202/Prov/Home/index.html");
        }

    }
}
?>