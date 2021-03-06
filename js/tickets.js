google.setOnLoadCallback(function(){
	$("#ticket-accordion").accordion({
		autoHeight: true,
		clearStyle: true,
      animated:true
	});

//  $(".ui-accordion-header").unbind("click");

  $(".ui-accordion-header")
    .live("mouseover mouseout", function(event){
      if(event.type == 'mouseover'){
				$(this).removeClass("ui-state-hover").css("cursor", "default").children().css("cursor", "default");
			}
      if(event.type == 'mouseout'){
				$(this).removeClass("ui-state-hover").css("cursor", "default").children().css("cursor", "default");
			}
    });

  $("#ticket-accordion h3 a").css("text-decoration", "none");

  $("#ticket-accordion").bind('accordionchange', function(event,ui){
    switch(ui.newHeader.index()){
      case 4:
        if($.cookie("userid") != ""){
          postLoginProcessing();
        }
        $("#password").bind("keyup", function(){onKeyupPassword()});
        //postLoginProcessing();
        break;
       default:
    }
  });

/*
  $(".service").hover(function(){
    $(this).css({
      backgroundPosition: '0 -190px'
    });
    //displayServiceInfo($(this).prevAll().length);
  }, function(){
    $(this).css({
      backgroundPosition: '0 0'
    });
//    $("#serviceInfo > *").fadeOut();
   })
   .click(function(){
     validateServicePanel($(this).parent().prevAll().length);
   });
*/

 $("input[name='deviceType']").click(function(){
   onClickDeviceType($(this).val());
 });

 $("#zip").blur(function() {
  if(!validateZip()) return;
	var city = $("#city");
  var state = $("#state");
  resetAjaxLoader("#zip");
	$.getJSON("http://www.geonames.org/postalCodeLookupJSON?&country=US&callback=?", {postalcode: this.value}, function(response) {
		if (response && response.postalcodes.length && response.postalcodes[0].placeName) {
			city.val(response.postalcodes[0].placeName);
		}
		if (response && response.postalcodes.length && response.postalcodes[0].adminCode1) {
			state.val(response.postalcodes[0].adminCode1);
		}
    $("#hiddenState").slideDown("slow", function(){
      validateCity();
      validateState();
      init_google_map();
    });
	})
 });

 $("#password").blur(function(){validatePassword()});
 $("#firstname").blur(function(){validateFirstName()});
 $("#lastname").blur(function(){validateLastName()});
 $("#street").blur(function(){validateStreet()});
 $("#city").blur(function(){validateCity()});
 $("#state").blur(function(){validateState()});
 $("#phone1, #phone2, #phone3").blur(function(){validatePhone()});
 
 jQuery.epc.mediaPanel = [0,0];
 jQuery.epc.shippingPanel = [0,0,0,0,0,0,0,0,0];

  $("#phone1,#phone2,#phone3").keydown(function(e){
    var data = "0123456789";
    var c = String.fromCharCode(e.which)
    if((data.indexOf(c) == -1) && (e.which != 8) && (e.which != 9) && (e.which != 37)&& (e.which != 39)){
      return false;
    }
  return true;
  });

  $("#priceTotal").jCounter({
      count: 99,
      currency: true,
      counterBg: "http://www.fmlrecovery.com/js/plugins/jCounter/bg-counter.png",
      counterImg: "http://www.fmlrecovery.com/js/plugins/jCounter/counter-numbers.png",
      dollarImg: "http://www.fmlrecovery.com/js/plugins/jCounter/dollar.png"
    });

  $(".epc-checkbox").change(function() {
    checkCheck($(this))
  });
 
});


