
document.addEventListener("DOMContentLoaded", function () {

    let loginForm = document.getElementById("loginForm");

    // Run only on pages that contain the login form.
    if (!loginForm) {
        return;
    }

    let loginId = document.getElementById("LoginId");
    let password = document.getElementById("password");

    loginForm.addEventListener("submit", function (event) {

        event.preventDefault();

        if (loginId.value.trim() === "" || password.value === "") {
            alert("Please fill all the fields!");
            return;
        }

        fetch("../api/login.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            credentials: "same-origin",
            body: JSON.stringify({
                loginId: loginId.value.trim(),
                password: password.value
            })
        })
        .then(async response => {
            let result;

            try {
                result = await response.json();
            } catch {
                throw new Error("Invalid server response. Check your PHP files.");
            }

            if (!response.ok) {
                throw new Error(
                    result.error || "Login could not be completed."
                );
            }

            return result;
        })
        
.then(result => {
    if (result.success && result.redirect) {
        alert("Login Successful!");
        window.location.href = result.redirect;
    } else {
        throw new Error("Login response is incomplete.");
    }
})

        .catch(error => {
            alert(error.message || "Could not connect to the server.");
        });

    });

    // Notify the user if the session timed out.
    let params = new URLSearchParams(window.location.search);

    if (params.get("timeout") === "1") {
        alert("Your session expired. Please log in again.");
    }

});
