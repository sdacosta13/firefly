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

  function getPlaces($mysqli, $testMsgs) {
    $sql = "SELECT placeID FROM places";
    $places = doSQL($mysqli, $sql, $testMsgs);
    $placeList = array();
    while($row = $places->fetch_assoc()){
      array_push($placeList, $row['placeID']);
    }
    return $placeList;
  }

  function getUnvisited($userID, $testMsgs, $mysqli){
    $placeList = getPlaces($mysqli, $testMsgs);

    $sql = "SELECT placeID FROM userPlaces WHERE userID = $userID";
    $visited = doSQL($mysqli, $sql, $testMsgs);
    $visitedList = array();
    while($row = $visited->fetch_assoc()){
      array_push($visitedList, $row['placeID']);
    }

    $unvisited = array();
    foreach($placeList as $uPlace){
      $inList = false;
      foreach($visitedList as $vPlace){
        if($uPlace == $vPlace){
          $inList = true;
        }
      }
      if(!$inList){
        array_push($unvisited, $uPlace);
      }

    }
    return $unvisited;
  }

  function getLatLongsMessage($placeIDs, $testMsgs, $mysqli) {
    // Getting the latitude and longitude for all locations in an array of placeIDs
    $latlongsmessage = array();

    foreach($placeIDs as $ID) {
      // SQL statement to get longitude and latitude
      $sql = "SELECT longitude, latitude, message FROM places WHERE placeID = $ID";
      $result = doSQL($mysqli, $sql, $testMsgs);

      while($row = $result->fetch_assoc()){
  			$long = strval($row['longitude']) . "*";
  			$lat = strval($row['latitude']) . "*";
        $message = strval($row['message']) . "*";

        // Adding latitude and longitude to array
        array_push($latlongsmessage, $lat, $long, $message);
  		}
    }

    return $latlongsmessage;
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

  if ($_SESSION["user"] == true) {
    $username = $_SESSION["username"];
  } else {
    $username = "ERROR";
  }

  $userID = getUserID($username, $testMsgs, $mysqli);
  if ($userID > 0) {
    $unvisited = getUnvisited($userID, $testMsgs, $mysqli);
    $data = getLatLongsMessage($unvisited, $testMsgs, $mysqli);
  } else {
    $places = getPlaces($mysqli, $testMsgs);
    $data = getLatLongsMessage($places, $testMsgs, $mysqli);
  }


  $mysqli->close();

  array_push($data, $username);

  echo json_encode($data);
?>