function onKeyupPhone(field)
{
  switch(field){
    case "phone1":
    case "phone2":
      if($("#"+field).val().length == 3){
        $("#phone").val($("#phone1").val() + $("#phone2").val() + $("#phone3").val());
        $("#"+field).focusNextInputField();
      }
      break;
    case "phone3":
      if($("#"+field).val().length == 4){
        $("#phone").val($("#phone1").val() + $("#phone2").val() + $("#phone3").val());
        validatePhone();
        $("#shippingForm").find("button[type='submit']").focus();
      }
      break;
  }
}

function onKeyupPassword()
{
  var npw = $("#password").val();
  var pwc = $("#passwordconf").val();
  if((npw == pwc) && (npw != "") && (npw.length > 3)){
    jQuery.epc.shippingPanel[1] = 1;
  }
  else{
    jQuery.epc.shippingPanel[1] = 0;
  }
  formCompleteCheck(jQuery.epc.shippingPanel, $("#shippingDiv").find(".accordion-control :last-child"));
}

function togglePanel(panel)
{
  $("#ticket-accordion").accordion("activate", panel);
}

function displayServiceInfo(index)
{
  var html ="";
  switch(index){
    case 0:
      html += "<div><h1><em>This</em> is info on Media Recovery</h1></div>";
      break;
    case 1:
      html += "<div><h1><em>This</em> is info on Full Recovery</h1></div>";
      break;
  }
  $("#serviceInfo").html(html);
}

function alreadyHaveAnAccount()
{
  $("#passwordConfDiv").slideUp("slow", function(){
    $("#password").unbind("keyup");
    $("#passwordCaption").html("Password");
    $("#shippingLogin").append("<div id='hiddenLogin'><div class='clearfix'></div><button id='loginButton' class='epc-button ui-state-default ui-corner-all' href='#' onClick='validateLoginForm(); postLoginProcessing(); return false;'>Log in</button> \n\
          <span class='fieldlink'><a href='#' onClick='forgotPassword()'>Forgot Password</a> or <a href='#' onClick='createAnAccount()'>Create an Account</a></span></div>")
  });
}

function createAnAccount()
{
  $("#hiddenLogin").fadeOut("slow", function(){
    $(this).remove();
    $("#password").bind("keyup", function(){onKeyupPassword($(this).val());});
    $("#passwordCaption").html("Choose a Password");
    $("#passwordConfDiv").slideDown("slow");
  });
}

function showShippingHistory()
{
  $("#shippingLogin").fadeOut("slow",function(){
    $("#shippingHistory").fadeIn("slow", function(){
      init_google_map();
    });
  });
}

function showShippingLogin()
{
  $("#shippingHistory").fadeOut("slow",function(){
    $("#shippingLogin").fadeIn("slow");
  });
}

function postLoginProcessing()
{
  jQuery.epc.shippingPanel[0] = 1;
  jQuery.epc.shippingPanel[1] = 1;
  $.post("ajax/users.php", {action: "get", key: "defaultAddress"},
    function(data){
      if(data == null) return;
      showShippingHistory();
      $("#firstname").val(data.firstname);
      $("#lastname").val(data.lastname);
      $("#street").val(data.street);
      $("#zip").val(data.zip);
      $("#city").val(data.city);
      $("#state").val(data.state);
      $("#phone").val(data.phone);
      if(data.phone != null){
        $("#phone1").val(data.phone.substring(0,3));
        $("#phone2").val(data.phone.substring(3,6));
        $("#phone3").val(data.phone.substring(6,10));
      }
      $("#hiddenState").slideDown("slow");
      validateFirstName();
      validateLastName();
      validateStreet();
      validateZip();
      validateCity();
      validateState();
      validatePhone();
    },'json');
}

function forgotPassword()
{
  if(!validateEmail()) return false;
  //TODO: Actually reset the password and send it!
  flashNotice("Your password has been reset. Your new password has been sent to your email.")
  return true;
}

