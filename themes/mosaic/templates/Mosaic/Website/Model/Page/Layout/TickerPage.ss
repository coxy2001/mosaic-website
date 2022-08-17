<div class="container">
	<% if $Companies %>
		<% loop $Companies %>
			<div class="company">
				<p>$Ticker - $Name</p>
				<p>$Date</p>
				<p>\${$MarketCap} - \${$Price}</p>
			</div>
		<% end_loop %>
	<% end_if %>
</div>

<br>
<br>
<br>

<div class="container">
	<% if $TopCompanies %>
		<% loop $TopCompanies %>
			<div class="company">
				<p>$Ticker - $Name</p>
				<p>$Date</p>
				<p>\${$MarketCap} - \${$Price}</p>
			</div>
		<% end_loop %>
	<% end_if %>
</div>