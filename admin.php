<?
session_start(); include_once("connect.php"); include_once("functions.php"); $election = "amercia";
$mysqli = new mysqli($GLOBALS['hostname'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['database']);
if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error()); return $status;}
if(isset($_POST["password"])){ $_SESSION["password"] = filter_var($_POST['password'], FILTER_SANITIZE_STRING);}
if(isset($_GET["logout"])){ session_destroy(); header("Location: http://vote.anewamercia.com/");}

function getVoters($election){
	$mysqli = new mysqli($GLOBALS['hostname'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['database']);
	if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error()); return $status;}
	$sql = "SELECT * FROM users join election_access on users.user_id = election_access.user_id where election_id = '$election'";
	if(!$result = $mysqli->query($sql)) die('There was an error running the query [' . $mysqli->error . ']');
	$voterlist = '';
	while($voters = $result->fetch_array(MYSQLI_ASSOC)){
		$voterlist .= "<a href='#' onclick=\"revoke('".$voters["access_id"]."','$election');\">(x)</a>".$voters["user_netid"]." ";
	}
	return $voterlist;
}
function getCandidates($election){
	$mysqli = new mysqli($GLOBALS['hostname'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['database']);
	if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error()); return $status;}
	$sql = "SELECT * FROM election_candidates where candidate_election_id = '$election'";
	if(!$result = $mysqli->query($sql)) die('There was an error running the query [' . $mysqli->error . ']');
	$candidatelist = '';
	while($candidates = $result->fetch_array(MYSQLI_ASSOC)){
		$candidatelist .= "<a href='#' onclick=\"trashCandidate('".$candidates["candidate_id"]."','$election');\">(x)</a>".$candidates["candidate_name"]." ";
	}
	return $candidatelist;
}

if(isset($_GET["dologin"])) {
	$status = "notloggedin";
	if($_SESSION["password"] != $adminpassword){
		$status = "badpassword";
	} else {
		$status = "success";
	}
	die($status);
}

