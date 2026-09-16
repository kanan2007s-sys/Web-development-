document.addEventListener("DOMContentLoaded", function () {

    let page = window.location.pathname;

    if (page.includes("Eventspage.html")) {
        loadEvents();
    }

    if (page.includes("Studentdashboard.html")) {
        loadStudents();
    }

    if (page.includes("FAQ.html")) {
        loadFAQs();
    }

});

function loadEvents() {

    fetch("../JSON/events.json")
        .then(response => response.json())
        .then(events => {

            console.log("Events loaded:", events);

        })
        .catch(error => {
            console.log("Error loading events:", error);
        });

}

function loadStudents() {

    fetch("../JSON/students.json")
        .then(response => response.json())
        .then(students => {

            console.log("Students loaded:", students);

        })
        .catch(error => {
            console.log("Error loading students:", error);
        });

}

function loadFAQs() {

    fetch("../JSON/faqs.json")
        .then(response => response.json())
        .then(faqs => {

            console.log("FAQs loaded:", faqs);

        })
        .catch(error => {
            console.log("Error loading FAQs:", error);
        });

}