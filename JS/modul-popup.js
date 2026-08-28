let certificates = document.querySelectorAll("a");

certificates.forEach(function(link) {

    if (link.href.includes("Certificates")) {

        link.addEventListener("click", function(event) {

            event.preventDefault();

            let answer = confirm("Do you want to open this certificate?");

            if (answer) {
                window.open(link.href, "_blank");
            }

        });
    }
});