<?php
    //Make sure to enter your username and password in the variables at the top
    $testMsgs = true;
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

  function getUnvisited($userID, $testMsgs, $mysqli){
    $sql = "SELECT placeID FROM places";
    $places = doSQL($mysqli, $sql, $testMsgs);
    $placeList = array();
    while($row = $places->fetch_assoc()){
      array_push($placeList, $row['placeID']);
    }

    $sql = "SELECT userPlaces.placeID FROM userPlaces WHERE userID = $userID";
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

  function getLatLongs($placeIDs, $testMsgs, $mysqli) {
    // Getting the latitude and longitude for all locations in an array of placeIDs
    $latlongs = array();

    foreach($placeIDs as $ID) {
      // SQL statement to get longitude and latitude
      $sql = "SELECT longitude, latitude FROM places WHERE placeID = $ID";
      $result = doSQL($mysqli, $sql, $testMsgs);

      while($row = $result->fetch_assoc()){
  			$long = $row['longitude'];
  			$lat = $row['latitude'];

        // Adding latitude and longitude to array
        array_push($latlongs, $lat, $long);
  		}
    }

    return $latlongs;
  }

  $unvisited = getUnvisited(7, $testMsgs, $mysqli);
  $coords = getLatLongs($unvisited, $testMsgs, $mysqli);

  $mysqli->close();

  echo json_encode($coords);
?>