function validateEmail()
{
  if(!isValidEmail($("#email").val(),"#email")){
    jQuery.epc.shippingPanel[0] = 0;
    formCompleteCheck(jQuery.epc.shippingPanel, $("#shippingDiv").find(".accordion-control :last-child"));
    return false;
  }
  jQuery.epc.shippingPanel[0] = 1;
  formCompleteCheck(jQuery.epc.shippingPanel, $("#shippingDiv").find(".accordion-control :last-child"));
  return true;
}

function validateFirstName()
{
  var pass = true;
//  if((!notEmpty($("#firstname").val(),"First name is required","#firstname"))){
if($("#firstname").val() == ""){
      jQuery.epc.shippingPanel[2] = 0;
      pass = false;
  }
  else{
    jQuery.epc.shippingPanel[2] = 1;
  }
  formCompleteCheck(jQuery.epc.shippingPanel, $("#shippingDiv").find(".accordion-control :last-child"));
  return pass
}

function validateLastName()
{
  var pass = true;
  //if((!notEmpty($("#lastname").val(),"Last name is required","#lastname"))){
  if($("#lastname").val() == ""){
      jQuery.epc.shippingPanel[8] = 0;
      pass = false;
  }
  else{
    jQuery.epc.shippingPanel[8] = 1;
  }
  formCompleteCheck(jQuery.epc.shippingPanel, $("#shippingDiv").find(".accordion-control :last-child"));
  return pass
}

function validateState(){
  var pass = true;
  //if((!notEmpty($("#state").val(),"State is required","#state"))){
  if($("#state").val() == ""){
      console.log($("#state").val()+":here");
      jQuery.epc.shippingPanel[6] = 0;
      pass = false;
  }
  else{
    jQuery.epc.shippingPanel[6] = 1;
  }
  formCompleteCheck(jQuery.epc.shippingPanel, $("#shippingDiv").find(".accordion-control :last-child"));
  return pass
}

function validateCity(){
  var pass = true;
  //if((!notEmpty($("#city").val(),"City is required","#city"))){
  if($("#city").val() == ""){
    jQuery.epc.shippingPanel[5] = 0;
    pass = false;
  }
  else{
    jQuery.epc.shippingPanel[5] = 1;
  }
  formCompleteCheck(jQuery.epc.shippingPanel, $("#shippingDiv").find(".accordion-control :last-child"));
  return pass
}

function validateStreet(){
  var pass = true;
  //if((!notEmpty($("#street").val(),"Street is required","#street"))){
  if($("#street").val() == ""){
    jQuery.epc.shippingPanel[3] = 0;
    pass = false;
  }
  else{
    jQuery.epc.shippingPanel[3] = 1;
  }
  formCompleteCheck(jQuery.epc.shippingPanel, $("#shippingDiv").find(".accordion-control :last-child"));
  return pass
}

function validatePhone(){
  var pass = true;
  if((($("#phone").val() == "")) ||
     (!inBounds($("#phone").val().length,10,10, "Not a valid phone number","#phone1, #phone2, #phone3"))
  ){
    jQuery.epc.shippingPanel[7] = 0;
    pass = false;
  }
  else{
    jQuery.epc.shippingPanel[7] = 1;
  }
  formCompleteCheck(jQuery.epc.shippingPanel, $("#shippingDiv").find(".accordion-control :last-child"));
  return pass
}

function validateZip(){
  var pass = true;
  
  if((!isNumeric($("#zip").val(),"Not a valid zip code","#zip")) || ($("#zip").val() == "")){
    jQuery.epc.shippingPanel[4] = 0;
    pass = false;
  }
  else{
    jQuery.epc.shippingPanel[4] = 1;
  }
  formCompleteCheck(jQuery.epc.shippingPanel, $("#shippingDiv").find(".accordion-control :last-child"));
  return pass
}

function validatePassword(){
  var pass = true;
  if(!inBounds($("#password").val().length,4,16,"Password must be at least 4 character in length","#password",0)){
    jQuery.epc.shippingPanel[1] = 0;
    pass = false;
  }
  else{
    jQuery.epc.shippingPanel[1] = 1;
  }
  formCompleteCheck(jQuery.epc.shippingPanel, $("#shippingDiv").find(".accordion-control :last-child"));
  return pass
}

