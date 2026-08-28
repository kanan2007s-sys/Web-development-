document.addEventListener("DOMContentLoaded", function () {

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
    banner.style.marginTop = "60px";
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