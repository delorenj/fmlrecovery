google.setOnLoadCallback(function(){
  initializeMyLocationMap();
});

function initializeMyLocationMap()
{
  var latlng = new google.maps.LatLng(40.9, -74.7);
  var myOptions = {
    zoom: 10,
    center: latlng,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };
  var map = new google.maps.Map(document.getElementById("myLocation"), myOptions);
}

function sendMessage()
{
  //TODO: Create a sendMessage function
  flashNotice("<h1 style='text-align:center; line-height:3.5em;'>Feature coming soon!</h1>");
}