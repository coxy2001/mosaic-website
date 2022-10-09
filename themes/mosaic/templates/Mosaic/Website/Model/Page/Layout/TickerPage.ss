<div class="buttons-row">
    <div class="" id="date-picker">
        <select name="history" id="history" onchange="List()">
            <% loop $HistoryOptions %>
                <option value="$ID" <% if $Top.CompanyList.ID == $ID %>selected<% end_if %>>$Year $Month</option>
            <% end_loop %>
        </select>
    </div>

    <select name="length" id="length" onchange="Length()">
        <% loop $LengthOptions %>
            <option value="$Value" <% if $Top.CurrentLength == $Value %>selected<% end_if %>>$Value</option>
        <% end_loop %>
    </select>

    <div class="-head" id="country-picker">
        <%-- <select class="selectpicker countrypicker" id="country-picker-button" multiple data-live-search="true" data-flag="true"></select> --%>
    </div>

    <div class=""></div>

    <div class="grid__head" id="download">
        <a href="home/csv?list={$CompanyList.ID}" download="mosaic_{$CompanyList.Year}_{$CompanyList.Name}.csv">Download CSV</a>
    </div>
</div>

<div class="grid">
    <div class="grid__row">
        <% loop $TableHeaders %>
            <div onclick="Sort('$Key')" class="grid__head <% if $Top.CurrentSort == $Key %>$Top.CurrentDirectionClass<% end_if %>">
                <div class="grid__text">$Value</div>
            </div>
        <% end_loop %>
    </div>

    <% if $Companies %>
        <% loop $Companies %>
            <div class="grid__row grid__row--gradient-{$Rank}
                <% if $Even %>
                    grid__row--even
                <% else %>
                    grid__row--odd
                <% end_if %>"
            >
                <div class="grid__item">$Rank</div>
                <div class="grid__item">
                    <a class="grid__link" href="$Link" target="_blank">$Name</a>
                </div>
                <!--Default-->
                <div class="grid__item">$Ticker</div>
                <div class="grid__item">$Exchange</div>
                <div class="grid__item">$Sector</div>
                <div class="grid__item">\${$MarketCap.Formatted}</div>
                <div class="grid__item">\${$Price.Nice}</div>
                <div class="grid__item">$ROA</div>
                <!--Side Scroll-->
                <div class="grid__item">$PE</div>
                <div class="grid__item">$EPS</div>
                <div class="grid__item">$FreeCashFlow</div>
                <div class="grid__item">$DividendsYield</div>
                <div class="grid__item">$CurrentRatio</div>
                <div class="grid__item">$PriceToBook</div>
            </div>
        <% end_loop %>
    <% end_if %>
</div>

<% if $Companies.MoreThanOnePage %>
    <div class="pagination">
        <% if $Companies.NotFirstPage %>
            <a class="pagination__item" href="$Companies.PrevLink">&laquo;</a>
        <% end_if %>

        <% loop $Companies.PaginationSummary %>
            <% if $Link %>
                <% if $CurrentBool %>
                    <span class="pagination__item pagination__item--current">$PageNum</span>
                <% else %>
                    <a class="pagination__item" href="$Link">$PageNum</a>
                <% end_if %>
            <% else %>
                <span class="pagination__item pagination__item--dots">. . .</span>
            <% end_if %>
        <% end_loop %>

        <% if $Companies.NotLastPage %>
            <a class="pagination__item" href="$Companies.NextLink">&raquo;</a>
        <% end_if %>
    </div>
<% end_if %>