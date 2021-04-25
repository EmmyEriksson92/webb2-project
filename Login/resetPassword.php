<?php
session_start();
include("../resources/dbconfig.php");
$error = "";
if (isset($_GET["key"]) && isset($_GET["email"]) && isset($_GET["action"]) 
&& ($_GET["action"]=="reset")){
  $key = $_GET["key"];
  $email = $_GET["email"];
  $curDate = date("Y-m-d H:i:s");
  $query = mysqli_query($link,
  "SELECT * FROM `password_reset_temp` WHERE `key`='".$key."' and `email`='".$email."';"
  );
  $row = mysqli_num_rows($query);
  if ($row==""){
  $error = "Felaktig länk.Länken är inaktiv. Antingen kopierade du fel länk eller så har länken utgått.";
 }else{
  $row = mysqli_fetch_assoc($query);
  $expDate = $row['expDate'];
  if ($expDate >= $curDate){
     $_SESSION["email"] = $email;
    header("Location: resetPassword.html");

}else{
$error .= "Länken är inaktiv.Länken du använt har utgått. Du har försökt använda en länk som utgår efter 24 timmar.(1 days after request).";
            }
      }
if($error!=""){
  echo $error . " Du kommer att dirigeras igen till sidan för återställning av lösenord om några sekunder. Där kan du skriva in din email igen.";
  header("refresh:3;url=forgotPassword.html");

  }
}
 

?>