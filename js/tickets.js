google.setOnLoadCallback(function(){

	$("#ticket-accordion").accordion({
		autoHeight: false,
		clearStyle: true,
    animated:false
	});

  $(".ui-accordion-header").unbind("click");

  $(".ui-accordion-header")
    .live("mouseover mouseout", function(event){
      if(event.type == 'mouseover'){
				$(this).removeClass("ui-state-hover").css("cursor", "default").children().css("cursor", "default");
			}
      if(event.type == 'mouseout'){
				$(this).removeClass("ui-state-hover").css("cursor", "default").children().css("cursor", "default");
			}
    })
  
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

  $(".service").hover(function(){
    $(this).css({
      borderBottom: '8px solid #8FA3C6'
    });
    //displayServiceInfo($(this).prevAll().length);
  }, function(){
    $(this).css({
      borderBottom: '8px solid #051E5D'
    });
    $("#serviceInfo > *").fadeOut();
   })
   .click(function(){
     validateServicePanel($(this).parent().prevAll().length);
   });

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
});


function onKeyupPhone(field)
{
  switch(field){
    case "phone1":
    case "phone2":
      if($("#"+field).val().length == 3){
        $("#"+field).focusNextInputField();
      }
      break;
    case "phone3":
      if($("#"+field).val().length == 4){
        $("#shippingForm").find("button[type='submit']").focus();
      }
      break;
  }
  $("#phone").val($("#phone1").val() + $("#phone2").val() + $("#phone3").val());
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
          <a href='#' class='small' onClick='forgotPassword()'>Forgot Password</a> or <a href='#' class='small' onClick='createAnAccount()'>Create an Account</a></div>")
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
  $.post("users.php", {action: "get", key: "defaultAddress"},
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
  //TODO: Send an email!
  flashNotice("Your password has been sent to your email.")
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

$.post("tickets.php", {action: "create", key: "service", val: index},
    function(data){
      if(data == "FAILED"){
        flashError("Oops, something went wrong. Try again.");
        return;
      }
      if(data.indexOf("SUCCESS") != -1){
        $("#ticket-accordion h3:first-child a").append("<img src='images/accept.png' style='margin-top:-5px;float:right;'/>")
      }
      $("#ticket-accordion").accordion("activate", 2);
    });
  switch(index){
    case 0: //Media
      $("#fileSelection").html("").append("\n\
        <div class='formfield'>\n\
          <div class='clearfix'>\n\
            <label for='fileTypeSelectInput'>What types of media are you interested in recovering?</label>\n\
            <div class='epc-checkbox-group lcolumn'>\n\
              <input type='checkbox' class='epc-checkbox' id='music'/>Music <br />\n\
              <input type='checkbox' class='epc-checkbox' id='documents'/>Text Documents <br />\n\
              <input type='checkbox' class='epc-checkbox' id='pictures'/>Pictures <br />\n\
              <input type='checkbox' class='epc-checkbox' id='videos'/>Videos <br />\n\
              <input type='checkbox' class='epc-checkbox' id='archives'/>Archived Files <br />\n\
              <input type='checkbox' class='epc-checkbox' id='other'/>Other <br />\n\
            </div>\n\
            <div id='extraTypes' class='epc-checkbox-group lcolumn'></div>\n\
          </div>\n\
        </div>\n\
        <div class='clearfix'>&nbsp;</div>\n\
        <div id='specificFileTypeField' class='float_left'>&nbsp;</div>\n\
        <div class='clearfix'></div>\n\
        <div class='formfield'>\n\
          <div class='clearfix' id='fileSelectDiv'>\n\
            <a href='#' onClick='doKnowFileNames()' style='font:normal 12px \"Lucida Grande\", Arial, sans-serif;'>I want to add specific files or directories</a>\n\
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
  $.post("tickets.php", {action: "create", key: "mediaDetail", val: "fileTypes="+fileTypeArray+"|specificFiles="+specificFileArray},
   function(data){
    if(data == "FAILED"){
      flashError("Oops, something went wrong. Try again.");
    }
    if(data.indexOf("SUCCESS") != -1){
      $("#ticket-accordion h3:eq(1) a").append("<img src='images/accept.png' style='margin-top:-5px;float:right;'/>")
      $("#ticket-accordion").accordion("activate", 2);
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

  if($("#passwordConfDiv").is(":visible")) {
    console.log("Creating Account");
    $.post("authenticate.php", {action: "create-account",
                                firstname: $("#firstname").val(),
                                lastname: $("#lastname").val(),
                                email: $("#email").val(),
                                password: $("#password").val(),
                                street: $("#street").val(),
                                zip: $("#zip").val(),
                                city: $("#city").val(),
                                state: $("#state").val(),
                                phone: $("#phone").val()},
      function(data){
        handleCreateAccountResponse(data.split("|"));
        if(xr(data.split("|")) == "0") {
          postLoginProcessing();
          console.log("Creating a new ticket for new user");
          $.post("tickets.php", {action: "finalize"}, function(datum){
            handleFinalizeTicket(datum);
          },'json');
        }
        else return;
      }
    );
  } else {
    console.log("User already logged in...");
    console.log("Creating a new ticket for existing user");
  }

  
/*
  $.post("tickets.php", {action: "create", key: "shippingDetail", val: "fileTypes="+fileTypeArray+"|specificFiles="+specificFileArray},
   function(data){
    if(data == "FAILED"){
      flashError("Oops, something went wrong. Try again.");
    }
    if(data.indexOf("SUCCESS") != -1){
      $("#ticket-accordion h3:eq(1) a").append("<img src='images/accept.png' style='margin-top:-5px;float:right;'/>")
      $("#ticket-accordion").accordion("activate", 2);
    }
  });
*/
  return false;
}

function handleFinalizeTicket(response)
{
  if(response.result == "0"){
    flashNotice(response.message);
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
    var numChecked = $("#fileSelection").find("input[type='checkbox']:checked").length;
    if((key == 5) && (val == 1)){ //Other
      specificFileType();
      numChecked--;
    }
    else if((key == 5) && (val == 0)){
      $("#specificFileTypeField").fadeOut("slow");
      $("#extraTypes").fadeOut("slow");
    }
/*
    if(numChecked > 0){
      jQuery.epc.mediaPanel[2] = 1;
    }
    else {
      jQuery.epc.mediaPanel[2] = 0;
    }
*/
    formCompleteCheck(jQuery.epc.mediaPanel, $("#mediaDiv").find(".accordion-control :last-child"));
}

function addType(type)
{
  var pass = true;
  pass = pass &&
          notEmpty($("input[name='specificFileTypeField']").val(),"File type cannot be blank", "input[name='specificFileTypeField']");

  if(!pass) return;

    $("#extraTypes").append("<input type='checkbox' class='epc-checkbox' value='"+$("input[name='specificFileTypeField']").val()+"' id= '"+$("input[name='specificFileTypeField']").val()+"' onChange='checkCheck($(\"this\"))' checked />"+$("input[name='specificFileTypeField']").val()+" <br />");
    $("#extraTypes:last-child").effect("highlight",1000);
    $("input[name='specificFileTypeField']").attr("value","");

}

function removeFile(index)
{
  $("#fileSelectionResults ol li:eq("+index+")").fadeOut("slow", function(){$(this).remove();});
}

function dontKnowMediaSize()
{
  resetAjaxLoader("input[name='mediaSizeInput']");
  $.post("tickets.php", {action: "create", key: "mediaSize", val: "0"},
    function(data){
      if(data.indexOf("SUCCESS") != -1){
        flashNotice("That's Ok - We'll discuss that later");
        $("#mediaSizeResult").css("border", "none").css("background-color", "#FFFFFF").html("<p>? GB</p>");
        $("input[name='mediaSizeInput']").attr("value","?");
        $("input[name='mediaSizeInput']").siblings(".fieldOK").fadeIn("slow");
        jQuery.epc.mediaPanel[1] = 1;
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
  $.post("tickets.php", {action: "create", key: "mediaType", val: "idk"},
    function(data){
      if(data.indexOf("SUCCESS") != -1){
        flashNotice("That's Ok - We'll discuss that later");
        $("select[name='mediaType']").attr("value","idk");
        $("select[name='mediaType']").siblings(".fieldOK").fadeIn("slow");
        jQuery.epc.mediaPanel[0] = 1;
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
  /*
  $("#specificFileTypeField").html("<div class='formfield'>\n\
                                      <label for='specificFileTypeField'>Popular File Types</label>\n\
                                      <select id='filetypes' class='multiselect' multiple='multiple' name='filetypes[]'>\n\
                                        <option value='doc'>doc</option>\n\
                                        <option value='doc'>ppt</option>\n\
                                        <option value='doc'>ai</option>\n\
                                        <option value='doc'>zip</option>\n\
                                        <option value='doc'>pdf</option>\n\
                                        <option value='doc'>mp3</option>\n\
                                        <option value='doc'>jpg</option>\n\
                                        <option value='doc'>rar</option>\n\
                                      </select>\n\
                                    </div>");

$("#filetypes").multiselect({sortable: false, searchable: false});
*/
  var fileTypes = "";
  $("#specificFileTypeField").html("<div class='formfield'>\n\
                                      <div class='clearfix'>\n\
                                        <label for='specificFileTypeField'>Specific File Type (i.e. mp3)</label>\n\
                                        <input id='extraTypeField' name='specificFileTypeField' size=10 maxlength=10 class='epc-textfield idleField float_left' type='text' /><span class='fieldOK'></span>\n\
                                        <button href='#' onClick='addType(); return false;' class='epc-button epc-button-icon-right ui-state-default ui-corner-all ie-fix-button-height'><span class='ui-icon ui-icon-circle-plus'></span><b>Add Type</b></button><br />\n\
                                      </div>\n\
                                     </div>");
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
    $("#fileSelectDiv").html("<span class='epc-text'><p>No Specific Files</p></span><a href='#' onClick='doKnowFileNames()' style='font:normal 12px \"Lucida Grande\", Arial, sans-serif;'>I want to add specific files or directories</a>").fadeIn("slow");
  });
}

function doKnowFileNames()
{
  $("#fileSelectDiv").fadeOut("slow", function(){
    $("#fileSelectDiv").html("<label for='fileSelectInput'>Any specific files or directories you'd like recovered?</label>\n\
            <input type='text' size=25 maxlength=25 name='fileSelectInput' class='epc-textfield idleField' />\n\
            <button href='#' onClick='addFile(); return false;' class='epc-button epc-button-icon-right ui-state-default ui-corner-all ie-fix-button-height'><span class='ui-icon ui-icon-circle-plus'></span><b>Add File</b></button><br />\n\
            <a href='#' style='font-size: 0.8em;' onClick='dontKnowFileNames()'>Nevermind, I don't want to add specific files or directories</a>\n\
            ").fadeIn("slow");
  });
}

function onChangeMediaType(val)
{
  resetAjaxLoader("select[name='mediaType']");
  switch(val){
    case "other":
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
      $.post("tickets.php", {action: "create", key: "mediaType", val: val},
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
  $.post("tickets.php", {action: "create", key: "mediaType", val: $("input[name='mediaTypeByTextbox']").val()},
    function(data){
      if(data.indexOf("SUCCESS") != -1){
        $("input[name='mediaTypeByTextbox']").siblings(".fieldOK").fadeIn("slow");
        $("select[name='mediaType']").siblings(".fieldOK").fadeIn("slow");
        jQuery.epc.mediaPanel[0] = 1;
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

  $.post("tickets.php",{action: "create", key: "mediaSize", val: size},
    function(data){
      if(data.indexOf("SUCCESS") != -1){
        $("input[name='mediaSizeInput']").siblings(".fieldOK").fadeIn("slow");
       jQuery.epc.mediaPanel[1] = 1;
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
