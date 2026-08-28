let banner = document.createElement("div");

banner.innerText = "New event notifications available!";

banner.style.padding = "10px";
banner.style.textAlign = "center";
banner.style.backgroundColor = "lightblue";

document.body.insertBefore(banner, document.body.firstChild);

setTimeout(function() {
    banner.remove();
}, 5000);