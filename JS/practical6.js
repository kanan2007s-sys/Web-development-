
document.addEventListener("DOMContentLoaded", function () {

    let page = window.location.pathname;

    if (page.includes("Eventspage.html"))
        loadEvents();

    if (page.includes("Studentdashboard.html"))
        loadStudents();

    if (page.includes("FAQ.html"))
        loadFAQs();
});
async function fetchJSON(url) {

    const key = "cache_" + url;

    try {
        const response = await fetch(url);

        if (!response.ok)
            throw new Error("HTTP error " + response.status);

        const data = await response.json();

        if (!Array.isArray(data))
            throw new Error("JSON must be an array of records");

        localStorage.setItem(key, JSON.stringify(data));
        return { data: data, fromCache: false };

    } catch (error) {
        // network / file problem -> try the last saved copy
        const cached = localStorage.getItem(key);

        if (cached)
            return { data: JSON.parse(cached), fromCache: true };

        throw error;
    }
}
function createSection(title) {

    const section = document.createElement("div");
    section.className = "p6-section";
    section.style.padding = "10px 10px 70px 10px";

    section.innerHTML = `
        <h2>${title}</h2>
        <div class="p6-status"></div>
        <div class="p6-controls"></div>
        <div class="p6-list"></div>
        <div class="p6-pages"></div>
    `;

    document.body.appendChild(section);
    return section;
}

// Sort helper: text (default), number or date
function sortItems(data, option) {

    if (option.type === "date")
        return data.sort((a, b) => new Date(a[option.key]) - new Date(b[option.key]));

    if (option.type === "number")
        return data.sort((a, b) => a[option.key] - b[option.key]);

    return data.sort((a, b) => String(a[option.key]).localeCompare(String(b[option.key])));
}

// Pagination buttons
function renderPager(container, totalPages, current, onChange) {

    container.innerHTML = "";

    for (let i = 1; i <= totalPages; i++) {

        let button = document.createElement("button");

        button.textContent = i;
        button.disabled = (i === current);   // current page is highlighted/disabled
        button.onclick = function () {
            onChange(i);
        };

        container.appendChild(button);
    }
}


/* ---------- 3. GENERIC LIST VIEW (used by events, students, FAQs) ---------- */
async function setupListView(config) {

    const { title, url, searchFields, filterField, sortOptions, renderItem } = config;
    const perPage = config.perPage || 5;

    const section  = createSection(title);
    const status   = section.querySelector(".p6-status");
    const controls = section.querySelector(".p6-controls");
    const list     = section.querySelector(".p6-list");
    const pages    = section.querySelector(".p6-pages");

    // LOADING state
    status.textContent = "Loading " + title.toLowerCase() + "...";

    let items;

    try {
        const result = await fetchJSON(url);
        items = result.data;
        status.textContent = result.fromCache ? "Offline: showing last saved data." : "";
    } catch (error) {
        // ERROR state
        status.textContent = "Could not load " + title.toLowerCase() + ". Check the JSON file path / run with a local server.";
        console.error("Error loading " + title + ":", error);
        return;
    }

    let html = `<input class="p6-search" placeholder="Search ${title.toLowerCase()}">`;

    if (filterField) {
        const values = [...new Set(items.map(i => i[filterField]))].sort();

        html += `
            <select class="p6-filter">
                <option value="all">All</option>
                ${values.map(v => `<option value="${v}">${v}</option>`).join("")}
            </select>`;
    }

    if (sortOptions) {
        html += `
            <select class="p6-sort">
                <option value="">Sort</option>
                ${sortOptions.map((o, i) => `<option value="${i}">${o.label}</option>`).join("")}
            </select>`;
    }

    controls.innerHTML = html;

    const search = controls.querySelector(".p6-search");
    const filter = controls.querySelector(".p6-filter");
    const sort   = controls.querySelector(".p6-sort");

    let pageNo = 1;

    function display() {

        // SEARCH
        let term = search.value.toLowerCase();
        let data = items.filter(item =>
            searchFields.some(f => String(item[f]).toLowerCase().includes(term))
        );

        // FILTER
        if (filter && filter.value !== "all")
            data = data.filter(item => String(item[filterField]) === filter.value);

        // SORT
        if (sort && sort.value !== "")
            data = sortItems(data, sortOptions[sort.value]);

        // PAGINATION
        let totalPages = Math.ceil(data.length / perPage);
        if (pageNo > totalPages) pageNo = 1;

        let start  = (pageNo - 1) * perPage;
        let result = data.slice(start, start + perPage);

        // RENDER
        list.innerHTML = result.length
            ? result.map(renderItem).join("")
            : "<p>No results found.</p>";

        renderPager(pages, totalPages, pageNo, function (p) {
            pageNo = p;
            display();
        });
    }

    // any change in search / filter / sort goes back to page 1
    [search, filter, sort].forEach(control => {
        if (control) {
            control.addEventListener("input", function () {
                pageNo = 1;
                display();
            });
        }
    });

    display();
}
/*4. PAGE-SPECIFIC LOADERS (only configuration)*/

function loadEvents() {

    setupListView({
        title: "Events",
        url: "../JSON/events.json",
        searchFields: ["title", "category", "location"],
        filterField: "category",
        sortOptions: [
            { label: "Name A-Z", key: "title" },
            { label: "Date", key: "date", type: "date" }
        ],
        renderItem: e =>
            `<p>${e.id}. ${e.title} | ${e.category} | ${e.date} | ${e.location}</p>`
    });
}


function loadStudents() {

    setupListView({
        title: "Students",
        url: "../JSON/students.json",
        searchFields: ["name", "enrollment", "course"],
        filterField: "course",
        sortOptions: [
            { label: "Name A-Z", key: "name" },
            { label: "Year", key: "year", type: "number" }
        ],
        renderItem: s =>
            `<p>${s.name} | ${s.enrollment} | ${s.course} | Year ${s.year}</p>`
    });
}


function loadFAQs() {

    setupListView({
        title: "FAQs",
        url: "../JSON/faqs.json",
        searchFields: ["question", "answer"],
        sortOptions: [
            { label: "Question A-Z", key: "question" }
        ],
        renderItem: f =>
            `<details>
                <summary>${f.question}</summary>
                <p>${f.answer}</p>
            </details>`
    });
}