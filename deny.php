
<?php
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
    function deny($id2, $id1, $mysqli, $testMsgs){
		$SQL = "DELETE FROM requests WHERE userIDReq = $id1 and userIDTarget = $id2";
		doSQL($mysqli, $SQL, $testMsgs);
		
	}
	
	$targetUser = convertTextToID($targetUser,$mysqli, $testMsgs);
	deny($sourceUser,$targetUser,$mysqli,$testMsgs);
?>
