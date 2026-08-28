function checkLogin() {

    let loginID = document.getElementById("loginID").value;
    let password = document.getElementById("password").value;

    if (loginID == "" || password == "") {
        alert("Please fill all the fields!");
        return false;
    }

    alert("Login Successful!");
    return true;
}