<?
if(!isset($_GET['refresh'])) $refresh = 2;
$username=""; $password=""; $database="";$hostname = "";
include_once("connect.php");
include_once("functions.php");
$mysqli = new mysqli($GLOBALS['hostname'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['database']);
if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error()); return $status;}
$votepass_hash = md5($votepassword);

?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=8" />
    <title>Vote for Amercia!</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Official results from Amercia's 2012 Presidential race">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding-top: ; /* 60px to make the container go all the way to the bottom of the topbar */
      background: url('img/bg.jpg') repeat-y;
        background-size: 100%;
        color:white;
        background-attachment: fixed;
      }
      h3 {
	      line-height: 25px;
	      padding: 5px 0 10px;
      }
      .pie{
	      height: 400px;
	      z-index: -9999;
      }
      .pieoutside{
	      height: ;
	      z-index: -9999;
	      overflow: hidden;
      }
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="../assets/ico/favicon.ico">
<meta property="og:image" content="http://vote.anewamercia.com/img/banner.png" />

<meta property="fb:admins" content="1235700223" /> 
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
       <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
       <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css" />
    <script src="http://code.jquery.com/ui/1.9.0/jquery-ui.js"></script>
    <script src="http://js.pusher.com/1.12/pusher.min.js" type="text/javascript"></script>
        <script type="text/javascript">
      google.load('visualization', '1', {packages: ['corechart']});
    </script>
    <script type="text/javascript">
    var data2 = null, v1width = $('#visualization').width(),v2width = $('#visualization2').width();
    function drawVisualization() {
  var array2 = [["Hour","Matt Diaz","Robert Lawrence","Carter Rhodes"],["8am",4, 5, 10],["9am",21, 9, 35],["10am",37, 22, 43],["11am",58, 41, 57],["noon",89, 64, 68],["1pm",115, 78, 85],["2pm",143, 89, 119],["3pm",167, 104, 138],["4pm",187, 117, 145],["5pm",204, 127, 166],["6pm",208, 131, 170],["7pm",208, 131, 170]];
  data2 = google.visualization.arrayToDataTable(array2);
    var options = {
          colors:['#00bfff','red','#FFFF33'], backgroundColor: 'transparent', pointSize: 5, top: 0,
          vAxis: {title: '# of Votes'},hAxis: { textStyle: {color: 'white'}},  vAxis: { title: 'Votes', titleTextStyle: {color:"white"}, textStyle: {color: 'white'}}, legend: {textStyle:{color: "white"}, position: "bottom", position: "in"}, chartArea: {top: 50}
        };

        var chart = new google.visualization.AreaChart(document.getElementById('visualization2'));
        chart.draw(data2, options);
}

      google.setOnLoadCallback(drawVisualization);
    </script>

  </head>
  <body>

        <div style="padding:30px 0 0 0;text-align:center;"><a href="http://www.electionclass.com/"><img src="img/banner.png" border="0" /></a></div>

    <div class="container-fluid">
    <div class="row-fluid">
    <div class="span12">
    <h1 style="text-align:center;margin:20px 0 0 0;">Final Presidential Results</h1>
    <div id="vizoutside" style="margin-top:; border-right:;width: ;">
    <div id="visualization2" style="width: ; height: 500px;display:none;"><img src="img/loader.gif" /></div>
        <div style="width:370px;margin:auto;margin-bottom:20px;"><!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style ">
<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
<a class="addthis_button_tweet"></a>
<a class="addthis_button_pinterest_pinit"></a>
<a class="addthis_counter addthis_pill_style"></a>
</div>
<script type="text/javascript">var addthis_config = {"data_track_addressbar":false};</script>
<script type="text/javascript" src="http://s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5099e1213a77f382"></script>
<!-- AddThis Button END --></div>
  	</div>
    <div class="row-fluid">
    <div class="span6" style="text-align:left;">
           <p><a href="/election.php" style="padding:5px;background-color:rgba(255,255,255,0.8);border:solid 1px black;">View more results</a></p>

    </div>
    <div class="span6">
    <p style="padding-top:5px;text-align:right;margin-bottom:40px;">[a NEXIS production by <a href="http://www.twitter.com/awbauer9" target="_blank">@awbauer9</a> and <a href="http://www.twitter.com/cbeck527" target="_blank">@cbeck527</a>]</p>
    <form id="loginform" style="display:none;"><div class="input-append" style="float:right;"><input type="password" id="password" style="width:150px;" placeholder="Password" />&nbsp;<input type="submit" onclick="" class="btn" value="Login"></div></form>
    </div>
    </div>
        </div>
    </div>
    <script type="text/javascript">
    $("#loginform").submit(function(evt){
	    evt.preventDefault();
	    login();
    });
    function login(){
    	var pass = $('#password').val();
	    $.post('admin.php?dologin', {password: pass}, function(data){
		   if(data == 'badpassword'){
		   		alert('THERE WILL BE NO VOTER FRAUD HERE!');
		   } else if(data != 'success'){
			   alert(data);
		   } else {
			   document.location = '/admin.php';
		   }
	    });
    }
var id;	
    id = setInterval(function() {
	    drawVisualization();
	}, 30000);
    $(function() {
	   //drawChart('2'); 
	   $('#visualization2').fadeIn(1000);
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
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-557565-12']);
  _gaq.push(['_setDomainName', 'anewamercia.com']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
  </body>
</html>