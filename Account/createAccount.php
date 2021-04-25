<?php
//Start a session.
session_start();
include "../resources/dbconfig.php";

// initializing variables
$username = "";
$email = "";
$errors = "";

//Create variables for wanted information & prevent SQL injections.
if (isset($_POST["submit"])) {
    $username = mysqli_real_escape_string($link, strip_tags($_POST["username"]));
    $password_1 = mysqli_real_escape_string($link, strip_tags($_POST["password_1"]));
    $password_2 = mysqli_real_escape_string($link, strip_tags($_POST["password_2"]));
    $email = mysqli_real_escape_string($link, strip_tags($_POST["email"]));

    //Validate if passwords are matching.
    if ($password_1 != $password_2) {
        $errors = "De två lösenorden matchar ej. Skriv in samma lösenord två gånger. Försök igen.\n";
    }

    //Check the database if user with the same username or email aleady exists.
    $query = "SELECT * FROM users WHERE username='$username' OR email='$email'";
    $result = mysqli_query($link, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user) { //If user exists show error.
        if ($user["username"] === $username) {
            $errors.="Användarnamnet existerar redan.\n";
        }

        //If email already exists show error.
        if ($user["email"] === $email) {
            $errors.= "Email existerar redan.\n";
        }
    }

    //Register user if there are no errors in the form.
    if ($errors == "") {
        $password = hash("sha512", $password_1); //Enctrypt password with sha512 to store password more safely.

        //Insert data into table users.
        $query = "INSERT INTO users (username, email,`password`)
        VALUES ('$username', '$email', '$password')";
        mysqli_query($link, $query);
        $_SESSION["username"] = $username;
        $_SESSION["last_login_timestamp"] = time();
        $_SESSION["isLoggedIn"] = true;
        header("Location: https://people.dsv.su.se/~emer4231/Webbutv%202/Prov/Home/index.html"); //redirect to homepage when registration is finished and user is logged in.
    }else{
        //if errors exists echo errors.
        $html = file_get_contents("createAccount.html");
            $html = str_replace("---errors---", $errors, $html);
            echo $html;
    }
}


?>
