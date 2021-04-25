<?php

//If quantity is changed set the new quantity in session for quantity & total.
if (isset($_POST["quantity"]) && $_POST["action"] == "change") {
    foreach ($_SESSION["shopping_cart"] as $value) {
        if ($value["code"] === $_POST["code"]) {
            $newQuantity = $_POST["quantity"];
            $total = ($value["price"] * $newQuantity);
            break;
        }
    }
    $_SESSION["shopping_cart"][$_REQUEST["code"]]["quantity"] = $newQuantity;
    $_SESSION["shopping_cart"][$_REQUEST["code"]]["total"] = $total;
}

//Remove product from cart.
$status = "";
if (isset($_POST["action"]) && $_POST["action"] == "remove") {
    if (!empty($_SESSION["shopping_cart"])) {
        foreach ($_SESSION["shopping_cart"] as $key => $value) {
            if ($_POST["code"] == $key) {
                unset($_SESSION["shopping_cart"][$key]);
                if (empty($_SESSION["shopping_cart"])) {
                    unset($_SESSION["shopping_cart"]);
                }
                header("location:displayCart.php");
            }
        }
    }
}
?>
