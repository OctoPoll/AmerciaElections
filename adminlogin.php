<?
session_start();
$password = '';
$pass = '';
include_once("connect.php");
$votepass_hash = md5($votepassword);
if(isset($_GET["logout"])){ 
	$_SESSION["AdminPass"] = null;
	header("Location: http://vote.anewamercia.com/adminlogin.php");
}
if(isset($_POST["pass"])){ 
	$pass_hash = md5($_POST["pass"]);
	if($pass_hash == $votepass_hash) {
		$_SESSION["AdminPass"] = $pass_hash;
		header("Location: http://vote.anewamercia.com/results.php");
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

  </head>
  <body>
     <div class="navbar navbar-inverse navbar-static-top" style="display:;">
      <div class="navbar-inner">
          <a class="brand" href="/touchvote.php">Amercia Elections</a>
      </div>
    </div>
    <div class="container-fluid" style="padding-top:10px;">
    <div class="row-fluid">
	    <div class="span12" style="text-align:center;padding-top:100px;" id="">
	    <h1>Election for President of Amercia</h1>
	    <form id="votelogin" method="post" action="adminlogin.php">
	    <input type="password" id="pass" value="" name="pass" /> <input type="submit" value="Login" class="btn btn-warning btn-large" />
	    </form>
	    </div>
	    
    </div>

    </div>
    <script type="text/javascript">
	    function addNum(num){
	    	var inVal = $('#input_suid').val();
	    	if(inVal.length < 9){
	    		$('#input_suid').val(inVal+num);
	    	}
	    }
	    function doAction(act){
	    	var inVal = $('#input_suid').val(), minusone = inVal.length - 1;;
	    	if(act == 'del'){
	    		$('#input_suid').val(inVal.substring(0,minusone));
	    	} else if(act == 'clr'){
	    		$('#input_suid').val(null);
	    }
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