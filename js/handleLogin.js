//If no errors is present hide text.
if ($("#errors").text() === "---errors---") {
    $("#errors").hide();
}

//When register-button is clicked redirect to create account page.
$("#register-button").click(function() {
    let url = "../Account/createAccount.html";
    window.open(url, "", "");
});