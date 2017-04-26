<?php
	require 'database.php';
    ini_set("session.cookie_httponly", 1);
    header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json
 
    session_start();
    $username = $_SESSION['username'];
	$year = $_POST['year'];
	$month = $_POST['month'];
	$fliter = $_POST['fliter'];
	
    //show all my post
if($fliter=="all"){
	$stmt = $mysqli->prepare("select id, title, tag, time from events where username=? AND year(time)=? AND month(time)=?");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
 
	$stmt->bind_param('sss', $username, $year, $month);
 
	$stmt->execute();
 
	$stmt->bind_result($eid, $etitle, $etag, $etime);
	$i=0;
	$my_array=array();
	while($stmt->fetch()){
        $my_array[$i]=array(
			"eid"=> $eid,
			"etitle" => $etitle,
			"etag" => $etag,
			"etime" => $etime
		);
		$i=$i+1;
	}
//	$my_array[$i]=array($count => $i);
	echo json_encode($my_array);
	 
	$stmt->close(); 
}else{
	$stmt = $mysqli->prepare("select id, title, tag, time from events where username=? AND year(time)=? AND month(time)=? AND tag=?");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
 
	$stmt->bind_param('ssss', $username, $year, $month, $fliter);
 
	$stmt->execute();
 
	$stmt->bind_result($eid, $etitle, $etag, $etime);
	$i=0;
	$my_array=array();
	while($stmt->fetch()){
        $my_array[$i]=array(
			"eid"=> $eid,
			"etitle" => $etitle,
			"etag" => $etag,
			"etime" => $etime
		);
		$i=$i+1;
	}
//	$my_array[$i]=array($count => $i);
	echo json_encode($my_array);
	 
	$stmt->close();
}
?>