function validateServicePanel(index)
{
  $("#ticket-accordion h3:first-child a img").remove();
  $(".loading").unbind("ajaxStart ajaxStop");
  $("#ticket-accordion h3:first-child a span").ajaxStart(function(){
    $(this).fadeIn("fast");
  });
  $("#ticket-accordion h3:first-child a span").ajaxStop(function(){
    $(this).hide();
  });

$.post("ajax/tickets.php", {action: "create", key: "service", val: index},
    function(data){
      if(data == "FAILED"){
        flashError("Oops, something went wrong. Try again.");
        return;
      }
      if(data.indexOf("SUCCESS") != -1){
        $("#ticket-accordion h3:first-child a").append("<img src='images/accept.png' style='margin-top:-5px;float:right;'/>")
      }
      $("#ticket-accordion").accordion("activate", 1);
    });
  switch(index){
    case 0: //Media
      $("#fileSelection").html("").append("\n\
        <div class='formfield'>\n\
          <div class='clearfix'>\n\
            <label for='fileTypeSelectInput'>What types of media are you interested in recovering?</label>\n\
            <div class='epc-checkbox-group lcolumn'>\n\
              <input type='checkbox' class='epc-checkbox' id='music'checked /><span style='position:relative; top:-1%;'>Music</span> <br />\n\
              <input type='checkbox' class='epc-checkbox' id='documents' checked /><span style='position:relative; top:-1%;'>Text Documents</span> <br />\n\
              <input type='checkbox' class='epc-checkbox' id='pictures' checked /><span style='position:relative; top:-1%;'>Pictures</span> <br />\n\
              <input type='checkbox' class='epc-checkbox' id='videos' checked /><span style='position:relative; top:-1%;'>Videos</span> <br />\n\
              <input type='checkbox' class='epc-checkbox' id='archives' checked /><span style='position:relative; top:-1%;'>Archived Files</span> <br />\n\
              <input type='checkbox' class='epc-checkbox' id='other'/><span style='position:relative; top:-1%;'>Other</span> <br />\n\
            </div>\n\
            <div id='extraTypes' class='epc-checkbox-group lcolumn'></div>\n\
          </div>\n\
        </div>\n\
        <div class='clearfix'>&nbsp;</div>\n\
        <div id='specificFileTypeField' class='float_left'>&nbsp;</div>\n\
        <div class='clearfix'></div>\n\
        <div class='formfield'>\n\
          <div class='clearfix' id='fileSelectDiv'>\n\
            <span class='fieldlink'><a href='#' onClick='doKnowFileNames()'>I want to add specific files or directories</a></span>\n\
          </div>\n\
        </div>\n\
        <div id='fileSelectionResults'><ol></ol></div>\n\
        ");
      break;
    default:
      $("#fileSelection").html("");
  }
  $("#fileSelection").find(".epc-checkbox").change(function() {checkCheck($(this))});
}

function validateMediaPanel()
{
  $("#ticket-accordion h3:eq(1) a img").remove();
  $(".loading").unbind("ajaxStart ajaxStop");
  $("#ticket-accordion h3:eq(1) a span").ajaxStart(function(){
    $(this).fadeIn("fast");
  });
  $("#ticket-accordion h3:eq(1) a span").ajaxStop(function(){
    $(this).hide();
  });

  fileTypeArray = new Array;
  $("#fileSelection").find(".epc-checkbox:checked").each(function(){
    textval = $(this).attr("id");
    if(textval == "other") {
      return;
    }
    fileTypeArray.push(textval);
  });

  specificFileArray = new Array;
  $("#fileSelectionResults ol").children().each(function(){
    fileval = $(this).text();
    fileval = fileval.substring(0,fileval.length - 1);
    specificFileArray.push(fileval);
  });
  $.post("ajax/tickets.php", {action: "create", key: "mediaDetail", val: "fileTypes="+fileTypeArray+"|specificFiles="+specificFileArray},
   function(data){
    if(data == "FAILED"){
      flashError("Oops, something went wrong. Try again.");
    }
    if(data.indexOf("SUCCESS") != -1){
      $("#ticket-accordion h3:eq(1) a").append("<img src='images/accept.png' style='margin-top:-5px;float:right;'/>")
      $("#ticket-accordion").accordion("activate", 1);
    }
  });
  return false;
}

