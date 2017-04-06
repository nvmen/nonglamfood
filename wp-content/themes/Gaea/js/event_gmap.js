function SingleMap() {
	
  var properties=[];
             properties.push({                                
        lat :jQuery('.event_container').find('.latitude').text(),
        lng :jQuery('.event_container').find('.longitude').text() ,
     });   
  
var mapOptions = {
                zoom: 4,
				center: new google.maps.LatLng(properties[0].lat,properties[0].lng),
                scrollwheel: false
            }
            var map = new google.maps.Map(document.getElementById("onemap"), mapOptions);
            var markers = new Array();
            var info_windows = new Array();
            for (var i=0; i < properties.length; i++) {
                              markers[i] = new google.maps.Marker({
                    position: map.getCenter(),
                    map: map,
                    icon: properties[i].icon,
                    title: properties[i].title,
                    animation: google.maps.Animation.DROP
                });
        attachInfoWindowToMarker(map, markers[i], info_windows[i]);
            }
            /* function to attach infowindow with marker */
            function attachInfoWindowToMarker( map, marker, infoWindow ){
                google.maps.event.addListener( marker, 'click', function(){
                    infoWindow.open( map, marker );
                });
jQuery('#map-modal').on('shown.bs.modal', function () {
  google.maps.event.trigger(map, 'resize');
  map.setCenter(new google.maps.LatLng(properties[0].lat, properties[0].lng));
});
            }
        }
  if(jQuery('.event_container').length>0){
   google.maps.event.addDomListener(window, 'load', SingleMap);
}		