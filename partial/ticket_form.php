<?php unset($_SESSION["newticket"]); ?>
<div>
  <div id="ticket-accordion" class="ui-accordion-container" style="padding-top: 10px; clear: both;">
    <h3><a href="javascript: void(0)" class="ui-accordion-link">Choose a service<span class="loading" style="float:right;"></span></a></h3>
    <div id="serviceDiv">
      <div id="serviceContainer">
        <div class="lcolumn">
          <div id="media-service" class="service"></div>
        </div>
        <div class="rcolumn">
          <div id="full-service" class="service"></div>
        </div>
      </div>
      <div id="serviceInfo"></div>
    </div>
    <h3><a href="javascript: void(0)" class="ui-accordion-link">Tell us about your media<span class="loading" style="float:right;"></span></a></h3>
    <div id="mediaDiv" style="display:none;">
      <div>
        <div class="lcolumn">
          <div class="formfield">
            <div class="clearfix">
              <label for="mediaType">Select a type of media:</label>
              <select name="mediaType" id="mediaType" class="epc-select float_left" onChange="onChangeMediaType(this.value)">
                <option value="none">--Choose one--</option>
                <option value="External Hard Drive">External Hard Drive</option>
                <option value="Desktop Hard Drive">Desktop Hard Drive</option>
                <option value="Laptop Hard Drive">Laptop Hard Drive</option>
                <option value="USB Stick">USB Stick</option>
                <option value="Flash Card">Flash Card</option>
                <option value="Phone">iPhone</option>
                <option value="Other">Other</option>
                <option value="idk">I don't know</option>
              </select>
              <span class="fieldOK">&nbsp;</span><span class="loading">&nbsp;</span>
            </div>
            <span class="fieldlink"><a href="#" onClick="dontKnowMediaType()">I don't know</a></span>
            <div class="clearfix"></div>
            <div id="mediaTypeByTextbox" class="float_left">&nbsp;</div>
            <div class="clearfix"></div>
          </div>
          <div class="formfield" style="overflow:hidden;">
            <div class="clearfix">
              <label for="mediaSize">What size is your media: </label>
              <input type="text" size="5" name="mediaSizeInput" id="mediaSize" class="epc-textfield float_left" onBlur="onChangeMediaSize(this.value)" /> <span class="epc-text float_left" style="padding-top:5px;">GB</span><div class="fieldOK" style="position:relative; top:5px;"></div><span class="loading" style="position:relative; top:5px;"></span>
            </div>
            <span class="fieldlink"><a href="#" onClick="dontKnowMediaSize()">I don't know</a></span>
          </div>
          <div class="formfield" style="overflow:hidden">
            <div id="priceTotal" style="width: 33px; height: 80px; border-right: 1px solid #EEEEEE; border-bottom:1px solid #EEEEEE;"></div>
            <span id="priceTotalModifier" style='display:none; color:red; font-size:smaller;'>*Price subject to change</span>
          </div>
        </div>
        <div class="rcolumn">
          <div style="width:100%; margin-left: auto; margin-right: auto;">
            <div id="fileSelection">&nbsp;</div>
          </div>
        </div>
        <div class="clearfix"></div>
        <div class="panelNav">
          <div class="accordion-control">
            <button class="epc-button ui-state-default ui-corner-all" onClick="togglePanel(0); return false;"><< Back</button>
            <button class="epc-button ui-state-disabled ui-corner-all" onClick="validateMediaPanel(); return false;" disabled>Next >></button>
          </div>
        </div>
      </div>
		</div>
		<h3><a href="javascript: void(0)" class="ui-accordion-link">Shipping Info<span class="loading" style="float:right;"></span></a></h3>
    <div id="shippingDiv" style="display:none;">
      <div>
        <form id="shippingForm">
          <div class="lcolumn">  
            <div id="shippingLogin">
              <div class="formfield">
                <div class="clearfix">
                  <label for="name">Email</label>
                  <input type="text" size="30" id="email" name="email" class="epc-textfield float_left" onBlur="validateEmail(this.value);"/>
                </div>
              </div>
              <div class="formfield">
                <div class="clearfix">
                  <label for="name" id="passwordCaption">Choose a password</label>
                  <input type="password" size="30" id="password" name="password" class="epc-textfield float_left" />
                </div>
              </div>
              <div class="formfield" id="passwordConfDiv">
                <div class="clearfix">
                  <label for="name">Retype your password</label>
                  <input type="password" size="30" id="passwordconf" name="passwordconf" class="epc-textfield float_left" onKeyup="onKeyupPassword();"/>
                </div>
                <span class="fieldlink"><a href="#" onClick="alreadyHaveAnAccount()">Already have an account?</a></span>
              </div>
            </div>
            <div id="shippingHistory" style="display:none;">
              <span>FedEx locations near you</span>
              <div style="width: 500px;">
                <div style="position: absolute; left: 440px; display:none;">
                  <div id="searchwell"></div>
                </div>
                <div id="map" style="height: 350px; width:265px;"></div>
              </div>
            </div>
          </div>
          <div class="rcolumn">
            <div style="width:100%; margin-left: auto; margin-right: auto;">
              <div class="formfield">
                <div class="clearfix">
                  <div class="float_left">
                    <label for="firstname">First Name</label>
                    <input type="text" size="9" id="firstname" name="firstname" class="epc-textfield float_left" />
                  </div>
                  <div class="float_left">
                    <label for="lastname">Last Name</label>
                    <input type="text" size="14" id="lastname" name="lastname" class="epc-textfield float_left" />
                  </div>
                </div>
              </div>
              <div class="formfield">
                <div class="clearfix">
                  <label for="address">Street</label>
                  <input type="text" size="30" name="street" id="street" class="epc-textfield float_left" />
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
              <div class="formfield">
                <div class="clearfix">
                  <label for="phone">Phone</label>
                  <input type="hidden" id="phone" />
                  <input type="text" id="phone1" size="3" maxlength="3" class="epc-textfield float_left" onKeyup="onKeyupPhone(this.id);"/><span class="float_left" style="margin:0 -10px; font-weight:bold;line-height:2;">-</span>
                  <input type="text" id="phone2" size="3" maxlength="3" class="epc-textfield float_left" onKeyup="onKeyupPhone(this.id);"/><span class="float_left" style="margin:0 -10px; font-weight:bold;line-height:2;">-</span>
                  <input type="text" id="phone3" size="4" maxlength="4" class="epc-textfield float_left" onKeyup="onKeyupPhone(this.id);"/>
                </div>
              </div>
            </div>
          </div>
          <div class="clearfix">&nbsp;</div>
          <div class="panelNav">
            <div class="accordion-control">
              <button class="epc-button ui-state-default ui-corner-all" onClick="togglePanel(1); return false;"><< Back</button>
              <button type="submit" class="epc-button ui-state-disabled ui-corner-all" onClick="validateShippingPanel(); return false;" disabled>Next >></button>
            </div>
          </div>
          <div id="shippingLabels">
            <ul></ul>
          </div>
        </form>
      </div>
    </div>
	</div>
<!--</form>-->
</div>
