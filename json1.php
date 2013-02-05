<? 
include_once("connect.php");
include_once("functions.php");
$election = "amercia";
$electionid = filter_var($_GET['election'], FILTER_SANITIZE_NUMBER_INT); 
$mysqli = new mysqli($GLOBALS['hostname'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['database']);
if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error()); return $status;}
$total = 0;

if(isset($_GET["q"])){
$q = filter_var($_GET['q'], FILTER_SANITIZE_NUMBER_INT); 
	$sql = "SELECT (select count(*) from exit_poll_results where result_question = $q and result_answer = 1),(select count(*) from exit_poll_results where result_question = $q and result_answer = 2),(select count(*) from exit_poll_results where result_question = $q and result_answer = 3),(select count(*) from exit_poll_results where result_question = $q and result_answer = 9) from exit_poll_results";
	if(!$result = $mysqli->query($sql)) die('There was an error running the query [' . $mysqli->error . ']');
	if($result->num_rows > 0) {
	$values = "[[\"Candidate\",\"Responses\"],";
	$row = $result->fetch_array(MYSQLI_NUM);
	if($q <= 3){
		$values .= "[\"Diaz\",".$row[0]."],";
		$values .= "[\"Rhodes\",".$row[1]."],";
		$values .= "[\"Lawrence\",".$row[2]."],";
		$values .= "[\"Undecided\",".$row[3]."]]";
	} else if ($q == 4) {
		$values .= "[\"Potter\",".$row[0]."],";
		$values .= "[\"Taylor\",".$row[1]."],";
		$values .= "[\"Countryman\",".$row[2]."],";
		$values .= "[\"Undecided\",".$row[3]."]]";	
	} else if ($q == 5){
		$values .= "[\"Diaz\",".$row[0]."],";
		$values .= "[\"Lawrence\",".$row[1]."],";
		$values .= "[\"Undecided\",".$row[3]."]]";
	}
	}
	die($values);
}

$sql = "select month(vote_time), day(vote_time), hour(vote_time), count(*) from votes v where vote_candidate_id != 0 group by month(vote_time), day(vote_time), hour(vote_time)";
if(!$result = $mysqli->query($sql)) die('There was an error running the query [' . $mysqli->error . ']');

if($result->num_rows > 0) {
$values = "[[\"Hour\",\"Votes by hour\",\"Total Votes\"],";
while($row = $result->fetch_array(MYSQLI_NUM)){
	$total = $total + intVal($row[3]);
	$date = DateTime::createFromFormat('U', STRTOTIME($row[0]."/".$row[1]."/2012 ".$row[2].":00 -0100"));
	//date_default_timezone_set('America/New_York');
	$actualhour = $date->format('ga');
	$now = $date1 = new DateTime("now");
	$interval = date_diff($now, $date);
	$hoursago = $interval->format('%r%h');
	if($hoursago == 0) {$hoursago = "now";} else {$hoursago = "$hoursago"."h";}
	//$hour = DATE("D ga", STRTOTIME($row[0]."/".$row[1]."/2012 ".$row[2].":00"));
	//date_default_timezone_set('America/New_York'); 
	//$hour = date("ga", $date);
	$values .= "[\"$actualhour"."\",".$row[3].", $total],";
}
echo substr($values, 0,-1)."]";
} else {
	echo "[[\"Hour\",\"Votes\"]]";
	}

?>