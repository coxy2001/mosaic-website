<style>
	.container {
		margin: 0 1rem;
	}
	.company {
		padding-bottom: 1rem;
	}
	.company p {
		margin: 0;
	}
	.company__history {
		display: flex;
	}
	.company__history-item {
		padding: 0 1rem;
	}
</style>

<div class="container">
	<% if $Companies %>
		<% loop $Companies %>
			<div class="company">
				<p>$Ticker - $Name</p>
				<div class="company__history">
					<% loop $History %>
						<div class="company__history-item">
							<p>$LastEdited</p>
							<p>\${$MarketCap} - \${$Price}</p>
						</div>
					<% end_loop %>
				</div>
			</div>
		<% end_loop %>
	<% end_if %>
</div>