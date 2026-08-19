document.getElementById("Myform").addEventListener("submit", function(event) {
    // 1. Stop the browser from instantly reloading or redirecting
    event.preventDefault(); 

    // 2. Grab the text inputs and strip out whitespace spaces
    let login = document.getElementById("login").value.trim();
    let password = document.getElementById("password").value.trim();

    // 3. Strict check for completely empty strings
    if (login === "" || password === "") {
        alert("Please fill in the required field");
        return; // Stops the code here completely
    }

    // 4. This only runs if BOTH inputs have valid text inside them
    alert("Form submitted successfully...");
    window.location.href = "homepage.html";
});
