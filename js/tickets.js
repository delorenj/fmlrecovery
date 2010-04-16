google.setOnLoadCallback(function(){

	$("#ticket-accordion").accordion({
		autoHeight: false,
		fillSpace: true
	});

//  $(".ui-accordion-header").unbind("click");
  $("#ticket-accordion h3 a").css("text-decoration", "none");
  
	$("#mediaSizeSlider").slider({
    value:1,
		min:1,
		max:10,
		slide: function(event, ui) {
  		$("#mediaSizeResult").css("border", "none").css("background-color", "#FFFFFF").html("<p>"+indexToGB(ui.value)+"</p>");
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
     validateService($(this).parent().prevAll().length);
   });

 $("input[name='deviceType']").click(function(){
   onClickDeviceType($(this).val());
 })
});

function onClickDeviceType(device)
{
  switch(device){
    case "desktop":
      flashNotice("Sounds like you have an internal hard drive");
      $("select[name='mediaType']").val("2");
      break;
    case "laptop":
      flashNotice("Sounds like you have an laptop hard drive");
      $("select[name='mediaType']").val("3");
      break;
    case "phone":
    case "usb":
      flashNotice("Sounds like you have flash media");
      $("select[name='mediaType']").val("4");
      break;
    case "other":
      flashNotice("Describe your media in the box below");
      $("select[name='mediaType']").val("5");
      break;
  }
  $("#mediaTypeHelpDialog").dialog("destroy");
}

function displayServiceInfo(index)
{
  var html ="";
  switch(index){
    case 0:
      html += "<div><h1><em>This</em> is info on Selective Recovery</h1></div>";
      break;
    case 1:
      html += "<div><h1><em>This</em> is info on Media Recovery</h1></div>";
      break;
    case 2:
      html += "<div><h1><em>This</em> is info on Full Recovery</h1></div>";
      break;
  }
  $("#serviceInfo").html(html);
}

function validateService(index)
{
  $.post("tickets.php", {action: "create", key: "service", val: index},
    function(data){
      if(data == "FAILED"){
        flashError("Oops, something went wrong. Try again.");
        return;
      }
      if(data == "SUCCESS_INITIAL_SELECTION"){
        $("#ticket-accordion h3:first-child a").append("<img src='images/accept.png' height=25 style='margin-top:-5px;float:right;'/>")
      }
      $("#ticket-accordion").accordion("activate", 1);   
    });
  switch(index){
    case 0: //Media
      $("#fileSelection").html("").append("\n\
        <div class='formfield clearfix'>\n\
          <label for='fileTypeSelectInput'>What types of media are you interested in recovering?</label>\n\
          <div class='epc-buttonset epc-buttonset-multi'\n\
            <button href='#' onClick='return false;' class='epc-button epc-button-icon-right ui-state-default ui-corner-left'><b>Music</b><span class='ui-icon ui-icon-volume-on'></span></button>\n\
            <button href='#' onClick='return false;' class='epc-button epc-button-icon-right ui-state-default'><b>Documents</b><span class='ui-icon ui-icon-document'></span></button>\n\
            <button href='#' onClick='return false;' class='epc-button epc-button-icon-right ui-state-default'><b>Pictures</b><span class='ui-icon ui-icon-image'></span></button>\n\
            <button href='#' onClick='return false;' class='epc-button epc-button-icon-right ui-state-default  ui-corner-right'><b>Videos</b><span class='ui-icon ui-icon-video'></span></button>\n\
          </div>\n\
          <div class='clearfix'></div>\n\
          <a href='#' style='font-size: 0.8em;' onClick='specificFileType()'>I want to add a specific file type</a>\n\
        </div>\n\
        <div id='specificFileTypeField'>&nbsp;</div>\n\
        <div class='formfield clearfix'>\n\
          <label for='fileSelectInput'>Any specific files or directories you'd like recovered?</label>\n\
          <input type='text' size=25 maxlength=25 name='fileSelectInput' class='epc-textfield idleField' />\n\
          <button href='#' onClick='addFile(); return false;' class='epc-button epc-button-icon-left ui-state-default ui-corner-all'><span class='ui-icon ui-icon-circle-plus'></span>Add File</button><br />\n\
          <a href='#' style='font-size: 0.8em;' onClick='dontKnowFileNames()'>I don't know</a>\n\
         </div>\n\
        <div id='fileSelectionResults'><ol></ol></div>\n\
        ");
      break;
    default:
      $("#fileSelection").html("");
  }
}

function addFile(file)
{
  var pass = true;
  pass = pass &&
          notEmpty($("#fileSelection input").val(),"File name cannot be blank", "#fileSelection input", "?");

  if(!pass) return;

  if($("#fileSelectionResults ol").children().size() < 5) {
    $("#fileSelectionResults ol").append("<li><span>"+$("#fileSelection input").val()+"</span><a href='#' onClick='removeFile($(this).parent().prevAll().length);'>X</a></li>");
    $("#fileSelectionResults ol li:last-child").effect("highlight",1000);
  }
  else {
    flashError("You may only recover up to 5 files. If you wish to recover more consider our Media Recovery service.");
  }
  $("#fileSelection input").attr("value","");

}

function removeFile(index)
{
  $("#fileSelectionResults ol li:eq("+index+")").fadeOut("slow", function(){$(this).remove();});
}

function dontKnowMediaSize()
{
  flashNotice("That's Ok - We'll discuss that later");
	$("#mediaSizeResult").css("border", "none").css("background-color", "#FFFFFF").html("<p>? GB</p>");
  $("input[name='mediaSizeInput']").attr("value","?");
}

function dontKnowMediaType()
{
  $("#mediaTypeHelpDialog").dialog({
    autoOpen: false,
    height: 300,
    width: 350,
    modal: true,
    buttons: {
/*
      'Ok': function() {
          $(this).dialog('close');

      },
      Cancel: function() {
        $(this).dialog('close');
      }
*/
    },
    close: function() {
      //TODO
    }
  });

//  $("#mediaTypeHelpDialog").dialog("open");
  flashNotice("That's Ok - We'll discuss that later");
  $("select[name='mediaType']").attr("value","dontknow");

}

function specificFileType()
{
  $("#specificFileTypeField").html("<div class='formfield'>\n\
                                      <label for='specificFileTypeField'>Popular File Types</label>\n\
                                      <select class='epc-select idleField'>\n\
                                        <option>doc [Microsoft Word Document]</option>\n\
                                        <option>ppt [Microsoft Powerpoint Document]</option>\n\
                                        <option>ai [Adobe Illustrator Document]</option>\n\
                                        <option>zip [Zip Archive]</option>\n\
                                        <option>pdf [PDF Document]</option>\n\
                                        <option>mp3 [MP3 Audio File]</option>\n\
                                        <option>jpg [JPG Image]</option>\n\
                                        <option>rar [RAR Archive]</option>\n\
                                      </select>\n\
                                    </div>");
}

function dontKnowFileNames()
{
  flashNotice("That's Ok - We'll discuss that later");
  $("#fileSelectionResults").append("<li style='padding-top:15px; font-size:1em;'>I don't know the names of the files I need recovered</li>");
  $("#fileSelectionResults:last-child").effect("highlight",1000);
  $("#fileSelection input").attr("value", "?");
}

function onChangeMediaType(val)
{
  $("#mediaTypeByTextbox").html("");
  switch(val){
    case "other":
      $("#mediaTypeByTextbox").html("<div class='formfield'>\n\
                                      <label for='mediaTypeByTextbox'>Describe your media</label>\n\
                                      <input class='epc-textfield idleField' type='text' onChange='onChangeMediaTypeByTextbox()' /></div>");
      break;
    default:
      //no action
  }
}

function onChangeMediaTypeByTextbox()
{
  flashNotice("Saved!\n(temporary message)");
}

function onChangeMediaSize(size)
{
	var pass = true;
	pass = pass &&
					isNumeric(size, "Media size should be a number!", "input[name='mediaSizeInput']","?") &&
					inBounds(size, 1, 9999, "Hmm, that doesn't seem right...You sure you have a "+size+" GB drive?","input[name='mediaSizeInput']", "?");

	if(!pass)
		return;

	$("#mediaSizeResult").css("border", "none").css("background-color", "#FFFFFF").html("<p>"+size+" GB</p>");
}
