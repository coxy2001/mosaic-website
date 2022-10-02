function Sort(column) {
    var url = new URL(window.location.href);
    if (url.searchParams.get("sort") === column) {
        if (url.searchParams.get("direction") === "ASC")
            url.searchParams.set('direction', "DESC");
        else
            url.searchParams.set('direction', "ASC");
    }
    else {
        url.searchParams.set('direction', "ASC");
    }
    url.searchParams.set('sort', column);
    window.location.replace(url);
}