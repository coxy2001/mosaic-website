<div id="site-footer">
    <footer id="footer">
        <div class="footer-grid">
            
            <div id="investing">
                <a href="https://www.investing.com/" target="_blank">
                    <img class="img" src="$SiteConfig.DataLogo.URL" alt="Data From Investing.com">
                </a>
            </div>

            <div></div>

            <div id="logo">
                <% if $SiteConfig.FooterLogo.URL %>
                    <img class="img" src="$SiteConfig.FooterLogo.URL" alt="logo">
                <% else_if $SiteConfig.Logo.URL %>
                    <img class="img" src="$SiteConfig.Logo.URL" alt="logo">
                <% end_if %>
            </div>

            <div></div>

            $SiteConfig.Disclaimer

        </div>
    </footer>
</div>