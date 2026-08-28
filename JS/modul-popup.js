document.addEventListener("DOMContentLoaded", function () {

    let links = document.querySelectorAll("a");

    links.forEach(function (link) {

        if (link.href.match(/\.(pdf|jpg|jpeg|png)$/i)) {

            link.addEventListener("click", function (event) {

                event.preventDefault();

                let popup = document.createElement("div");

                popup.style.position = "fixed";
                popup.style.top = "0";
                popup.style.left = "0";
                popup.style.width = "100%";
                popup.style.height = "100%";
                popup.style.backgroundColor = "rgba(0,0,0,0.6)";
                popup.style.display = "flex";
                popup.style.justifyContent = "center";
                popup.style.alignItems = "center";
                popup.style.zIndex = "9999";

                let box = document.createElement("div");

                box.style.backgroundColor = "white";
                box.style.padding = "25px";
                box.style.textAlign = "center";
                box.style.borderRadius = "10px";

                box.innerHTML = `
                    <h3>Open Certificate?</h3>
                    <p>Do you want to open this certificate?</p>
                    <button id="yesButton">Yes</button>
                    <button id="noButton">No</button>
                `;

                popup.appendChild(box);
                document.body.appendChild(popup);

                document.getElementById("yesButton").addEventListener("click", function () {
                    window.open(link.href, "_blank");
                    popup.remove();
                });

                document.getElementById("noButton").addEventListener("click", function () {
                    popup.remove();
                });

            });

        }

    });

});