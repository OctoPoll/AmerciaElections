<?
session_start();
include_once("connect.php");
include_once("functions.php");
$election = "amercia";
$electionid = filter_var($_GET['election'], FILTER_SANITIZE_NUMBER_INT); 
$mysqli = new mysqli($GLOBALS['hostname'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['database']);
if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error()); return $status;}
$total = 0;

$hour = 8;
$candidate1=0;
$candidate2=0;
$candidate3=0;
$values = "[[\"Hour\",\"Matt Diaz\",\"Robert Lawrence\",\"Carter Rhodes\"],";
while($hour < 20){
$sql = "select count(*),vote_candidate_id from votes v where vote_candidate_id != 0 and hour(vote_time) = '$hour' group by vote_candidate_id";
if(!$result = $mysqli->query($sql)) die('There was an error running the query [' . $mysqli->error . ']');

while($row = $result->fetch_array(MYSQLI_NUM)){
if($row[1] == 52){
	$candidate1=$candidate1+$row[0];
} else if($row[1] == 53){
	$candidate2=$candidate2+$row[0];
} else if($row[1] == 54){
	$candidate3=$candidate3+$row[0];
	}
}
	$values .= "[\"$hour"."00"."\",$candidate1, $candidate2, $candidate3],";

$hour++;
}

echo substr($values, 0,-1)."]";

?>