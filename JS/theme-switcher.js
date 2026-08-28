document.addEventListener("DOMContentLoaded", function () {

    let button = document.createElement("button");

    button.innerText = "Dark Mode";
    button.style.position = "fixed";
    button.style.top = "10px";
    button.style.right = "10px";
    button.style.zIndex = "9999";

    document.body.appendChild(button);

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

        button.innerText = "Light Mode";
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

        button.innerText = "Dark Mode";
    }

    if (localStorage.getItem("theme") == "dark") {
        makeDark();
    }

    button.addEventListener("click", function () {

        if (localStorage.getItem("theme") == "dark") {
            makeLight();
            localStorage.setItem("theme", "light");
        } else {
            makeDark();
            localStorage.setItem("theme", "dark");
        }

    });

});