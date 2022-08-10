<div class="container">
    <% if $ShowTitle && $Title %>
        <div class="element__title">
            <h2>$Title</h2>
        </div>
    <% end_if %>

    <div class="content__container">
        $HTML
    </div>
</div>