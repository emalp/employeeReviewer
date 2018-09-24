<?php
    session_start();
    if($_SESSION["logoffError"]){
        $loginError = urlencode($_SESSION["logoffError"]);
    }   
    $_SESSION = array();
    session_destroy();
    header("Location:login.php?Error=".$loginError);

?>