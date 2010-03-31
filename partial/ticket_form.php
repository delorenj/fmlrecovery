<form id="ticket-form" method="post" action="validateTicket.php">
	<div id="ticket-accordion">
		<h3><a href="#">Service</a></h3>
		<div id="serviceDiv">
			<div class="lcolumn">
				<div class="formfield">
					<label for="mediaType">Select a type of media:</label>
					<select name="mediaType" onChange="onMediaTypeChange(this.value)">
						<option value="1">External Hard Drive</option>
						<option value="2">Internal Hard Drive</option>
						<option value="3">Laptop Hard Drive</option>
						<option value="4">USB/Flash Drive</option>
					</select>
				</div>
				<div class="formfield">
					<label for="mediaSize">What size is your media: </label>
					<input type="text" id="mediaSize" style="border:0; color:#f6931f; font-weight:bold;" />
					<div id="mediaSizeSlider" style="width: 225px;"></div>
				</div>
			</div>
			<div class="rcolumn">
				<div style="width:380px; margin-left: auto; margin-right: auto;">
					<div class="select-result float_left" id="mediaSizeResult">Media Size</div>
					<div class="select-result float_left" id="mediaTypeResult">Media Type</div>
				</div>
			</div>
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
