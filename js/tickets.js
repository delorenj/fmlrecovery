$(document).ready(function() {
//	$("#phase1").css("display", "block");
	$("#ticket-accordion").accordion({
		autoHeight: false,
		fillSpace: true
	});

	$("#mediaSizeSlider").slider({
		min:10,
		max:2000,
		step: 10,
		slide: function(event, ui) {
  		$("#mediaSizeResult").css("border", "none").css("background-color", "#FFFFFF").html("<p>"+$("#mediaSizeSlider").slider("value")+" GB</p>");
		}
	});
});

function onMediaTypeChange(val)
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

function initPhase2()
{
	$("#phase2").slideDown('slow');
}

function initPhase3()
{
	$("#phase3").slideDown('slow');
}
