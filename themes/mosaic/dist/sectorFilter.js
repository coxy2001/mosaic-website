const sectorSelect = document.getElementById("sector-select");
const sectorOptions = document.getElementById("sector-options");
const sectorText = document.getElementById("sector-text");
const sectorAll = document.querySelector('[data-sector="all"]');
const sectors = document.querySelectorAll("[data-sector]");

function renderSectors() {
    if (sectorSelect == null) return;

    // Toggle expand click event
    sectorSelect.addEventListener("click", () => {
        sectorOptions.classList.toggle("multiselect__options--expanded");
        document
            .getElementById("country-options")
            .classList.remove("multiselect__options--expanded");
    });

    // All Sectors click event
    sectorAll.addEventListener("click", () => {
        setSectors(sectorAll.checked);
        updateSectors();
    });

    // Sector click events
    sectors.forEach((sector) => {
        if (sector != sectorAll) {
            sector.addEventListener("click", () => {
                sectorAll.checked = false;
                updateSectors();
            });
        }
    });

    // If the url has a filter, update options to match filter
    var url = new URL(window.location.href);
    if (url.searchParams.get("sectors")) {
        setSectors(false);
        const sectorList = url.searchParams.get("sectors").split(",");
        sectorList.forEach((sector) => {
            document.querySelector(`[data-sector="${sector}"]`).checked = true;
        });
    }

    updateSectors();
}

// Update the selector text
function updateSectors() {
    if (sectorAll.checked) {
        sectorText.textContent = "All Sectors";
    } else {
        sectorText.textContent = selectedSectors().join(", ") || "None";
    }
}

// Returns an array of the selected sectors
function selectedSectors() {
    var selected = [];

    sectors.forEach((sector) => {
        if (sector.checked) {
            selected.push(sector.dataset.sector);
        }
    });

    return selected;
}

// Set all sectors to the given value
function setSectors(checked) {
    sectors.forEach((sector) => {
        sector.checked = checked;
    });
}

// Refresh the website with the sector query
function applySectorFilter() {
    var url = new URL(window.location.href);
    if (sectorAll.checked || selectedSectors().length == 0) {
        url.searchParams.delete("sectors");
    } else {
        url.searchParams.set("sectors", selectedSectors());
    }
    window.history.replaceState(null, "", url);
}

renderSectors();
