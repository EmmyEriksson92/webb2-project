<?php
//The script creates a session for shopping cart containing an cart array of values from products table.
session_start();
include "../resources/dbconfig.php";
$status = "";

if (isset($_POST["code"])){

$code = $_POST["code"];

//Get product that is selected by its product code.
$result1 = mysqli_query($link,"SELECT * FROM products WHERE code='$code'");
$row = mysqli_fetch_assoc($result1);
$name = $row["name"];
$code = $row["code"];
$price = $row["price"];
$image = $row["image"];
 
//Create an array for selected product so it can be saved in a session.
$cartArray = array($code=>array("name"=>$name,"code"=>$code,"price"=>$price,"quantity"=>1,"image"=>$image,"total"=>$price*1,));
 
if(empty($_SESSION["shopping_cart"])) {
    $_SESSION["shopping_cart"] = $cartArray;
    $status = "Produkten har lagts in i din kundkorg!";
}else{
    //Check if product is already added do cart.
    $array_keys = array_keys($_SESSION["shopping_cart"]);
    if(in_array($code,$array_keys)) {
 $status = "Produkten ligger redan i din kundkorg!"; 
    } else {
        //Add product to session,
    $_SESSION["shopping_cart"] = array_merge($_SESSION["shopping_cart"], $cartArray);
    $status = "Produkten har lagts in i din kundkorg!";
 }
 
 }
}
?>