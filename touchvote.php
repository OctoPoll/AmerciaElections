<?
session_start();
$password = '';
$pass = '';
//if(isset($_GET["thanks"])) session_destroy();
include_once("connect.php");
include_once("pushersettings.php");
$mysqli = new mysqli($GLOBALS['hostname'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['database']);
$votepass_hash = md5($votepassword);
if($_COOKIE["VotePass"] != $votepass_hash && $pass_hash != $votepass_hash) { 
header('HTTP/1.1 403 Forbidden'); //header('Location: http://vote.anewamercia.com/'); 
include("votelogin.php");
die();
}
if(isset($_GET["check"])) {
	if($_SESSION["vote_id"] > 0){
		$arr = array('status' => 'claimed','pending_voters' => 1, 'next_voter' => $_SESSION["vote_id"]);
		$status = json_encode($arr);	
	} else {
		$sql = "SELECT * FROM votes WHERE vote_candidate_id = 0 order by vote_id asc limit 1";
		if(!$result = $mysqli->query($sql)) die('There was an error running the query [' . $mysqli->error . ']');	
		$count = $result->num_rows;
		$vote = $result->fetch_array(MYSQLI_ASSOC);
		$_SESSION["user_id"] = $vote["user_id"];
		$_SESSION["vote_id"] = $vote["vote_id"];
		$arr = array('status' => 'unclaimed','pending_voters' => $count);
		if($count) {$arr["next_voter"] = $vote["vote_id"];
		$sql = "UPDATE votes SET vote_candidate_id = '999' WHERE vote_id = '".$vote["vote_id"]."'";
		if(!$result = $mysqli->query($sql)) die('There was an error running the query [' . $mysqli->error . ']');
		$pusher->trigger('vote_admin', 'ticket_claimed', $vote["vote_id"]);}
				$status = json_encode($arr);

	}
	die($status);
} 
	
	if(isset($_GET["next"])){
			if(!$_SESSION["vote_id"]) {
		die("<script>window.location = 'http://vote.anewamercia.com/touchvote.php'</script>");
		} else {
		die("<script>window.location = 'http://vote.anewamercia.com/vote2.php'</script>");	
		}
	}
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=8" />
    <title>Vote!</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding-top: 0; /* 60px to make the container go all the way to the bottom of the topbar */
        background: url('img/bg.jpg') repeat-y;
        background-size: 100%;
        color:white;
      }
      h3 {
	      line-height: 25px;
	      padding: 5px 0 10px;
	      margin: auto;
      }
      #keypad button {
	      padding:30px 0;
	      margin: 5px;
	      font-size: 30px;
	      height:100px;
	      width:100px;
	      text-align: center;
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
       <script src="http://js.pusher.com/1.12/pusher.min.js" type="text/javascript"></script>
       <script src="/js/bootstrap-alert.js"></script>
       <script src="/js/bootstrap-transition.js"></script>
  </head>
  <body>

    <div class="container-fluid" style="padding-top:;">
    	    <div style="padding:30px 0 40px 0;text-align:center;"><img src="img/banner.png" /></div>

    <div style="text-align:center;">
	    <img src="img/title.png" />
    </div>

    <div class="row-fluid">
	    <div class="span12" style="text-align:center;padding-top:20px;" id="votediv">
	    
	    <div id="voteaction" style="<? if(isset($_GET["thanks"])){ echo "display:none;"; }?>">
	    <div style="height:40px;"><div id="nextvoterdiv" style="display:none;"><h3>Welcome, voter #<span id="nextvoter">000</span>.</h3></div></div>
	    <button class="btn btn-large btn-success" style="padding:60px 100px; margin-top:40px; font-size:20px;" onclick="goVote2();" id="letsgo" disabled="disabled">Let's vote!</button>
	    <div style="padding-top:40px;">
	    		    	<div id="pendingvotersdiv" style="display:none;"><span id="pendingvoters">No</span> voters in line</div>
	    </div>
	    </div>
	    </div>
    </div>
<?
if(isset($_GET["thanks"])){
?>
    <div style="height:60px;margin-top:40px;width:80%;margin:auto;" id="status">

<div class="alert alert-success fade in" style="text-align:center;" id="alert">
			    	<button type="button" class="close" data-dismiss="alert">x</button>
			    	<h1><b>You're done!</b> Thanks for voting & GO AMERCIA!</h1>
			    </div> 
			</div>
			    </div>

<?
}
?>
    </div>
    <script type="text/javascript">
            // Enable pusher logging - don't include this in production
    Pusher.log = function(message) {
      if (window.console && window.console.log) window.console.log(message);
    };

    // Flash fallback logging - don't include this in production
    WEB_SOCKET_DEBUG = true;

    var pusher = new Pusher('4158bb9ce27c36289add'), channel = pusher.subscribe('vote_admin');
    channel.bind('ticket_count', function(data) {
	    getVoters();
    });
    	    function goVote2(){
		    window.location.href = "//vote.anewamercia.com/vote2.php";
	    }
	    /*
	    var checkstatus;	
	    checkstatus = setInterval( function() {   $.get('touchvote.php?check', function(data){
			    if(data == 'letsgo'){
				 	$('#letsgo').removeAttr("disabled");   
				 	clearInterval(checkstatus);
			    }
		    }); }, 5000);
		    */
		    function getVoters(){
			    $.get('touchvote.php?check', function(data){
			    if(data.pending_voters > 0){
			    console.log(data.status);
				 	$('#nextvoter').html(data.next_voter);
				 	$('#nextvoterdiv').fadeIn(1000, function(){
					 	$('#letsgo').removeAttr("disabled");
				 	});
				 	/*if(data.pending_voters > 2){
				 		$('#pendingvotersdiv').show();
					 	$('#pendingvoters').html(data.pending_voters-1);
				 	} else {
					 	$('#pendingvotersdiv').hide();
				 	}*/
			    } else {
			    $('#letsgo').attr("disabled","disabled");
			    	$('#nextvoterdiv').hide();
				   $('#pendingvotersdiv').hide(); 
			    }
		    }, "json");
		    }
		    var id;	
    id = setInterval(function() {
	   getVoters();
	}, 30000);
	    $(function(){
	    	getVoters();
		    setTimeout( function() {   $("#alert").alert('close'); }, 5000);
		    setTimeout( function() {   $("#voteaction").fadeIn(2000); }, 5000);
	    });
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
  </body>
</html>