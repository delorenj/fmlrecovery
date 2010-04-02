google.load("jquery", "1.4.2");
google.load("jqueryui", "1.8");


//$(document).ready(function() {
google.setOnLoadCallback(function(){
			$('.epc-textfield').addClass("idleField");
  		$('.epc-textfield').focus(function() {
   			$(this).removeClass("idleField").addClass("focusField");
  	    if (this.value == this.defaultValue){ 
  	    	this.value = '';
				}
				if(this.value != this.defaultValue){
	    			this.select();
	   		}
   		});
   		$('.epc-textfield').blur(function() {
   			$(this).removeClass("focusField").addClass("idleField");
   	    if ($.trim(this.value) == ''){
			   	this.value = (this.defaultValue ? this.defaultValue : '');
				}
    	});


	$(function(){
		//all hover and click logic for buttons
		$(".epc-button:not(.ui-state-disabled)")
		.hover(
			function(){ 
				$(this).addClass("ui-state-hover"); 
			},
			function(){ 
				$(this).removeClass("ui-state-hover"); 
			}
		)
		.mousedown(function(){
				$(this).parents('.epc-buttonset-single:first').find(".epc-button.ui-state-active").removeClass("ui-state-active");
				if( $(this).is('.ui-state-active.epc-button-toggleable, .epc-buttonset-multi .ui-state-active') ){ $(this).removeClass("ui-state-active"); }
				else { $(this).addClass("ui-state-active"); }	
		})
		.mouseup(function(){
			if(! $(this).is('.epc-button-toggleable, .epc-buttonset-single .epc-button,  .epc-buttonset-multi .epc-button') ){
				$(this).removeClass("ui-state-active");
			}
		});
	});
});

function logout()
{
	$.post("authenticate.php", {action: "logout"},
		function(data){
			$("#login-message").text("Welcome Guest").parent().children(':last').remove();
		});
}

function validateLoginForm()
{

	$("#login-form-loading").ajaxStart(function(){
		$(this).html("<img src='images/ajax-loader.gif' />");
	});

	$("#login-form-loading").ajaxComplete(function(){
		$(this).html("");
	});

	$.post("authenticate.php", $("#login-form").serialize(),
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
      $("#login-message").text("Welcome "+respArray[3]).parent().append("<a href='#' onClick='logout()'>Log out</a>");
      break;
    case "1":
      alert(xm(respArray));
      break;
  }
}

function handleCreateAccountResponse(respArray)
{
	var result = xr(respArray);
	var message = xm(respArray);
	switch(result)
	{
		case "0":
			alert(message);
			break;
		case "1":
			alert(message);
			break;
		default:
			alert("Unkown Response from Server");
			break;
	}
}

function extractResult(a) {return a[1];}
function extractMessage(a) {return a[2];}
function xr(a) { return extractResult(a);}
function xm(a) { return extractMessage(a);}

function isNumeric(strString, msg, selector)
{
   var strValidChars = "0123456789";
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

function inBounds(val, l, r, msg, selector)
{
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
	$(".flash_error").html("<p class='message'>"+msg+"</p>").fadeIn();
	setTimeout("$('.flash_error').fadeOut('slow')","5000");
}

function flashNotice(msg)
{
	$(".flash_notice").html("<p class='message'>"+msg+"</p>").fadeIn();
	setTimeout("$('.flash_notice').fadeOut('slow')","5000");
}
