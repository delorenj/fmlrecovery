$(document).ready(function() {
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
});

function indexToGB(i)
{
  switch(i)
  {
    case 1:
      return "< 1 GB";
    case 2:
      return "8 GB";
    case 3:
      return "16 GB";
    case 4:
      return "40 GB";
    case 5:
      return "60 GB"
    case 6:
      return "80 GB";
    case 7:
      return "100 GB";
    case 8:
      return "250 GB";
    case 9:
      return "500 GB";
    case 10:
      return "> 1 TB";
  }
}

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
