function initMap() {
  // Initialize and add the map
  var infoWindow;
  var fogOverlay;

  // The location of our markers
  var kilburn = {lat: 53.467539, lng: -2.233927};
  var building2 = {lat: 53.468634, lng: -2.235898};
  // The map, centered at the Kilburn building
  var map = new google.maps.Map(
      document.getElementById('map'), {zoom: 17, maxZoom: 17, minZoom: 17, center: kilburn, disableDefaultUI: false,
      zoomControl: false,
      mapTypeControl: false,
      scaleControl: false,
      streetViewControl: false,
      rotateControl: false,
      fullscreenControl: false,});
  infoWindow = new google.maps.InfoWindow;

  if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position) {
        var pos = {
          lat: position.coords.latitude,
          lng: position.coords.longitude
        };

        var person = new google.maps.Marker({position: pos, map: map});
        map.setCenter(pos);
      }, function() {
        handleLocationError(true, infoWindow, map.getCenter());
      });
    } else {
      // Browser doesn't support Geolocation
      handleLocationError(false, infoWindow, map.getCenter());
    }


  function handleLocationError(browserHasGeolocation, infoWindow, pos) {
    infoWindow.setPosition(pos);
    infoWindow.setContent(browserHasGeolocation ?
                          'Error: The Geolocation service failed.' :
                          'Error: Your browser doesn\'t support geolocation.');
    infoWindow.open(map);
  }

  // The markers, positioned at the buidlings we are using
  var kilburnMarker = new google.maps.Marker({position: kilburn, map: map});
  var marker2 = new google.maps.Marker({position: building2, map: map});
  var imageBounds = {
    north: 53.468755,
    south: 53.405814,
    east: -2.230045,
    west: -2.244038,
  };

  fogOverlay = new google.maps.GroundOverlay(
    'https://lh4.googleusercontent.com/proxy/rpCG_YNAAsVgeen5Yz06GIBN6arMktQlgt-Z6-bSoZWcettRMFLefSTZ8XiLU2ENh0jo2ZZ_wmX97I_wkljdmAKfGJOqCxJC3tUTfelDVKSD3aTweMIyViPWEDY',
    imageBounds);
    fogOverlay.setMap(map);
    fogOverlay.setOpacity(0.5);
}