function validateShippingPanel()
{
  $("#ticket-accordion h3:eq(2) a img").remove();
  $(".loading").unbind("ajaxStart ajaxStop");
  $("#ticket-accordion h3:eq(2) a span").ajaxStart(function(){
    $(this).fadeIn("fast");
  });
  $("#ticket-accordion h3:eq(2) a span").ajaxStop(function(){
    $(this).hide();
  });

  if($("#passwordConfDiv").is(":visible"))
  {
    console.log("Creating Account");    
    
    $.ajax({
      type: "POST",
      url:  "ajax/users.php",
      data: {action: "create",
             firstname: $("#firstname").val(),
             lastname: $("#lastname").val(),
             email: $("#email").val(),
             password: $("#password").val(),
             street: $("#street").val(),
             zip: $("#zip").val(),
             city: $("#city").val(),
             state: $("#state").val(),
             phone: $("#phone").val()},
      success:function(data){
                  handleCreateAccountResponse(data.split("|"));
                  if(xr(data.split("|")) == "0") {
                    postLoginProcessing();
                    console.log("Creating a new ticket for new user");
                    $.ajax({
                      type: "POST",
                      url:  "ajax/tickets.php",
                      dataType: "json",
                      data: {action: "finalize",
                             firstname: $("#firstname").val(),
                             lastname: $("#lastname").val(),
                             password: $("#password").val(),
                             street: $("#street").val(),
                             zip: $("#zip").val(),
                             city: $("#city").val(),
                             state: $("#state").val(),
                             phone: $("#phone").val()},
                      success:function(data){
                        handleFinalizeTicket(data);
                      }
                    });
                  }
                  else return;
                }
    });
  } 
  else
  {
    console.log("User already logged in...");
    console.log("Creating a new ticket for existing user");
    $.ajax({
      type: "POST",
      url:  "ajax/tickets.php",
      dataType: "json",
      data: {action: "finalize",
             firstname: $("#firstname").val(),
             lastname: $("#lastname").val(),
             email: $("#email").val(),
             password: $("#password").val(),
             street: $("#street").val(),
             zip: $("#zip").val(),
             city: $("#city").val(),
             state: $("#state").val(),
             phone: $("#phone").val()},
      success:function(data){
        handleFinalizeTicket(data);
      }
    });
  }
  return false;
}

function handleFinalizeTicket(response)
{
  if(response.result == "OK"){
    $("#shippingDiv").find(".panelNav").fadeOut("slow", function(){
      flashNotice("<div style='padding:15px; text-align:center;'>Your pre-paid FedEx shipping label has been sent to the email you provided!<br /><span style='font-size:smaller;'>It should arrive within a few minutes.</span></div>");
    });
  }
  else {
    flashError(response.message);
  }
}

function addFile(file)
{
  var pass = true;
  pass = pass &&
          notEmpty($("#fileSelection input[name='fileSelectInput']").val(),"File name cannot be blank", "#fileSelection input", "?");

  if(!pass) return;

//  if($("#fileSelectionResults ol").children().size() < 5) {
    $("#fileSelectionResults ol").append("<li><span>"+$("#fileSelection input[name='fileSelectInput']").val()+"</span><a href='#' onClick='removeFile($(this).parent().prevAll().length);'>X</a></li>");
    $("#fileSelectionResults ol li:last-child").effect("highlight",1000);
//  }
//  else {
//    flashError("You may only recover up to 5 files. If you wish to recover more consider our Media Recovery service.");
//  }
  $("#fileSelection input[name='fileSelectInput']").attr("value","");

}

