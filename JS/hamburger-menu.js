document.addEventListener("DOMContentLoaded", function () {

    let button = document.createElement("button");

    button.innerText = "MENU";
    button.style.position = "fixed";
    button.style.top = "10px";
    button.style.left = "10px";
    button.style.padding = "5px 8px";
    button.style.zIndex = "9999";
    button.style.cursor = "pointer";

    document.body.appendChild(button);

    let menu = document.createElement("div");

    menu.innerHTML = `
        <a href="homepage.html">Home</a>
        <a href="Studentdashboard.html">Dashboard</a>
        <a href="Eventspage.html">Events</a>
        <a href="contacts.html">Contact</a>
        <a href="FAQ.html">FAQ</a>
        <a href="Feedback.html">Feedback</a>
        <a href="about.html">About</a>
    `;

    menu.style.position = "fixed";
    menu.style.top = "45px";
    menu.style.left = "10px";
    menu.style.display = "none";
    menu.style.backgroundColor = "white";
    menu.style.padding = "10px";
    menu.style.zIndex = "9998";

    menu.querySelectorAll("a").forEach(function (link) {
        link.style.display = "block";
        link.style.padding = "5px 10px";
        link.style.textDecoration = "none";
    });

    document.body.appendChild(menu);

    button.addEventListener("click", function () {

        if (menu.style.display == "none") {
            menu.style.display = "block";
        } else {
            menu.style.display = "none";
        }
        

    });

});