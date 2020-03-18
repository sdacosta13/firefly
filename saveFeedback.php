<?php
	$testMsgs = true;
    //Initialise variables for DB connection
    $database_host = "dbhost.cs.man.ac.uk";
    $database_user = "p11469sd";
    $database_pass = "unidatabase2";
    $group_dbnames = array("2019_comp10120_y1");
    $email = $_POST['Email'];
	$selField = $_POST['Select-Field'];
    $requestType = $_SERVER['REQUEST_METHOD'];
	$name = $_POST['Name'];
	$message = $_POST['Message'];
    
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
	function updateDBWithFeed($name, $email, $message, $selField,$conn, $testMsgs){
		$SQL = "INSERT INTO feedback(name,email,userOption,message) VALUES ('$name', '$email', '$selField', '$message')";
		doSQL($conn, $SQL, $testMsgs);
		
	}
	updateDBWithFeed($name, $email, $message, $selField,$mysqli, $testMsgs);
	
	?>