if($_SESSION["password"] != $adminpassword){
if(isset($_GET["addusers"])) {
		echo "<script type='text/javascript'>document.location = '/admin.php';</script>";
		die();
	}
	echo "Login: ";
	echo "<form action='/admin.php' method='post' name='login'><input type='password' name='password' value='' /><input type='submit' /></form>";
	echo "<script type='text/javascript' language='JavaScript'>
document.forms['login'].elements['password'].focus();
</script>";
} else {
if(isset($_GET["revoke"])){
	$accessid = filter_var($_POST['accessid'], FILTER_SANITIZE_NUMBER_INT);
	$election_id = filter_var($_GET['election'], FILTER_SANITIZE_NUMBER_INT);
	if(!$election_id) die('No election');
	$sql = "DELETE FROM election_access WHERE access_id = '$accessid'";
	if(!$result = $mysqli->query($sql)) {
		die($mysqli->error);
	} else {
		echo getVoters($election_id);
	}
		die();

}
if(isset($_GET["trashCandidate"])){
	$id = filter_var($_POST['candidateid'], FILTER_SANITIZE_NUMBER_INT);
	$election_id = filter_var($_GET['election'], FILTER_SANITIZE_NUMBER_INT);
	if(!$election_id) die('No election');
	$sql = "DELETE FROM election_candidates WHERE candidate_id = '$id' AND candidate_election_id = '$election_id'";
	//echo $sql;
	if(!$result = $mysqli->query($sql)) {
		die('error');
	} else {
		echo getCandidates($election_id);
	}
			die();
}
if(isset($_GET["clearvotes"])){
	$election_id = filter_var($_GET['election'], FILTER_SANITIZE_NUMBER_INT);
	$sql = "DELETE FROM log WHERE log_category = 'user_voted' AND log_election = '$election_id'";
	//echo $sql;
	if(!$result = $mysqli->query($sql)) {
		die('error');
	}
	$sql = "delete from votes where vote_candidate_id = (select candidate_id from election_candidates where candidate_election_id = '$election_id' AND vote_candidate_id = candidate_id)";
	//echo $sql;
	if(!$result = $mysqli->query($sql)) {
		die('error');
	}
	die('success');
}
if(isset($_GET["addCandidate"])){
	$candidate = strtolower(filter_var($_POST['candidate'], FILTER_SANITIZE_STRING));
	if(strlen($candidate) < 3) die('Too short');
	$election_id = filter_var($_GET['election'], FILTER_SANITIZE_NUMBER_INT);
	if(!$election_id) die('No election');
	$sql = "INSERT INTO election_candidates VALUES (null,'$candidate','$election_id','1')";
	if(!$result = $mysqli->query($sql)) {
		die($mysqli->error);
	}
		echo getCandidates($election_id);
	
			die();
}
if(isset($_GET["addusers"])){
	$userlist = filter_var($_POST['users'], FILTER_SANITIZE_STRING);
	$election_id = filter_var($_GET['election'], FILTER_SANITIZE_NUMBER_INT);
	if(!$election_id) die('No election');
	$userarr = explode(",", $userlist);
	$x = 0;
		while($x < count($userarr)){
		$userarr[$x] = trim($userarr[$x]);
		$sql = "SELECT * FROM users WHERE user_netid = '".$userarr[$x]."'";
		if(!$result = $mysqli->query($sql)) die($mysqli->error);
		$user = $result->fetch_array(MYSQLI_ASSOC);
		if($result->num_rows > 0){
			$sql = "INSERT INTO election_access VALUES (null,'$election_id',".$user["user_id"].")";
			if(!$result = $mysqli->query($sql)) { 
				echo $userarr[$x].": <b>error</b> "; 
			} else {
				echo $userarr[$x].": success "; 
			}
		} else {
			$sql = "INSERT INTO users VALUES (null,'".$userarr[$x]."',null,null)";
			//echo $sql;
			if(!$result = $mysqli->query($sql)) echo $userarr[$x].": <b>error adding</b>";
			$user_id = $mysqli->insert_id;
			$sql = "INSERT INTO election_access VALUES (null,'$election_id','$user_id')";
			if(!$result = $mysqli->query($sql)) { 
				echo $userarr[$x].": <b>error</b> "; 
			} else {
				echo $userarr[$x].": success "; 
			}
		}
		$x++;
		}
		die();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Vote! | Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="../assets/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
  </head>
  <body>
     <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="brand" href="/">Amercia Elections</a>
                 </div>
      </div>
    </div>
    <div class="container">
    	<div class="row">
	    	<div class="span8"><h3>Republican Primary</h3><a href="#" onclick="clearVotes(1);">erase all votes</a><br /><br /></div>
	    	<div class="span4" style="text-align:right;"><p><a href="admin.php?logout">logout</a></p></div>
	    </div>
	    <div class="row">
		    <div class="span4">
			    <div>Current voters:<br />
				    <div id="currentvoters1"><? echo getVoters(1); ?></div><br />
				</div>
				<div style="">Add users: (comma-separated list)<br /><textarea id="users1"></textarea><button onclick="addUsers('1');" id="addButton1" class="btn">Add users</button></div>
				<div id="userStatus1">&nbsp;</div>
				<br /><br />
			</div>
			<div class="span4">
				&nbsp;Candidates:
				<div id="candidates1"><? echo getCandidates(1); ?></div><br /><br />
				<div style="">Add candidate:<br /><input type="text" id="candidate1" /><br /><button onclick="addCandidate('1');" class="btn" id="addCandidate1">Add candidate</button><br /><span id="candidatestatus1"></span></div>
			</div>
			<div class="span4">
			&nbsp; 
			</div>
	    </div>
	    <div class="row">
    <div class="span8"><h3>Democratic Primary</h3><a href="#" onclick="clearVotes(2);">erase all votes</a><br /><br /></div>
    </div>
<div class="row">
<div class="span4">
<div>Current voters:<br />
<div id="currentvoters2"><? echo getVoters(2); ?></div><br /></div>
<div style="">Add users: (comma-separated list)<br /><textarea id="users2"></textarea><button onclick="addUsers('2');" id="addButton2" class="btn">Add users</button></div>
<div id="userStatus2">&nbsp;</div><br /><br />
</div>
<div class="span4">
&nbsp;Candidates:
<div id="candidates2"><? echo getCandidates(2); ?></div><br /><br />
<div style="">Add candidate:<br /><input type="text" id="candidate2" /><br /><button onclick="addCandidate('2');" class="btn" id="addCandidate2">Add candidate</button><br /><span id="candidatestatus2"></span></div>
</div>
</div>
<div class="span4">
&nbsp; 
</div>
</div>
	</div>
 <script type="text/javascript">
 	function clearVotes(elec){
 	if(confirm("You sure?")) {
	 	$.post('admin.php?clearvotes&election='+elec, function(data){
	    	alert(data);
	    });
	    }
 	}
    function addUsers(no){
    	var userlist = $('#users'+no).val();
    	$('#userStatus'+no).html('Loading...');
    	$('#addButton'+no).attr('disabled','disabled');
	    $.post('admin.php?addusers&election='+no, { users : userlist }, function(data){
			   $('#userStatus'+no).html(data); 
			   $('#addButton'+no).removeAttr('disabled');
			   console.log(data);
	    });
    }
    function addCandidate(no){
    	var can = $('#candidate'+no).val();
    	$('#addcandidate'+no).attr('disabled','disabled');
	    $.post('admin.php?addCandidate&election='+no, { candidate : can }, function(data){
			   $('#candidates'+no).html(data); 
			   $('#addcandidate'+no).removeAttr('disabled');
	    });
    }
    function revoke(id,elec){
    	$('#currentvoters'+elec).html('Loading...');
	    $.post('admin.php?revoke&election='+elec, { accessid : id }, function(data){
	    	if(data != 'error'){
	    		$('#currentvoters'+elec).html(data);
	    	} else {
		    	alert(data);
	    	}
	    });
    }
    function trashCandidate(id,elec){
    	$('#candidates'+elec).html('Loading...');
	    $.post('admin.php?trashCandidate&election='+elec, { candidateid : id }, function(data){
	    	if(data != 'error'){
	    		$('#candidates'+elec).html(data);
	    	} else {
		    	alert(data);
	    	}
	    });
    }
    </script>
    <!-- Start of StatCounter Code for Default Guide -->
<script type="text/javascript">
var sc_project=8382375; 
var sc_invisible=1; 
var sc_security="c4d43e79"; 
var sc_https=1; 
var sc_remove_link=1; 
var scJsHost = (("https:" == document.location.protocol) ?
"https://secure." : "http://www.");
document.write("<sc"+"ript type='text/javascript' src='" +
scJsHost +
"statcounter.com/counter/counter.js'></"+"script>");</script>
<noscript><div class="statcounter"><img class="statcounter"
src="https://c.statcounter.com/8382375/0/c4d43e79/1/"
alt="web analytics"></div></noscript>
<!-- End of StatCounter Code for Default Guide -->
    </div>
  </body>
</html>
<?
}
?>