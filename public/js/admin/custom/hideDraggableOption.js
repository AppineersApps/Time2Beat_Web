$(document).ready(function () {
$(".frm-gmf-address").hide();
$(".frm-gmf-options").hide();
	var latitude=parseFloat(document.getElementById("u_latitude").value);
    var longitude=parseFloat(document.getElementById("u_longitude").value);

 setTimeout(function(){ 
 	
 var uluru = {lat: latitude, lng: longitude};
  // The map, centered at Uluru
  var map = new google.maps.Map(
      document.getElementById('map_canvas_u_address'), {zoom: 6, center: uluru});
  // The marker, positioned at Uluru
  var marker = new google.maps.Marker({position: uluru, map: map});
		
	},100);

})