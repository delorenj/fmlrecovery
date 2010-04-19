<?php unset($_SESSION["newticket"]); ?>
<form id="ticket-form" method="post" action="validateTicket.php">
  <div id="ticket-accordion">
    <h3><a href="#">Choose a service<span class="loading"></span></a></h3>
    <div id="serviceDiv">
      <div id="serviceContainer">
        <div class="lcolumn">
          <div class="service">
            <div><label>Media Recovery</label></div>
            <p>I want to recover some, or all of my personal media, including photos, music, and documents.</p>
            <img src="images/polaroids.jpg" height=60% style="float:right;text-align: right;" alt="image of polaroids"/>
          </div>
        </div>
        <div class="rcolumn">
          <div class="service float_right">
            <div><label>Full Recovery</label></div>
            <p>I want to recover all of the data on my damaged media.</p>
            <img src="images/cabinet.jpg" height=60% style="float:right;text-align: right;" alt="image of file cabinet"/>
          </div>
        </div>
      </div>
      <div id="serviceInfo"></div>
		</div>
		<h3><a href="#">Tell us about your media</a></h3>
    <div id="mediaDiv" style="display:none;">
      <div>
        <div class="lcolumn" style="width:40%;">
          <div class="formfield">
            <label for="mediaType">Select a type of media:</label>
            <select name="mediaType" class="epc-select float_left" onChange="onChangeMediaType(this.value)">
              <option value="none">--Choose one--</option>
              <option value="external">External Hard Drive</option>
              <option value="internal">Internal Hard Drive</option>
              <option value="desktop">Desktop</option>
              <option value="laptop">Laptop</option>
              <option value="usb">USB Stick</option>
              <option value="flash">Flash Card</option>
              <option value="phone">Phone</option>
              <option value="other">Other</option>
              <option value="dontknow">I don't know</option>
            </select>
            <span class="fieldOK"></span><div class="loader"></div>
            <div class="clearfix"></div>
            <a href="#" style="font-size: 0.8em;" onClick="dontKnowMediaType()">I don't know</a>
            <div class="clearfix"></div>
            <div id="mediaTypeByTextbox" class="float_left">&nbsp;</div>
            <div class="clearfix"></div>
            <div id="mediaTypeHelpDialog" title="What kind of media do I have?">
              <div class="formfield">
                <label for="deviceType">I want to recover files from my:</label>
                <input type="radio" name="deviceType" value="desktop"/>Desktop PC <br />
                <input type="radio" name="deviceType" value="laptop"/>Laptop <br />
                <input type="radio" name="deviceType" value="phone"/>Phone <br />
                <input type="radio" name="deviceType" value="usb"/>USB Stick <br />
                <input type="radio" name="deviceType" value="other"/>Other <br />
              </div>
            </div>
          </div>
          <div class="formfield" style="overflow:hidden;">
            <label for="mediaSize">What size is your media: </label>
            <input type="text" size="5" name="mediaSizeInput" class="epc-textfield float_left" onChange="onChangeMediaSize(this.value)" /> <span class="epc-text float_left" style="padding-top:5px;">GB</span><div class="fieldOK"></div>
            <div class="clearfix"></div>
            <a href="#" style="font-size: 0.8em;" onClick="dontKnowMediaSize()">I don't know</a>
          </div>
        </div>
        <div class="rcolumn" style="width:60%;">
          <div style="width:100%; margin-left: auto; margin-right: auto;">
            <div id="fileSelection">&nbsp;</div>
          </div>
        </div>
      </div>
		</div>
		<h3><a href="#">Shipping Info</a></h3>
		<div  style="display:none;">
		</div>
	</div>
</form>
