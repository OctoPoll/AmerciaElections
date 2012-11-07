<? 
/* 

http://vote.anewamercia.com 

tables: 
	votes (vote_id,vote_candidate,vote_time)
	users (user_id,user_netid,user_phone)
	log (log_id,log_type,log_message,user_id,log_time)
*/

require_once("twilio-php/Services/Twilio.php");

/*function doVote($phone, $message){
	$status = 'othererror'; $userid = 0; $electionid = 0;
	
	//-- connect to DB
	$mysqli = new mysqli($GLOBALS['hostname'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['database']);
	if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error()); return $status;}
	
	//-- split message into array of netid & candidate
	$msgarray = explode(" ", $message);
	
	//-- validate election
	$election = strtolower(filter_var($msgarray[0], FILTER_SANITIZE_STRING));
    if(!$result = $mysqli->query("SELECT * FROM elections WHERE election_keyword = '$election'")) return $mysqli->error;
    $electionarr = $result->fetch_array(MYSQLI_ASSOC);
	if (!$result->num_rows) { 
		$sql = "INSERT INTO log VALUES (null,null,'error','invalid election: $election',null,null)";
		if(!$result = $mysqli->query($sql)) return $mysqli->error;
		$status = "invalidelection"; 
		return $status; 
		}
	$electionid = $electionarr["election_id"];
		
	//--sanitize netid input
	$netid = strtolower(filter_var($msgarray[1], FILTER_SANITIZE_STRING));
	if(strlen($netid) > 9 || strlen($netid) < 3){ 
		$sql = "INSERT INTO log VALUES (null,null,'error','invalid netid: \"$netid\"',null,null)";
		if(!$result = $mysqli->query($sql)) return $mysqli->error;
		$status = "invalidnetid"; 
		return $status; 
		}
	
	//--sanitize/check candidate input
	$candidate = strtolower(filter_var($msgarray[2], FILTER_SANITIZE_STRING));
	if(strlen($candidate) > 50) return $status;
	$sql = "SELECT * FROM election_candidates WHERE candidate_name = '$candidate' AND candidate_election_id = '$electionid'";
	//echo $sql;
    if(!$result = $mysqli->query($sql)) return $mysqli->error;
	if (!$result->num_rows) {  
		$status = 'invalidcandidate'; 
		//log error
		$sql = "INSERT INTO log VALUES (null,'$electionid','error','invalid candidate: $candidate user: $netid',null,null)";
		if(!$result = $mysqli->query($sql)) return $mysqli->error;
		return $status;
	}
	$candidate = $result->fetch_array(MYSQLI_ASSOC);
	$candidate_id = $candidate["candidate_id"];
	
	//-- find user
	$sql = "SELECT * FROM users WHERE user_netid = '$netid'";
    if(!$result = $mysqli->query($sql)) return $mysqli->error;
	$user = $result->fetch_array(MYSQLI_ASSOC);
	if (!$result->num_rows) {  
		$status = 'notinclass'; 
		//log error
		$sql = "INSERT INTO log VALUES (null,'$electionid','error_user_not_found','user: $netid candidate: $candidate',null,null)";
		if(!$result = $mysqli->query($sql)) return $mysqli->error;
		return $status;
	} else {
		$userid = $user["user_id"];	
	}
	
	//-- register/verify phone number
	if(!$user["user_phone"]){
		$sql = "SELECT * FROM users WHERE user_phone = '$phone'";
		if(!$result = $mysqli->query($sql)) return $mysqli->error;
		$existinguser = $result->fetch_array(MYSQLI_ASSOC);
		$e_user_netid = $existinguser["user_netid"];
		if($result->num_rows > 0){
			$sql = "INSERT INTO log VALUES (null,'$electionid', 'error','phone exists: $phone registered to: $e_user_netid','$userid',null)";
			if(!$mysqli->query($sql)) return $mysqli->error;
			$status = "phoneexists";
			return $status;
		}
		$sql1 = "UPDATE users SET user_phone = '$phone' WHERE user_id = $userid";
		if(!$mysqli->query($sql1)) return $mysqli->error;
	} else {
		if($user["user_phone"] != $phone) { 
			$sql = "INSERT INTO log VALUES (null,'$electionid', 'error_invalid_phone','$phone','$userid',null)";
			if(!$mysqli->query($sql)) return $mysqli->error;
			$status = 'invalidphone'; return $status; 
		}
	}
	
	//-- check authorization for election
	$sql = "SELECT * FROM election_access WHERE user_id = '$userid' AND election_id = '$electionid'";
    if(!$result = $mysqli->query($sql)) return $mysqli->error;
	$user = $result->fetch_array(MYSQLI_ASSOC);
	if (!$result->num_rows) { 
		$status = 'unauthorizedelection'; 
		//log error
		$sql = "INSERT INTO log VALUES (null,'$electionid','error','user unauthorized',$userid,null)";
		if(!$result = $mysqli->query($sql)) return $mysqli->error;
		return $status;
	}
		
	//-- check for previous votes for user
	$sql = "SELECT * FROM users JOIN log ON users.user_id = log.user_id WHERE log_category = 'user_voted' AND log_election = '$electionid' AND users.user_id = '$userid'";
    if(!$result = $mysqli->query($sql)) return $mysqli->error;
	    if ($result->num_rows > 0) {  
			$status = 'alreadyvoted'; 
			//log error
			$sql = "INSERT INTO log VALUES (null,'$electionid','error_already_voted','attempt from: $phone',$userid,null)";
			if(!$result = $mysqli->query($sql)) return $mysqli->error;
			return $status;
		}
	
	//-- add vote to db
	$mysqli->autocommit(FALSE);
	try {
		$q2 = $mysqli->prepare("INSERT INTO votes VALUES (null,?,null,null)");
		$q2->bind_param('s', $candidate_id);
		if (!$q2->execute()) throw new Exception("Cannot insert record. Reason :".$q2->error);
		if($userid != 1){$q1 = $mysqli->prepare("INSERT INTO log VALUES (null,?,'user_voted',null,?,null)");
		$q1->bind_param('ii', $electionid, $userid);
		if (!$q1->execute()) throw new Exception("Cannot insert record. Reason :".$q1->error); 
		}
		$mysqli->commit();
		$status = "votesuccess";
	} catch (Exception $e) {
		$mysqli->rollback();  
	}
	$mysqli->autocommit(TRUE);
	return $status;
}*/

