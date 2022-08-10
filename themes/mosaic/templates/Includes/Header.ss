<header class="header">
    <div class="container">
        <a class="header__link" href="$BaseHref" title="Return to homepage">
            <% if $SiteConfig.Logo %>
                <img class="header__logo" height="81" src="$SiteConfig.Logo.URL" alt="$SiteConfig.Logo.Title">
            <% else %>
                <h1 class="header__title">$SiteConfig.Title</h1>
            <% end_if %>
        </a>
        <% include Navigation %>
    </div>
</header>