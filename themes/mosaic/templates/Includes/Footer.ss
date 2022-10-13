<footer class="footer">
    <div class="footer__images">
        <div class="footer__investing">
            <a href="https://www.investing.com/" target="_blank">
                <img class="footer__img" src="$SiteConfig.DataLogo.URL" title="Data From Investing.com" alt="Data From Investing.com">
            </a>
        </div>

        <div class="footer__logo">
            <% if $SiteConfig.FooterLogo.URL %>
                <img class="footer__img" src="$SiteConfig.FooterLogo.URL" alt="logo">
            <% else_if $SiteConfig.Logo.URL %>
                <img class="footer__img" src="$SiteConfig.Logo.URL" alt="logo">
            <% end_if %>
        </div>
    </div>

    <div class="footer__disclaimer">
        $SiteConfig.Disclaimer
    </div>
</footer>