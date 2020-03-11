<?php
session_start();
$testMsgs = true;
$database_host = "dbhost.cs.man.ac.uk";
$database_pass = "unidatabase2";
$group_dbnames = array("2019_comp10120_y1");
$mysqli = new mysqli($database_host, $database_user, $database_pass, $group_dbnames[0]);
    if($mysqli -> connect_error) {
        die('Connect Error ('.$mysqli -> connect_errno.') '.$mysqli -> connect_error);
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

    	function convertIDToText($uID, $mysqli, $testMsgs){
    		$SQL = "SELECT uname FROM users WHERE userID = $uID";
    		$username = doSQL($mysqli, $SQL, $testMsgs);
    		$uname2 = "";
    		while($row = $username->fetch_assoc()){
    			$uname2 = $row['uname'];
    		}
    		return $uname2;
    	}

    	foreach(getRequests(4,$mysqli, $testMsgs) as $i){
    		echo($i);
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
?>
<!DOCTYPE html>
<html>
<style>
#rcorners2 {
  border-radius: 25px;
  border: 2px solid #73AD21;
  padding: 20px;
  width: 500px;
  height: 150px;
}
.rcorners2 {
  border-radius: 25px;
  border: 2px solid #73AD21;
  padding: 20px;
  width: 500px;
  height: 50px;
}
</style>
<p id="rcorners2">
Send a request to a user in order to share found map locations:
                <label>
                    <input type="text" value="username" name="uname" id="rqUser" onfocus="if(value==='username'){value=''; this.style.color='#000'}" onblur="if(value===''){value='username'; this.style.color='#adadad'}">
                </label>
                <button type="button" onclick="makeRequestJS();">Send</button>
 </p>
<?php
//$uname = $_SESSION["username"];
//$boxArray = getRequests($uname,$mysqli,$testMsgs);
$uname = "someuser";
$boxArray = array("user1","user2");
$requestNum = count($boxArray);
  for($i=0;$i<$requestNum;$i++){
        echo "<p class=\"rcorners2\" id=\"",$i,"\"> Request to share from: ",$boxArray[$i], "<input type=\"button\" value=\"Accept\" id=\"",$i,"\" onclick=\"acceptJS(this.id)\">
        <input type=\"button\" value=\"Deny\" id=\"",$i,"\" onclick=\"denyJS(this.id)\">";
}
?>

</body>
<script type="text/javascript" language="javascript">
var jsUname = "<?php echo $uname; ?>";
var jsArr = new Array();
    <?php foreach($boxArray as $key => $val){ ?>
        jsArr.push('<?php echo $val; ?>');
    <?php } ?>
function makeRequestJS(){
  var inputVal = document.getElementById("rqUser").value;
  alert("A request to the username "+inputVal+" attempted to send from "+jsUname);
}

function acceptJS(identifier){
  alert("Request from "+jsArr[parseInt(identifier)]+" accepted");
  document.getElementById(identifier).style.visibility = "hidden";
}
function denyJS(identifier){
  alert("Request from "+jsArr[parseInt(identifier)]+" denied");
  document.getElementById(identifier).style.visibility = "hidden";
}
</script>
</html>
