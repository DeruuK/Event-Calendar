
<?php
	require 'database.php';
    ini_set("session.cookie_httponly", 1);
    header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json
 
    session_start();
    $username = $_SESSION['username'];
    $subject = htmlentities($_POST['etitle']);
    $tag = htmlentities($_POST['etags']);
    $eid = htmlentities($_POST['eid']);
    $desc = $_POST['edesc'];
    $date = $_POST['etime'];
    $to = $_POST['address'];
    
//    $date=date("Y-m-d",strtotime($date));
 
    // Check to see if the username and password are valid.  (You learned how to do this in Module 3.)
    if( !isset($subject) ){
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
        $message=sprintf("From: %s<br>Title: %s<br>Date: %s \r\t tag: %s<br><p>%s</p>", $username,$subject,$date,$tag,$desc);
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        if(mail($to,$subject,$message,$headers)){
            echo json_encode(array(
                "success" => true,
                "message" => "Send email success!"
            ));
            exit;
        }
    }    

?>
