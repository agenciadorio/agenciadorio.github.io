<h2><?php _e('Description', 'woocommerce-events'); ?></h2>

<p><?php echo $eventContent; ?></p>

<div id="google-map-holder" style="width: 100%; height: 400px;"></div>

<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $globalWooCommerceEventsGoogleMapsAPIKey; ?>&v=3.exp"></script>
<script>
function initialize() {
  var mapOptions = {
    zoom: 14,
    center: new google.maps.LatLng(<?php echo $WooCommerceEventsGoogleMaps; ?>),
scrollwheel: false, 
    mapTypeId: google.maps.MapTypeId.ROADMAP
  }
  var map = new google.maps.Map(document.getElementById('google-map-holder'),
                                mapOptions);

  var image = '<?php echo plugins_url(); ?>/fooevents/images/pin.png';
  var myLatLng = new google.maps.LatLng(<?php echo $WooCommerceEventsGoogleMaps; ?>);
  var beachMarker = new google.maps.Marker({
      position: myLatLng,
      map: map,
      icon: image
  });

}

google.maps.event.addDomListener(window, 'load', initialize);
    



</script>