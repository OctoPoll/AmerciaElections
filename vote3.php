<?
if(isset($_GET["done"])){
	session_destroy();
	die("<script>window.location = 'http://vote.anewamercia.com/touchvote.php?thanks'</script>");
}
session_start();
$password = '';
$pass = '';
include_once("connect.php");
$mysqli = new mysqli($GLOBALS['hostname'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['database']);
$votepass_hash = md5($votepassword);
if($_COOKIE["VotePass"] != $votepass_hash && $pass_hash != $votepass_hash) { 
header('HTTP/1.1 403 Forbidden');
include("votelogin.php");
die();
}
$user = $_SESSION["user_id"];
$vote_id = $_SESSION["vote_id"];
if(isset($_POST["candidate"])){
	$_SESSION["candidate"] = $_POST["candidate"];
	if($_SESSION["candidate"] == 1){
		$sql = "UPDATE votes SET vote_candidate_id = '52' WHERE vote_id = '$vote_id'";
		if(!$result = $mysqli->query($sql)) die('There was an error running the query [' . $mysqli->error . ']');	
	} else if ($_SESSION["candidate"] == 2){
		$sql = "UPDATE votes SET vote_candidate_id = '53' WHERE vote_id = '$vote_id'";
		if(!$result = $mysqli->query($sql)) die('There was an error running the query [' . $mysqli->error . ']');	
	} else if ($_SESSION["candidate"] == 3){
		$sql = "UPDATE votes SET vote_candidate_id = '54' WHERE vote_id = '$vote_id'";
		if(!$result = $mysqli->query($sql)) die('There was an error running the query [' . $mysqli->error . ']');	
	}
	$_SESSION["voted"] = true;
	$sql = "SELECT count(*) FROM votes WHERE vote_candidate_id = 0";
	if(!$result = $mysqli->query($sql)) die('There was an error running the query [' . $mysqli->error . ']');
	$row = $result->fetch_row();
	$pusher->trigger('vote_admin', 'ticket_count', $row[0]);
	die("success");
}
if(isset($_GET["doQ"])){
	$q = filter_var($_POST['q'], FILTER_SANITIZE_NUMBER_INT);
	$a = filter_var($_POST['a'], FILTER_SANITIZE_NUMBER_INT);
	
	$sql = "UPDATE exit_poll_results SET result_answer = '$a' WHERE user_id = '$user' AND result_question = '$q'";
	if(!$result = $mysqli->query($sql)) die('There was an error running the query [' . $mysqli->error . ']');
	die("ok");
}

if(isset($_SESSION["candidate"])){
	if($_SESSION["candidate"] == 1){
		$candidate = "Matt Diaz";
	} else if ($_SESSION["candidate"] == 2){
		$candidate = "Robert Lawrence";
	} else if ($_SESSION["candidate"] == 3){
		$candidate = "Carter Rhodes";
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
      <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>

       <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
         <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
       <script src="/js/bootstrap-alert.js"></script>
       <script src="/js/bootstrap-transition.js"></script>
           <style>
      body {
        padding-top: 0; /* 60px to make the container go all the way to the bottom of the topbar */
      }
      h3 {
	      line-height: 25px;
	      padding: 5px 0 10px;
	      margin: auto;
      }
      .votebutton{
	      width: 400px;
      }
      input[type="radio"]{
	      width:30px;
	      height:30px;
	      content: url('/img/checkbox-unchecked.jpg');
      }

input[type="radio"]:checked{
    content: url('/img/checkbox-checked.jpg');
}
label>span {
	font-size:35px;
	padding-right:30px;
	padding-left:5px;
}
label{
	padding-top:20px;
}
hr{
	margin: 40px 0;
	padding: 0;
}
.radio1{
	padding:10px 20px;
	padding-left:20px;
}
    </style>
  </head>
  <body>
      <div style="display:none;">
	<script type="text/javascript">
		<!--//--><![CDATA[//><!--

			if (document.images) {
				img1 = new Image();
				img2 = new Image();
				img1.src = "/img/checkbox-unchecked.jpg";
				img2.src = "/img/checkbox-checked.jpg";
			}

		//--><!]]>
	</script>
</div>
     <div class="navbar navbar-inverse navbar-static-top" style="display:;">
           	<div class="navbar-inner">
          <a class="brand" href="/touchvote.php">Amercia Elections</a>
          <div class="pull-right">
          	<ul class="nav" >
	          <li>
		          <!--<a href="votelogin.php?logout">logout</a>-->
		          </li>
		         </ul>
          </div>
      </div>
    </div>
    <div class="container-fluid" style="padding-top:10px;">
    	<div class="row-fluid">
	    	<div class="span6 offset3" style="text-align:center;padding-top:10px;" id="">
		    	<div class="alert alert-success fade in">
			    	<button type="button" class="close" data-dismiss="alert">×</button>
			    	<b>Vote submitted for <? echo $candidate; ?>.</b> Long live Amercia!
			    </div> 
			</div>
		</div>
		<div class="row-fluid">
		<div class="span1">&nbsp;</div>
			<div class="span10">
				<form action="vote3.php" method="post" class="form form-inline">
				<h3>Which candidate has used the Web and Social Media most effectively?</h3>
				<label class="radio1">
  <input type="radio" name="1" id="optionsRadios1" value="1" style="margin-top:-5px;">
  <span class="labelcontent">Diaz</span></label>
<label class="radio1">
  <input type="radio" name="1" id="optionsRadios2" value="2" style="margin-top:-5px;">
  <span class="labelcontent">Rhodes</span>
</label>
<label class="radio1">
  <input type="radio" name="1" id="optionsRadios2" value="3" style="margin-top:-5px;">
  <span class="labelcontent">Lawrence</span>
</label>
<label class="radio1" style="margin-top:;">
  <input type="radio" name="1" id="optionsRadios2" value="4" style="margin-top:-5px;">
  <span class="labelcontent">Undecided / Unsure</span>
</label>
<hr />

				<h3 style="margin-top:;">Which candidate has performed best in the debates?</h3>
				<label class="radio1">
  <input type="radio" name="2" id="optionsRadios1" value="1" style="margin-top:-15px;">
  <span class="labelcontent">Diaz</span></label>
<label class="radio1">
  <input type="radio" name="2" id="optionsRadios2" value="2" style="margin-top:-15px;">
  <span class="labelcontent">Rhodes</span>
</label>
<label class="radio1">
  <input type="radio" name="2" id="optionsRadios2" value="3" style="margin-top:-15px;">
  <span class="labelcontent">Lawrence</span>
</label>
<label class="radio1" style="margin-top:;">
  <input type="radio" name="2" id="optionsRadios2" value="4" style="margin-top:-15px;">
  <span class="labelcontent">Undecided / Unsure</span>
</label>
<hr />
				<h3 style="margin-top:;">Which candidate has had the most effective advertisements?</h3>
				<label class="radio1">
  <input type="radio" name="3" id="optionsRadios1" value="1" style="margin-top:-15px;">
  <span class="labelcontent">Diaz</span></label>
<label class="radio1">
  <input type="radio" name="3" id="optionsRadios2" value="2" style="margin-top:-15px;">
  <span class="labelcontent">Rhodes</span>
</label>
<label class="radio1">
  <input type="radio" name="3" id="optionsRadios2" value="3" style="margin-top:-15px;">
  <span class="labelcontent">Lawrence</span>
</label>
<label class="radio1" style="margin-top:;">
  <input type="radio" name="3" id="optionsRadios2" value="4" style="margin-top:-15px;">
  <span class="labelcontent">Undecided / Unsure</span>
</label>
<hr />
				<h3 style="margin-top:;">Which Vice Presidential running mate added the most to the ticket?</h3>
				<label class="radio1">
  <input type="radio" name="4" id="optionsRadios1" value="1" style="margin-top:-15px;">
  <span class="labelcontent">Diaz</span></label>
<label class="radio1">
  <input type="radio" name="4" id="optionsRadios2" value="2" style="margin-top:-15px;">
  <span class="labelcontent">Rhodes</span>
</label>
<label class="radio1">
  <input type="radio" name="4" id="optionsRadios2" value="3" style="margin-top:-15px;">
  <span class="labelcontent">Lawrence</span>
</label>
<label class="radio1" style="margin-top:;">
  <input type="radio" name="4" id="optionsRadios2" value="4" style="margin-top:-15px;">
  <span class="labelcontent">Undecided / Unsure</span>
</label>
<hr />

<h3 style="margin-top:;">If no independent candidates were included on the ballot, who would receive<br />your vote for president?</h3>
				<label class="radio1">
  <input type="radio" name="5" id="optionsRadios1" value="1" style="margin-top:-15px;">
  <span class="labelcontent">Matt Diaz</span></label>
<label class="radio1">
  <input type="radio" name="5" id="optionsRadios2" value="2" style="margin-top:-15px;">
  <span class="labelcontent">Robert Lawrence</span>
</label>
				</form>
			</div>
			<div class="span1" style=""><div style="position:fixed;bottom:0;right:0;padding:0 20px 20px 0;"><button id="go" class="btn btn-primary btn-large" style="padding: 20px 30px;font-size:20px;" onclick="window.location.href = 'vote3.php?done';" disabled>Done > ></button></div></div>
		</div>
    </div>
    <script type="text/javascript">
	    $(function(){
		    //setTimeout( function() {   $(".alert").alert('close'); }, 5000);
	    });
	    var totalcount = 0;
	    $('input[type="radio"]').change(function(){
	    	$("#go").attr("disabled","disabled");
	    	var val = $(this).val(), nameval = parseInt($(this).attr("name")), inputobj = $(this), q1val = $('input:radio[name=1]:checked').val(), q2val = $('input:radio[name=2]:checked').val(), q3val = $('input:radio[name=3]:checked').val(), q4val = $('input:radio[name=4]:checked').val(), q5val = $('input:radio[name=5]:checked').val();
	    	$('input[name="'+nameval+'"]').css('content', 'url("/img/checkbox-unchecked.jpg")');
	    	$(this).css('content', 'url("/img/loader.gif")');
		    $.post('vote3.php?doQ', {q : name, a : val}, function(data){
		    inputobj.css('content', 'url("/img/checkbox-checked.jpg")');
			   if(data != 'ok') {
			   	alert(data); 
			   	} else {
			   	totalcount = totalcount + nameval;
			   	inputobj.parent().effect("highlight", {}, 1000);
			   	console.log(q1val + ' ' +q2val + ' ' +q3val + ' ' +q4val + ' ' +q5val);
			   	if(q1val > 0 && q2val > 0 && q3val > 0 && q4val > 0 && q5val > 0 ) $("#go").removeAttr("disabled");
			   }
		    });
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