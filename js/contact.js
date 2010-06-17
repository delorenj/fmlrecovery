google.setOnLoadCallback(function(){
  initializeMyLocationMap();

  $(function(){
    $("#socialMedia img").live("mouseover mouseout", function(event){
      if(event.type =='mouseover'){
        $(this).animate({
          top: '-=8'
        }, 100);
      }
      if(event.type == 'mouseout'){
        $(this).animate({
          top: '+=8'
        }, 100);
      }
    });
  });
});

function initializeMyLocationMap()
{
  var latlng = new google.maps.LatLng(40.9, -74.7);
  var myOptions = {
    zoom: 10,
    center: latlng,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };
  var marker = new google.maps.Marker({
      position: latlng,
      title:"FML Recovery"
  });

  // To add the marker to the map, call setMap();
  var map = new google.maps.Map(document.getElementById("myLocation"), myOptions);
  marker.setMap(map);
}

function sendMessage()
{
  //TODO: Create a sendMessage function
  comingSoon();
}