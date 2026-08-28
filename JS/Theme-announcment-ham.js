document.addEventListener("DOMContentLoaded", function () {

    // =========================
    // DARK / LIGHT MODE
    // =========================

    let themeButton = document.createElement("button");

    themeButton.innerText = "Dark Mode";
    themeButton.style.position = "fixed";
    themeButton.style.top = "10px";
    themeButton.style.right = "10px";
    themeButton.style.zIndex = "9999";

    document.body.appendChild(themeButton);

    function makeDark() {
        document.body.style.backgroundImage = "none";
        document.body.style.backgroundColor = "#222";
        document.body.style.color = "white";

        document.querySelectorAll("table").forEach(function (table) {
            table.style.backgroundColor = "#333";
            table.style.color = "white";
        });

        document.querySelectorAll("input, textarea, select").forEach(function (element) {
            element.style.backgroundColor = "#444";
            element.style.color = "white";
        });

        document.querySelectorAll("a").forEach(function (link) {
            link.style.color = "#8ab4f8";
        });

        themeButton.innerText = "Light Mode";
    }

    function makeLight() {
        document.body.style.backgroundImage = "";
        document.body.style.backgroundColor = "";
        document.body.style.color = "";

        document.querySelectorAll("table").forEach(function (table) {
            table.style.backgroundColor = "";
            table.style.color = "";
        });

        document.querySelectorAll("input, textarea, select").forEach(function (element) {
            element.style.backgroundColor = "";
            element.style.color = "";
        });

        document.querySelectorAll("a").forEach(function (link) {
            link.style.color = "";
        });

        themeButton.innerText = "Dark Mode";
    }

    if (localStorage.getItem("theme") == "dark") {
        makeDark();
    }

    themeButton.addEventListener("click", function () {

        if (localStorage.getItem("theme") == "dark") {
            makeLight();
            localStorage.setItem("theme", "light");
        } else {
            makeDark();
            localStorage.setItem("theme", "dark");
        }

    });


    // =========================
    // HAMBURGER MENU
    // =========================

    let menuButton = document.createElement("button");

    menuButton.innerText = "MENU";
    menuButton.style.position = "fixed";
    menuButton.style.top = "10px";
    menuButton.style.left = "10px";
    menuButton.style.padding = "5px 8px";
    menuButton.style.zIndex = "9999";
    menuButton.style.cursor = "pointer";

    document.body.appendChild(menuButton);

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

    menuButton.addEventListener("click", function () {

        if (menu.style.display == "none") {
            menu.style.display = "block";
        } else {
            menu.style.display = "none";
        }

    });


    // =========================
    // ANNOUNCEMENT SLIDER
    // =========================

    let announcements = [
        "Welcome to CHARUSAT Student Portal",
        "Check your latest examination results",
        "Don't forget to check your attendance",
        "New events and workshops are available"
    ];

    let index = 0;

    let banner = document.createElement("div");

    banner.innerText = announcements[index];
    banner.style.textAlign = "center";
    banner.style.padding = "10px";
    banner.style.margin = "10px";
    banner.style.fontSize = "18px";
    banner.style.fontWeight = "bold";

    document.body.insertBefore(banner, document.body.firstChild);

    setInterval(function () {

        index++;

        if (index >= announcements.length) {
            index = 0;
        }

        banner.innerText = announcements[index];

    }, 2000);

});