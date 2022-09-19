<div class="grid__row">
	<div class="grid__head" id="date-picker">
		<select name="history" id="history">
			<% loop $HistoryOptions %>
				<option value="$ID">$Name</option>
			<% end_loop %>
		</select>
	</div>

	<div class="grid-head" id="country-picker">
		<select class="selectpicker countrypicker" id="country-picker-button" multiple data-live-search="true" data-flag="true"></select>
	</div>

	<div class="grid__blank"></div>

	<div class="grid__head" id="download">
		<h3 class="grid__text">Download CSV</h3>
		<!--TODO: download button-->
	</div>
</div>

<div class="grid">
	<div class="grid__row">
		<div class="grid__head"><h4 class="grid__text">RANK</h4></div>
		<div class="grid__head"><h4 class="grid__text">COMPANY NAME</h4></div>
		<div class="grid__head"><h4 class="grid__text">TICKER</h4></div>
		<div class="grid__head"><h4 class="grid__text">P/E</h4></div>
		<div class="grid__head"><h4 class="grid__text">ROA</h4></div>
		<div class="grid__head"><h4 class="grid__text">MARKET SECTOR</h4></div>
		<div class="grid__head"><h4 class="grid__text">MARKET CAP</h4></div>
		<div class="grid__head"><h4 class="grid__text">FREE CASH FLOW</h4></div>
		<div class="grid__head"><h4 class="grid__text">DIVIDENDS YIELD</h4></div>
	</div>

	<% if $TopCompanies %>
		<% loop $TopCompanies %>
			<div class="grid__row">
				<div class="grid__item"><p class="grid__text">$Rank</p></div>
				<div class="grid__item"><p class="grid__text">$Name</p></div>
				<div class="grid__item"><p class="grid__text">$Ticker</p></div>
				<div class="grid__item"><p class="grid__text">$PE</p></div>
				<div class="grid__item"><p class="grid__text">$ROA</p></div>
				<div class="grid__item"><p class="grid__text">$Sector</p></div>
				<div class="grid__item"><p class="grid__text">$MarketCap</p></div>
				<div class="grid__item"><p class="grid__text">$FreeCashFlow</p></div>
				<div class="grid__item"><p class="grid__text">$Dividends</p></div>
			</div>
		<% end_loop %>

		<% if $TopCompanies.MoreThanOnePage %>
			<div class="pagination">
				<% if $TopCompanies.NotFirstPage %>
					<a class="pagination__item" href="$TopCompanies.PrevLink">&lt;</a>
				<% end_if %>
	
				<% loop $TopCompanies.PaginationSummary %>
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
	
				<% if $TopCompanies.NotLastPage %>
					<a class="pagination__item" href="$TopCompanies.NextLink">&gt;</a>
				<% end_if %>
			</div>
		<% else %>
			<p><a href="?length=1">Show pagination</a></p>
		<% end_if %>
	<% end_if %>
</div>