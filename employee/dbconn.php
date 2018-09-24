<?php
   
    $dbConn = new mysqli("localhost", "twa349", "twa349bb", "performancereview349");
    if($dbConn->connect_error) {
        die("Failed to connect to database " . $dbConn->connect_error);
    }
?>