<?php

//Call session_start() to access session.
session_start();

//If user is logged in logout user.
if (isset($_SESSION["isLoggedIn"])) {
    unset($_SESSION["isLoggedIn"]);

//Send user to loginpage.
    header("Location: https://people.dsv.su.se/~emer4231/Webbutv%202/Prov/Login/login.html");

}
?>
