<footer class="footer">
    <div class="container">
        <ul class="nav">
            <% loop Menu(1) %>
                <a class="nav__link" href="$Link" title="$Title.XML">$MenuTitle.XML</a>
            <% end_loop %>
        </ul>

        <p class="copyright">$SiteConfig.CompanyName &copy; $Now.Year</p>
    </div>
</footer>