<div class="nav">
    <% loop Menu(1) %>
        <div class="nav__item <% if $isCurrent %>nav__item--current<% end_if %>">
            <a class="nav__link <% if $isCurrent %>nav__link--current<% end_if %>" href="$Link" title="$Title.XML">$MenuTitle.XML</a>
        </div>
    <% end_loop %>
</div>