function checkCheck(sel)
{
    var key = $(sel).prevAll().length/2;
    var val = $(sel).attr("checked");
    var numChecked = $("#fileSelection").find("input[type='checkbox']:checked").length;console.log(key+":"+val+":"+numChecked);
    formCompleteCheck(jQuery.epc.mediaPanel, $("#mediaDiv").find(".accordion-control :last-child"));
}

function addType(type)
{
  var pass = true;
  pass = pass &&
          notEmpty($("input[name='extraTypeField']").val(),"File type cannot be blank", "input[name='extraTypeField']");

  if(!pass) return;

    $("#extraTypes").append("<input type='checkbox' class='epc-checkbox' value='"+$("input[name='extraTypeField']").val()+"' id= '"+$("input[name='extraTypeField']").val()+"' onChange='checkCheck($(\"this\"))' checked />"+$("input[name='extraTypeField']").val()+" <br />");
    $("#extraTypes:last-child").effect("highlight",1000);
    $("input[name='extraTypeField']").attr("value","");

}

function removeFile(index)
{
  $("#fileSelectionResults ol li:eq("+index+")").fadeOut("slow", function(){$(this).remove();});
}

function dontKnowMediaSize()
{
  resetAjaxLoader("input[name='mediaSizeInput']");
  $.post("ajax/tickets.php", {action: "create", key: "mediaSize", val: "0"},
    function(data){
      if(data.indexOf("SUCCESS") != -1){
        flashNotice("That's Ok - We'll discuss that later");
        $("#mediaSizeResult").css("border", "none").css("background-color", "#FFFFFF").html("<p>? GB</p>");
        $("input[name='mediaSizeInput']").attr("value","?");
        $("input[name='mediaSizeInput']").siblings(".fieldOK").fadeIn("slow");
        jQuery.epc.mediaPanel[1] = 1;
        recalculatePrice();
        formCompleteCheck(jQuery.epc.mediaPanel, $("#mediaDiv").find(".accordion-control :last-child"));
      }
      else{
        jQuery.epc.mediaPanel[1] = 0;
        flashError("Oops, something went wrong. Try Again.")
      }
    })
}

function dontKnowMediaType()
{
  resetAjaxLoader("select[name='mediaType']");
  $.post("ajax/tickets.php", {action: "create", key: "mediaType", val: "idk"},
    function(data){
      if(data.indexOf("SUCCESS") != -1){
        flashNotice("That's Ok - We'll discuss that later");
        $("select[name='mediaType']").attr("value","idk");
        $("select[name='mediaType']").siblings(".fieldOK").fadeIn("slow");
        jQuery.epc.mediaPanel[0] = 1;
        recalculatePrice();
        formCompleteCheck(jQuery.epc.mediaPanel, $("#mediaDiv").find(".accordion-control :last-child"));
      }
      else{
        jQuery.epc.mediaPanel[0] = 0;
        flashError("Oops, something went wrong. Try Again.")
      }
    })
}

function specificFileType()
{
  var fileTypes = "";
  $("#specificFileTypeField").slideDown("slow");
  $("#ticket-form").animate({height:"+=2em"},1000);

  $("#extraTypeField").autocomplete({
    source: ["zip","pdf","mp3","jpg","rar","exe","wmv","doc","avi","ppt","mpg","tif","wav","mov","psd","wma","sitx","sit","eps","cdr","ai","xls","mp4","txt","m4a","rmvb","bmp","pps","aif","pub","dwg","gif","qbb","mpeg","indd","swf","asf","png","dat","rm","mdb","chm","jar","htm","dvf","dss","dmg","iso","flv","wpd","cda","m4b","7z","gz","fla","qxd","rtf","aiff","msi","jpeg","3gp","cdl","vob","ace","m4p","divx","html","pst","cab","ttf","xtm","hqx","qbw","sea","ptb","bin","mswmm","ifo","tgz","log","dll","mcd","ss","m4v","eml","mid","ogg","ram","lnk","torrent","ses","mp2","vcd","bat","asx","ps","bup","cbr","amr","wps","sql"]
  });
  setTimeout("$(\"input[name='specificFileTypeField']\").focus()", 700);
}

