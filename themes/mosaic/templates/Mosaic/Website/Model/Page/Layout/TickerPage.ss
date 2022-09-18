<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mosaic Investment Securities</title>

    <link rel="stylesheet" href="css/style.css">
    <link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/3.3.7/readable/bootstrap.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
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
        <div class="grid-head" id="country-picker">
            <select class="selectpicker countrypicker" multiple data-live-search="true" data-flag="true"></select>
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
		<%-- Pagination --%>	<!-- Re-styled pagination to appear inline; can't find original css-->
		<div class="center">
		<% if $TopCompanies.MoreThanOnePage %>
			<div class="pagination">
					<% if $TopCompanies.NotFirstPage %>
						<a class="pagination__link" href="$TopCompanies.PrevLink">&lt;</a>
					<% end_if %>

					<% loop $TopCompanies.PaginationSummary %>
						<% if $Link %>
							<% if $CurrentBool %>
								<a class="pagination__link" style="color:black;"	>$PageNum</a>
							<% else %>
								<a class="pagination__link" href="$Link">$PageNum</a>
							<% end_if %>
						<% else %>
						<% end_if %>
					<% end_loop %>

					<% if $TopCompanies.NotLastPage %>
						<li class="pagination__item">
							<a class="pagination__link" href="$TopCompanies.NextLink">&gt;</a>
						</li>
					<% end_if %>
			</div>
		<% else %>
			<p><a href="?length=1">Show pagination</a></p>
		<% end_if %>
		</div>
	<% end_if %>

    
    </div>
	
	<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
    <script src="js/countrypicker.js"></script>





</html>