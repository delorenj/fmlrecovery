google.setOnLoadCallback(function(){

	$("#ticket-accordion").accordion({
		autoHeight: false,
		fillSpace: true,
    change: function(event, ui) {
      console.log("change triggered:" + ui.newContent.css("height")); // jQuery object, activated content
      //ui.oldContent // jQuery object, previous content
    }
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
 });
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
  var winHeight = $(window).height();
var tabHeight = winHeight - 10;
$('#ticket-accordion').height(tabHeight);
var contentPanelHeight = $("#ticket-accordion").find(".ui-tabs-panel:visible").height();
console.log(contentPanelHeight);


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
      $("#ticket-accordion").accordion("activate", 1);   
    });
  switch(index){
    case 0: //Media
      $("#fileSelection").html("").append("\n\
        <div class='formfield'>\n\
          <div class='clearfix'>\n\
            <label for='fileTypeSelectInput'>What types of media are you interested in recovering?</label>\n\
            <div class='epc-checkbox-group lcolumn'>\n\
              <input type='checkbox' class='epc-checkbox' value='music'/>Music <br />\n\
              <input type='checkbox' class='epc-checkbox' value='documents'/>Text Documents <br />\n\
              <input type='checkbox' class='epc-checkbox' value='pictures'/>Pictures <br />\n\
              <input type='checkbox' class='epc-checkbox' value='videos'/>Videos <br />\n\
              <input type='checkbox' class='epc-checkbox' value='archives'/>Archived Files <br />\n\
            </div>\n\
            <div id='extraTypes' class='epc-checkbox-group lcolumn'></div>\n\
          </div>\n\
          <a href='#' style='font-size: 0.8em;' onClick='specificFileType()'>I want to add a specific file type</a>\n\
        </div>\n\
        <div class='clearfix'>&nbsp;</div>\n\
        <div id='specificFileTypeField' class='float_left'>&nbsp;</div>\n\
        <div class='clearfix'></div>\n\
        <div class='formfield'>\n\
          <div class='clearfix'>\n\
            <label for='fileSelectInput'>Any specific files or directories you'd like recovered?</label>\n\
            <input type='text' size=25 maxlength=25 name='fileSelectInput' class='epc-textfield idleField' />\n\
            <button href='#' onClick='addFile(); return false;' class='epc-button epc-button-icon-right ui-state-default ui-corner-all ie-fix-button-height'><span class='ui-icon ui-icon-circle-plus'></span><b>Add File</b></button><br />\n\
            <a href='#' style='font-size: 0.8em;' onClick='dontKnowFileNames()'>I don't know</a>\n\
          </div>\n\
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

function addType(type)
{
  var pass = true;
  pass = pass &&
          notEmpty($("input[name='specificFileTypeField']").val(),"File type cannot be blank", "input[name='specificFileTypeField']");

  if(!pass) return;

    $("#extraTypes").append("<input type='checkbox' class='epc-checkbox' value='"+$("input[name='specificFileTypeField']").val()+"' checked />"+$("input[name='specificFileTypeField']").val()+" <br />");
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
      }
      else{
        flashError("Oops, something went wrong. Try Again.")
      }
    })
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
  resetAjaxLoader("select[name='mediaType']");
  $.post("tickets.php", {action: "create", key: "mediaType", val: "idk"},
    function(data){
      if(data.indexOf("SUCCESS") != -1){
        flashNotice("That's Ok - We'll discuss that later");
        $("select[name='mediaType']").attr("value","dontknow");
        $("select[name='mediaType']").siblings(".fieldOK").fadeIn("slow");
      }
      else{
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
  flashNotice("That's Ok - We'll discuss that later");
  $("#fileSelectionResults").append("<li style='padding-top:15px; font-size:1em;'>I don't know the names of the files I need recovered</li>");
  $("#fileSelectionResults:last-child").effect("highlight",1000);
  $("#fileSelection input").attr("value", "?");
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
          }
          else {
            flashError("Oops, something went wrong. Try Again.");
          }
        })
      //no action
  }
}

function onChangeMediaTypeByTextbox()
{
  $.post("tickets.php", {action: "create", key: "mediaType", val: $("input[name='mediaTypeByTextbox']").val()},
    function(data){
      if(data.indexOf("SUCCESS") != -1){
        $("input[name='mediaTypeByTextbox']").siblings(".fieldOK").fadeIn("slow");
        $("select[name='mediaType']").siblings(".fieldOK").fadeIn("slow");
      }
      else {
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
      }
    });
}
