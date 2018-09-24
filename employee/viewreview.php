<?php

    session_start();

    date_default_timezone_set('Australia/Sydney');
    $info = getdate();
    $date = $info['mday'];
    $month = $info['mon'];
    $year = $info['year'];
    $hour = $info['hours'];
    $min = $info['minutes'];
    $sec = $info['seconds'];
    $current_date = "$date/$month/$year :: $hour:$min:$sec";

    if(!$_SESSION["loggedIn"]){
        header("Location:login.php");
    }

    if(isset($_GET["reviewId"])){
        $reviewID = $_GET["reviewId"];
    } else {
        header("Location:choosereview.php");
    }

    require_once('dbconn.php');   

    $mainSql = "SELECT review.employee_id, employee.surname, employee.firstname, employee.employment_mode, review.review_year, 
                review.job_knowledge, review.work_quality, review.initiative, review.communication, review.dependability,
                review.additional_comment, review.goals, review.action, review.date_completed FROM review 
                INNER JOIN employee ON review.employee_id=employee.employee_id WHERE review.review_id='$reviewID' 
                ORDER BY review.review_year DESC";

    $firstResults = $dbConn->query($mainSql)
    or die ('Problem with query: ' . $dbConn->error);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">

  <title>View Review - Wayne Enterprises</title>
  <meta name="description" content="The final project">

  <link rel="stylesheet" href="stylesheets/menubar.css">
  <link rel="stylesheet" href="stylesheets/tableDesign.css">
</head>
<body>

    <nav class="mainMenuContainer">
        <ul class="mainMenu">
            <li><p><a href="logoff.php">Log off</a></p></li>
            <li><p><?php echo $current_date ?></p></li>
            <li><p><?php echo $_SESSION["fullName"]; ?></p></li>
        </ul>
    </nav>

    <br/>

    <section>
     <table>
     <caption><strong>Employee Information section</strong></caption>
    <tr>
    <th>Employee ID</th>
    <th>Surname</th>
    <th>Firstname</th>
    <th>Employment mode</th>
    <th>Review Year</th>
    </tr>

    <?php
            while ($firstSection = $firstResults->fetch_assoc()) { 
    ?>
        <tr>
            <td><?php echo $firstSection["employee_id"]?></td>
            <td><?php echo $firstSection["surname"]?></td>
            <td><?php echo $firstSection["firstname"]?></td>
            <td><?php echo $firstSection["employment_mode"]?></td>
            <td><?php echo $firstSection["review_year"]?></td>
            <!-- output the other fields here from the $row array -->
        </tr>
    </table>
            </section>

    <br/>
    <br/><br/><br/>


                <section>
    <table>
    <caption><strong>Ratings Information section</strong></caption>
    <tr>
    <th>Job knowledge</th>
    <th>Work quality</th>
    <th>Initiative</th>
    <th>Communication</th>
    <th>Dependability</th>
    </tr>
        <tr>
            <td><?php echo $firstSection["job_knowledge"]?></td>
            <td><?php echo $firstSection["work_quality"]?></td>
            <td><?php echo $firstSection["initiative"]?></td>
            <td><?php echo $firstSection["communication"]?></td>
            <td><?php echo $firstSection["dependability"]?></td>
            <!-- output the other fields here from the $row array -->
        </tr>
    </table>
            </section>

    <br/><br/><br/>

                <section>
     <table>
     <caption><strong>Evaluation and Action section</strong></caption>
    <tr>
    <th>Additional Comments</th>
    <th>Goals for employees</th>
    <th>Action required</th>
    <th>Date when review was completed</th>
    </tr>
        <tr>
            <td><?php echo $firstSection["additional_comment"]?></td>
            <td><?php echo $firstSection["goals"]?></td>
            <td><?php echo $firstSection["action"]?></td>
            <td><?php echo $firstSection["date_completed"]?></td>
            <!-- output the other fields here from the $row array -->
        </tr>
    <?php 
        }
        $dbConn->close(); 
    ?>
    </table>
    </section>


</body>
</html>