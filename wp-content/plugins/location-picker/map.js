/**
 * map.js - client-side map functionality
 */

  var geocoder;
  var map;
  var marker;
  var searchTimer;
  
  jQuery(document).ready( function() {
	  jQuery('#cf7_location_picker_postcode').change(function() {
		  if (searchTimer != null)
			  window.clearTimeout(searchTimer);
		  searchTimer = window.setTimeout(function() { search(); }, 600);
	  });
	 

    
  });
  
  function init() {
    geocoder = new google.maps.Geocoder();
    if (typeof(preLat) != 'undefined')
    {
    	var latlng = new google.maps.LatLng(preLat, preLong);
    	var zoom = 10;
    }
    else
    {
    	var latlng = new google.maps.LatLng(28.6189542,77.289605);
    	var zoom = 10;
    }
    var myOptions = {
      zoom: zoom,
      center: latlng,
	  streetViewControl: true,
      disableDefaultUI: false,
	  panControl:true,
	  rotateControl:true,
	  scaleControl:true,
	  overviewMapControl:true,
      mapTypeId: google.maps.MapTypeId.TERRAIN,
      zoomControl: true,
      zoomControlOptions: { position: google.maps.ControlPosition.TL, style: google.maps.ZoomControlStyle.SMALL },
	  mapTypeControl:true,
    mapTypeControlOptions: {
      style:google.maps.MapTypeControlStyle.DROPDOWN_MENU
    }
    };
    
    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
    
    if (typeof(preLat) != 'undefined')
    {
        marker = new google.maps.Marker({
            map: map,
            position: latlng,
            title: 'This is my roof',
            animation: google.maps.Animation.BOUNCE
          });          	
    }
    google.maps.event.addListener(map, 'click', function(event) {
      mapclicked(event.latLng);
    });
    
  }

  function search() {
    var address = document.getElementById("cf7_location_picker_postcode").value;
    geocoder.geocode( { 'address': address}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {

        map.setCenter(results[0].geometry.location);            
        map.setZoom(15);
        
      } else {
    	  alert("Sorry! We couldn't find that location (error code " + status + ")");
      }
    });
  }

  function mapclicked(loc) {
	  
		var geocoder = new google.maps.Geocoder;
		 geocoder.geocode({'location': loc}, function(results, status) {
    if (status === google.maps.GeocoderStatus.OK) {
      if (results[1]) {
       
	  jQuery('#cf7_location_picker_address').html(results[1].formatted_address);
    //   alert(results[1].formatted_address);
        
      } else {
        window.alert('No results found');
      }
    } else {
      window.alert('Geocoder failed due to: ' + status);
    }
  });
  
		
      var latlng = loc.toString();
     jQuery('#cf7_location_picker_output').val(latlng);
      if (marker != null)
        marker.setPosition(loc);
      else
      marker = new google.maps.Marker({
        map: map,
        position: loc,
        title: 'This is my roof',
        animation: google.maps.Animation.BOUNCE
      });      
  }

  google.maps.event.addDomListener(window, 'load', init);
