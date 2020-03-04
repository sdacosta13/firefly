function reqListener () {
  console.log(this.responseText);
}

function handleLocationError(browserHasGeolocation, infoWindow, pos) {
  infoWindow.setPosition(pos);
  infoWindow.setContent(browserHasGeolocation ?
                        'Error: The Geolocation service failed.' :
                        'Error: Your browser doesn\'t support geolocation.');
  infoWindow.open(map);
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

// Code in here so it runs when the website loads
var oReq = new XMLHttpRequest();
oReq.onload = function() {
  // Get the text from the PHP file that accesses the database
  var latlongsstr = this.responseText;

  // Clean the string that is input
  latlongsstr = latlongsstr.replace("[", "");
  latlongsstr = latlongsstr.replace("]", "");
  latlongsstr = latlongsstr.split("\"").join("");

  // Split the string into an array
  var latlongs = latlongsstr.split(",");

  var markerLocations = [];

  // Loop through the array and create objects for each location
  for (i = 0; i < latlongs.length; i = i + 2) {
    temp = {lat: latlongs[i], lng: latlongs[i+1]};
    markerLocations.push(temp);
  }

  // To initialise the map
  initMap(markerLocations);

  function initMap(markerLocations) {
    // Initialise and add the map
    var infoWindow;
    var person = null;
    var pos;

    // The description for each building
    var kilburnDescription = "This is the Kilburn building, the building for Computer Science at the University of Manchester. \nThe building is named after Tom Kilburn, a famous mathematician and computer scienctist who worked on the world's first electronic stored-program computer, the Manchester Baby";
    var museumDescription = "This is the Manchester Museum";
    var unionDescription = "This is the student's union for the University of Manchester";
    var descriptions = [kilburnDescription, museumDescription, unionDescription];

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
    var markers = [];
    for (i = 0; i < markerLocations.length; i++) {
      temp = new google.maps.Marker({position: markerLocations[i], map: map});
      markers.push(temp);
    }

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
            // Setting up the message for the user
            var alertMessage = "You have found a building"
            alertMessage = alertMessage.concat("\n", descriptions[i]);
            alertMessage = alertMessage.concat("\n", "View your progress from the menu to find out more to find out more")

            // Removing the marker
            markers[i].setMap(null);

            // Removing the building from the relevant arrays
            markerLocations.splice(i, 1);
            markers.splice(i, 1);
            descriptions.splice(i, 1);

            // Displaying the message to the user
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
  }
};
oReq.open("get", "getLatLong.php", true);
oReq.send()
