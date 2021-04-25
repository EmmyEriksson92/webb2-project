<?php
//The script uploads products into the database.

// Include the database configuration file
include "../resources/dbconfig.php";

//If submitted create variables for wanted information, prevent SQL injections & remove html-tags from input.
if (isset($_POST["submit"])) {
    $name = mysqli_real_escape_string($link, strip_tags($_POST["pname"]));
    $code = mysqli_real_escape_string($link, strip_tags($_POST["code"]));
    $price = mysqli_real_escape_string($link, strip_tags($_POST["price"]));
    $file_tmp = mysqli_real_escape_string($link, strip_tags($_FILES["file"]["tmp_name"]));

    //If image is uploaded.
    if (is_uploaded_file($file_tmp)) {
        $mimetype = mime_content_type($file_tmp);
        $allowed = array("image/jpeg", "image/gif", "image/jpg", "image/png");
        header("content-type: text/plain");
        //Check if mimetype is correct. If correct upload file.
        if (in_array($mimetype, $allowed)) {
            $upload_directory = "../MyUploadImages/";
            $uploadfile = $upload_directory . basename($_FILES["file"]["name"]);
            //If uploading file to directory works insert product to the table.
            if (move_uploaded_file($file_tmp, $uploadfile)) {
                
                $query = "INSERT INTO products (`name`,code,price,`image`)
                VALUES ('$name','$code', $price, '$uploadfile')";

                //If insertion fails show error message.
                if (!mysqli_query($link, $query)) {
                    die("Ett fel uppstod när du skulle ladda upp din produkt i databasen.\n");
                }else{
                    echo "Produkten har nu laddats upp i databasen!";
                }
            } else {
                echo "Uppladdning misslyckades.";
            }
        }else{
            echo "Endast tillåtna filtyper är: image/jpeg, image/gif, image/jpg, image/png";
        }
    }
}
?>
