<form id="ticket-form" method="post" action="validateTicket.php">
	<div id="ticket-accordion">
		<h3><a href="#">Select a Service</a></h3>
		<div>
			<ul>
				<li><a href="#" onClick="initPhase2()"><img src="images/media/external.gif" /></a></li>
				<li><a href="#" onClick="initPhase2()"><img src="images/media/laptop.gif" /></a></li>
				<li><a href="#" onClick="initPhase2()"><img src="images/media/flash.gif" /></a></li>
			</ul>			
		</div>
		<h3><a href="#">Shipping Info</a></h3>
		<div>
			<ul>
				<li><a href="#" onClick="initPhase3()"><img src="images/media/external.gif" /></a></li>
				<li><a href="#" onClick="initPhase3()"><img src="images/media/laptop.gif" /></a></li>
				<li><a href="#" onClick="initPhase3()"><img src="images/media/flash.gif" /></a></li>
			</ul>			
		</div>
		<h3><a href="#">Payment Info</a></h3>
		<div>
			<ul>
				<li><a href="#" onClick="initPhase4()"><img src="images/media/external.gif" /></a></li>
				<li><a href="#" onClick="initPhase4()"><img src="images/media/laptop.gif" /></a></li>
				<li><a href="#" onClick="initPhase4()"><img src="images/media/flash.gif" /></a></li>
			</ul>			
			<div class="formfield clearfix">
				<button type="submit" class="epc-button ui-state-default ui-corner-all">Submit</button>
			</div>
		</div>
	</div>
</form>
