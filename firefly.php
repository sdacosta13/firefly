<!DOCTYPE html>
<html>
  <head>
    <title>Firefly</title>
    <script type="text/javascript" src="map.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
  </head>
  <body>
    <!-- Title section of the webpage -->
    <h1> Firefly </h1>
    <p> Light Up Manchester </p>
    <a href="index.html"> Return to homepage </a>

    <!--The div element for the map -->
    <div id="map"></div>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBMn9h54D2apYR9hZWrTYXdeDMeaGRHeGs&callback=initMap">
    </script>

    <!-- Menu button and sidebar -->
    <label for="show-menu">MENU</label>
    <input type="checkbox" id="show-menu">
    <div id="sidebar">
        <ol>
            <li><a href="firefly.progress.html">Progress</a></li>
            <li><a href="#">Settings</a></li>
            <li><a href="#">Privacy & Policy</a></li>
            <li><a href="#">About</a></li>
            <li>Welcome back,</li>
            <li>#user</li>
        </ol>
    </div>
  </body>
</html>
