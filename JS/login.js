document.getElementById("Myform").addEventListener("submit", function(event) {
    event.preventDefault(); 

    let login = document.getElementById("login").value.trim();
    let password = document.getElementById("password").value.trim();

    if (login === "" || password === "") {
        alert("Please fill in the required field");
        return;
    }
        localStorage.setItem("savedUsername",login);    


    alert("Form submitted successfully...");
    window.location.href = "homepage.html";
});
