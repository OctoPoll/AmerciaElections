<?
include_once("connect.php");
include_once("functions.php");
$election = "amercia";
$electionid = filter_var($_GET['election'], FILTER_SANITIZE_NUMBER_INT); 
$mysqli = new mysqli($GLOBALS['hostname'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['database']);
if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error()); return $status;}

if(isset($_GET["userstatus"])){
$array = array();
$array1 = array();
$array2 = array();
$array3 = array();
$array1[] = "Hour";
$array1[] = "Votes";
$array[] = $array1;
$sql = "SELECT HOUR(vote_time), COUNT(*), (SELECT COUNT(*) FROM votes AS v1 WHERE HOUR(v1.vote_time) <= hour(v.vote_time)) FROM votes AS v GROUP BY HOUR(vote_time)";
if(!$result = $mysqli->query($sql)) die('There was an error running the query [' . $mysqli->error . ']');
$row = $result->fetch_array(MYSQLI_NUM);
$total = $row[0];
$array2[] = "Have not voted";
$array2[] = $total;
//$sql = "select count(*) from log where log_category = 'user_voted' and log_election = $electionid";
//if(!$result = $mysqli->query($sql)) die('There was an error running the query [' . $mysqli->error . ']');
//$row = $result->fetch_array(MYSQLI_NUM);
//$voted = $row[0];
$array3[] = "Voted";
$array3[] = $voted;
$array[] = $array2;
$array[] = $array3;
//echo "$total],";
//echo "[\"Voted\"s,$voted]]";
echo json_encode($array, JSON_NUMERIC_CHECK);

	die();
}
$chartData1 = array();
$chartData2 = array();
$chartData1[] = "Votes";
$chartData2[] = "Votes";
$sql = "SELECT count(vote_id) as vote_count,candidate_name FROM votes right join election_candidates on vote_candidate_id = candidate_id where candidate_election_id = '$electionid' group by candidate_id,candidate_name";
if(!$result = $mysqli->query($sql)) die('There was an error running the query [' . $mysqli->error . ']');
while($res = $result->fetch_array(MYSQLI_ASSOC)){
	$chartData2[] = $res["vote_count"];
	$chartData1[] = ucwords($res["candidate_name"]);
}
$finalChart = array();
$finalChart[] = $chartData1;
$finalChart[] = $chartData2;
//print_r($chartData1);
//print_r($chartData2);
echo json_encode($finalChart, JSON_NUMERIC_CHECK);
?>