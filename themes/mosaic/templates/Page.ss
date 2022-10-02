<!DOCTYPE html>
<html lang="$ContentLocale">

<head>
    <% base_tag %>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>$Title | $SiteConfig.Title</title>
    $MetaTags(false)
</head>

<body>
    <% include Header %>

    <main id="maincontent" role="main">
        $Layout
    </main>

    <% include Footer %>
</body>

</html>
