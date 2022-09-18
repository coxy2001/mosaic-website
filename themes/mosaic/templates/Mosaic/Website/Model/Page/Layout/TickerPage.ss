<div class="container">
    <select name="history" id="history">
        <% loop $HistoryOptions %>
        <option value="$ID">$Name</option>
        <% end_loop %>
    </select>
</div>

<!--BUTTONS FOR DATE, COUNTRY PICKERS AND DOWNLOAD BUTTON-->
<div class="grid__header">
	<!--DATE PICKER-->
    <div class="grid__head" id="date-picker">
        <h5 class="grid-text">DATE</h5>
        <!--TODO: date picker -->
    </div>
	<!--COUNTRY PICKER-->
    <div class="grid-head" id="country-picker">
        <select class="selectpicker countrypicker" id="country-picker-button" multiple data-live-search="true" data-flag="true"></select>
    </div>
    <div class="grid__blank">
	</div>
	<!--DOWNLOAD BUTTON-->
    <div class="grid__head" id="download">
        <h5 class="grid-text">DOWNLOAD CSV</h5>
        <!--TODO: download button-->
    </div>
</div>

<div class="grid__header">
    <!--HEADINGS FOR EACH COLUMN-->
    <div class="grid__head"><h6 class="grid-text"> RANK 			</h6></div>
    <div class="grid__head"><h6 class="grid-text"> COMPANY NAME 	</h6></div>
    <div class="grid__head"><h6 class="grid-text"> TICKER:EXCHANGE 	</h6></div>
    <div class="grid__head"><h6 class="grid-text"> P/E 				</h6></div>
    <div class="grid__head"><h6 class="grid-text"> ROA 				</h6></div>
    <div class="grid__head"><h6 class="grid-text"> MARKET:SECTOR 	</h6></div>
    <div class="grid__head"><h6 class="grid-text"> MARKET CAP 		</h6></div>
    <div class="grid__head"><h6 class="grid-text"> FREE CASH FLOW 	</h6></div>
    <div class="grid__head"><h6 class="grid-text"> DIVIDENDS YIELD 	</h6></div>
</div>

<!-- Rows go here -->
<% if $TopCompanies %>
<% loop $TopCompanies %>
<div class="grid__row">
	<div class="grid__item"><p class="grid__text">$RANK </p></div>
	<div class="grid__item"><p class="grid__text">$Name </p></div>
	<div class="grid__item"><p class="grid__text">$Ticker $EXCHANGE </p></div>
	<div class="grid__item"><p class="grid__text">P/E </p></div>
	<div class="grid__item"><p class="grid__text">$ROA </p></div>
	<div class="grid__item"><p class="grid__text">$SECTOR </p></div>
	<div class="grid__item"><p class="grid__text">$MarketCap </p></div>
	<div class="grid__item"><p class="grid__text">$FREE </p></div>
	<div class="grid__item"><p class="grid__text">$DIVIDENDS </p></div>
	<!--<p>$Date</p>-->
	<!--<p>\${$MarketCap} - \${$Price}</p>-->
</div>
<% end_loop %>

</div>
<%-- Pagination --%>
<!-- Re-styled pagination to appear inline; can't find original css-->
<div class="center">
    <% if $TopCompanies.MoreThanOnePage %>
    <div class="pagination">
        <% if $TopCompanies.NotFirstPage %>
        <a class="pagination__link" href="$TopCompanies.PrevLink">&lt;</a>
        <% end_if %>

        <% loop $TopCompanies.PaginationSummary %>
        <% if $Link %>
        <% if $CurrentBool %>
        <a class="pagination__link" style="color:black;">$PageNum</a>
        <% else %>
        <a class="pagination__link" href="$Link">$PageNum</a>
        <% end_if %>
        <% else %>
        <% end_if %>
        <% end_loop %>

        <% if $TopCompanies.NotLastPage %>
        <li class="pagination__item">
            <a class="pagination__link" href="$TopCompanies.NextLink">&gt;</a>
        </li>
        <% end_if %>
    </div>
    <% else %>
    <p><a href="?length=1">Show pagination</a></p>
    <% end_if %>
</div>
<% end_if %>


</div>






</html>
