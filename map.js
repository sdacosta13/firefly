function initMap() {
  // Initialize and add the map
  var infoWindow;
  var person = null;
  var pos;

  // The location of our markers
  var kilburn = {lat: 53.467539, lng: -2.233927};
  var museum = {lat: 53.466341, lng: -2.234195};
  var union = {lat: 53.464374, lng: -2.232154};
  var markerLocations = [kilburn, museum, union];

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

  // The markers, positioned at the buidlings we are using
  var kilburnMarker = new google.maps.Marker({position: kilburn, map: map});
  var museumMarker = new google.maps.Marker({position: museum, map: map});
  var unionMarker = new google.maps.Marker({position: union, map: map});
  var markers = [kilburnMarker, museumMarker, unionMarker];

  createFog(map);

  if (navigator.geolocation) {
      // Repeatedly gets the user's location
      navigator.geolocation.watchPosition(function(position) {
        pos = {
          lat: position.coords.latitude,
          lng: position.coords.longitude
        };

        // Set marker for user's location
        if (person === null) {
          person = new google.maps.Marker({position: pos, map: map});
        } else {
          person.setPosition(pos);
        }

        map.setCenter(pos);

        // Check if user is near buildings
        for (i = 0; i < markerLocations.length; i++) {
          if ((Math.abs(pos.lat - markerLocations[i].lat) <= 0.001) && (Math.abs(pos.lng - markerLocations[i].lng) <= 0.01)) {
            var alertMessage = "You have found a building"
            markerLocations.splice(i, 1);
            markers[i].setMap(null);
            markers.splice(i, 1);
            alert(alertMessage);
          }
        }
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

}

// Creates the fog overlay on the map
function createFog(map) {
  var imageBounds = {
    north: 53.477281,
    south: 53.457807,
    east: -2.218672,
    west: -2.244703,
  };
  var fogOverlay;
  fogOverlay = new google.maps.GroundOverlay(
    'https://lh4.googleusercontent.com/proxy/rpCG_YNAAsVgeen5Yz06GIBN6arMktQlgt-Z6-bSoZWcettRMFLefSTZ8XiLU2ENh0jo2ZZ_wmX97I_wkljdmAKfGJOqCxJC3tUTfelDVKSD3aTweMIyViPWEDY',
    imageBounds);
    fogOverlay.setMap(map);
    fogOverlay.setOpacity(0.5);
}
