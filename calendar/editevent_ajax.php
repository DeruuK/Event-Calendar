
<?php
	require 'database.php';
    ini_set("session.cookie_httponly", 1);
    header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json
 
    session_start();
    $username = $_SESSION['username'];
    $title = htmlentities($_POST['etitle']);
    $tag = htmlentities($_POST['etags']);
    $eid = htmlentities($_POST['eid']);
    $desc = $_POST['edesc'];
    $date = $_POST['etime'];
	if(!hash_equals($_SESSION['token'], $_POST['token'])){
		die("Request forgery detected");
	}
	$etime = htmlspecialchars($_POST['addetime']);
//    $date=date("Y-m-d",strtotime($date));
 
    // Check to see if the username and password are valid.  (You learned how to do this in Module 3.)
    if( !isset($title) ){
        echo json_encode(array(
            "success" => false,
            "message" => "Invalid Title..."
        ));
        exit;
    }elseif( !preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $date)){
        echo json_encode(array(
            "success" => false,
            "message" => "Invalid date..."
        ));
        exit;
    }else{
        $date=date("Y-m-d",strtotime($date));
        $stmt = $mysqli->prepare("SELECT COUNT(*) FROM events WHERE username=? AND time=? AND title=? AND description=? AND tag=? AND etime=?");
        // Bind the parameter
        $stmt->bind_param('ssssss', $username, $date, $title, $desc, $tag, $etime);
        $stmt->execute();
 
        // Bind the results
        $stmt->bind_result($cnt);
        $stmt->fetch();
 
        // Compare the submitted password to the actual password hash
        // In PHP < 5.5, use the insecure: if( $cnt == 1 && crypt($pwd_guess, $pwd_hash)==$pwd_hash){
     
        if($cnt === 0 && isset($_SESSION['username'])){
        	// Login succeeded!
            $stmt->close();
            $stmt = $mysqli->prepare("UPDATE events SET title=?, description=?, tag=?, time=?, etime=? WHERE id=?");
            if(!$stmt){
                printf("Query Prep Failed: %s\n", $mysqli->error);
                echo json_encode(array(
                    "success" => false,
                    "message" => "Query failed"
                ));
                exit;
            }
            // Bind the parameter
            $stmt->bind_param('ssssss', $title, $desc, $tag, $date, $etime, $eid);
            if($stmt->execute()){
                echo json_encode(array(
                    "success" => true,
                    "title" => $title
                ));
            }
            $stmt->close();
            exit;
        } else{
            echo json_encode(array(
                "success" => false,
                "message" => "event has existed..."
            ));
            exit;
        	// Login failed; redirect back to the login screen
        }
        
    }    

?>
