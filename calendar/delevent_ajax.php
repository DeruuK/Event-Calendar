
<?php
	require 'database.php';
    ini_set("session.cookie_httponly", 1);
    header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json
 
    session_start();
    $username = $_SESSION['username'];
    $eid = htmlentities($_POST['eid']);
//    $date=date("Y-m-d",strtotime($date));
 
    // Check username
    $stmt = $mysqli->prepare("SELECT username FROM events WHERE id=?");
    $stmt->bind_param('s', $eid);
    $stmt->execute();
    $stmt->bind_result($user);
    $stmt->fetch();
    if($user===$username){
        $stmt->close();
        $stmt = $mysqli->prepare("DELETE FROM events WHERE id=?");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
			echo json_encode(array(
                "success" => false,
                "message" => "Query failed"
            ));
            exit;
        }
        $stmt->bind_param('s', $eid);
        $stmt->execute();
        $stmt->close();
        echo json_encode(array(
            "success"=>true
        ));
        exit;
    }else{
        echo json_encode(array(
            "success"=>false,
            "message"=> "Don't have authority."
        ));
        exit;
    }

?>