function dontKnowFileNames()
{
/*
  flashNotice("That's Ok - We'll discuss that later");
  $("#fileSelectionResults").append("<li style='padding-top:15px; font-size:1em;'>I don't know the names of the files I need recovered</li>");
  $("#fileSelectionResults:last-child").effect("highlight",1000);
  $("#fileSelection input").attr("value", "?");
*/
  $("#fileSelectionResults ol").children().fadeOut("slow");
  $("#fileSelectDiv").fadeOut("slow", function(){
    $("#fileSelectDiv").html("<span class='epc-text'><p>No Specific Files</p></span><span class='fieldlink'><a href='#' onClick='doKnowFileNames()'>I want to add specific files or directories</a></span>").fadeIn("slow");
  });
}

function doKnowFileNames()
{
  $("#fileSelectDiv").fadeOut("slow", function(){
    $("#fileSelectDiv").html("<label for='fileSelectInput'>Any specific files or directories you'd like recovered?</label>\n\
            <input type='text' size=25 maxlength=25 name='fileSelectInput' class='epc-textfield idleField' />\n\
            <button href='#' onClick='addFile(); return false;' class='epc-button epc-button-icon-right ui-state-default ui-corner-all ie-fix-button-height'><span class='ui-icon ui-icon-circle-plus'></span><b>Add File</b></button><br />\n\
            <span class='fieldlink'><a href='#'onClick='dontKnowFileNames()'>Nevermind, I don't want to add specific files or directories</a></span>\n\
            ").fadeIn("slow");
  });
}

function onChangeMediaType(val)
{
  resetAjaxLoader("select[name='mediaType']");
  console.log(val);
  switch(val){
    case "Other":
      $("#mediaTypeByTextbox").html("<div class='formfield'>\n\
                                      <label for='mediaTypeByTextbox'>Describe your media</label>\n\
                                      <input name='mediaTypeByTextbox' class='epc-textfield idleField float_left' type='text' onChange='onChangeMediaTypeByTextbox()' /><span class='fieldOK'></span>\n\
                                     </div>");
      $("#mediaTypeByTextbox").slideDown("slow");
      setTimeout("$(\"input[name='mediaTypeByTextbox']\").focus()", 700);
      break;
    case "none":
      $("#mediaTypeByTextbox").slideUp("slow");
      $("select[name='mediaType']").siblings(".fieldOK").fadeOut("slow");
      break;
    default:
      $("#mediaTypeByTextbox").slideUp("slow");
      $.post("ajax/tickets.php", {action: "create", key: "mediaType", val: val},
        function(data){
          if(data.indexOf("SUCCESS") != -1){
            $("select[name='mediaType']").siblings(".fieldOK").fadeIn("slow");
            jQuery.epc.mediaPanel[0] = 1;
            formCompleteCheck(jQuery.epc.mediaPanel, $("#mediaDiv").find(".accordion-control :last-child"));
          }
          else {
            jQuery.epc.mediaPanel[0] = 0;
            flashError("Oops, something went wrong. Try Again.");
          }
        })
      //no action
  }
  recalculatePrice();
}

function handleCreateAccountResponse(respArray)
{
	var result = xr(respArray);
	var message = xm(respArray);
	switch(result)
	{
		case "0":
			flashNotice(message);
      $("#login-message").html("Welcome "+respArray[3]+"<br /><a href='#' onClick='logout()'>Log out</a>");
			break;
		case "1":
			flashError(message);
			break;
		default:
			alert("Unkown Response from Server");
			break;
	}
}

