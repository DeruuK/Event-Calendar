
<?php
    ini_set("session.cookie_httponly", 1);
	require 'database.php';
    header("Content-Type: application/json");
    
    $username = htmlentities($_POST['username']);
    $pwd_guess = htmlentities($_POST['password']);
    if( !preg_match('/^[\w_\-]+$/', $username) ){
        echo json_encode(array(
            "success" => false,
            "message" => "Invalid Username..."
        ));
        exit;
    }else{
        $stmt = $mysqli->prepare("SELECT COUNT(*) FROM users WHERE username=?");
        // Bind the parameter
        $stmt->bind_param('s', $username);
        $stmt->execute();
 
        // Bind the results
        $stmt->bind_result($cnt);
        $stmt->fetch();
 
        // Check whether user existed
    
        if($cnt == 1){
			$stmt->close();
            echo json_encode(array(
                "success" => false,
                "message" => "This username has existed!"
            ));
            exit;
        } else{
			$stmt->close();
            // incert user information to database.
            $pwd_hash=password_hash($pwd_guess, PASSWORD_DEFAULT);
            $stmt = $mysqli->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            if(!$stmt){
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }
            // Bind the parameter
            $stmt->bind_param('ss', $username, $pwd_hash);
            $stmt->execute();
            $stmt->close();
			// set up $_SESSION ['username']['user_id']
            session_start();
            $_SESSION['username']=$username;
			$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
            echo json_encode(array(
                "success" => true
            ));
            exit;
        }
        
    }
?>
