<?php
include "../resources/dbconfig.php";

 //Display all products from the database.
$result = mysqli_query($link,"SELECT * FROM `products`");
//Get number of rows from table.
$rowcount = mysqli_num_rows($result);

  //Read html-file into a string.
  $html = file_get_contents("displayProducts.html");

  //Split this string into serveral parts, creating an array of strings.
  $html_pieces = explode("<!--===entries===-->", $html);

  //Write out first substring.
  echo $html_pieces[0];

  //Initialize second substring.
  $second_string = $html_pieces[1];

  $third_string = $html_pieces[2];
  
//Check if rows exists.
if ($rowcount) {
//Show all the products from the database.
while($row = mysqli_fetch_row($result)){
    $string = str_replace("---name---", $row[1], $second_string);
    $string = str_replace("---code---", $row[2], $string);
    $string= str_replace("---price---", $row[3], $string);
    $string = str_replace("---image---", $row[4], $string);
    echo $string;
}

include "index.php";
 $show_status = str_replace("---status---", $status, $third_string);
echo $show_status;
$forth_string = $html_pieces[3];
echo $forth_string;
 

}
mysqli_close($link);

?>