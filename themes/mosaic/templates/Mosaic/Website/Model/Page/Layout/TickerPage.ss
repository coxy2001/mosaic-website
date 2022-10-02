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
		<h3 class="grid__text">
			<a href="home/csv?list={$TopCompanies.ID}" download="mosaic_{$TopCompanies.Year}_{$TopCompanies.Name}.csv">Download CSV</a>
		</h3>
	</div>
</div>

<div class="grid">
	<div class="grid__row">
		<div class="grid__head"><h4 class="grid__text">RANK</h4></div>
		<div class="grid__head"><h4 class="grid__text">COMPANY NAME</h4></div>
		<div class="grid__head"><h4 class="grid__text">TICKER</h4></div>
		<div class="grid__head"><h4 class="grid__text">EXCHANGE</h4></div>
		<div class="grid__head"><h4 class="grid__text">SECTOR</h4></div>
		<div class="grid__head"><h4 class="grid__text">MARKET CAP</h4></div>
		<div class="grid__head"><h4 class="grid__text">PRICE</h4></div>
		<div class="grid__head"><h4 class="grid__text">ROA</h4></div>
		<div class="grid__head"><h4 class="grid__text">PE</h4></div>
		<div class="grid__head"><h4 class="grid__text">EPS</h4></div>
		<div class="grid__head"><h4 class="grid__text">FREE CASH FLOW</h4></div>
		<div class="grid__head"><h4 class="grid__text">DIVIDENDS YIELD</h4></div>
		<div class="grid__head"><h4 class="grid__text">CURRENT RATIO</h4></div>
		<div class="grid__head"><h4 class="grid__text">PRICE TO BOOK</h4></div>
	</div>

	<% if $Companies %>
		<% loop $Companies %>
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
		<% else %>
			<p><a href="?length=1">Show pagination</a></p>
		<% end_if %>
	<% end_if %>
</div>