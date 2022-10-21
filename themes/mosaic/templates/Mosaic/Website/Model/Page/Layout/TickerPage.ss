<div class="grid-options">
    <div class="grid-options__item">
        Date:
        <select name="history" id="history" onchange="List()">
            <% loop $HistoryOptions %>
                <option value="$ID" <% if $Top.CompanyList.ID == $ID %>selected<% end_if %>>$Name</option>
            <% end_loop %>
        </select>
    </div>

    <div class="grid-options__item">
        Page Length: 
        <select name="length" id="length" onchange="Length()">
            <% loop $LengthOptions %>
                <option value="$Value" <% if $Top.CurrentLength == $Value %>selected<% end_if %>>$Value</option>
            <% end_loop %>
        </select>
    </div>

    <div class="grid-options__item">
        Country Filter:
        <div class="multiselect">
            <div class="multiselect__select-container" id="country-select">
                <select class="multiselect__select"></select>
                <div class="multiselect__select-cover" id="country-text"></div>
            </div>
            <div class="multiselect__options" id="country-options"></div>
        </div>
    </div>

    <div class="grid-options__item">
        Sector Filter:
        <div class="multiselect">
            <div class="multiselect__select-container" id="sector-select">
                <select class="multiselect__select"></select>
                <div class="multiselect__select-cover"  id="sector-text"></div>
            </div>
            <div class="multiselect__options" id="sector-options">
                <label class="multiselect__option">
                    <input data-sector="all" type="checkbox" checked/>
                    All Sectors
                </label>
                <% loop $SectorOptions %>
                    <% if $Value%>
                        <label class="multiselect__option">
                            <input data-sector="$Value" type="checkbox" checked/>
                            $Value
                        </label>
                    <% end_if %>
                <% end_loop %>
            </div>
        </div>
    </div>

    <div class="grid-options__item">
        <button class="btn-primary" onclick="applyCountryFilter();applySectorFilter();location.reload();">Apply Filters</button>
    </div>

    <div class="grid-options__item">
        <a class="btn-danger" href="$BaseHref">Reset Filters</a>
    </div>

    <div class="grid-options__item grid-options__item--download">
        <a class="btn-primary" href="home/csv?list={$CompanyList.ID}"
            download="mosaic_{$CompanyList.Year}_{$CompanyList.Month}.csv"
        >
            Download CSV
        </a>
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
                <div class="grid__item">
                    $Rank <% if $CustomCalculation %>*<% end_if %>
                </div>
                <div class="grid__item">
                    <a class="grid__link" href="$Link" target="_blank">$Name</a>
                </div>
                <div class="grid__item">$Ticker</div>
                <div class="grid__item">$Exchange</div>
                <div class="grid__item">$Sector</div>
                <div class="grid__item">$MarketCapFormatted</div>
                <div class="grid__item">$PriceFormatted</div>
                <div class="grid__item">
                    $ROA <% if $CustomCalculation %>*<% end_if %>
                </div>
                <div class="grid__item">
                    $PE <% if $CustomCalculation %>*<% end_if %>
                </div>
                <div class="grid__item">
                    <% if $EPS %> $EPS <% else %> - <% end_if %>
                </div>
                <div class="grid__item">
                    <% if $FreeCashFlow %> $FreeCashFlow.Formatted <% else %> - <% end_if %>
                </div>
                <div class="grid__item">
                    <% if $DividendsYield %> $DividendsYield <% else %> - <% end_if %>
                </div>
                <div class="grid__item">
                    <% if $CurrentRatio %> $CurrentRatio <% else %> - <% end_if %>
                </div>
                <div class="grid__item">
                    <% if $PriceToBook %> $PriceToBook <% else %> - <% end_if %>
                </div>
            </div>
        <% end_loop %>
    <% end_if %>
</div>

<% if $Companies.MoreThanOnePage %>
    <div class="pagination pagination--desktop">
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

    <div class="pagination pagination--mobile">
        <% if $Companies.NotFirstPage %>
            <a class="pagination__item" href="$Companies.PrevLink">&laquo;</a>
        <% end_if %>

        <% loop $Companies.Pages(5) %>
            <% if $Link %>
                <% if $CurrentBool %>
                    <span class="pagination__item pagination__item--current">$PageNum</span>
                <% else %>
                    <a class="pagination__item" href="$Link">$PageNum</a>
                <% end_if %>
            <% end_if %>
        <% end_loop %>

        <% if $Companies.NotLastPage %>
            <a class="pagination__item" href="$Companies.NextLink">&raquo;</a>
        <% end_if %>
    </div>
<% end_if %>