<?
session_start();
$username=""; $password=""; $database="";$hostname = "";
include_once("connect.php");
$votepass_hash = md5($votepassword);
if($_COOKIE["VotePass"] != $votepass_hash && $pass_hash != $votepass_hash) { 
header('HTTP/1.1 403 Forbidden');
include("votelogin.php");
die();
}
//if(!isset($_SESSION["user"])) $_SESSION["user"] = time();
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
      .votebutton{
	      width: 400px;
	      height: 80px;
	      margin-top:20px; 
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

  </head>
  <body>
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
	    <div class="span12" style="text-align:center;padding-top:60px;" id="">
	    <h1>Choose a candidate for President of Amercia</h1>
	    <div id=""><button class="btn btn-large votebutton" style="" id="candidate-1" onclick="doVote(1);"><span  style="font-size:22px;font-weight:bold;">Matt Diaz</span><br /><span style="font-size:16px;font-style:italic;">Democratic party</span></button></div>
	    <div id=""><button class="btn btn-large votebutton" style="" id="candidate-2" onclick="doVote(2);"><span  style="font-size:22px;font-weight:bold;">Robert Lawrence</span><br /><span style="font-size:16px;font-style:italic;">Republican party</span></button></div>
	    <div id=""><button class="btn btn-large votebutton" style="" id="candidate-3" onclick="doVote(3);"><span  style="font-size:22px;font-weight:bold;">Carter Rhodes</span><br /><span style="font-size:16px;font-style:italic;">Everyone is invited to this Party</span></button></div>
	    </div>
    </div>

    </div>
    <script type="text/javascript">

		function shuffle(array){
		var shuffled = array.slice();
	 	var len = shuffled.length;
		var i = len;
		 while (i--) {
		 	var p = parseInt(Math.random()*len);
			var t = shuffled[i];
	  		shuffled[i] = shuffled[p];
		  	shuffled[p] = t;
	 	}
	 	return shuffled;
	 	}
	 	function doVote(c){
	 		$('.votebutton').attr("disabled","disabled");
	 		$('#candidate-'+c).html("Submitting...");
		 	$.post('vote3.php', { candidate : c }, function(data){
		 		if(data == 'success'){
			 		window.location.href = "http://vote.anewamercia.com/vote3.php";
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
  </body>
</html>