<?php
//Call session_start() to access session.
session_start();

//If the user has been logged in for 15 minutes logout user.
 if (isset($_SESSION["isLoggedIn"])) {
    if ((time() - $_SESSION["last_login_timestamp"]) > 900) {
        header("Location: logout.php");
    } else {
        $_SESSION["last_login_timestamp"] = time();

    }
}

//Send user to profile page if logged in & show username.
if (isset($_SESSION["isLoggedIn"])) {
    $show_username = file_get_contents("profile.html");
    $show_username = str_replace("hit", $_SESSION["username"], $show_username);
    echo $show_username;

} else {
    //else redirect user to login page.
    header("Location: https://people.dsv.su.se/~emer4231/Webbutv%202/Prov/Login/login.html");
}
?>
