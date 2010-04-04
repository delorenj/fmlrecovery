<form id="ticket-form" method="post" action="validateTicket.php">
  <div id="ticket-accordion">
    <h3><a href="#">Choose a service</a></h3>
    <div id="serviceDiv">
      <div id="serviceContainer">
        <div class="service column3">
          <div style="height:30px;"><label>Selective Recovery</label></div>
          <p>I have an individual file or a group of files that I need recovered.</p>
          <img src="images/files.jpg" height=65% style="float:right;text-align: right;"/>
        </div>
        <div class="service column3">
          <div style="height:30px;"><label>Media Recovery</label></div>
          <p>I want to recover all of my personal media, including photos, music, and documents.</p>
          <img src="images/polaroids.jpg" height=65% style="float:right;text-align: right;"/>
        </div>
        <div class="service column3">
          <div style="height:30px;"><label>Full Recovery</label></div>
          <p>I want a complete recovery of the data on my damaged media.</p>
          <img src="images/cabinet.jpg" height=65% style="float:right;text-align: right;"/>
        </div>
      </div>
		</div>
		<h3><a href="#">Tell us about your media</a></h3>
		<div id="mediaDiv">
			<div class="lcolumn">
				<div class="formfield">
					<label for="mediaType">Select a type of media:</label>
					<select name="mediaType" onChange="onChangeMediaType(this.value)">
						<option value="1">External Hard Drive</option>
						<option value="2">Internal Hard Drive</option>
						<option value="3">Laptop Hard Drive</option>
						<option value="4">USB/Flash Drive</option>
					</select>
				</div>
				<div class="formfield">
					<label for="mediaSize">What size is your media: </label>
<!--					<input type="text" id="mediaSize" style="border:0; color:#f6931f; font-weight:bold;" />-->
<!--					<div id="mediaSizeSlider" style="width: 225px;"></div> -->
					<input type="text" size="5" name="mediaSizeInput" onChange="onChangeMediaSize(this.value)" /> GB
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
