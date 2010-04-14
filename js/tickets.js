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
});

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
          <label for='fileSelectInput'>What files would you like recovered?</label>\n\
          <input type='text' size=30 maxlength=25 name='fileSelectInput' class='epc-textfield idleField' />\n\
          <button href='#' onClick='addFile(); return false;' class='epc-button epc-button-icon-left ui-state-default ui-corner-all'><span class='ui-icon ui-icon-circle-plus'></span>Add file</button><br />\n\
          <a href='#' style='font-size: 0.8em;' onClick='dontKnowFileNames()'>I don't know</a>\n\
         </div>\n\
        <div id='fileSelectionResults><ol></ol></div>\n\
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
  flashError("not implemented yet!");
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
  //var res = $("select [name='mediaType']:selected").val();
  var img;
  $("#mediaTypeByTextbox").html("");
  switch(val){
    case "1":
      img = "images/media/external.gif";
      break;
    case "2":
      img = "images/media/internal.gif";
      break;
    case "3":
      img = "images/media/laptop.gif";
      break;
    case "4":
      img = "images/media/flash.gif";
      break;
    case "5":
      $("#mediaTypeByTextbox").html("<div class='formfield'>\n\
                                      <label for='mediaTypeByTextbox'>Describe your media</label>\n\
                                      <input class='epc-textfield idleField' type='text' onChange='onChangeMediaTypeByTextbox()' /></div>");
      break;
    default:
      img = "images/media/nopicture.gif";
  }
//  $("#mediaTypeResult").css("border", "none").css("background-color", "#FFFFFF").html("<div class='wraptocenter'><span></span><img src='"+img+"'/></div>");
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
