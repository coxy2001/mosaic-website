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
		<div class="grid__head"><p class="grid__text">RANK</p></div>
		<div class="grid__head"><p class="grid__text">COMPANY NAME</p></div>
		<div class="grid__head"><p class="grid__text">TICKER</p></div>
		<div class="grid__head"><p class="grid__text">EXCHANGE</p></div>
		<div class="grid__head"><p class="grid__text">SECTOR</p></div>
		<div class="grid__head"><p class="grid__text">MARKET CAP</p></div>
		<div class="grid__head"><p class="grid__text">PRICE</p></div>
		<div class="grid__head"><p class="grid__text">ROA</p></div>
		<div class="grid__head"><p class="grid__text">PE</p></div>
		<div class="grid__head"><p class="grid__text">EPS</p></div>
		<div class="grid__head"><p class="grid__text">FREE CASH FLOW</p></div>
		<div class="grid__head"><p class="grid__text">DIVIDENDS YIELD</p></div>
		<div class="grid__head"><p class="grid__text">CURRENT RATIO</p></div>
		<div class="grid__head"><p class="grid__text">PRICE TO BOOK</p></div>
	</div>

	<% if $TopCompanies %>
    <!--TODO: Sort items by specified heading-->
		<% loop $TopCompanies %>
			<div class="grid__row">
				<div class="grid__item"><p class="grid__text">$Rank</p></div>
				<div class="grid__item"><a class="grid__text" href="$link">$Name</a></div>
				<div class="grid__item"><p class="grid__text">$Ticker</p></div>
				<div class="grid__item"><p class="grid__text">$Exchange</p></div>
				<div class="grid__item"><p class="grid__text">$Sector</p></div>
				<div class="grid__item"><p class="grid__text">$MarketCap</p></div>
				<div class="grid__item"><p class="grid__text">$Price</p></div>
				<div class="grid__item"><p class="grid__text">$ROA</p></div>
				<div class="grid__item"><p class="grid__text">$PE</p></div>
				<div class="grid__item"><p class="grid__text">$EPS</p></div>
				<div class="grid__item"><p class="grid__text">$FreeCashFlow</p></div>
				<div class="grid__item"><p class="grid__text">$DividendsYield</p></div>
				<div class="grid__item"><p class="grid__text">$CurrentRatio</p></div>
				<div class="grid__item"><p class="grid__text">$PriceToBook</p></div>
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