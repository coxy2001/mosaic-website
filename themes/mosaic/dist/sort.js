function sort(column) {
    var url = new URL(window.location.href);

    // If the same column has been clicked again, reverse the sort direction
    if (
        url.searchParams.get("sort") === column &&
        url.searchParams.get("direction") === "ASC"
    ) {
        url.searchParams.set("direction", "DESC");
    } else {
        url.searchParams.set("direction", "ASC");
    }

    url.searchParams.set("sort", column);
    window.location.replace(url);
}

function list() {
    var url = new URL(window.location.href);
    url.searchParams.set("list", document.querySelector("#history").value);
    url.searchParams.set("p", 0);
    window.location.replace(url);
}

function pageLength() {
    var url = new URL(window.location.href);
    url.searchParams.set("length", document.querySelector("#length").value);
    url.searchParams.set("p", 0);
    window.location.replace(url);
}

function pageReset() {
    var url = new URL(window.location.href);
    url.searchParams.set("p", 0);
    window.location.replace(url);
}
