<?php
session_start();

//Read html-file into a string.
$html = file_get_contents("displayCart.html");

//Split this string into serveral parts, creating an array of strings.
$html_pieces = explode("<!--===entries===-->", $html);

//Write out first substring.
echo $html_pieces[0];

$sixth_string = $html_pieces[5];

//Get cart by its session & display cart if not empty.
if (isset($_SESSION["shopping_cart"]) && count($_SESSION["shopping_cart"]) !== 0) {
    include "handleCart.php";
    $totalPrice = 0;
    $newQuantity = 0;
    $second_string = $html_pieces[1];
    $third_string = $html_pieces[2];
    $forth_string = $html_pieces[3];
    echo $second_string;
 
    foreach ($_SESSION["shopping_cart"] as $product) {
        $string = str_replace("---name---", $product["name"], $third_string);
        $string = str_replace("---image---", $product["image"], $string);
        $string = str_replace("---code---", $product["code"], $string);
        $string = str_replace("---price---", $product["price"], $string);
        $string = str_replace("---totalPrice---", $product["total"], $string);
        $string = str_replace("Välj här", $product["quantity"], $string);
        $totalPrice += $product["total"];
        $show_total = str_replace("---total---", $totalPrice, $forth_string);

        echo $string;
    
    }

    echo $show_total;
    echo $sixth_string;

} else {
    //Show message if cart is empty.
    $empty = "Din kundvagn är tom!";

    //Initialize fifth substring.
    $fifth_string = $html_pieces[4];
    $show_empty_cart = str_replace("---empty---", $empty, $fifth_string);
    //Echo empty cart and substring.
    echo $show_empty_cart;
    echo $sixth_string;
}
