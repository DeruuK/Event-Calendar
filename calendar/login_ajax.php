
<?php
	require 'database.php';
    ini_set("session.cookie_httponly", 1);
    header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json
 
    $username = htmlentities($_POST['username']);
    $pwd_guess = htmlentities($_POST['password']);
 
    // Check to see if the username and password are valid.  (You learned how to do this in Module 3.)
    if( !preg_match('/^[\w_\-]+$/', $username) ){
        echo json_encode(array(
            "success" => false,
            "message" => "Invalid Username..."
        ));
        exit;
    }elseif(!preg_match('/^[\w_\-]+$/', $pwd_guess)){
		echo json_encode(array(
            "success" => false,
            "message" => "Invalid password..."
        ));
        exit;
	}else{
        $stmt = $mysqli->prepare("SELECT COUNT(*), id, password FROM users WHERE username=?");
        // Bind the parameter
        $stmt->bind_param('s', $username);
        $stmt->execute();
 
        // Bind the results
        $stmt->bind_result($cnt, $user_id, $pwd_hash);
        $stmt->fetch();
 
        // Compare the submitted password to the actual password hash
        // In PHP < 5.5, use the insecure: if( $cnt == 1 && crypt($pwd_guess, $pwd_hash)==$pwd_hash){
     
        if($cnt == 1 && password_verify($pwd_guess, $pwd_hash)){
        	// Login succeeded!
            $stmt->close();
            session_start();
//        	$_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
            echo json_encode(array(
                "success" => true,
				"token" => $_SESSION['token']
            ));
            exit;
//            header("Location: index.php");
        	// Redirect to your target page
        } else{
            echo json_encode(array(
                "success" => false,
                "message" => "Incorrect Username or Password"
            ));
            exit;
        	// Login failed; redirect back to the login screen
        }
        
    }    

?>
