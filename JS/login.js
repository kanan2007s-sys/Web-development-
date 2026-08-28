document.addEventListener("DOMContentLoaded", function () {

    let loginId = document.getElementById("LoginId");
    let password = document.getElementById("password");
    let submit = document.querySelector("a[href='homepage.html']");

    submit.addEventListener("click", function (event) {

        if (loginId.value == "" || password.value == "") {

            event.preventDefault();

            alert("Please fill all the fields!");

        } else {

            alert("Login Successful!");

        }

    });

});