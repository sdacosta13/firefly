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

  function updatePoints($userID, $points, $testMsgs, $mysqli) {
    $sql = "SELECT points FROM users WHERE userID = $userID;";
    $result = doSQL($mysqli, $sql, $testMsgs);

    while($row = $result->fetch_assoc()) {
      $currentPoints = $row['points'];
    }

    $totalPoints = $points + $currentPoints;

    $sql = "UPDATE users SET points = $totalPoints WHERE userID = $userID;";
    $result = doSQL($mysqli, $sql, $testMsgs);
  }

  function userPlaces($userID, $placeID, $testMsgs, $mysqli) {
    $sql = "SELECT * FROM userPlaces WHERE placeID = $placeID AND userID = $userID;";
    $result = doSQL($mysqli, $sql, $testMsgs);
    $numRows = $result->num_rows;
    $success = false;
    if ($numRows == 0) {
      $sql = "INSERT INTO userPlaces (placeID, userID) VALUES ($placeID, $userID);";
      $result = doSQL($mysqli, $sql, $testMsgs);
      $success = true;
    }
    return $success;
  }


  $latitude = $_POST['latitude'];
  $longitude = $_POST['longitude'];
  $username = $_POST['username'];

  $userID = getUserID($username, $testMsgs, $mysqli);
  $placeID = getPlaceID($longitude, $latitude, $testMsgs, $mysqli);
  $points = getPoints($placeID, $testMsgs, $mysqli);

  $success = userPlaces($userID, $placeID, $testMsgs, $mysqli);
  if ($success) {
    updatePoints($userID, $points, $testMsgs, $mysqli);
  }

  $mysqli->close();

  // Returning to main page
  header('Location: firefly.html');
  exit(0);
?>
