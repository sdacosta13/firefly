<?php
  //Make sure to enter your username and password in the variables at the top
  $testMsgs = false;
  //Initialise variables for DB connection
  $database_host = "dbhost.cs.man.ac.uk";
  $database_user = "j38067bl";
  $database_pass = "Databas3";
  $group_dbnames = array("2019_comp10120_y1");
  $mysqli = new mysqli($database_host, $database_user, $database_pass, $group_dbnames[0]);

  // Check for errors before doing anything else
  if($mysqli -> connect_error) {
      die('Connect Error ('.$mysqli -> connect_errno.') '.$mysqli -> connect_error);
  }else{
  if ($testMsgs){
    //echo "Connected successfully.";
  }
  }

  function doSQL($conn, $sql, $testMsgs)
  {
  if ($testMsgs)
  {
    //echo("<br><code>SQL: $sql</code>");
    if ($result = $conn->query($sql)) {
      //echo("<code> - OK</code>");
    } else {
      echo("<code> - FAIL! " . $conn->error." </code>");
    }
  }
  else
    $result = $conn->query($sql);
  return $result;
  }

  function getUserID($username, $testMsgs, $mysqli) {
    $sql = "SELECT userID, uname FROM users;";
    $result = doSQL($mysqli, $sql, $testMsgs);

    $userID = -1;

    while($row = $result->fetch_assoc()) {
      if ($username == $row['uname']) {
        $userID = $row['userID'];
        break;
      }
    }
    return $userID;
  }

  function getPlaceID($longitude, $latitude, $testMsgs, $mysqli) {
    $sql = "SELECT placeID, longitude, latitude FROM places;";
    $result = doSQL($mysqli, $sql, $testMsgs);

    $placeID = -1;

    while($row = $result->fetch_assoc()) {
      if(round($longitude, 4) == round($row['longitude'], 4) and round($latitude, 4) == round($row['latitude'], 4)) {
        $temp1 = round($longitude, 5);
        $temp2 = round($row['longitude'], 5);
        $temp3 = round($latitude, 5);
        $temp4 = round($row['latitude'], 5);
        $temp = $temp1 . $temp2 . $temp3 . $temp4;
        echo "<p>$temp</p>";
        $placeID = $row['placeID'];
        break;
      }
    }
    return $placeID;
  }

  function getPoints($placeID, $testMsgs, $mysqli) {
    $sql = "SELECT worth FROM places WHERE placeID = $placeID;";
    $result = doSQL($mysqli, $sql, $testMsgs);
    while($row = $result->fetch_assoc()) {
      $points = $row['worth'];
    }
    return $points;
  }


  $latitude = $_POST['latitude'];
  $longitude = $_POST['longitude'];
  $username = $_POST['username'];

  $userID = getUserID($username, $testMsgs, $mysqli);
  $placeID = getPlaceID($longitude, $latitude, $testMsgs, $mysqli);
  $points = getPoints($placeID, $testMsgs, $mysqli);

  $mysqli->close();

  $toAlert1 = $userID . $placeID;
  $toAlert2 = $userID . $points;

  echo "<h1 id='hi'>Hello</h1>";
  echo "<script type=\"text/javascript\">alert($toAlert1)</script>";
  echo "<script type=\"text/javascript\">alert($toAlert2)</script>";

  // Returning to main page
  //header('Location: firefly.html');
  //exit(0);
?>
