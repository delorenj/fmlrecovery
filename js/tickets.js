google.setOnLoadCallback(function(){

//	$("#phase1").css("display", "block");
	$("#ticket-accordion").accordion({
		autoHeight: false,
		fillSpace: true
	});

	$("#mediaSizeSlider").slider({
    value:1,
		min:1,
		max:10,
		slide: function(event, ui) {
  		$("#mediaSizeResult").css("border", "none").css("background-color", "#FFFFFF").html("<p>"+indexToGB(ui.value)+"</p>");
		}
	});
/*
  $(".service").hover(function(){
    $(this).find("label").animate({
      fontSize: '+=4px',
    }, 200);
  }, function(){
    $(this).find("label").animate({
      fontSize: '-=4px',
    },200);
   })
   .click(function(){
     validateService($(this).prevAll().length);
   });
*/
  $(".service").hover(function(){
    $(this).css({
      borderBottom: '8px solid #8FA3C6',
    });
    displayServiceInfo($(this).prevAll().length);
    $("#serviceInfo").fadeIn('fast', function(){
    });
  }, function(){
    $(this).css({
      borderBottom: '8px solid #051E5D',
    });
    $("#serviceInfo").fadeOut();
   })
   .click(function(){
     validateService($(this).prevAll().length);
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
      html += "<p>This is info on Media Recovery</p>";
      break;
    case 2:
      html += "<p>This is info on Full Recovery</p>";
      break;
  }
  $("#serviceInfo").html(html);
}

function validateService(index)
{
  $("#ticket-accordion").accordion("activate", 1)
}


function onChangeMediaType(val)
{
  //var res = $("select [name='mediaType']:selected").val();
  var res = val;
  var img;
  switch(res){
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
    default:
      img = "images/media/nopicture.gif";
  }
  $("#mediaTypeResult").css("border", "none").css("background-color", "#FFFFFF").html("<div class='wraptocenter'><span></span><img src='"+img+"'/></div>");
}

function onChangeMediaSize(size)
{
	var pass = true;
	pass = pass &&
					isNumeric(size, "Media size should be a number!", "input[name='mediaSizeInput']") &&
					inBounds(size, 1, 9999, "Hmm, that doesn't seem right...You sure you have a "+size+" GB drive?","input[name='mediaSizeInput']");

	if(!pass)
		return;

  		$("#mediaSizeResult").css("border", "none").css("background-color", "#FFFFFF").html("<p>"+size+" GB</p>");
	flashNotice("Looks Good holmes!");
}
