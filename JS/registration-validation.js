document.addEventListener("DOMContentLoaded", function () {

    let form = document.getElementById("registrationForm");
    let name = document.getElementById("name");
    let enrollment = document.getElementById("enrollment");
    let email = document.getElementById("email");
    let mobile = document.getElementById("mobile");
    let password = document.getElementById("password");
    let confirmPassword = document.getElementById("confirmPassword");
    let course = document.getElementById("course");
    let year = document.getElementById("year");
    let terms = document.getElementById("terms");
    let strength = document.getElementById("strength");

    let nameRegex = /^[A-Za-z ]{3,}$/;
    let enrollmentRegex = /^D?\d{2}DCE\d{3}$/;
    let emailRegex = /^[a-zA-Z0-9]+@charusat\.edu\.in$/i;
    let mobileRegex = /^[6-9]\d{9}$/;
    let passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@$!%*?&]).{8,}$/;

    let users = [];

    fetch("../JSON/registration.json")
        .then(response => response.json())
        .then(data => {
            users = data;
        })
        .catch(error => {
            console.log("Error loading registration.json:", error);
        });

    form.addEventListener("submit", function (event) {

        event.preventDefault();

        let valid = true;

        document.querySelectorAll("span").forEach(function (span) {
            span.innerText = "";
        });

        if (name.value.trim() == "") {
            document.getElementById("nameError").innerText = "Name is required";
            valid = false;
        } else if (!nameRegex.test(name.value.trim())) {
            document.getElementById("nameError").innerText =
                "Name must contain only letters and spaces, minimum 3 characters";
            valid = false;
        }

        if (enrollment.value.trim() == "") {
            document.getElementById("idError").innerText = "Enrollment ID is required";
            valid = false;
        } else if (!enrollmentRegex.test(enrollment.value.trim())) {
            document.getElementById("idError").innerText =
                "Enrollment ID must be in the format 25DCE001 or D26DCE126";
            valid = false;
        }

        if (email.value.trim() == "") {
            document.getElementById("emailError").innerText = "Email is required";
            valid = false;
        } else if (!emailRegex.test(email.value.trim())) {
            document.getElementById("emailError").innerText =
                "Enter a valid university email (e.g. name@charusat.edu.in)";
            valid = false;
        }

        if (mobile.value.trim() == "") {
            document.getElementById("mobileError").innerText =
                "Mobile number is required";
            valid = false;
        } else if (!mobileRegex.test(mobile.value.trim())) {
            document.getElementById("mobileError").innerText =
                "Mobile number must be exactly 10 digits and start with 6-9";
            valid = false;
        }

        if (password.value == "") {
            document.getElementById("passwordError").innerText =
                "Password is required";
            valid = false;
        } else if (!passwordRegex.test(password.value)) {
            document.getElementById("passwordError").innerText =
                "Password must be at least 8 characters and include uppercase, lowercase, number and special character";
            valid = false;
        }

        if (confirmPassword.value == "") {
            document.getElementById("confirmPasswordError").innerText =
                "Please confirm your password";
            valid = false;
        } else if (password.value != confirmPassword.value) {
            document.getElementById("confirmPasswordError").innerText =
                "Passwords do not match";
            valid = false;
        }

        if (course.value == "") {
            document.getElementById("courseError").innerText =
                "Please select a course";
            valid = false;
        }

        if (year.value == "") {
            document.getElementById("yearError").innerText =
                "Please select a year";
            valid = false;
        }

        let gender = document.querySelector('input[name="gender"]:checked');

        if (!gender) {
            document.getElementById("genderError").innerText =
                "Please select your gender";
            valid = false;
        }

        if (!terms.checked) {
            document.getElementById("termsError").innerText =
                "Please accept the terms and conditions";
            valid = false;
        }

        if (valid) {
            alert("Registration Successful!");
            window.location.href = "../HTML/homepage.html";
        }
    });

    password.addEventListener("keyup", function () {

        let value = password.value;

        if (value == "") {
            strength.innerText = "Password Strength: Not entered";
        } else if (value.length < 6) {
            strength.innerText = "Password Strength: Weak";
        } else if (
            value.length >= 8 &&
            /[A-Z]/.test(value) &&
            /[a-z]/.test(value) &&
            /[0-9]/.test(value) &&
            /[@$!%*?&]/.test(value)
        ) {
            strength.innerText = "Password Strength: Strong";
        } else {
            strength.innerText = "Password Strength: Medium";
        }
    });

    form.addEventListener("reset", function () {

        setTimeout(function () {

            document.querySelectorAll("span").forEach(function (span) {
                span.innerText = "";
            });

            strength.innerText = "";

        }, 0);
    });

});