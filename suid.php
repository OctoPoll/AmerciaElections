<?
$username=""; $password=""; $database="";$hostname = "";
include_once("connect.php");
$votepass_hash = md5($votepassword);
if($_COOKIE["VotePass"] != $votepass_hash && $pass_hash != $votepass_hash) { 
header('HTTP/1.1 403 Forbidden');
include("votelogin.php");
die();
}
$mysqli = new mysqli($GLOBALS['hostname'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['database']);
if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error()); return $status;}
if(isset($_GET["clear"])){
	$sql = "DELETE FROM votes WHERE vote_candidate_id = 0";
	if(!$result = $mysqli->query($sql)) die('There was an error running the query [' . $mysqli->error . ']');
	$status = "Queue cleared";
	$pusher->trigger('vote_admin', 'ticket_count', 0);
}
if(filter_var($_POST['id'], FILTER_VALIDATE_INT)){
$status = "othererror";
	$id = $_POST['id'];
	$suid = substr($id, -4, 4);
	$suid_hash = md5($id);
	$sql = "SELECT * FROM votes WHERE user_id = '$suid_hash'";
	if(!$result = $mysqli->query($sql)) die('There was an error running the query [' . $mysqli->error . ']');
	if($result->num_rows > 0) {
		$row = $result->fetch_row();
		$voter_number = $row[0];
		$status = "alreadyvoted";
		$data = array("message" => "SUID xxx$suid already voted", "status" => $status, "voter_number" => $voter_number);
		$pusher->trigger('vote_admin', 'voter_reg_error', $data);
		//die('alreadyvoted'); 
	} else {
		$status = "ok";
		$sql = "INSERT INTO votes VALUES (null,0,'$suid_hash',null)";
		if(!$result = $mysqli->query($sql)) die('There was an error running the query [' . $mysqli->error . ']');
		
		$sql = "INSERT INTO exit_poll_results VALUES (null,1,0,'$suid_hash',null)";
		if(!$result = $mysqli->query($sql)) die('There was an error running the query [' . $mysqli->error . ']');
		
		$sql = "INSERT INTO exit_poll_results VALUES (null,2,0,'$suid_hash',null)";
		if(!$result = $mysqli->query($sql)) die('There was an error running the query [' . $mysqli->error . ']');
		
		$sql = "INSERT INTO exit_poll_results VALUES (null,3,0,'$suid_hash',null)";
		if(!$result = $mysqli->query($sql)) die('There was an error running the query [' . $mysqli->error . ']');
		
		$sql = "INSERT INTO exit_poll_results VALUES (null,4,0,'$suid_hash',null)";
		if(!$result = $mysqli->query($sql)) die('There was an error running the query [' . $mysqli->error . ']');
		
		$voter_number = $mysqli->insert_id;
		$data = array("message" => "Voter #$voter_number registered (SUID: x$suid)", "status" => $status, "voter_number" => $voter_number);
		$pusher->trigger('vote_admin', 'voter_reg', $data);
	}
	$sql = "SELECT count(*) FROM votes WHERE vote_candidate_id = 0";
	if(!$result = $mysqli->query($sql)) die('There was an error running the query [' . $mysqli->error . ']');
	$row = $result->fetch_row();
	$pusher->trigger('vote_admin', 'ticket_count', $row[0]);
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
      .alert {
	      margin-bottom: 5px;
	      font-size:20px;
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
       <script src="/js/bootstrap-alert.js"></script>
       <script src="/js/bootstrap-transition.js"></script>
         <script src="http://js.pusher.com/1.12/pusher.min.js" type="text/javascript"></script>
  <script type="text/javascript">
  </script>
  </head>
  <body>
     <div class="navbar navbar-inverse navbar-static-top" style="display:;">
      <div class="navbar-inner">
          <a class="brand" href="/touchvote.php">Amercia Elections</a>
      </div>
    </div>
    <div class="container-fluid" style="padding-top:30px;">
    <div class="row-fluid">
	    <div class="span6" style="text-align:center;" id="keypad">
	    <div><h3>Enter SUID#</h3>
<input type="text" style="width:305px;font-size:30px;height:50px;text-align:center;background-color:#fff;letter-spacing:2px;font-weight:bold;" id="input_suid" readonly /></div>
	    	<button class="btn btn-large keypadbtn" onclick="addNum('1');" id="btnone">1</button>
	    	<button class="btn btn-large keypadbtn" onclick="addNum('2');" id="btntwo">2</button>
	    	<button class="btn btn-large keypadbtn" onclick="addNum('3');" id="btnthree">3</button><br />
	    	<button class="btn btn-large keypadbtn" onclick="addNum('4');" id="btnfour">4</button>
	    	<button class="btn btn-large keypadbtn" onclick="addNum('5');" id="btnfive">5</button>
	    	<button class="btn btn-large keypadbtn" onclick="addNum('6');" id="btnsix">6</button><br />
	    	<button class="btn btn-large keypadbtn" onclick="addNum('7');" id="btnseven">7</button>
	    	<button class="btn btn-large keypadbtn" onclick="addNum('8');" id="btneight">8</button>
	    	<button class="btn btn-large keypadbtn" onclick="addNum('9');" id="btnnine">9</button><br />
	    	<button class="btn btn-large keypadbtn" onclick="doAction('clr');" style="font-size:25px;" id="btnclr">CLR</button>
	    	<button class="btn btn-large keypadbtn" onclick="addNum('0');" id="btnzero">0</button>
	    	<button class="btn btn-large keypadbtn" onclick="doAction('del');" style="font-size:25px;" id="btndel">DEL</button>
	    	<div style="padding-top:10px;"><span style="" id="ticketcount">0 voting tickets</span> pending<br /><a href="suid.php?clear">clear queue</a></div> 
	    </div>
	    <div class="span6">
	        <?
    if($status != ""){
?>
<div class="alert alert-success fade in" id="alert" style="">
			    	<button type="button" class="close" data-dismiss="alert">×</button>
			    	<? echo $status ?>
			    </div> 
			<script>setTimeout( function() {   $("#alert").alert('close'); }, 5000);</script>
<?
}
?>
	    <div id="loader" style="display:none;"><img src="/img/loader.gif" /></div>
    		<div id="alerts" style="padding-top:;">	    		
</div>
    	</div>
    </div>
    <div class="row-fluid">
    	<div class="span6 offset3">
    	</div>
    </div>
    <script type="text/javascript">
        // Enable pusher logging - don't include this in production
    Pusher.log = function(message) {
      if (window.console && window.console.log) window.console.log(message);
    };

    // Flash fallback logging - don't include this in production
    WEB_SOCKET_DEBUG = true;

    var pusher = new Pusher('4158bb9ce27c36289add'), channel = pusher.subscribe('vote_admin'), count = 0;
    channel.bind('voter_reg', function(data) {
    			count++;
    			var countst = String(count);
      			var status = "alert-success";
			    $("#alerts").prepend('<div class=\'alert '+status+' fade in\' id=\'alert-'+countst+'\'>'+data.message+'</div>');
			    setTimeout( function() {   $('#alert-'+countst).alert('close'); }, 10000);
    });
    channel.bind('voter_reg_error', function(data) {
    			count++;
    			var countst = String(count);
      			var status = "alert-error";
			    $("#alerts").prepend('<div class=\'alert '+status+' fade in\' id=\'alert-'+countst+'\'>'+data.message+'</div>');
			    setTimeout( function() {   $('#alert-'+countst).alert('close'); }, 10000);
    });
    channel.bind('ticket_count', function(data) {
			    var count = parseInt(data);
			    if (!count){
				   $("#ticketcount").html("No voting tickets");
			    } if (count == 1){
				   $("#ticketcount").html("1 voting ticket");
			    } else {
				    $("#ticketcount").html(data+" voting tickets");
			    }
    });
	    function addNum(num){
	    	var inVal = $('#input_suid').val();
	    	if(inVal.length < 9){
	    		$('#input_suid').val(inVal+num);
	    	}
	    	checkValue();
	    }
	    function doAction(act){
	    	var inVal = $('#input_suid').val(), minusone = inVal.length - 1;;
	    	if(act == 'del'){
	    		$('#input_suid').val(inVal.substring(0,minusone));
	    	} else if(act == 'clr'){
	    		$('#input_suid').val(null);
	    }
	    }
	    function checkValue(){
	    	var suidVal = $('#input_suid').val();
		    if(suidVal.length == 9) {
		    $("#loader").show();
		    $(".keypadbtn").attr("disabled","disabled");
		    $.post('suid.php', {id : suidVal}, function(data){
		     $('#input_suid').val(null);
		    	$(".keypadbtn").removeAttr("disabled");
			    $("#loader").hide();
		    });
		    	}
	    }
	    $(window).keydown(function(event){
	    	event.preventDefault();
		    if(event.keyCode == 48){
			    $("#btnzero").click();
		    } else if(event.keyCode == 49){
			    $("#btnone").click();
		    } else if(event.keyCode == 50){
			    $("#btntwo").click();
		    } else if(event.keyCode == 51){
			    $("#btnthree").click();
		    } else if(event.keyCode == 52){
			    $("#btnfour").click();
		    } else if(event.keyCode == 53){
			    $("#btnfive").click();
		    } else if(event.keyCode == 54){
			    $("#btnsix").click();
		    } else if(event.keyCode == 55){
			    $("#btnseven").click();
		    } else if(event.keyCode == 56){
			    $("#btneight").click();
		    } else if(event.keyCode == 57){
			    $("#btnnine").click();
		    } else if(event.keyCode == 8){
			    $("#btndel").click();
		    }
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