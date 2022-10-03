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
        <h3 class="grid__text">
            <a href="home/csv?list={$CompanyList.ID}" download="mosaic_{$CompanyList.Year}_{$CompanyList.Name}.csv">Download CSV</a>
        </h3>
    </div>
</div>

<div class="grid">
    <div class="grid__row">
        <% loop $TableHeaders %>
            <div onclick="Sort('$Key')" class="grid__head <% if $Top.CurrentSort == $Key %>grid__head--sorted-{$Top.CurrentDirection}<% end_if %>">
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
                onclick="window.open('$Link', '_blank');"
            >
                <div class="grid__item"><div class="grid__text">$Rank</div></div>
                <div class="grid__item"><div class="grid__text">$Name</div></div>
                <!--Default-->
                <div class="grid__item"><div class="grid__text">$Ticker</div></div>
                <div class="grid__item"><div class="grid__text">$Exchange</div></div>
                <div class="grid__item"><div class="grid__text">$Sector</div></div>
                <div class="grid__item"><div class="grid__text">\${$MarketCap.Formatted}</div></div>
                <div class="grid__item"><div class="grid__text">\${$Price.Nice}</div></div>
                <div class="grid__item"><div class="grid__text">$ROA</div></div>
                <!--Side Scroll-->
                <div class="grid__item"><div class="grid__text">$PE</div></div>
                <div class="grid__item"><div class="grid__text">$EPS</div></div>
                <div class="grid__item"><div class="grid__text">$FreeCashFlow</div></div>
                <div class="grid__item"><div class="grid__text">$DividendsYield</div></div>
                <div class="grid__item"><div class="grid__text">$CurrentRatio</div></div>
                <div class="grid__item"><div class="grid__text">$PriceToBook</div></div>
            </div>
        <% end_loop %>
    <% end_if %>
</div>

<% if $Companies.MoreThanOnePage %>
    <div class="pagination">
        <% if $Companies.NotFirstPage %>
            <a class="pagination__item" href="$Companies.FirstLink">&laquo;</a>
            <a class="pagination__item" href="$Companies.PrevLink">&lsaquo;</a>
        <% end_if %>

        <% loop $Companies.PaginationSummary %>
            <% if $Link %>
                <% if $CurrentBool %>
                    <span class="pagination__item pagination__item--current">$PageNum</span>
                <% else %>
                    <a class="pagination__item" href="$Link">$PageNum</a>
                <% end_if %>
            <% else %>
                <span class="pagination__item">...</span>
            <% end_if %>
        <% end_loop %>

        <% if $Companies.NotLastPage %>
            <a class="pagination__item" href="$Companies.NextLink">&rsaquo;</a>
            <a class="pagination__item" href="$Companies.LastLink">&raquo;</a>
        <% end_if %>
    </div>
<% end_if %>