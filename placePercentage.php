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

  function getVisited($userID, $testMsgs, $mysqli){
    $sql = "SELECT places.placeID
    FROM places
    LEFT JOIN userPlaces
    ON places.placeID = userPlaces.placeID
    WHERE userPlaces.userID = $userID";
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

    $numPlaces = sizeof($result);
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

  $mysqli->close();
?>
