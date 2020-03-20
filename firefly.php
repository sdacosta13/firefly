<?php
session_start();
if(!(isset($_SESSION['user']) && $_SESSION['user'] == true)){
    die("You haven't logged in! <a href='index.html'>Back to home page</a>");
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Firefly</title>
    <script type="text/javascript" src="map.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/firefly.css">
  </head>
  <body>
    <!-- Title section of the webpage -->
    <h1 class="firefly-font-heading"> Firefly </h1>
    <h2 class="firefly-slogan-heading"> Light Up Manchester </h2>
    <a href="index.html"> Return to homepage </a>

    <!--The div element for the map -->
    <div id="map"></div>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBMn9h54D2apYR9hZWrTYXdeDMeaGRHeGs&callback=begin">
    </script>

    <!-- Menu button and sidebar -->
    <label for="show-menu">Menu</label>
    <input type="checkbox" id="show-menu">
    <div id="sidebar">
        <ol>
            <li><a href="index.html">Return to Index</a></li>
            <li><a href="firefly.progress.html">Your Progress</a></li>
            <a href="Friends.php" style="border-bottom: 1px solid #165cff40;">Share Location With Friends</a>            <br>
            <br>
            <br>
            <li><br>Welcome back,</li>
            <br>

            <li id = "welcome">user</li>
        </ol>
    </div>

    <!-- Hidden form -->
    <form method="post" action="foundPlace.php" id="sendtodb" name="found">
      <input type="hidden" id="latitude" name="latitude" value="1">
      <input type="hidden" id="longitude" name="longitude" value="1">
      <input type="hidden" id="username" name="username" value="user">
    </form>
  </body>
</html>
