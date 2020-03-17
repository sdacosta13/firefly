<?php
	    //Make sure to enter your username and password in the variables at the top
    $testMsgs = false;
    //Initialise variables for DB connection
    $database_host = "dbhost.cs.man.ac.uk";
    $database_user = "p11469sd";
    $database_pass = "unidatabase2";
    $group_dbnames = array("2019_comp10120_y1");
    $sourceUser = $_POST['sourceUser'];
    $targetUser = $_POST['targetUser'];
    #The require_once line below was in the cs wiki page about connecting to MySQL
    #but for some reason it gives an error so I've just commented it out for now
    
    // Load the configuration file containing your database credentials
    //require_once('config.inc.php'); 

    // Connect to the group database
    $mysqli = new mysqli($database_host, $database_user, $database_pass, $group_dbnames[0]);

    // Check for errors before doing anything else
    if($mysqli -> connect_error) {
        die('Connect Error ('.$mysqli -> connect_errno.') '.$mysqli -> connect_error);
    }else{
	if ($testMsgs){
		echo "Connected successfully.";
	}
    }
    // register query
    
    // Always close your connection to the database cleanly!
    function convertTextToID($uName, $mysqli, $testMsgs){
		$SQL = "SELECT userID FROM users WHERE uname = '$uName'";
		$username = doSQL($mysqli, $SQL, $testMsgs);
		$uname2 = "";
		while($row = $username->fetch_assoc()){
			$uname2 = $row['userID'];
		}
		return $uname2;
	}
    
    function doSQL($conn, $sql, $testMsgs)
    {
	if ($testMsgs)
	{
	    echo("<br><code>SQL: $sql</code>");
	    if ($result = $conn->query($sql))
		echo("<code> - OK</code>");
	    else
		echo("<code> - FAIL! " . $conn->error." </code>");
	}
	else
	    $result = $conn->query($sql);
	return $result;
    }
    function shareLocationsSingle($userID1, $userID2,$mysqli, $testMsgs){
		$sql = "SELECT placeID FROM userPlaces WHERE userID = $userID1"; 
		$result = doSQL($mysqli, $sql, $testMsgs);
		$placesV = array();
		while($row = $result->fetch_assoc()){
			array_push($placesV, $row['placeID']);
		}
		$placesV2 = array();
		$sql = "SELECT placeID FROM userPlaces WHERE userID = $userID2";
		$result = doSQL($mysqli, $sql, $testMsgs);
		while($row = $result->fetch_assoc()){
			array_push($placesV2, $row['placeID']);
		}
		$placesToAdd = array();
		foreach($placesV2 as $i){
			if(!in_array($i, $placesV)){
				array_push($placesToAdd, $i); 
				
			}
				
		}
		foreach($placesToAdd as $i){
			$sql = "INSERT INTO userPlaces (placeID, userID) VALUES ($i, $userID1)";
			doSQL($mysqli, $sql, $testMsgs);
		}
	}
	function shareLocationsDouble($userID1, $userID2,$mysqli, $testMsgs){
		shareLocationsSingle($userID1, $userID2,$mysqli, $testMsgs);
		shareLocationsSingle($userID2, $userID1,$mysqli, $testMsgs);
	}
    
	function makeRequest($uIDRequester, $uIDTarget, $mysqli, $testMsgs){
		#returns 0 if the request is made or is accepted, returns 1 if request has already been made
		$results = checkRequest($uIDRequester, $uIDTarget, $mysqli, $testMsgs);
		$a = $results[0];
		$b = $results[1];
		$error = 0;
		if($b){
			#share
			$SQL = "DELETE FROM requests
			WHERE userIDReq = $uIDTarget AND userIDTarget = $uIDRequester";
			doSQL($mysqli, $SQL, $testMsgs);
			shareLocationsDouble($uIDRequester, $uIDTarget,$mysqli, $testMsgs);

			
		} else if(!$a){
			#update db
			$SQL = "INSERT INTO requests (userIDReq, userIDTarget) VALUES ($uIDRequester, $uIDTarget)";
			doSQL($mysqli, $SQL, $testMsgs);

			
		} else {
			$error = 1;
		}
		return $error;
		
	}
	function checkRequest($uIDRequester, $uIDTarget, $mysqli, $testMsgs){
		#returns two bools, 1 to say if a request has been made by uIDRequester, 1 to say if a request has been made by $uIDTarget
		$SQL = "SELECT * FROM requests
		WHERE userIDReq = $uIDRequester AND userIDTarget = $uIDTarget";
		$results = doSQL($mysqli, $SQL, $testMsgs);
		$a = False;
		while($row = $results->fetch_assoc()){
			$a = True;
		}
		$SQL = "SELECT * FROM requests
		WHERE userIDReq = $uIDTarget AND userIDTarget = $uIDRequester";
		$results = doSQL($mysqli, $SQL, $testMsgs);
		$b = False;
		while($row = $results->fetch_assoc()){
			$b = True;
		}
		return array($a,$b);
	}
//	$sourceUser = convertTextToID($sourceUser,$mysqli, $testMsgs);
	$targetUser = convertTextToID($targetUser,$mysqli, $testMsgs);
	makeRequest($sourceUser,$targetUser,$mysqli, $testMsgs);
?>
