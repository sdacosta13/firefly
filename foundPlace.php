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
      if($longitude == round($row['longitude'], 6) and $latitude == round($row['latitude'], 6)) {
        $placeID = $row['placeID'];
        break;
      }
    }
    return $placeID;
  }


  $latitude = $_POST['latitude'];
  $longitude = $_POST['longitude'];
  $username = $_POST['username'];
  $userID = getUserID($username, $testMsgs, $mysqli);
  $placeID = getPlaceID($longitude, $latitude, $testMsgs, $mysqli);

  $mysqli->close();

  $toAlert = $latitude . $longitude . $username . $userID . $placeID;

  echo "<h1 id='hi'>Hello</h1>";
  echo "<script type=\"text/javascript\">alert($toAlert)</script>";
  echo "<script type=\"text/javascript\">document.getElementById('hi').innerHTML = $latitude</script>";

  // Returning to main page
  //header('Location: firefly.html');
  //exit(0);
?>
