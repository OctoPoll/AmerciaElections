<?
session_start();
$password = '';
$pass = '';
include_once("connect.php");
$mysqli = new mysqli($GLOBALS['hostname'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['database']);
$votepass_hash = md5($votepassword);
if($_COOKIE["VotePass"] != $votepass_hash && $pass_hash != $votepass_hash) { 
header('HTTP/1.1 403 Forbidden'); //header('Location: http://vote.anewamercia.com/'); 
include("votelogin.php");
die();
}
if(isset($_GET["check"])) {
	$sql = "SELECT * FROM votes WHERE vote_candidate_id = 0 order by vote_time asc";
	if(!$result = $mysqli->query($sql)) die('There was an error running the query [' . $mysqli->error . ']');	
	$count = $result->num_rows;
	$vote = $result->fetch_array(MYSQLI_ASSOC);
	$_SESSION["user_id"] = $vote["user_id"];
	$_SESSION["vote_id"] = $vote["vote_id"];
	$arr = array('pending_voters' => $count);
	if($count) $arr["next_voter"] = $vote["vote_id"];
	$status = json_encode($arr);
	die($status);
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
     <div class="navbar navbar-inverse navbar-static-top" style="display:;">
     	<div class="navbar-inner">
          <a class="brand" href="/touchvote.php">Amercia Elections</a>
          <div class="pull-right">
          	<ul class="nav" >
	          <li>
		         <!-- <a href="votelogin.php?logout">logout</a>-->
		          </li>
		         </ul>
          </div>
      </div>
    </div>
    <div class="container-fluid" style="padding-top:10px;">
    <div style="height:30px;" id="status">
<?
if(isset($_GET["thanks"])){
?>
<div class="alert alert-success fade in" id="alert">
			    	<button type="button" class="close" data-dismiss="alert">x</button>
			    	<b>Exit poll submitted.</b> Thanks for voting! GO AMERCIA!
			    </div> 
			</div>
<?
}
?>
    </div>

    <div class="row-fluid">
	    <div class="span12" style="text-align:center;padding-top:100px;" id="">
	    <h1>Election for President of Amercia</h1>
	    <button class="btn btn-large btn-success" style="padding:40px 80px; margin-top:40px; font-size:20px;" onclick="goVote2();" id="letsgo" disabled="disabled">Let's vote!</button>
	    <div style="padding-top:20px;">
	    	<div id="nextvoterdiv" style="display:none;">Current voter: #<span id="nextvoter">000</span></div>
	    	<div id="pendingvotersdiv" style="display:none;"><span id="pendingvoters">No</span> pending voters</div>
	    </div>
	    </div>
    </div>

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
				 	$('#letsgo').removeAttr("disabled");
				 	$('#nextvoterdiv').show();
				 	$('#nextvoter').html(data.next_voter);
				 	if(data.pending_voters > 1){
				 		$('#pendingvotersdiv').show();
					 	$('#pendingvoters').html(data.pending_voters);
				 	} else {
					 	$('#pendingvotersdiv').hide();
				 	}
			    }
		    }, "json");
		    }
	    $(function(){
	    	getVoters();
		    setTimeout( function() {   $("#alert").alert('close'); }, 5000);
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