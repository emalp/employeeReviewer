<!-- Coded by Sushant Adhikari -->
<!-- Student id : 19485485 -->
<?php
    session_start();
    $userIsSupervisor = FALSE;

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
        $_SESSION["logoffError"] = "An error occurred.";
        header("Location:logoff.php");
    }
    $employeeId = $_SESSION["employeeID"];

    require_once('dbconn.php');   

    $mainSql = "SELECT review_id, review_year, completed, date_completed, supervisor_id FROM review WHERE employee_id='$employeeId' ORDER BY review_year DESC";

    $firstResults = $dbConn->query($mainSql)
    or die ('Problem with query: ' . $dbConn->error);

    //$firstSection = $firstResults->fetch_assoc();
    // ---------------------------- first section -----------------------
    
    $supervisorSql = "SELECT department.department_name, employee.surname, employee.firstname, review.review_year, review.review_id, review.employee_id, review.completed, review.action, review.date_completed 
        FROM ((review INNER JOIN employee
        ON review.employee_id=employee.employee_id)
        INNER JOIN department ON review.department_id=department.department_id)
        WHERE review.supervisor_id LIKE '%$employeeId%' ORDER BY review.review_year DESC ";

    $secondResults = $dbConn->query($supervisorSql)
    or die ('Problem with query: ' . $dbConn->error);

    $secondResults2 = $dbConn->query($supervisorSql)
    or die ('Problem with query: ' . $dbConn->error); 

    //$secondSection = $secondResults->fetch_assoc();
    // ------------------------- second section -------------------------------
    
    if(!(mysqli_num_rows($secondResults) == 0)){
        $userIsSupervisor = TRUE;
    } else {
        $userIsSupervisor = FALSE;
    }
    
?>

<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>Choose Review - Wayne Enterprises</title>
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

    <?php  
            //if(!$userIsSupervisor){ 
        ?>
     <table>
    <tr>
    <th>Review year</th>
    <th>Date Completed</th>
    </tr>

    <?php
            while ($firstSection = $firstResults->fetch_assoc()) { 
    ?>
        <tr>
            <td><a href="<?php 
                    if($firstSection["completed"] == "Y") {
                        echo "viewreview.php?reviewId=".$firstSection["review_id"];
                    } else {
                        echo "finalisereview.php?reviewId=".$firstSection["review_id"];
                    }
                 ?>">
                 <?php echo $firstSection["review_year"]?></a></td>
            <td><?php echo $firstSection["date_completed"]?></td>
            <!-- output the other fields here from the $row array -->
        </tr>
    <?php }
        //$dbConn->close(); 
    ?>
    </table>
            <?php 
                //} 
            ?>

        <br/>
        <br/>
        

                    <!-- SUPERVISOR TABLES START HERE. -->
    <?php 
            if($userIsSupervisor){ 
        ?>
     <table>
    <tr>
    <th>Department Name</th>
    <th>Surname</th>
    <th>Firstname</th>
    <th>Review Year</th>
    <th>Review ID</th>
    <th>Employee ID</th>
    <th>Action</th>
    <th>Completed</th>
    <th>Date Completed</th>
    </tr>

    <?php

            while ($secondSection = $secondResults->fetch_assoc()) { 
                 if($secondSection["completed"] == "Y"){
    ?>
        <tr>
            <td><?php echo $secondSection["department_name"]?></td>
            <td><?php echo $secondSection["surname"]?></td>
            <td><?php echo $secondSection["firstname"]?></td>
            <td><a href="viewreview.php?reviewId=<?php echo $secondSection['review_id'] ?>"><?php echo $secondSection["review_year"]?></a></td>
            <td><?php echo $secondSection["review_id"]?></td>
            <td><?php echo $secondSection["employee_id"]?></td>
            <td><?php echo $secondSection["action"]?></td>
            <td><?php echo $secondSection["completed"]?></td>
            <td><?php echo $secondSection["date_completed"]?></td>
            <!-- output the other fields here from the $row array -->
        </tr>
                 <?php } 
                 }
                    //}     
              //  } $dbConn->close(); 
                ?>
    </table>   

    <br/>
    <br/>

     <table>
    <tr>
    <th>Department Name</th>
    <th>Surname</th>
    <th>Firstname</th>
    <th>Review Year</th>
    <th>Review ID</th>
    <th>Employee ID</th>
    <th>Action</th>
    </tr>

    <?php
           //else {
      //  if($userIsSupervisor){

            while ($secondSection2 = $secondResults2->fetch_assoc()) { 
                 if($secondSection2["completed"] == "N"){
    ?>
        <tr>
            <td><?php echo $secondSection2["department_name"]?></td>
            <td><?php echo $secondSection2["surname"]?></td>
            <td><?php echo $secondSection2["firstname"]?></td>
            <td><a href="finalisereview.php?reviewId=<?php echo $secondSection2['review_id']; ?>"><?php echo $secondSection2["review_year"];?></a></td>
            <td><?php echo $secondSection2["review_id"]?></td>
            <td><?php echo $secondSection2["employee_id"]?></td>
            <td><?php echo $secondSection2["action"]?></td>
           
            <!-- output the other fields here from the $row array -->
        </tr>
                 <?php }
                    }     
                $dbConn->close(); 
                ?>
    </table>   

    <?php  }
        else {
            $dbConn->close(); 
        } 
    ?> 


</body>
</html>