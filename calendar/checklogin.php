<?php
    session_start();
    if(isset($_SESSION['username'])){
        echo json_encode(array(
            "status" => true
        ));
    }else{
        echo json_encode(array(
            "status" => false
        ));
    }	
?>