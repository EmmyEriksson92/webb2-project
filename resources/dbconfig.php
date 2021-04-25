<?php
$servername = "localhost";
$username = "usr_20458025";
$password = "458025";
$dbname = "db_20458025";

// Create connection to the database.
$link = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if ($link->connect_errno) {
    echo "Failed to connect to MySQL: " . $link->connect_error;
    exit();
}
?>
