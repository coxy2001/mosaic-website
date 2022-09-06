<div class="container">
	<% if $TopCompanies %>
		<% loop $TopCompanies %>
			<div class="company">
				<p>$Ticker - $Name</p>
				<p>$Date</p>
				<p>\${$MarketCap} - \${$Price}</p>
			</div>
		<% end_loop %>

		<%-- BEGIN PAGINATION --%>
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
							<li class="pagination__item <% if $CurrentBool %>pagination__item--current<% end_if %>">
								<a class="pagination__link" href="$Link">$PageNum</a>
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
		<%-- END PAGINATION --%>
	<% end_if %>
</div>