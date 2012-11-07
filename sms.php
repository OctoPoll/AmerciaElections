<?php
    // grabs POST data from the Twillio callback
    
    $user_number = filter_var($_POST['From'], FILTER_SANITIZE_STRING);
    $message =filter_var($_POST['Body'], FILTER_SANITIZE_STRING);

    
    /* 
    doVote($election, $phone, $message)
   		responses:  invalidelection, invalidnetid, invalidcandidate, notinclass, alreadyvoted, invalidphone, votesuccess, unauthorizedelection, phoneexists, othererror
    */

    header("content-type: text/xml");
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    include_once("functions.php");
    include_once("connect.php");


//phone number unique
?>
<Response>
    <Sms>
    	<?
	    	$vote = doVote2($user_number,$message);
	    	if($vote == "votesuccess"){
		    	echo "Vote submitted! Long live Amercia.";
	    	} else if ($vote == "alreadyvoted"){
		    	echo "Voter fraud! You only get one vote!";
	    	} else if ($vote == "notinclass"){
	    		echo "Nice try, but you're not in this class.";
	    	} else if ($vote == "invalidcandidate" || $vote == "invalidnetid"){
	    		echo "Umm.... That doesn't look right. Try again.";
	    	} else if ($vote == "invalidphone"){
	    		echo "Can't vote from a different phone, sorry.";
	    	} else if ($vote == "unauthorizedelection"){
	    		echo "Whoops, you're not authorized to vote here.";
	    	} else if ($vote == "phoneexists"){
	    		echo "This phone is already registered to another account.";
	    	} else {
		    	echo "Something went wrong. Try again?";
	    	}
    	?>
    </Sms>
</Response>