<nav>
    <ul class="nav">
        <% loop Menu(1) %>
            <li class="nav__item $LinkingMode">
                <a class="nav__link $LinkingMode" href="$Link" title="$Title.XML">$MenuTitle.XML</a>
            </li>
        <% end_loop %>
    </ul>
</nav>