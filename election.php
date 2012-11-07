<?
if(!isset($_GET['refresh'])) $refresh = 2;
$username=""; $password=""; $database="";$hostname = "";
include_once("connect.php");
if($_COOKIE["VotePass"] != $votepass_hash && $pass_hash != $votepass_hash) { 
header('HTTP/1.1 403 Forbidden');
include("votelogin.php");
die();
}
include_once("functions.php");
$mysqli = new mysqli($GLOBALS['hostname'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['database']);
if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error()); return $status;}
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
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">

    <!-- Le styles -->
    <style>
      body {
         background: url('img/bg.jpg') repeat-y;
        background-size: 100%;
        color:white;
        background-attachment: fixed;
        padding-top: 0; /* 60px to make the container go all the way to the bottom of the topbar */
      }
      h3 {
	      line-height: 25px;
	      padding: 5px 0 10px;
      }
      .pie{
	      height: 350px;
	      width:100%;
      }
      .pieoutside{
	      width: 50%;
	      height: 280px;
	      float:left;
	      margin:auto;
	      margin-top:10px;
      }
    </style>

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
    var chart1 = null, data1 = null, jsonData1 = null, dataArray1 = null, chart2 = null, data2 = null, jsonData2 = null, dataArray2 = null, chart3 = null, data3 = null, jsonData3 = null, dataArray3 = null, chart4 = null, data4 = null, jsonData4 = null, dataArray4 = null, chart5 = null, data5 = null, jsonData5 = null, dataArray5 = null, chart6 = null, data6 = null, jsonData6 = null, dataArray6 = null, v1width = $('#visualization').width(),v2width = $('#visualization2').width();
    function drawVisualization() {

  var array1 = [["Hour","Votes by hour","Total Votes"],["9am",19, 19],["10am",46, 65],["11am",37, 102],["12pm",54, 156],["1pm",65, 221],["2pm",57, 278],["3pm",73, 351],["4pm",58, 409],["5pm",40, 449],["6pm",48, 497],["7pm",12, 509]];
    var array2 = [["Candidate","Responses"],["Diaz",152],["Rhodes",175],["Lawrence",103],["Undecided",78]];
  var array3 = [["Candidate","Responses"],["Diaz",158],["Rhodes",94],["Lawrence",112],["Undecided",144]];
  var array4 = [["Candidate","Responses"],["Diaz",157],["Rhodes",176],["Lawrence",109],["Undecided",66]];
  var array5 = [["Candidate","Responses"],["Potter",149],["Taylor",71],["Countryman",127],["Undecided",161]];
  var array6 = [["Candidate","Responses"],["Diaz",217],["Lawrence",145],["Undecided",146]];
  
    data1 = google.visualization.arrayToDataTable(array2);
  data2 = google.visualization.arrayToDataTable(array1);
    data3 = google.visualization.arrayToDataTable(array3);
  data4 = google.visualization.arrayToDataTable(array4);
  data5 = google.visualization.arrayToDataTable(array5);


  chart2 = new google.visualization.ComboChart(document.getElementById('visualization2'));
    chart1 = new google.visualization.PieChart(document.getElementById('visualization1'));
      chart3 = new google.visualization.PieChart(document.getElementById('visualization3'));
        chart4 = new google.visualization.PieChart(document.getElementById('visualization4'));
  chart5 = new google.visualization.PieChart(document.getElementById('visualization5'));



  chart2.draw(data2, {title:"Votes by hour, with total", legend: {position: 'none'}, chartArea : {},hAxis: {textStyle : {color:"white"}}, pieSliceText: 'value', titleTextStyle:{color:"white"}, backgroundColor: { fill:'transparent' }, seriesType: "line", vAxes: {0: {logScale: false, textStyle: { color: "#00CCFF"}},
            1: {logScale: false, textStyle: { color: "#FF3333"}}},
    series:{ 0:{targetAxisIndex:0, type: "bars"}, 1:{targetAxisIndex:1}}}); 

  chart1.draw(data1, {title:"Which candidate has used the Web & Social Media most effectively?", backgroundColor: { fill:'transparent' },titleTextStyle:{color:"white"}, legend: {position: 'left', textStyle:{color:"white"}}, is3D: true, pieSliceText: 'percentage', chartArea: {width: "400px"},hAxis: {color:"white"}}); 

  chart3.draw(data3, {title:"Which candidate has performed best in the debates?", backgroundColor: { fill:'transparent' },titleTextStyle:{color:"white"}, legend: {position: 'left', textStyle:{color:"white"}}, is3D: true, pieSliceText: "percentage", chartArea: {height: "400px", left: 20, right:0}}); 
 
  chart4.draw(data4, {title:"Which candidate has had the most effective advertisements?", backgroundColor: { fill:'transparent' }, titleTextStyle:{color:"white"}, legend: {position: 'left', textStyle:{color:"white"}}, is3D: true, pieSliceText: "percentage", chartArea: {width: "400px"}}); 

  chart5.draw(data5, {title:"Which Vice Presidential running mate added the most to the ticket?", backgroundColor: { fill:'transparent' }, titleTextStyle:{color:"white"}, legend: {position: 'left', textStyle:{color:"white"}}, is3D: true, pieSliceText: "percentage", chartArea: {width: "400px", left: 20, right:0}}); 

}
      google.setOnLoadCallback(drawVisualization);

    </script>

  </head>
  <body>
          <div style="padding:30px 0 20px 0;text-align:center;"><a href="http://www.electionclass.com/"><img src="img/banner.png" border="0" /></a></div>
<div style="text-align:left;padding-left:20px;"><a href="/" style="padding:5px;background-color:rgba(255,255,255,0.8);border:solid 1px black;">< < < Back to main results</a></div>
  <div style="height:1200px;background-color:;">
  <div id="vizoutside" style="margin-top:; border-right:;width: ;">
    <div id="visualization2" style="width: ; height: 500px;"><img src="img/loader.gif" /></div>
  	</div>
  	  	<div style="padding:0 20px;"><h3 style="padding:0;margin:0;">Exit poll responses</h3></div>
  	<div id="vizoutside" style="margin-top:; border-right:;" class="pieoutside">
    <div id="visualization1" style="width: ; height: ;" class="pie"><img src="img/loader.gif" /></div>
  	</div>
  	 <div id="vizoutside" style="margin-top:; border-right:;width: ;" class="pieoutside">
    <div id="visualization3" style="width: ; height: ;" class="pie"><img src="img/loader.gif" /></div>
  	</div>
    <div id="vizoutside" style="margin-top:; border-right:;width: ;" class="pieoutside">
    <div id="visualization4" style="width: ; height: ;" class="pie"><img src="img/loader.gif" /></div>
  	</div>
  	<div id="vizoutside" style="margin-top:; border-right:;width: ;" class="pieoutside">
    <div id="visualization5" style="width: ; height: ;" class="pie"><img src="img/loader.gif" /></div>
  	</div>
  </div>
<div style="clear:both;margin-top:;">
<div style="padding:0 20px;">

    <p style="padding-top:5px;text-align:right;">[a NEXIS production by <a href="http://www.twitter.com/awbauer9" target="_blank">@awbauer9</a> and <a href="http://www.twitter.com/cbeck527" target="_blank">@cbeck527</a>]</p>
</div>

  	    <script type="text/javascript">

    $(function() {
	  // drawChart('2'); 

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