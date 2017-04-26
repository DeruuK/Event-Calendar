<?php
	require 'database.php';
    ini_set("session.cookie_httponly", 1);
    header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json
 
    session_start();
    $username = htmlentities($_SESSION['username']);
	$eid = htmlentities($_POST['eid']);
	
    //show all my post
	$stmt = $mysqli->prepare("select title, description, tag, time, etime from events where username=? AND id=?");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
        echo json_encode(array(
            "success" => false,
            "message" => "Query Failed"
        ));
		exit;
	}
 
	$stmt->bind_param('ss', $username, $eid);
 
	$stmt->execute();
 
	$stmt->bind_result($etitle, $edesc, $etag, $etime, $editetime);
	$stmt->fetch();
    echo json_encode(array(
        "success" => true,
        "edesc" => $edesc,
		"etitle" => $etitle,
		"etag" => $etag,
		"etime" => $etime,
		"editetime" => $editetime
    ));
	$stmt->close(); 
?>