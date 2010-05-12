//google.load("jquery", "1.4.2");
//google.load("jqueryui", "1.8");
google.load("search", "1");

jQuery.namespace = function() {
    var a=arguments, o=null, i, j, d;
    for (i=0; i<a.length; i=i+1) {
        d=a[i].split(".");
        o=window;
        for (j=0; j<d.length; j=j+1) {
            o[d[j]]=o[d[j]] || {};
            o=o[d[j]];
        }
    }
    return o;
};

$.fn.focusNextInputField = function() {
    return this.each(function() {
        var fields = $(this).parents('form:eq(0),body').find('button,input,textarea,select');
        var index = fields.index( this );
        if ( index > -1 && ( index + 1 ) < fields.length ) {
            fields.eq( index + 1 ).focus();
        }
        return false;
    });
};

jQuery.namespace("jQuery.epc");

//$(document).ready(function() {
google.setOnLoadCallback(function(){
      //Set console.log to 'undefined' when firebug is not active
      if(typeof console === "undefined") {
          console = {log: function() { }};
      }
      
			$('.epc-textfield, .epc-select, .epc-checkbox').addClass("idleField");
  		$('.epc-textfield, .epc-select, .epc-checkbox').live('focus',function() {
   			$(this).removeClass("idleField").addClass("focusField");
  	    if (this.value == this.defaultValue){ 
  	    	this.value = '';
				}
//				if(this.value != this.defaultValue){
//	    			this.select();
//	   		}
   		});
   		$('.epc-textfield, .epc-select, .epc-checkbox').live('blur', function() {
   			$(this).removeClass("focusField").addClass("idleField");
   	    if ($.trim(this.value) == ''){
			   	this.value = (this.defaultValue ? this.defaultValue : '');
				}
    	});


	$(function(){
		//all hover and click logic for buttons
		$(".epc-button:not(.ui-state-disabled)")
    .live("mouseover mouseout", function(event){
      if(event.type == 'mouseover'){
				$(this).addClass("ui-state-hover"); 
			}
      if(event.type == 'mouseout'){
				$(this).removeClass("ui-state-hover"); 
			}
    })
    .live("mousedown mouseup", function(event){
      if(event.type =='mousedown'){
				$(this).parents('.epc-buttonset-single:first').find(".epc-button.ui-state-active").removeClass("ui-state-active");
				if( $(this).is('.ui-state-active.epc-button-toggleable, .epc-buttonset-multi .ui-state-active') ){$(this).removeClass("ui-state-active");}
				else {$(this).addClass("ui-state-active");}
      }
      if(event.type == 'mouseup'){
        if(! $(this).is('.epc-button-toggleable, .epc-buttonset-single .epc-button,  .epc-buttonset-multi .epc-button') ){
          $(this).removeClass("ui-state-active");
        }
      }
		});
	});
});

function logout()
{
	$.post("authenticate.php", {action: "logout"},
		function(data){
			$("#login-message").text("Welcome Guest").parent().find("a").remove();
      $("input", "#shippingForm").not(":button, :submit, :reset, :hidden").val("").removeAttr("checked").removeAttr("selected");
      showShippingLogin();
      for(i in jQuery.epc.shippingPanel) {
        jQuery.epc.shippingPanel[i] = 0;
      };
      formCompleteCheck(jQuery.epc.shippingPanel, $("#shippingDiv").find(".accordion-control :last-child"));
		});
}

function validateLoginForm()
{
	resetAjaxLoader("#loginButton");

	$.post("authenticate.php", {action: "login", email: $("#email").val(), password: $("#password").val()},
		function(data){
			handleLoginResponse(data.split("|"));
		});
}

function validateCreateAccount()
{
	$.post("authenticate.php", $("#create-user-form").serialize(),
		function(data){
			handleCreateAccountResponse(data.split("|"));
		});
}

function handleLoginResponse(respArray)
{
  switch(xr(respArray))
  {
    case "0":
      $("#login-message").html("Welcome "+respArray[3]+"<br /><a href='#' onClick='logout()'>Log out</a>");
      break;
    case "1":
      flashError(xm(respArray));
      break;
  }
}


function extractResult(a) {return a[1];}
function extractMessage(a) {return a[2];}
function xr(a) {return extractResult(a);}
function xm(a) {return extractMessage(a);}

function isNumeric(strString, msg, selector, exception)
{
   var strValidChars = "0123456789"+exception;
   var strChar;
   var blnResult = true;

   if (strString.length == 0) return false;

   //  test strString consists of valid characters listed above
   for (i = 0; i < strString.length && blnResult == true; i++)
      {
      strChar = strString.charAt(i);
      if (strValidChars.indexOf(strChar) == -1)
         {
         blnResult = false;
         }
      }
   if(!blnResult)
	{
		fieldError(msg, selector);
		return false;
	}
	fieldErrorOff(selector);
	return true;
}

function inBounds(val, l, r, msg, selector, exception)
{
  if(val == exception) {
    fieldErrorOff(selector);
    return true;
  }
  
	if(!isNumeric(val, msg, selector))
	{
		return false;
	} 
	if((val < l) || (val > r))
	{
		fieldError(msg,selector)
		return false;
	}
	fieldErrorOff(selector);
	return true;
}