function doVote2($phone, $message){
	$status = 'othererror'; $userid = 0; $electionid = 0;
	
	//-- connect to DB
	$mysqli = new mysqli($GLOBALS['hostname'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['database']);
	if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error()); return $status;}
	
	//-- split message into array of netid & candidate
	$msgarray = explode(" ", $message);
	//print_r($msgarray);
		
	//--sanitize netid input
	$netid = strtolower(filter_var($msgarray[0], FILTER_SANITIZE_STRING));
	if(strlen($netid) > 9 || strlen($netid) < 3){ 
		$sql = "INSERT INTO log VALUES (null,null,'error','invalid netid: \"$netid\"',null,null)";
		if(!$result = $mysqli->query($sql)) return $mysqli->error;
		$status = "invalidnetid"; 
		return $status; 
		}
	
	//--sanitize/check candidate
	$candidate = strtolower(filter_var($msgarray[1], FILTER_SANITIZE_STRING));
	if(strlen($candidate) > 50) return $status;
	$sql = "SELECT * FROM election_candidates WHERE candidate_name = '$candidate'";
	//echo $sql;
    if(!$result = $mysqli->query($sql)) return $mysqli->error;
	if (!$result->num_rows) {  
		$status = 'invalidcandidate'; 
		//log error
		$sql = "INSERT INTO log VALUES (null,'$electionid','error','invalid candidate: $candidate user: $netid',null,null)";
		if(!$result = $mysqli->query($sql)) return $mysqli->error;
		return $status;
	}
	$candidate = $result->fetch_array(MYSQLI_ASSOC);
	$candidate_id = $candidate["candidate_id"];
	$electionid = $candidate["candidate_election_id"];
	
	//-- find user
	$sql = "SELECT * FROM users WHERE user_netid = '$netid'";
    if(!$result = $mysqli->query($sql)) return $mysqli->error;
	$user = $result->fetch_array(MYSQLI_ASSOC);
	if (!$result->num_rows) {  
		$status = 'notinclass'; 
		//log error
		$sql = "INSERT INTO log VALUES (null,'$electionid','error_user_not_found','user: $netid candidate: $candidate',null,null)";
		if(!$result = $mysqli->query($sql)) return $mysqli->error;
		return $status;
	} else {
		$userid = $user["user_id"];	
	}
	
	//-- register/verify phone number
	if(!$user["user_phone"]){
		$sql = "SELECT * FROM users WHERE user_phone = '$phone'";
		if(!$result = $mysqli->query($sql)) return $mysqli->error;
		$existinguser = $result->fetch_array(MYSQLI_ASSOC);
		$e_user_netid = $existinguser["user_netid"];
		if($result->num_rows > 0){
			$sql = "INSERT INTO log VALUES (null,'$electionid', 'error','phone exists: $phone registered to: $e_user_netid','$userid',null)";
			if(!$mysqli->query($sql)) return $mysqli->error;
			$status = "phoneexists";
			return $status;
		}
		$sql1 = "UPDATE users SET user_phone = '$phone' WHERE user_id = $userid";
		if(!$mysqli->query($sql1)) return $mysqli->error;
	} else {
		if($user["user_phone"] != $phone) { 
			$sql = "INSERT INTO log VALUES (null,'$electionid', 'error_invalid_phone','$phone','$userid',null)";
			if(!$mysqli->query($sql)) return $mysqli->error;
			$status = 'invalidphone'; return $status; 
		}
	}
	
	//-- check authorization for election
	$sql = "SELECT * FROM election_access WHERE user_id = '$userid' AND election_id = '$electionid'";
    if(!$result = $mysqli->query($sql)) return $mysqli->error;
	$user = $result->fetch_array(MYSQLI_ASSOC);
	if (!$result->num_rows) { 
		$status = 'unauthorizedelection'; 
		//log error
		$sql = "INSERT INTO log VALUES (null,'$electionid','error','user unauthorized',$userid,null)";
		if(!$result = $mysqli->query($sql)) return $mysqli->error;
		return $status;
	}
		
	//-- check for previous votes for user
	$sql = "SELECT * FROM users JOIN log ON users.user_id = log.user_id WHERE log_category = 'user_voted' AND log_election = '$electionid' AND users.user_id = '$userid'";
    if(!$result = $mysqli->query($sql)) return $mysqli->error;
	    if ($result->num_rows > 0) {  
			$status = 'alreadyvoted'; 
			//log error
			$sql = "INSERT INTO log VALUES (null,'$electionid','error_already_voted','attempt from: $phone',$userid,null)";
			if(!$result = $mysqli->query($sql)) return $mysqli->error;
			return $status;
		}
	
	//-- add vote to db
	$mysqli->autocommit(FALSE);
	try {
		$q2 = $mysqli->prepare("INSERT INTO votes VALUES (null,?,null,null)");
		$q2->bind_param('s', $candidate_id);
		if (!$q2->execute()) throw new Exception("Cannot insert record. Reason :".$q2->error);
		if($userid != 1){$q1 = $mysqli->prepare("INSERT INTO log VALUES (null,?,'user_voted',null,?,null)");
		$q1->bind_param('ii', $electionid, $userid);
		if (!$q1->execute()) throw new Exception("Cannot insert record. Reason :".$q1->error); 
		}
		$mysqli->commit();
		$status = "votesuccess";
	} catch (Exception $e) {
		$mysqli->rollback();  
	}
	$mysqli->autocommit(TRUE);
	return $status;
}

?>