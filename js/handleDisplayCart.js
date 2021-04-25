//When continue shopping button is clicked redirect to display product page.
$("#contShop").on("click", function() {
    window.open("../Products/displayProducts.php");
});


//When quantity is changed submit the change.
$("form select").on("change", function() {
    $(this).closest("form").submit();
});