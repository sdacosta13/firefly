<?php
    session_start();
    $testMsgs = false;
    //Initialise variables for DB connection
    $database_host = "dbhost.cs.man.ac.uk";
    $database_user = "p11469sd";
    $database_pass = "unidatabase2";
    $group_dbnames = array("2019_comp10120_y1");
    $mysqli = new mysqli($database_host, $database_user, $database_pass, $group_dbnames[0]);

    // Check for errors before doing anything else
    if($mysqli -> connect_error) {
        die('Connect Error ('.$mysqli -> connect_errno.') '.$mysqli -> connect_error);
    }else{
      if ($testMsgs){
        echo "Connected successfully.";
      }
    }
    function getRequests($uID, $mysqli, $testMsgs){
		$SQL = "SELECT userIDReq FROM requests
		WHERE userIDTarget = $uID";
		$results = doSQL($mysqli, $SQL, $testMsgs);
		$resultsArr = array();
		while($row = $results->fetch_assoc()){
			array_push($resultsArr, $row['userIDReq']);
		}
		$nameArr = array();
		foreach($resultsArr as $uid){
			$newUID = convertIDToText($uid, $mysqli, $testMsgs);
			array_push($nameArr, $newUID);
		}
		return $nameArr;
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
    function convertIDToText($uID, $mysqli, $testMsgs){
		$SQL = "SELECT uname FROM users WHERE userID = $uID";
		$username = doSQL($mysqli, $SQL, $testMsgs);
		$uname2 = "";
		while($row = $username->fetch_assoc()){
			$uname2 = $row['uname'];
		}
		return $uname2;
    }
    function convertTextToID($uName, $mysqli, $testMsgs){
		$SQL = "SELECT userID FROM users WHERE uname = '$uName'";
		$username = doSQL($mysqli, $SQL, $testMsgs);
		$uname2 = "";
		while($row = $username->fetch_assoc()){
			$uname2 = $row['userID'];
		}
		return $uname2;
	}

?>

<!DOCTYPE html>
<html>
<style>
#rcorners2 {
  border-radius: 25px;
  box-shadow: 0 1px 3px 0 hsla(221.97424892703862, 100.00%, 54.31%, 1.00);
  padding: 20px;
  width: max-content;
  height: max-content;
  max-width: 90%;
  background-color: white;
  margin-left: auto;
  margin-right: auto;

}
.rcorners2 {
  border-radius: 25px;
  box-shadow: 0 1px 3px 0 hsla(221.97424892703862, 100.00%, 54.31%, 1.00);
  padding: 20px;
  width: max-content;
  height: max-content;
  max-width: 90%;
  background-color: #9ccaff;
  margin-left: auto;
  margin-right: auto;
}
a{
text-align: center;
border-radius: 25px;
position: center;
background-color: white;
width: max-content;
height: max-content;
margin: 0;
padding-inline :30px;
text-decoration: none;
color: black;
font-size: 2.25rem;
display: block;
box-shadow: 0 1px 3px 0 hsla(221.97424892703862, 100.00%, 54.31%, 1.00);
margin-left: auto;
margin-right: auto;

}
body{
background-color: #fafafa;
font-family: Arial, sans-serif;

}
img {
    display: block;
    margin-left: auto;
    margin-right: auto;
   position: center;
   width: 10%;
}
div{
  bottom:0;
  width:100%;
}
</style>
<body>
<a href=firefly.php>Back</a>
<p id="rcorners2">
Send a request to a user:
                <label>
                    <input type="text" autofocus value="username" name="uname" id="rqUser" onfocus="if(value==='username'){value=''; this.style.color='#000'}" onblur="if(value===''){value='username'; this.style.color='#adadad'}">
                </label>
                <button type="button" onclick="makeRequestJS();">Send</button>
                <br><br>Accepting/Sending an accepted request will share all map locations that either user has been to for both users
 </p>
<?php

$uname = $_SESSION["username"];
//$uname = convertIDToText($uname);
$uname = convertTextToID($uname,$mysqli,$testMsgs);

$boxArray = getRequests($uname,$mysqli,$testMsgs);//(check this line should work as intended)
$requestNum = count($boxArray);
  for($i=0;$i<$requestNum;$i++){
        echo "<p class=\"rcorners2\" id=\"",$i,"\"> Request to share from: ",$boxArray[$i], "  <input type=\"button\" value=\"Accept\" id=\"",$i,"\" onclick=\"acceptJS(this.id)\">
        <input type=\"button\" value=\"Deny\" id=\"",$i,"\" onclick=\"denyJS(this.id)\"></p>";
}
?>
<img
                    alt="Footer Firefly Logo" class="footer"
                    src="images/firefly_transparent_logo.png">
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript" language="javascript">
var jsUname = "<?php echo $uname; ?>";
var jsArr = new Array();
    <?php foreach($boxArray as $key => $val){ ?>
        jsArr.push('<?php echo $val; ?>');
    <?php } ?>
function makeRequestJS(){
  var inputVal = document.getElementById("rqUser").value;
  $.ajax({
    url: "makeReq.php",//(CHECK THIS)
    data: {sourceUser: jsUname, targetUser: inputVal},
    type: "POST",
    success: function(serverReturn1){
      alert("a request to username "+inputVal+" sent");
      document.getElementById(identifier).style.visibility = "hidden";},
    error: function(errorThrown){
      alert("Error: " + JSON.stringify(errorThrown));}
    });
  //alert("A request to the username "+inputVal+" attempted to send from "+jsUname);
}

function acceptJS(identifier){
  $.ajax({
    url: "makeReq.php",//(CHECK THIS)
    data: {sourceUser: jsUname, targetUser: jsArr[parseInt(identifier)]},
    type: "POST",
    success: function(serverReturn1){
      alert("Request from "+jsArr[parseInt(identifier)]+" accepted");
      document.getElementById(identifier).style.visibility = "hidden";},
    error: function(errorThrown){
      alert("Error: " + JSON.stringify(errorThrown));}
    });
  //alert("Request from "+jsArr[parseInt(identifier)]+" accepted");
  //document.getElementById(identifier).style.visibility = "hidden";
}
function denyJS(identifier){
  $.ajax({
    url: "deny.php",//(CHECK THIS)
    data: {sourceUser: jsUname, targetUser: jsArr[parseInt(identifier)]},
    type: "POST",
    success: function(serverReturn1){
      alert("Request from "+jsArr[parseInt(identifier)]+" denied");
      document.getElementById(identifier).style.visibility = "hidden";},
    error: function(errorThrown){
      alert("Error: " + JSON.stringify(errorThrown));}
    });
  //alert("Request from "+jsArr[parseInt(identifier)]+" denied");
  //document.getElementById(identifier).style.visibility = "hidden";
}
</script>
</html>
