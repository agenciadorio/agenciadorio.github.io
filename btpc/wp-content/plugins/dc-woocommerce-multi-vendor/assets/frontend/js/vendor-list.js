/* global wcmp_vendor_list_script_data */
(function ($) {
    
    // wcmp_vendor_list_script_data is required to continue, ensure the object exists
    if ( typeof wcmp_vendor_list_script_data === 'undefined' ) {
        return false;
    }

    var markers = [];
    var infoWindow;
    var map;
    var bounds;
    var init_options;
    var styles
    var styledMap
    
    function initialize() { 
	// Create a map object and specify the DOM element for display.
        var init_options = wcmp_vendor_list_script_data.map_data.map_options;
        if(wcmp_vendor_list_script_data.map_data.map_style == '1'){
            wcmp_vendor_list_script_data.map_data.map_options.mapTypeControlOptions.mapTypeIds.push('wcmp_map_style');
            var styles = wcmp_vendor_list_script_data.map_data.map_style_data;
            var styledMap = new google.maps.StyledMapType(styles, { name: wcmp_vendor_list_script_data.map_data.map_style_title });
        }
        var map = new google.maps.Map(document.getElementById("wcmp-vendor-list-map"), init_options);
        if(wcmp_vendor_list_script_data.map_data.map_style == '1'){
            map.mapTypes.set('wcmp_map_style', styledMap);
            map.setMapTypeId('wcmp_map_style');
        }
        
	infoWindow = new google.maps.InfoWindow();
        bounds = new google.maps.LatLngBounds();
	// Try HTML5 geolocation.
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                // set center position
                $('#wcmp_vlist_center_lat').val(position.coords.latitude);
                $('#wcmp_vlist_center_lng').val(position.coords.longitude);

                map.setCenter(pos);
                map.fitBounds(bounds);
            }, function(error) {
                handleLocationError(true, infoWindow, map.getCenter(), error);
            });
        } else {
            // Browser doesnt support Geolocation
            handleLocationError(false, infoWindow, map.getCenter(), -1);
        }

        function handleLocationError(browserHasGeolocation, infoWindow, pos, error) {
            if( error == -1 ){
                alert( wcmp_vendor_list_script_data.lang.geolocation_doesnt_support );
            }else{
                switch( error.code ) {
                    case error.PERMISSION_DENIED:
                    alert( wcmp_vendor_list_script_data.lang.geolocation_permission_denied );
                    break;
                    case error.POSITION_UNAVAILABLE:
                    alert( wcmp_vendor_list_script_data.lang.geolocation_position_unavailable );
                    break;
                    case error.TIMEOUT:
                    alert( wcmp_vendor_list_script_data.lang.geolocation_timeout );
                    break;
                    case error.UNKNOWN_ERROR:
                    alert( wcmp_vendor_list_script_data.lang.geolocation_service_failed );
                    break;
                }
            }
        
            infoWindow.setPosition(pos);
            infoWindow.setContent(browserHasGeolocation ? wcmp_vendor_list_script_data.lang.geolocation_service_failed : wcmp_vendor_list_script_data.lang.geolocation_doesnt_support);
            infoWindow.open(map);
        }

	function createMarker(storeInfo) { 
            bounds.extend(new google.maps.LatLng(storeInfo.location.lat, storeInfo.location.lng));
            var marker = new google.maps.Marker({
                map: map,
                icon: wcmp_vendor_list_script_data.map_data.marker_icon,
                position: new google.maps.LatLng(storeInfo.location.lat, storeInfo.location.lng),
                title: storeInfo.store_name
            });
            google.maps.event.addListener(marker, "click", function() {
                infoWindow.setContent(storeInfo.info_html);
                infoWindow.open(map, marker);
            });
            markers.push(marker);
        }

	wcmp_vendor_list_script_data.stores.forEach(function(store){
            createMarker(store);
	}); 
        
        $('#locationText').on('input', function(){
            var value = $(this).val();
            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({address: value}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    var res_location = results[0].geometry.location;
                    map.setCenter(res_location);
                    // set center position
                    $('#wcmp_vlist_center_lat').val(res_location.lat());
                    $('#wcmp_vlist_center_lng').val(res_location.lng());
                } else {  
                }
            });
        });
  
    }
    
    google.maps.event.addDomListener(window, "load", initialize);
    
})(jQuery);

