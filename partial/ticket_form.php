<?php unset($_SESSION["newticket"]); ?>
<form id="ticket-form" method="post" action="validateTicket.php">
  <div id="ticket-accordion">
    <h3><a href="#">Choose a service</a></h3>
    <div id="serviceDiv">
      <div id="serviceContainer">
        <div class="service column3">
          <div style="height:30px;"><label>Selective Recovery</label></div>
          <p>I have an individual file or a group of files that I need recovered.</p>
          <img src="images/files.jpg" height=60% style="float:right;text-align: right;"alt="image of files"/>
        </div>
        <div class="service column3">
          <div style="height:30px;"><label>Media Recovery</label></div>
          <p>I want to recover all of my personal media, including photos, music, and documents.</p>
          <img src="images/polaroids.jpg" height=55% style="float:right;text-align: right;" alt="image of polaroids"/>
        </div>
        <div class="service column3">
          <div style="height:30px;"><label>Full Recovery</label></div>
          <p>I want a complete recovery of the data on my damaged media.</p>
          <img src="images/cabinet.jpg" height=60% style="float:right;text-align: right;" alt="image of file cabinet"/>
        </div>
      </div>
      <div id="serviceInfo"></div>
		</div>
		<h3><a href="#">Tell us about your media</a></h3>
    <div id="mediaDiv" style="display:none;">
      <div>
        <div class="lcolumn">
          <div class="formfield">
            <label for="mediaType">Select a type of media:</label>
            <select name="mediaType" class="epc-select" onChange="onChangeMediaType(this.value)">
              <option value="1">External Hard Drive</option>
              <option value="2">Internal Hard Drive</option>
              <option value="3">Laptop Hard Drive</option>
              <option value="4">USB/Flash Drive</option>
              <option value="5">Other</option>
            </select>
            <div id="mediaTypeByTextbox">&nbsp</div>
          </div>
          <div class="formfield">
            <label for="mediaSize">What size is your media: </label>
  <!--					<input type="text" id="mediaSize" style="border:0; color:#f6931f; font-weight:bold;" />-->
            <!--					<div id="mediaSizeSlider" style="width: 225px;"></div> -->
            <input type="text" size="5" name="mediaSizeInput" class="epc-textfield" onChange="onChangeMediaSize(this.value)" /> <span class="epc-text">GB</span><br />
            <a href="#" style="font-size: 0.8em;" onClick="dontKnowMediaSize()">I don't know</a>
          </div>
        </div>
        <div class="rcolumn">
          <div style="width:380px; margin-left: auto; margin-right: auto;">
            <div id="fileSelection">&nbsp</div>
<!--
            <div class="select-result float_left" id="mediaSizeResult"></div>
            <div class="select-result float_left" id="mediaTypeResult"></div>
-->
          </div>
        </div>
      </div>
		</div>
		<h3><a href="#">Shipping Info</a></h3>
		<div  style="display:none;">
			<ul>
				<li><a href="#" onClick="initPhase3()"><img src="images/media/external.gif" /></a></li>
				<li><a href="#" onClick="initPhase3()"><img src="images/media/laptop.gif" /></a></li>
				<li><a href="#" onClick="initPhase3()"><img src="images/media/flash.gif" /></a></li>
			</ul>			
		</div>
	</div>
</form>
