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

function begin() {
  // Code in here so it runs when the website loads
  var oReq = new XMLHttpRequest();
  oReq.onload = function() {
    // Get the text from the PHP file that accesses the database
    var datastr = this.responseText;

    // Clean the string that is input
    datastr = datastr.replace("[", "");
    datastr = datastr.replace("]", "");
    datastr = datastr.split("\"").join("");

    // Split the string into an array
    var data = datastr.split("*");

    // Get the username
    var username = data[data.length-1];
    username = username.substring(1, username.length);
    data.splice(data.length-1, 1);

    // Setting the welcome back message
    document.getElementById("welcome").innerHTML = username;

    // Set up empty arrays for marker locations and descriptions
    var markerLocations = [];
    var descriptions = [];

    // Loop through the array and create objects for each location and their descriptions
    for (i = 0; i < data.length; i = i + 3) {
      if (i == 0) {
        temp = {lat: parseFloat(data[i].substring(0, data[i].length - 1)), lng: parseFloat(data[i+1].substring(1, data[i+1].length - 1))};
      } else {
        temp = {lat: parseFloat(data[i].substring(1, data[i].length - 1)), lng: parseFloat(data[i+1].substring(1, data[i+1].length - 1))};
      }
      markerLocations.push(temp);
      descriptions.push(data[i+2].substring(1, data[i+2].length));
    }

    // Making sure \n is used for new lines in each description
    for (i = 0; i < descriptions.length; i++) {
      descriptions[i] = descriptions[i].replace("\\n", "\n");
      descriptions[i] = descriptions[i].replace("\\", "");
    }

    // To initialise the map
    initMap(markerLocations, descriptions, username);

    function initMap(markerLocations, descriptions, username) {
      // Initialise and add the map
      var infoWindow;
      var person = null;
      var pos;

      // The map, centered at the Kilburn building
      var map = new google.maps.Map(
          document.getElementById('map'), {zoom: 17, maxZoom: 17, minZoom: 17, center: markerLocations[0], disableDefaultUI: false,
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

      // Creates the fog overlay on the map
      var imageBounds = {
        north: 53.477281,
        south: 53.457807,
        east: -2.218672,
        west: -2.244703,
      };
      var fogOverlay;
      fogOverlay = new google.maps.GroundOverlay(
        'fog.png',
        imageBounds);
      fogOverlay.setMap(map);
      fogOverlay.setOpacity(0.5);

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

              // Setting the values to send to the database
              //document.getElementById('latitude').value = markerLocations[i].lat;
              //document.getElementById("longitude").value = markerLocations[i].lng;
              //document.getElementById("username").value = descriptions[i];
              //document.getElementById("sendtodb").submit();

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
}
