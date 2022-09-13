<div class="container">
	<select name="history" id="history">
		<% loop $HistoryOptions %>
			<option value="$ID">$Name</option>
		<% end_loop %>
	</select>
</div>

<br>
<br>
<br>

<div class="container-full">
	<% if $TopCompanies %>
		<% loop $TopCompanies %>
			<div class="grid__row">
				<p>$Ticker - $Name</p>
				<p>$Date</p>
				<p>\${$MarketCap} - \${$Price}</p>
			</div>
		<% end_loop %>

		<%-- Pagination --%>
		<% if $TopCompanies.MoreThanOnePage %>
			<div class="pagination">
				<ul class="pagination__list">
					<% if $TopCompanies.NotFirstPage %>
						<li class="pagination__item">
							<a class="pagination__link" href="$TopCompanies.PrevLink">&lt;</a>
						</li>
					<% end_if %>

					<% loop $TopCompanies.PaginationSummary %>
						<% if $Link %>
							<li class="pagination__item">
								<% if $CurrentBool %>
									<span class="pagination__current">$PageNum</span>
								<% else %>
									<a class="pagination__link" href="$Link">$PageNum</a>
								<% end_if %>
							</li>
						<% else %>
							<li class="pagination__item">...</li>
						<% end_if %>
					<% end_loop %>

					<% if $TopCompanies.NotLastPage %>
						<li class="pagination__item">
							<a class="pagination__link" href="$TopCompanies.NextLink">&gt;</a>
						</li>
					<% end_if %>
				</ul>
			</div>
		<% else %>
			<p><a href="?length=1">Show pagination</a></p>
		<% end_if %>
	<% end_if %>
</div>

<div class="grid-container">
    <div class="grid-head" id="date-picker">
        <h5 class="grid-text">DATE</h5>
        <!--TODO: date picker -->
    </div>
    <div class="grid-head" id="country-picker">
        <h5 class="grid-text">COUNTRY</h5>
        <!--TODO: country picker-->
    </div>
    <div class="grid-blank">
    </div>
    <div class="grid-head" id="download">
        <h5 class="grid-text">DOWNLOAD CSV</h5>
        <!--TODO: download button-->
    </div>
    <div class="grid-head">
        <h6 class="grid-text">RANK</h6>
    </div>
    <div class="grid-head">
        <h6 class="grid-text">COMPANY NAME</h6>
    </div>
    <div class="grid-head">
        <h6 class="grid-text">TICKER:EXCHANGE</h6>
    </div>
    <div class="grid-head">
        <h6 class="grid-text">P/E</h6>
    </div>
    <div class="grid-head">
        <h6 class="grid-text">ROA</h6>
    </div>
    <div class="grid-head">
        <h6 class="grid-text">MARKET:SECTOR</h6>
    </div>
    <div class="grid-head">
        <h6 class="grid-text">MARKET CAP</h6>
    </div>
    <div class="grid-head">
        <h6 class="grid-text">FREE CASH FLOW</h6>
    </div>
    <div class="grid-head">
        <h6 class="grid-text">DIVIDENDS YIELD</h6>
    </div>
    <!-- Rows go here -->


</div>
    


</html>