function isValidEmail(email, selector)
{
  if(email == ""){
    fieldErrorOff(selector);
    return false;
  }
  var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
	if(!pattern.test(email)){
    fieldError("Please enter a valid email address",selector);
    return false;
  }
  fieldErrorOff(selector);
  return true;
}

function isValidFirstAndLastName(nameArray, selector)
{
  if(nameArray.length < 2){
    fieldError("First and last name is required", selector)
    return false
  }
  else{
    fieldErrorOff(selector);
    return true;
  }
}

function notEmpty(val, msg, selector, exception)
{
  if(val == exception) return true;
  
	if(val == "")
	{
		fieldError(msg,selector)
		return false;
	}
	fieldErrorOff(selector);
	return true;
}

function fieldError(msg, selector)
{
	$(selector).addClass("fieldError");
	flashError(msg);
	
}

function fieldErrorOff(selector)
{
	$(selector).removeClass("fieldError");
}


function flashError(msg)
{
	$(".flash_error").append("<p class='message'>* "+msg+"</p>").fadeIn();
	setTimeout("$('.flash_error').fadeOut('slow',function(){$(this).html('')})","8000");
}

function flashNotice(msg)
{
	$(".flash_notice").append("<p class='message'>"+msg+"</p>").fadeIn();
	setTimeout("$('.flash_notice').fadeOut('slow',function(){$(this).html('')})","8000");
}

function resetAjaxLoader(selector)
{
  $(".loading").unbind("ajaxStart ajaxStop");
  $(selector).siblings(".loading").ajaxStart(function(){
    $(selector).siblings(".fieldOK").hide();
    $(this).show();
  });
  $(selector).siblings(".loading").ajaxStop(function(){
    $(this).hide();
  });
}

function getMethods(obj) {
  var result = [];
  for (var id in obj) {
    try {
      if (typeof(obj[id]) == "function") {
        result.push(id + ": " + obj[id].toString());
      }
    } catch (err) {
      result.push(id + ": inaccessible");
    }
  }
  return result;
}

function initialize_google_map() {

  jQuery.epc.geocoder = new google.maps.Geocoder();
  var latlng = new google.maps.LatLng(-34.397, 150.644);
  var myOptions = {
    zoom: 10,
    center: latlng,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  }
  jQuery.epc.map = new google.maps.Map(document.getElementById("shippingHistory"), myOptions);

  var searchControl = new google.search.SearchControl();
  searcher = new google.search.LocalSearch(); // create the object
  searcher.setCenterPoint(jQuery.epc.map); // bind the searcher to the map
  var options = new google.search.SearcherOptions(); // create the object
  options.setExpandMode(google.search.SearchControl.EXPAND_MODE_OPEN);
  searchControl.addSearcher(searcher , options);
/*
  searchControl.setSearchCompleteCallback(searcher , function() {
    jQuery.epc.map.clearOverlays();
    var results = searcher.results; // Grab the results array
    // We loop through to get the points
    for (var i = 0; i < results.length; i++) {
      var result = results[i]; // Get the specific result
      console.log(result.lat + ":" + result.lng);
      var markerLatLng = new google.maps.LatLng(parseFloat(result.lat),
                                                parseFloat(result.lng));
      var marker = new google.maps.Marker(markerLatLng); // Create the marker

      // Bind information for the infoWindow aka the map marker popup
      marker.bindInfoWindow(result.html.cloneNode(true));
      result.marker = marker; // bind the marker to the result
      jQuery.epc.map.addOverlay(marker); // add the marker to the map
    }

    // Store where the map should be centered
    var center = searcher.resultViewport.center;

    // Calculate what the zoom level should be
    var ne = new google.maps.LatLng(searcher.resultViewport.ne.lat,
                                    searcher.resultViewport.ne.lng);
    var sw = new google.maps.LatLng(searcher.resultViewport.sw.lat,
                                    searcher.resultViewport.sw.lng);
    var bounds = new google.maps.LatLngBounds(sw, ne);
    var zoom = jQuery.epc.map.getBoundsZoomLevel(bounds, new google.maps.Size(350, 350));

    // Set the new center of the map
    // parseFloat converts the lat/lng from a string to a float, which is what
    // the LatLng constructor takes.
    jQuery.epc.map.setCenter(new google.maps.LatLng(parseFloat(center.lat),
                                         parseFloat(center.lng)),
                                         zoom);
  });
*/
  // Draw the control
  //searchControl.draw(controlContainer);

  // Set the map's center point and finish!
//  jQuery.epc.map.setCenter(new google.maps.LatLng(37.443915 , -122.163610), 11);

 // Execute an initial search
  searchControl.execute('fedex');
}

function codeAddress() {
  //var address = document.getElementById("address").value;
  var address = "fedex near 07874";
  if (jQuery.epc.geocoder) {
    jQuery.epc.geocoder.geocode( {
      'address': address
    }, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        jQuery.epc.map.setCenter(results[0].geometry.location);
        var marker = new google.maps.Marker({
          map: jQuery.epc.map,
          position: results[0].geometry.location
        });
      } else {
        alert("Geocode was not successful for the following reason: " + status);
      }
    });
  }
}
