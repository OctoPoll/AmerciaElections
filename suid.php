<?
$username=""; $password=""; $database="";$hostname = "";
include_once("connect.php");
include_once("pushersettings.php");
$votepass_hash = md5($votepassword);
if($_COOKIE["VotePass"] != $votepass_hash && $pass_hash != $votepass_hash) { 
header('HTTP/1.1 403 Forbidden');
include("votelogin.php");
die();
}
$mysqli = new mysqli($GLOBALS['hostname'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['database']);
if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error()); return $status;}
if(isset($_GET["getq"])){
	$end = '[';
	$sql = "SELECT vote_id FROM votes WHERE vote_candidate_id = 0 OR vote_candidate_id = 999";
	if(!$result = $mysqli->query($sql)) die('There was an error running the query [' . $mysqli->error . ']');
	while($row = $result->fetch_row()) {
		$end .= '{ "voteid":"'.$row[0].'" },';
	}
	$end = substr($end, 0,-1);
	$end .= ']';
die($end);
}
if(isset($_POST["doclear"])){
	$sql = "DELETE FROM votes WHERE vote_candidate_id = 0 OR vote_candidate_id = 999";
	if(!$result = $mysqli->query($sql)) die('There was an error running the query [' . $mysqli->error . ']');
	$sql = "DELETE  ep.* FROM exit_poll_results AS ep LEFT JOIN votes AS v ON v.user_id = ep.user_id WHERE v.vote_id IS NULL";
	if(!$result = $mysqli->query($sql)) die('There was an error running the query [' . $mysqli->error . ']');
	$pusher->trigger('vote_admin', 'ticket_count', 0);
	//header("Location: http://vote.anewamercia.com/suid.php");
	die("ok");
}
if(isset($_GET["clear"])){
	$status = "Queue cleared";
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
		$data = array("message" => "SUID xxx$suid already registered (Voter #$voter_number)", "status" => $status, "voter_number" => $voter_number);
		$pusher->trigger('vote_admin', 'voter_reg_error', $data);
		//die('alreadyvoted'); 
	} else {
		$status = "ok";
		$sql = "INSERT INTO votes VALUES (null,0,'$suid_hash',null)";
		if(!$result = $mysqli->query($sql)) die('There was an error running the query [' . $mysqli->error . ']');
				$voter_number = $mysqli->insert_id;
		
		$sql = "INSERT INTO exit_poll_results VALUES (null,1,0,'$suid_hash',null)";
		if(!$result = $mysqli->query($sql)) die('There was an error running the query [' . $mysqli->error . ']');
		
		$sql = "INSERT INTO exit_poll_results VALUES (null,2,0,'$suid_hash',null)";
		if(!$result = $mysqli->query($sql)) die('There was an error running the query [' . $mysqli->error . ']');
		
		$sql = "INSERT INTO exit_poll_results VALUES (null,3,0,'$suid_hash',null)";
		if(!$result = $mysqli->query($sql)) die('There was an error running the query [' . $mysqli->error . ']');
		
		$sql = "INSERT INTO exit_poll_results VALUES (null,4,0,'$suid_hash',null)";
		if(!$result = $mysqli->query($sql)) die('There was an error running the query [' . $mysqli->error . ']');
		
		$sql = "INSERT INTO exit_poll_results VALUES (null,5,0,'$suid_hash',null)";
		if(!$result = $mysqli->query($sql)) die('There was an error running the query [' . $mysqli->error . ']');
		
		$data = array("message" => "Voter #$voter_number <span id='status-$voter_number'>ready to vote</span> (SUID: x$suid)", "status" => $status, "voter_number" => $voter_number);
		$pusher->trigger('vote_admin', 'voter_reg', $data);
	}
	$sql = "SELECT count(*) FROM votes WHERE vote_candidate_id = 0";
	if(!$result = $mysqli->query($sql)) die('There was an error running the query [' . $mysqli->error . ']');
	$row = $result->fetch_row();
	$pusher->trigger('vote_admin', 'ticket_count', $row[0]);
	die($status);
}
if(isset($_GET["ping"])){
	$sql = "SELECT count(*) FROM votes WHERE vote_candidate_id = 0";
	if(!$result = $mysqli->query($sql)) die('There was an error running the query [' . $mysqli->error . ']');
	$row = $result->fetch_row();
	$pusher->trigger('vote_admin', 'ticket_count', $row[0]);
	die($row[0]);
}
include 'Mobile_Detect.php';
$detect = new Mobile_Detect();
if ($detect->isMobile()) {
   $tablet = true;
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
  function NoClickDelay(el) {
	this.element = el;
	if( window.Touch ) this.element.addEventListener('touchstart', this, false);
}

NoClickDelay.prototype = {
	handleEvent: function(e) {
		switch(e.type) {
			case 'touchstart': this.onTouchStart(e); break;
			case 'touchmove': this.onTouchMove(e); break;
			case 'touchend': this.onTouchEnd(e); break;
		}
	},
	
	onTouchStart: function(e) {
		e.preventDefault();
		this.moved = false;
		
		this.element.addEventListener('touchmove', this, false);
		this.element.addEventListener('touchend', this, false);
	},
	
	onTouchMove: function(e) {
		this.moved = true;
	},
	
	onTouchEnd: function(e) {
		this.element.removeEventListener('touchmove', this, false);
		this.element.removeEventListener('touchend', this, false);

		if( !this.moved ) {
			var theTarget = document.elementFromPoint(e.changedTouches[0].clientX, e.changedTouches[0].clientY);
			if(theTarget.nodeType == 3) theTarget = theTarget.parentNode;

			var theEvent = document.createEvent('MouseEvents');
			theEvent.initEvent('click', true, true);
			theTarget.dispatchEvent(theEvent);
		}
	}
};
var theTap = $('.keypadbtn');
new NoClickDelay(theTap);
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
	    <div class="span6" style="text-align:center;<? if(false) echo "display:none;";?>" id="keypad">
	    <div><h3>Enter SUID#</h3>
<input type="tel" style="width:305px;font-size:30px;height:50px;text-align:center;background-color:#fff;letter-spacing:2px;font-weight:bold;" id="input_suid"   /></div>
<div style="<? if(false) echo "display:none;";?>">
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
</div>
	    	<div style="padding-top:10px;"><span style="" id="ticketcount">0 voting tickets</span> active<br /><a href="#" onclick="clearList();">clear queue</a></div> 
	    </div>
	    <div class="span6">
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
      			var status = "alert-success";
			    $("#alerts").append('<div class=\'alert '+status+' fade in\' id=\'alert-'+data.voter_number+'\'>'+data.message+'</div>');
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
    channel.bind('user_voted', function(data) {
    	$('#alert-'+data).removeClass('alert-warning');
    	$('#alert-'+data).addClass('alert-error');
    	$('#status-'+data).html("is DONE VOTING");
    	setTimeout( function() {   $("#alert-"+data).alert('close'); }, 30000);

    });
    channel.bind('user_voting', function(data) {
    	$('#alert-'+data).removeClass('alert-success');
    	$('#alert-'+data).addClass('alert-warning');
    	$('#status-'+data).html("is now voting");
    });
    channel.bind('ticket_claimed', function(data) {
    	$('#alert-'+data).removeClass('alert-success');
    	$('#alert-'+data).addClass('alert-warning');
    	$('#status-'+data).html(" voting ticket claimed");
    });
	    function addNum(num){
	    	var inVal = $('#input_suid').val();
	    	if(inVal.length < 9){
	    	<? if(true) {?>
	    		$('#input_suid').val(inVal+num);
	    		<? } else { ?>
		    		checkValue();
		    		
	    		<? } ?>
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
	    <? if(true) {?>
	    $(window).keydown(function(event){
	    	if($('.keypadbtn').is(':disabled')) return false;
		    if(event.keyCode == 48 || event.keyCode == 96){
			    $("#btnzero").click();
		    } else if(event.keyCode == 49 || event.keyCode == 97){
			    $("#btnone").click();
		    } else if(event.keyCode == 50 || event.keyCode == 98){
			    $("#btntwo").click();
		    } else if(event.keyCode == 51 || event.keyCode == 99){
			    $("#btnthree").click();
		    } else if(event.keyCode == 52 || event.keyCode == 100){
			    $("#btnfour").click();
		    } else if(event.keyCode == 53 || event.keyCode == 101){
			    $("#btnfive").click();
		    } else if(event.keyCode == 54 || event.keyCode == 102){
			    $("#btnsix").click();
		    } else if(event.keyCode == 55 || event.keyCode == 103){
			    $("#btnseven").click();
		    } else if(event.keyCode == 56 || event.keyCode == 104){
			    $("#btneight").click();
		    } else if(event.keyCode == 57 || event.keyCode == 105){
			    $("#btnnine").click();
		    } else if(event.keyCode == 8){
		    	event.preventDefault();
			    $("#btndel").click();
		    }
	    });
	    <? } ?>
	    function clearList(){
		    $.post('suid.php?clear', {doclear: true}, function(data){
		    $("#alerts").html('<div class=\'alert alert-success fade in\' id=\'cleared\'>Queue cleared</span></div>');
		    	setTimeout( function() {   $("#cleared").alert('close'); }, 5000);
		    		    	});
	    }
	    /*var id;	
    id = setInterval(function() {
	   $.get('suid.php?ping', function(data){  });
	}, 30000);*/
	    $(function(){
	    	$.get('suid.php?getq', function(data){
		    	for (var i = 0; i < data.length; i++) {
				    console.log(data[i].voteid);
				    $("#alerts").append('<div class=\'alert alert-success fade in\' id=\'alert-'+data[i].voteid+'\'>Voter #'+data[i].voteid+' <span id="status-'+data[i].voteid+'">vote pending</span></div>');
				}
	    	},"json");
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