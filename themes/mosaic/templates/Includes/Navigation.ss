<nav class="nav">
    <% loop Menu(1) %>
        <a class="nav__link <% if $isCurrent %>nav__link--current<% end_if %>" href="$Link" title="$Title.XML">$MenuTitle.XML</a>
    <% end_loop %>
</div>