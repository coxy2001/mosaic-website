<div class="container">
	$DataList
</div>

<br>
<br>
<br>

<div class="container">
	<% if $Companies %>
		<% loop $Companies %>
			<div class="company">
				<p>$Ticker - $Name</p>
				<div class="company__history">
					<% loop $History %>
						<div class="company__history-item">
							<p>$Date</p>
							<p>\${$MarketCap} - \${$Price}</p>
						</div>
					<% end_loop %>
				</div>
			</div>
		<% end_loop %>
	<% end_if %>
</div>

<br>
<br>
<br>

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
	<% if $Companies %>
		<% loop $Companies %>
			<% if $OldData %>
				<div class="company">
					<p>$Ticker - $Name</p>
					<% with $OldData %>
						<p>$Date</p>
						<p>\${$MarketCap} - \${$Price}</p>
					<% end_with %>
				</div>
			<% end_if %>
		<% end_loop %>
	<% end_if %>
</div>