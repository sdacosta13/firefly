<?php
  session_start();
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

  function getVisited($userID, $testMsgs, $mysqli){
    $sql = "SELECT placeID FROM userPlaces WHERE userID = $userID";
    $resultArr = array();
    $i = 0;
    $result = doSQL($mysqli, $sql, $testMsgs);
    while($row = $result->fetch_assoc()){
      $resultArr[$i] = $row["placeID"];
      $i += 1;
    }
    return $resultArr;
  }

  function getTotalPlaces($mysqli, $testMsgs) {
    $sql = "SELECT placeID FROM places";
    $result = doSQL($mysqli, $sql, $testMsgs);

    $numPlaces = 0;
    while($row = $result->fetch_assoc()) {
      $numPlaces += 1;
    }
    return $numPlaces;
  }

  function getVisitedPlaces($userID, $mysqli, $testMsgs) {
    $visited = getVisited($userID, $testMsgs, $mysqli);
    $numVisited = sizeof($visited);
    return $numVisited;
  }

  function getPercentage($userID, $mysqli, $testMsgs) {
    $numPlaces = getTotalPlaces($mysqli, $testMsgs);
    $numVisited = getVisitedPlaces($userID, $mysqli, $testMsgs);
    $percentage = ($numVisited / $numPlaces) * 100;
    return $percentage;
  }

  function getDescriptions($userID, $mysqli, $testMsgs) {
    $visited = getVisited($userID, $testMsgs, $mysqli);
    $descriptions = array();
    for ($i = 0; $i < count($visited); $i++) {
      $sql = "SELECT description FROM places WHERE placeID = $visited[$i];";
      $result = doSQL($mysqli, $sql, $testMsgs);
      while($row = $result->fetch_assoc()) {
        $descriptions[$i] = $row["description"];
      }
    }
    return $descriptions;
  }

  function getPoints($userID, $mysqli, $testMsgs) {
    $sql = "SELECT points FROM users WHERE userID = $userID;";
    $result = doSQL($mysqli, $sql, $testMsgs);
    while ($row = $result->fetch_assoc()) {
      $points = $row["points"];
    }
    return $points;
  }

  if ($_SESSION["user"] == true) {
    $username = $_SESSION["username"];
  } else {
    $username = "ERROR";
  }

  $userID = getUserID($username, $testMsgs, $mysqli);
  if ($userID >= 0) {
    $percentage = getPercentage($userID, $mysqli, $testMsgs);
    $descriptions = getDescriptions($userID, $mysqli, $testMsgs);
    $points = getPoints($userID, $mysqli, $testMsgs);
  } else {
    $percentage = 0.0;
    $descriptions = ["None!"];
    $points = 0;
  }

  $mysqli->close();

  $data = [strval($percentage) . "*", strval($points) . "*"];
  $data = array_merge

  echo json_encode($data);
?>
