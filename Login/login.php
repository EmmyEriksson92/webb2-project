<?php
session_start();
include "../resources/dbconfig.php";
$error = "";

//Logout user automatically after 15 minutes.
if (isset($_SESSION["isLoggedIn"])) {
    if ((time() - $_SESSION["last_login_timestamp"]) > 900) {
        header("Location: https://people.dsv.su.se/~emer4231/Webbutv%202/Prov/Profile/logout.php");
    }
}

//Create variables for wanted information, prevent SQL injections & remove html-tags.
if (isset($_POST["submit"])) {
    $username = mysqli_real_escape_string($link, strip_tags($_POST["username"]));
    $password = mysqli_real_escape_string($link, strip_tags($_POST["password"]));

    //Check if user already is logged in.
    if (isset($_SESSION["isLoggedIn"]) && $_SESSION["username"] == $username) {
        $error = "Du är redan inloggad med användarnamnet: $username. Du kommer att dirigeras till startsidan om några sekunder.";
        $html = file_get_contents("login.html");
        $html = str_replace("---errors---", $error, $html);
        echo $html;
        header("refresh:2;url=https://people.dsv.su.se/~emer4231/Webbutv%202/Prov/Home/index.html");
    }

    //If no errors login user.
    if ($error == "") {

        $password = hash("sha512", $password); //Enctrypt password with sha512 to store password more safely.

        $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $results = mysqli_query($link, $query);

        //If loggging in succeed create a timestamp for when user logged in and add username to a new session.
        if (mysqli_num_rows($results) == 1) {
            $_SESSION["username"] = $username;
            $_SESSION["last_login_timestamp"] = time();
            $_SESSION["isLoggedIn"] = true;
            header("Location: https://people.dsv.su.se/~emer4231/Webbutv%202/Prov/Home/index.html");

        } else {
            $error = "Fel användarnamn/lösenord.";
            $html = file_get_contents("login.html");
            $html = str_replace('---errors---', $error, $html);
            echo $html;
            header("refresh:2;url=login.html");
        }
    }
}
?>