<?php 

    $dbConn = new mysqli("localhost", "twa349", "twa349bb", "performancereview349");
    if($dbConn->connect_error){
        die("There was a problem connecting to the database". $dbConn->connect_error);
    }

    $sql = "UPDATE review SET completed = 'N', date_completed=NULL WHERE review_id=''";
    $results = $dbConn->query($sql)
    or die("Problem with query" . $dbConn->error);

?>