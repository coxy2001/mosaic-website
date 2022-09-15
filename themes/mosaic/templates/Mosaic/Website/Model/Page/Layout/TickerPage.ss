<div class="container">
	<select name="history" id="history">
		<% loop $HistoryOptions %>
			<option value="$ID">$Name</option>
		<% end_loop %>
	</select>
</div>

<div class="container">
	<% if $TopCompanies %>
		<% loop $TopCompanies %>
			<div class="company">
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