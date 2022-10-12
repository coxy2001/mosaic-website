function Sort(column) {
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

function List() {
    var url = new URL(window.location.href);
    url.searchParams.set("list", document.querySelector("#history").value);
    window.location.replace(url);
}

function Length() {
    var url = new URL(window.location.href);
    url.searchParams.set("length", document.querySelector("#length").value);
    window.location.replace(url);
}
