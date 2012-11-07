<?
session_start();
if(!isset($_GET['refresh'])) $refresh = 2;
$username=""; $password=""; $database="";$hostname = "";
include_once("connect.php");
include_once("functions.php");
$mysqli = new mysqli($GLOBALS['hostname'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['database']);
if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error()); return $status;}
$votepass_hash = md5($votepassword);
if(isset($_GET["logout"])) 	$_SESSION["AdminPass"] = null;

if($_SESSION["AdminPass"] != $votepass_hash) { 
	header('HTTP/1.1 403 Forbidden');
	include("adminlogin.php");
	die();
}
if(isset($_GET["candidate"])){
	$c = filter_var($_GET['candidate'], FILTER_SANITIZE_NUMBER_INT);
	$sql = "SELECT count(*),(select count(*) from votes) FROM votes WHERE vote_candidate_id = '$c'";
	if(!$result = $mysqli->query($sql)) die('There was an error running the query [' . $mysqli->error . ']');
	$row = $result->fetch_row();
	$end = $row[0].",".$row[1];
	die($end);
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
    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
       <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
       <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css" />
    <script src="http://code.jquery.com/ui/1.9.0/jquery-ui.js"></script>
        <script type="text/javascript">
      google.load('visualization', '1', {packages: ['corechart']});
    </script>
    <script type="text/javascript">
    var chart1 = null, data1 = null, jsonData1 = null, dataArray1 = null, chart2 = null, data2 = null, jsonData2 = null, dataArray2 = null, v1width = $('#visualization').width(),v2width = $('#visualization2').width();
    function drawVisualization() {
  jsonData2 = $.ajax({
	  url: "json1.php?election=<? echo $electionid; ?>",
	  dataType: "json",
	  async: false
  }).responseText;
  data2 = google.visualization.arrayToDataTable(JSON.parse(jsonData2));
  // Create and draw the visualization.
  chart2 = new google.visualization.ComboChart(document.getElementById('visualization2'));
  chart2.draw(data2, {title:"Votes by hour, with total", legend: {position: 'none'}, pieSliceText: 'value',seriesType: "bars",
          series: {1: {type: "line"}}}); 
}
	function drawChart(c){
		  jsonData1 = $.ajax({
	  url: "json1.php",
	  dataType: "json",
	  async: false
  }).responseText;
  data1 = google.visualization.arrayToDataTable(JSON.parse(jsonData1));
  chart1 = new google.visualization.ComboChart(document.getElementById('visualization'+c));
  chart1.draw(data1, {title:"Votes by hour, with total", legend: {position: 'none'}, pieSliceText: 'value',seriesType: "bars",
          series: {1: {type: "line"}}}); 
	}
      //google.setOnLoadCallback(drawVisualization);
    </script>

  </head>
  <body>
     <div class="navbar navbar-inverse navbar-static-top" style="display:;">
      <div class="navbar-inner">
          <a class="brand" href="/">Amercia Elections</a>
      </div>
    </div>
    <div class='alert alert-error fade in' id='session' style="display:none; width:80%;margin:20px auto;">Session expires in 30 sec. <a href="secretstats.php">Continue session >></a></span></div>
    <div class="container-fluid">
    <div class="row-fluid">
    <div class="span12">
    <h1>Secret stats</h1>
    </div>
    </div>
    <div class="row-fluid">
    <div class="span12" style="text-align:center;">
    <h3><span style="font-weight:normal;">Matt Diaz:</span> <span id="52number">0</span>&nbsp;<span id="52pct" style="font-size:18px;">(0%)</span></h3>
    <h3><span style="font-weight:normal;">Robert Lawrence:</span> <span id="53number">0</span>&nbsp;<span id="53pct" style="font-size:18px;">(0%)</span></h3>
    <h3><span style="font-weight:normal;">Carter Rhodes:</span> <span id="54number">0</span>&nbsp;<span id="54pct" style="font-size:18px;">(0%)</span></h3>
    <h3><span style="font-weight:normal;">Total votes:</span> <span id="total">0</span></h3>
    </div>
    </div>
    <div class="row-fluid">
    <div class="span6" style="text-align:left;">
        <p style="text-align:left;">
            <? if(!$refresh){?><p><a class="btn btn-info" href="/?refresh&id=<? echo $electionid; ?>">Auto-refresh results</a></p> <? } else { ?> 
<p><img src="img/loader.gif" id="loader" style="display:none;" /> Results updated every 30 sec &nbsp;</p>
<? } ?></p>

    </div>
    <div class="span6">
    <p style="padding-top:5px;text-align:right;">[a NEXIS production by <a href="http://www.twitter.com/awbauer9" target="_blank">@awbauer9</a> and <a href="http://www.twitter.com/cbeck527" target="_blank">@cbeck527</a>]</p><br /><br />
    <form id="loginform" style="display:none;"><div class="input-append" style="float:right;"><input type="password" id="password" style="width:150px;" placeholder="Password" />&nbsp;<input type="submit" onclick="" class="btn" value="Login"></div></form>
    </div>
    </div>
    <div class="row-fluid">
    <div class="span8" style="">
<div style="padding-top:30px;margin:auto;">

</div>
    </div>
        <div class="span4" style="text-align:right;padding-top:40px;">
        
   &nbsp;    </div>
    </div>
    </div>
    <script type="text/javascript">
    var total = 0;
    function updateNumbers(c){
    $("#loader").show();
	     $.get('secretstats.php', {candidate: c}, function(data){
	     $("#loader").hide();
	     	var s = data.split(",");
	     	var pct = Math.round((s[0]/s[1])*100);
	     	$('#'+c+'number').html(s[0]);
	     	$('#'+c+'pct').html("("+pct+"%)");
	     	total = total + parseInt(s[0]);
	     	$('#total').html(total);
	     	
	    });
    }
var id;	
    id = setInterval(function() {
	 $("#session").show();
	 	}, 570000);
	var id1;	
    id1 = setInterval(function() {
	   window.location = "http://vote.anewamercia.com/secretstats.php?logout";
	}, 600000);
	var id2;	
    id2 = setInterval(function() {
	 updateNumbers(52);
	   updateNumbers(53);
	   updateNumbers(54);
	 	 	}, 30000);
    $(function() {
	   //drawChart('2'); 
	   updateNumbers(52);
	   updateNumbers(53);
	   updateNumbers(54);
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