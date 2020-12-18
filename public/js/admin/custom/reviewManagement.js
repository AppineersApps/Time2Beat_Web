$(document).ready(function () {
$(".gmf_addr_label_r_address").hide();
$(".frm-gmf-options").hide();
	var latitude=parseFloat(document.getElementById("r_latitude").value);
    var longitude=parseFloat(document.getElementById("r_longitude").value);

 setTimeout(function(){ 
 	
 var uluru = {lat: latitude, lng: longitude};
  // The map, centered at Uluru
  var map = new google.maps.Map(
      document.getElementById('map_canvas_r_address'), {zoom: 6, center: uluru});
  // The marker, positioned at Uluru
  var marker = new google.maps.Marker({position: uluru, map: map});
		
	},100);

});