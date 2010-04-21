<?php unset($_SESSION["newticket"]); ?>
<form id="ticket-form" method="post" action="validateTicket.php">
  <div id="ticket-accordion" style="clear:both;">
    <h3><a href="#">Choose a service<span class="loading" style="float:right;"></span></a></h3>
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
		<h3><a href="#">Tell us about your media<span class="loading"></span></a></h3>
    <div id="mediaDiv" style="display:none;">
      <div>
        <div class="lcolumn roundy-border" style="width:38%;margin-left:-20px; padding-left:20px;">
          <div class="formfield">
            <div class="clearfix">
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
                <option value="idk">I don't know</option>
              </select>
              <span class="fieldOK">&nbsp;</span><span class="loading">&nbsp;</span>
            </div>
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
            <div class="clearfix">
              <label for="mediaSize">What size is your media: </label>
              <input type="text" size="5" name="mediaSizeInput" class="epc-textfield float_left" onChange="onChangeMediaSize(this.value)" /> <span class="epc-text float_left" style="padding-top:5px;">GB</span><div class="fieldOK" style="position:relative; top:5px;"></div><span class="loading" style="position:relative; top:5px;"></span>
            </div>
            <a href="#" style="font-size: 0.8em;" onClick="dontKnowMediaSize()">I don't know</a>
          </div>
        </div>
        <div class="rcolumn roundy-border" style="width:56%;margin-left:-20px; padding-left:20px;">
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
