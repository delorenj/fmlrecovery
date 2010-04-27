<?php unset($_SESSION["newticket"]); ?>
<!--<form id="ticket-form" method="post" action="validateTicket.php">-->
<div>
  <div id="ticket-accordion" class="ui-accordion-container" style="padding-top: 10px; clear: both;">
    <h3><a href="javascript: void(0)" class="ui-accordion-link">Choose a service<span class="loading" style="float:right;"></span></a></h3>
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
		<h3><a href="javascript: void(0)" class="ui-accordion-link">Tell us about your media<span class="loading" style="float:right;"></span></a></h3>
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
          </div>
          <div class="formfield" style="overflow:hidden;">
            <div class="clearfix">
              <label for="mediaSize">What size is your media: </label>
              <input type="text" size="5" name="mediaSizeInput" class="epc-textfield float_left" onBlur="onChangeMediaSize(this.value)" /> <span class="epc-text float_left" style="padding-top:5px;">GB</span><div class="fieldOK" style="position:relative; top:5px;"></div><span class="loading" style="position:relative; top:5px;"></span>
            </div>
            <a href="#" style="font-size: 0.8em;" onClick="dontKnowMediaSize()">I don't know</a>
          </div>
        </div>
        <div class="rcolumn roundy-border" style="width:56%;margin-left:-20px; padding-left:20px;">
          <div style="width:100%; margin-left: auto; margin-right: auto;">
            <div id="fileSelection">&nbsp;</div>
          </div>
        </div>
        <div class="clearfix"></div>
        <div class="accordion-control">
          <button class="epc-button ui-state-default ui-corner-all" onClick="togglePanel(0); return false;"><< Back</button>
          <button class="epc-button ui-state-disabled ui-corner-all" onClick="validateMediaPanel(); return false;" disabled>Next >></button>
        </div>
      </div>
		</div>
		<h3><a href="javascript: void(0)" class="ui-accordion-link">Shipping Info<span class="loading" style="float:right;"></span></a></h3>
    <div id="shippingDiv" style="display:none;">
      <form id="shippingForm" action="tickets.php" method="post">
        <div class="lcolumn roundy-border" style="width:38%;margin-left:-20px; padding-left:20px;">
          <div class="formfield">
            <div class="clearfix">
              <label for="address">Street</label>
              <input type="text" size="30" name="address" class="epc-textfield float_left" />
            </div>
          </div>
          <div class="formfield">
            <div class="clearfix">
              <label for="zip">Zip</label>
              <input id="zip" type="text" size="5" name="zip" class="epc-textfield float_left" /><div class="fieldOK" style="position:relative; top:5px;"></div><span class="loading" style="float:right;"></span>
            </div>
          </div>
          <div id="hiddenState" style="display:none;">
            <div class="formfield">
              <div class="clearfix">
                <label for="city">City</label>
                <input id="city" type="text" size="30" name="city" class="epc-textfield float_left" />
              </div>
            </div>
            <div class="formfield">
              <div class="clearfix">
                <label for="state">State</label>
                <input type="text" id="state" size="30" name="state" class="epc-textfield float_left" />
              </div>
            </div>
          </div>

        </div>
        <div class="rcolumn roundy-border" style="width:56%;margin-left:-20px; padding-left:20px;">
          <div style="width:100%; margin-left: auto; margin-right: auto;">
            <? include "login_form.php"; ?>
          </div>
        </div>
        <div class="clearfix">&nbsp;</div>
        <div class="accordion-control">
          <button class="epc-button ui-state-default ui-corner-all" onClick="togglePanel(1); return false;"><< Back</button>
          <button type="submit" class="epc-button ui-state-disabled ui-corner-all" disabled>Next >></button>
        </div>
      </form>
    </div>
	</div>
<!--</form>-->
</div>
