<?php
     session_start();
    //Make sure to enter your username and password in the variables at the top
    $testMsgs = true;
    //Initialise variables for DB connection
    $database_host = "dbhost.cs.man.ac.uk";
    $database_user = "p11469sd";
    $database_pass = "unidatabase2";
    $group_dbnames = array("2019_comp10120_y1");
    //$email = $_POST['email'];
    $uname = $_POST['uname'];
    $password = $_POST['password'];
    $requestType = $_SERVER['REQUEST_METHOD'];

    $uname = stripcslashes($uname);
    $password = stripcslashes($password);

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
    function addUser($fname,$sname,$uname,$password,$email, $testMsgs, $mysqli){
	if (!checkNewValidUser($uname, $email, $testMsgs,$mysqli)){
	    $sql = "INSERT INTO users (fname, sname, uname, password, email) VALUES ('$fname','$sname','$uname','$password','$email')";

	    doSQL($mysqli, $sql, $testMsgs);
	    return True;
	} else {
	    return False;
	}
    }
    function checkNewValidUser($uname, $email, $testMsgs, $mysqli){
	$sql = "SELECT * FROM users";
	$users = doSQL($mysqli, $sql, $testMsgs);
	$indb = False;
	while($row = $users->fetch_assoc()){
	    if($row['uname'] == $uname or $row['email'] == $email){
		$indb = True;
	    }

	}
	return $indb;
    }
    function checkUserNameInDB($uname, $testMsgs, $mysqli){
	$sql = "SELECT * FROM users";
	$users = doSQL($mysqli, $sql, $testMsgs);
	$indb = False;
	while($row = $users->fetch_assoc()){
	    if($row['uname'] == $uname){
		$indb = True;
	    }

	}
	return $indb;
    }
    function grantLogin($uname,$password, $testMsgs, $mysqli){
	$sql = "SELECT password FROM users WHERE uname = '$uname'";
	$result = doSQL($mysqli, $sql, $testMsgs);
	$login = False;
	while($row = $result->fetch_assoc()){
	    if($row['password'] == $password){
		$login = True;
	    }
	}
	return $login;

    }
    function loginUser($uname, $password, $testMsgs, $mysqli){
	if (checkUserNameInDB($uname, $testMsgs, $mysqli)){
	    if(grantLogin($uname, $password, $testMsgs,$mysqli)){
	        $_SESSION['user'] = true;
          $_SESSION['username'] = $uname;
		return "Login";
	    } else {
		return "Password Incorrect";
	    }
	} else {
	    return "User not found";
	}
    }
    function getLocationId($long, $lat, $testMsgs, $mysqli){
	$sql = "SELECT placeID FROM places WHERE longitude = $long AND latitude = $lat";

    }

    //if($requestType == 'POST'){
	//echo(addUser('sam','da costa',$uname,$password,$email, $testMsgs, $mysqli));
	//$mysqli->close();
    //}

//    echo (addUser("Sam","da Costa","sdacosta15","no", "sam.dacosta2005@gmail.com", $testMsgs, $mysqli));
    if($requestType == 'POST'){
	$password = hash("sha256",$password);
	if(loginUser($uname, $password, $testMsgs, $mysqli) == "Login"){
	   echo("good");
	   header("Location: firefly.html");
	} else {
	    echo("fail");
	    echo("<script type='text/javascript'>alert('Username or password is incorrect');location.href='login.html';</script>");
	    // header("http://localhost/php/login.html");

	}
	//echo(loginUser($uname, $password, $testMsgs, $mysqli));
	$mysqli->close();
    }
?>