function onChangeMediaTypeByTextbox()
{
  $.post("ajax/tickets.php", {action: "create", key: "mediaType", val: $("input[name='mediaTypeByTextbox']").val()},
    function(data){
      if(data.indexOf("SUCCESS") != -1){
        $("input[name='mediaTypeByTextbox']").siblings(".fieldOK").fadeIn("slow");
        $("select[name='mediaType']").siblings(".fieldOK").fadeIn("slow");
        jQuery.epc.mediaPanel[0] = 1;
        recalculatePrice();
        formCompleteCheck(jQuery.epc.mediaPanel, $("#mediaDiv").find(".accordion-control :last-child"));
      }
      else {
        jQuery.epc.mediaPanel[0] = 0;
        flashError("Oops, something went wrong. Try Again.");
      }
    });
}

function onChangeMediaSize(size)
{
	var pass = true;
	pass = pass &&
					isNumeric(size, "Media size should be a number!", "input[name='mediaSizeInput']","?") &&
					inBounds(size, 1, 9999, "Hmm, that doesn't seem right...You sure you have a "+size+" GB drive?","input[name='mediaSizeInput']", "?");

	if(!pass)
		return;

  resetAjaxLoader("input[name='mediaSizeInput']");

  $.post("ajax/tickets.php",{action: "create", key: "mediaSize", val: size},
    function(data){
      if(data.indexOf("SUCCESS") != -1){
        $("input[name='mediaSizeInput']").siblings(".fieldOK").fadeIn("slow");
       jQuery.epc.mediaPanel[1] = 1;
       recalculatePrice();
       formCompleteCheck(jQuery.epc.mediaPanel, $("#mediaDiv").find(".accordion-control :last-child"));
      }
    });
}

function formCompleteCheck(p, button)
{
  var pass = true;
  for(i=0; i<p.length;i++){
    pass = pass && p[i];
  }
  if(pass){
    $(button).removeAttr("disabled").removeClass("ui-state-disabled").addClass("ui-state-default");
  }
  else {
    $(button).attr("disabled","true").removeClass("ui-state-default").addClass("ui-state-disabled");
  }
}

function testMail()
{
  $.post("ajax/tickets.php", {action:"testMail"}, function(){
    flashNotice("Test Mail Sent");
  });
}

function recalculatePrice()
{
  var total = 99;
  var fudge = 0;
  var mediaType = $("#mediaType").val().toLowerCase();
  var mediaSize = $("#mediaSize").val();


  if( (mediaType == "external hard drive") ||
      (mediaType == "desktop hard drive") ||
      (mediaType == "laptop hard drive")) {
      total += 30;
  }
  else if(  (mediaType == "phone") ) {
    total += 50;
  }
  if((mediaSize >= 100) && (mediaSize < 300)) {
    fudge += 30
  }
  else if((mediaSize >= 300) && (mediaSize < 500)) {
    fudge += 40
  }
  else if((mediaSize >= 500)) {
    fudge += 50
  }
  
  $("#priceTotal").jCounter({
      count: total+fudge,
      currency: true,
      counterBg: "http://www.fmlrecovery.com/js/plugins/jCounter/bg-counter.png",
      counterImg: "http://www.fmlrecovery.com/js/plugins/jCounter/counter-numbers.png",
      dollarImg: "http://www.fmlrecovery.com/js/plugins/jCounter/dollar.png"
    });

  if( (mediaType == "idk") ||
      (mediaType == "other") ||
      (mediaSize == "?") ||
      (mediaSize >= 500 )){
    $("#priceTotalModifier").fadeIn("slow");
  }
  else {
    $("#priceTotalModifier").fadeOut("slow");
  }
}
