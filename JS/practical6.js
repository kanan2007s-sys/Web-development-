document.addEventListener("DOMContentLoaded", function () {

    let page = window.location.pathname;

    if (page.includes("Eventspage.html"))
        loadEvents();

    else if (page.includes("Studentdashboard.html"))
        loadStudents();

    else if (page.includes("FAQ.html"))
        loadFAQs();

});


async function fetchJSON(url) {

    try {

        let response = await fetch(url);

        if (!response.ok)
            throw new Error("File not found");

        let data = await response.json();

        localStorage.setItem(url, JSON.stringify(data));

        return data;

    } catch (error) {

        let savedData = localStorage.getItem(url);

        if (savedData)
            return JSON.parse(savedData);

        throw error;

    }

}


function createSection(title) {

    let section = document.createElement("div");

    section.className = "p6-section";

    section.innerHTML = `
        <h2>${title}</h2>
        <p class="status">Loading...</p>
        <div class="controls"></div>
        <div class="list"></div>
        <div class="pages"></div>
    `;

    document.body.appendChild(section);

    return section;

}


function createPages(container, totalPages, currentPage, display) {

    container.innerHTML = "";

    for (let i = 1; i <= totalPages; i++) {

        let button = document.createElement("button");

        button.textContent = i;

        button.disabled = i === currentPage;

        button.onclick = function () {
            display(i);
        };

        container.appendChild(button);

    }

}


async function setupList(config) {

    let section = createSection(config.title);

    let status = section.querySelector(".status");
    let controls = section.querySelector(".controls");
    let list = section.querySelector(".list");
    let pages = section.querySelector(".pages");

    let items;

    try {

        items = await fetchJSON(config.url);

        status.textContent = "Data loaded successfully";

    } catch (error) {

        status.textContent = "Error loading JSON file";

        return;

    }


    let search = document.createElement("input");

    search.placeholder = "Search here";


    let filter = document.createElement("select");

    filter.innerHTML = `<option value="all">All</option>`;


    if (config.filterField) {

        let values = [...new Set(
            items.map(item => item[config.filterField])
        )];

        values.forEach(value => {

            filter.innerHTML += `
                <option value="${value}">${value}</option>
            `;

        });

    }


    let sort = document.createElement("select");

    sort.innerHTML = `
        <option value="">Sort By</option>
        <option value="name">Name A-Z</option>
        <option value="date">Date</option>
    `;


    controls.append(search, filter, sort);


    let currentPage = 1;
    let perPage = 5;


    function display(page = 1) {

        currentPage = page;

        let keyword = search.value.toLowerCase();

        let data = items.filter(item => {

            return config.searchFields.some(field =>
                String(item[field]).toLowerCase().includes(keyword)
            );

        });


        if (config.filterField && filter.value !== "all") {

            data = data.filter(item =>
                item[config.filterField] === filter.value
            );

        }


        if (sort.value === "name") {

            data.sort((a, b) =>
                String(a.name || a.title)
                .localeCompare(String(b.name || b.title))
            );

        }


        if (sort.value === "date") {

            data.sort((a, b) =>
                new Date(a.date) - new Date(b.date)
            );

        }


        let totalPages = Math.ceil(data.length / perPage);

        let start = (currentPage - 1) * perPage;

        let result = data.slice(start, start + perPage);


        list.innerHTML = result.length
            ? result.map(config.renderItem).join("")
            : "<p>No results found</p>";


        createPages(
            pages,
            totalPages,
            currentPage,
            display
        );

    }


    search.addEventListener("input", () => display(1));

    filter.addEventListener("change", () => display(1));

    sort.addEventListener("change", () => display(1));


    display();

}


function loadEvents() {

    setupList({

        title: "Events",

        url: "../JSON/events.json",

        searchFields: ["title", "category", "location"],

        filterField: "category",

        renderItem: e =>
            `<p>${e.id}. ${e.title} | ${e.category} | ${e.date} | ${e.location}</p>`

    });

}


function loadStudents() {

    setupList({

        title: "Students",

        url: "../JSON/students.json",

        searchFields: ["name", "enrollment", "course"],

        filterField: "course",

        renderItem: s =>
            `<p>${s.name} | ${s.enrollment} | ${s.course} | Year ${s.year}</p>`

    });

}


function loadFAQs() {

    setupList({

        title: "FAQs",

        url: "../JSON/faqs.json",

        searchFields: ["question", "answer"],

        renderItem: f =>
            `<details>
                <summary>${f.question}</summary>
                <p>${f.answer}</p>
            </details>`

    });

}