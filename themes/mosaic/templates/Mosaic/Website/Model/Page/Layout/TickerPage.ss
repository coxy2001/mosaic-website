<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mosaic Investment Securities</title>
    <link rel="stylesheet" href="style.css">
    <link href="http://fonts.cdnfonts.com/css/effra-heavy" rel="stylesheet">
</head>


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


</div>

<div class="grid__header">
    <div class="grid__head" id="date-picker"><h5 class="grid-text">DATE</h5>
        <!--TODO: date picker -->
    </div>
    <div class="grid__head" id="country-picker"><h5 class="grid-text">COUNTRY</h5>
        <!--TODO: country picker-->
    </div>
    <div class="grid__blank"></div>
    <div class="grid__head" id="download"><h5 class="grid-text">DOWNLOAD CSV</h5>
        <!--TODO: download button-->
    </div>
</div>

<div class="grid__header"> <!--Headings for every following row-->
	<div class="grid__head"><h6 class="grid-text">	RANK				</h6></div>
	<div class="grid__head"><h6 class="grid-text">	COMPANY NAME		</h6></div>
	<div class="grid__head"><h6 class="grid-text">	TICKER:EXCHANGE		</h6></div>
	<div class="grid__head"><h6 class="grid-text">	P/E					</h6></div>
	<div class="grid__head"><h6 class="grid-text">	ROA					</h6></div>
	<div class="grid__head"><h6 class="grid-text">	MARKET:SECTOR		</h6></div>
	<div class="grid__head"><h6 class="grid-text">	MARKET CAP			</h6></div>
	<div class="grid__head"><h6 class="grid-text">	FREE CASH FLOW		</h6></div>
	<div class="grid__head"><h6 class="grid-text">	DIVIDENDS YIELD		</h6></div>
</div>

    <!-- Rows go here -->
	<% if $TopCompanies %>
		<% loop $TopCompanies %>
			<div class="grid__header">
				<div class="grid__item">	<p class="grid__text">$RANK				</p></div>
				<div class="grid__item">	<p class="grid__text">$Name				</p></div>
				<div class="grid__item">	<p class="grid__text">$Ticker $EXCHANGE	</p></div>
				<div class="grid__item">	<p class="grid__text">P/E					</p></div>
				<div class="grid__item">	<p class="grid__text">$ROA					</p></div>
				<div class="grid__item">	<p class="grid__text">$SECTOR				</p></div>
				<div class="grid__item">	<p class="grid__text">$MarketCap			</p></div>
				<div class="grid__item">	<p class="grid__text">$FREE				</p></div>
				<div class="grid__item">	<p class="grid__text">$DIVIDENDS			</p></div>
				<!--<p>$Date</p>-->
				<!--<p>\${$MarketCap} - \${$Price}</p>-->
			</div>
		<% end_loop %>
    </div>
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

    

    <div class="center">
        <div class="pagination">
            <a href="#">&laquo;</a>
            <a href="#">1</a>
            <a href="#">2</a>
            <a href="#">3</a>
            <a href="#">4</a>
            <a href="#">5</a>
            <a href="#">6</a>
            <a href="#">&raquo;</a>
        </div>
    </div>
 






</html>