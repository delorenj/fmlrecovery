		$(document).ready(function() {
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


function validateTicketForm()
{

	$("#ticket-form-loading").ajaxStart(function(){
		$(this).html("<img src='images/ajax-loader.gif' />");
	});

	$("#ticket-form-loading").ajaxComplete(function(){
		$(this).html("");
	});

	$.post("authenticate.php", $("#ticket-form").serialize(),
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
      $("#login-message").text("Welcome "+respArray[3]);
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
