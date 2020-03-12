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

  function convertTextToID($uname, $mysqli, $testMsgs){
    $SQL = "SELECT userID FROM users WHERE uname = '$uname'";
    $uID = doSQL($mysqli, $SQL, $testMsgs);
    $uname2 = "";
    while($row = $uID->fetch_assoc()){
      $uname2 = $row['userID'];
    }
    return $uname2;
  }

  $latitude = $_POST['latitude'];
  $longitude = $_POST['longitude'];
  $username = $_POST['username'];
  $userID = convertTextToID();
  echo "<script>alert(" . $latitude . $longitude . $username . $userID . ")</script";
?>
