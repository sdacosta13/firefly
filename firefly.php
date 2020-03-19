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
    <h1> Firefly </h1>
    <p> Light Up Manchester </p>
    <a href="index.html"> Return to homepage </a>

    <!--The div element for the map -->
    <div id="map"></div>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBMn9h54D2apYR9hZWrTYXdeDMeaGRHeGs&callback=begin">
    </script>

    <!-- Menu button and sidebar -->
    <label for="show-menu">MENU</label>
    <input type="checkbox" id="show-menu">
    <div id="sidebar">
        <ol>
            <li><a href="index.html">Return</a></li>
            <li><br></li>
            <li><a href="firefly.progress.html">Progress</a></li>
            <li><br></li>
            <li><a href="Friends.php">Share Location With Friends</a><li>
            <li><a href="firefly.html">Close</a></li>
            <li><br></li>
            <li>Welcome back,</li>
            <li id = "welcome">user</li>
            <li><br></li>
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
