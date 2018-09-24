<!-- Coded by Sushant Adhikari -->
<!-- Student id : 19485485 -->
<?php
    //error_reporting(0);

    session_start();

    $goalError = "";
    $goalFine = FALSE;

    date_default_timezone_set('Australia/Sydney');
    $info = getdate();
    $date = $info['mday'];
    $month = $info['mon'];
    $year = $info['year'];
    $hour = $info['hours'];
    $min = $info['minutes'];
    $sec = $info['seconds'];
    $current_date = "$date/$month/$year :: $hour:$min:$sec";
    $sqlDate = "$year-$month-$date";

    if(!$_SESSION["loggedIn"]){
        $_SESSION["logoffError"] = "An error occurred.";
        header("Location:logoff.php");
    }

    if(isset($_GET["reviewId"])){
        $reviewID = $_GET["reviewId"];
        $_SESSION["savedReviewId"] = $reviewID;
    } else {
        //header("Location:choosereview.php");
    }

    $reviewID = $_SESSION["savedReviewId"];

    // WORKING AFTER GETTINIG REVIEWID
    if($reviewID){
            require_once('dbconn.php');   

            $mainSql = "SELECT employee.employee_id, employee.surname, employee.firstname, job.job_title, employee.employment_mode, department.department_name,
                            review.review_year, review.job_knowledge, review.work_quality, review.initiative, review.communication,
                            review.dependability, review.additional_comment, review.goals, review.action
                            FROM (((employee 
                            INNER JOIN job ON employee.job_id=job.job_id)
                            INNER JOIN review ON employee.employee_id=review.employee_id)
                            INNER JOIN department ON employee.department_id=department.department_id)
                            WHERE review.review_id='$reviewID'";

            $checkIfEmployee = $dbConn->query($mainSql)
            or die ('Problem with query: '. $dbConn->error);

            $firstResults = $dbConn->query($mainSql)
            or die ('Problem with query: ' . $dbConn->error);
            
            while($employeeRow = $checkIfEmployee->fetch_assoc())
            if($employeeRow["employee_id"] == $_SESSION["employeeID"]){
                $iAmEmployee = TRUE;
                break;
            } else {
                $iAmEmployee = FALSE;
                break;
            }
    }

    // WORKING AFTER SUBMITTING OR SAVING
    if(isset($_POST["submit"]) || isset($_POST["save"])){
        
        $jobKnowledge=(int)$_POST['jobKnowledge'];
        $workQuality=(int)$_POST['workQuality'];
        $initiative=(int)$_POST['initiative'];
        $communication=(int)$_POST['communication'];
        $dependability=(int)$_POST['dependability'];
        $additionalComment=$_POST['additionalComments'];
        $goals=$_POST['goals'];
        $action=$_POST['actionRequired'];
        $pattern = '/[a-zA-Z0-9\040,.!-]/';

        if(!$iAmEmployee){
            if(!empty($goals)){
                for($i=0;$i<= strlen($goals)-1; $i++){
                    $matched = preg_match($pattern, $goals[$i], $matches);
                    if($matched == 0){
                        $goalError = "Goals may only contain alphanumeric ['0' to '9', 'a' to 'z', 'A' to 'Z'] characters, spaces [' '],
                        hyphens ['-'], commas [','], period ['.'] and exclamation marks ['!'].";
                        break;
                    } else if($matched == 1) {
                        if($i == strlen($goals)-1){
                            $goalFine = TRUE;
                        }
                    }
                }
            } else {
                $goalError = "Goal field is mandatory.";
                $goalFine = FALSE;
            }
        } else {
            $goalFine = TRUE;
        }
       

        if($goalFine == TRUE){

            if(isset($_POST["save"])){
                require_once('dbconn.php');   
                $sql = "UPDATE review SET job_knowledge = ". ($jobKnowledge == 0 ? 'NULL' : $jobKnowledge) . ", work_quality = ".($workQuality == 0 ? 'NULL' : $workQuality).",
                        initiative = ".($initiative == 0 ? 'NULL' : $initiative)." , communication = ".($communication == 0 ? 'NULL' : $communication).",
                        dependability = ".($dependability == 0 ? 'NULL' : $dependability).", additional_comment = '$additionalComment',
                        goals = '$goals', action = '$action' WHERE review_id = '$reviewID'";

                $results = $dbConn->query($sql)
                or die("Problem with query ". $dbConn->error);

                if($results == TRUE){
                  echo "The current reviews have been updated.";
                  header("Refresh:2; url=choosereview.php");
                   // $dbConn->close();  
                } else {
                    echo "The reviews could not be updated.";
                }
            } else if (isset($_POST['submit'])){
                require_once('dbconn.php');   
                $sql = "UPDATE review SET job_knowledge = ". ($jobKnowledge == 0 ? 'NULL' : $jobKnowledge) . ", work_quality = ".($workQuality == 0 ? 'NULL' : $workQuality).",
                        initiative = ".($initiative == 0 ? 'NULL' : $initiative)." , communication = ".($communication == 0 ? 'NULL' : $communication).",
                        dependability = ".($dependability == 0 ? 'NULL' : $dependability).", additional_comment = '$additionalComment',
                        goals = '$goals', action = '$action', completed = 'Y', date_completed = '$sqlDate' WHERE review_id = '$reviewID'";

                $results = $dbConn->query($sql)
                or die("Problem with query ". $dbConn->error);

                if($results == TRUE){
                    if(!$iAmEmployee){
                        echo "The performance review has been updated in the database.\n";
                        echo "Review updated by: ". $_SESSION['fullName'] . ". \n";
                        echo "Review updated at: ". $current_date . " \n";
                        header("Refresh:2; url=choosereview.php");
                    } else if($iAmEmployee){
                        echo "You have successfully accepted the review.";
                        header("Refresh:2; url=choosereview.php");
                    }
                  //  $dbConn->close();  
                } else {
                    echo "The reviews could not be updated.";
                }
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">

  <title>Finalise Review - Wayne Enterprises</title>
  <meta name="description" content="The final project">

  <link rel="stylesheet" href="stylesheets/menubar.css">
  <link rel="stylesheet" href="stylesheets/finaliseStyle.css">
</head>
<body>

    <nav class="mainMenuContainer">
        <ul class="mainMenu">
            <li><p><a href="logoff.php">Log off</a></p></li>
            <li><p><?php echo $current_date ?></p></li>
            <li><p><?php echo $_SESSION["fullName"]; ?></p></li>
        </ul>
    </nav>


    <?php
        if($reviewID){
            while ($row = $firstResults->fetch_assoc()) { 
    ?>

     <form action="finalisereview.php" method="post" onSubmit="return checkForm()">

        <section>
                        <h2>Employee information section:</h2>

                <label for="employeeId">Employee ID:</label>
                <input type="text" value="<?php echo $row['employee_id']; ?>" disabled name="employeeId" id="employeeId"/>

                <label for="surname">Family Name (Surname) :</label>
                <input type="text" value="<?php echo $row['surname'];?>" disabled name="surname" id="surname"/>

                <label for="firstName">Given Name (Firstname) : </label>
                <input type="text" value="<?php echo $row['firstname']; ?>" disabled name="firstName" id="firstName"/>

                <label for="jobTitle">Job Title</label>
                <input type="text" value="<?php echo $row['job_title'];?>" disabled name="jobTitle" id="jobTitle"/>

                <label for="employmentMode">Employment Mode:</label>
                <input type="text" value="<?php echo $row['employment_mode']; ?>" disabled name="employmentMode" id="employmentMode"/>

                <label for="departmentName">Department Name:</label>
                <input type="text" value="<?php echo $row['department_name']; ?>" disabled name="departmentName" id="departmentName"/>

                <label for="reviewYear">Review Year:</label>
                <input type="text" value="<?php echo $row['review_year']; ?>" disabled name="reviewYear" id="reviewYear"/>

        </section>

        <section>
                        <h2>Ratings information section:</h2>
                <label for="jobKnowledge">Job Knowledge</label>
                <input type="text" value="<?php echo $row['job_knowledge'];?>" <?php if($iAmEmployee == TRUE){echo 'readonly';} else { echo '';} ?> name="jobKnowledge" id="jobKnowledge" onBlur="checkRating(this)"/>
                <span></span>

                <label for="workQuality">Work Quality</label>
                <input type="text" value="<?php echo $row['work_quality'];?>" <?php if($iAmEmployee == TRUE){echo 'readonly';} else { echo '';} ?> name="workQuality" id="workQuality" onBlur="checkRating(this)"/>
                <span></span>

                <label for="initiative">Initiative</label>
                <input type="text" value="<?php echo $row['initiative'];?>" <?php if($iAmEmployee == TRUE){echo 'readonly';} else { echo '';} ?> name="initiative" id="initiative" onBlur="checkRating(this)"/>
                <span></span>

                <label for="communication">Communication</label>
                <input type="text" value="<?php echo $row['communication'];?>" <?php if($iAmEmployee == TRUE){echo 'readonly';} else { echo '';} ?> name="communication" id="communication" onBlur="checkRating(this)"/>
                <span></span>

                <label for="dependability">Dependability</label>
                <input type="text" value="<?php echo $row['dependability'];?>" <?php if($iAmEmployee == TRUE){echo 'readonly';} else { echo '';} ?> name="dependability" id="dependability" onBlur="checkRating(this)"/>
                <span></span>

                        <h2>Evaluation and action section:</h2>
                <label for="additionalComments">Additional Comments:</label>
                <input type="text" value="<?php echo $row['additional_comment'];?>" <?php if($iAmEmployee == TRUE){echo 'readonly';} else { echo '';} ?> name="additionalComments" id="additionalComments"/>

                <label for="goals">Goals</label>
                <input type="text" value="<?php echo $row['goals'];?>" <?php if($iAmEmployee == TRUE){echo 'readonly';} else { echo '';} ?> name="goals" id="goals"/> 
                <span><?php echo $goalError; ?></span>

                <label for="actionRequired">Action Required:</label>
                <input type="text" value="<?php echo $row['action'];?>" <?php if($iAmEmployee == TRUE){echo 'readonly';} else { echo '';} ?> name="actionRequired" id="actionRequired" onBlur="checkAction(this)"/>
                <span></span>

        </section>


        <section>

                    <h2>Verification Section:</h2>

                    <label for="sign">Enter your name as signature: </label>
                    <input type="text" placeholder="" name="sign" id="sign"/>
                    <br/>
                    <p>Thank you for taking part in your Performance Review. This review is an important aspect
                        of the development of our organisation and its profits and of you as a valued employee.
                    </p>
                    <p>
                    <strong>By electronically signing this form, you confirm that you have discussed this review in
                    detail with your supervisor.</strong> <i>The fine print: Signing this form does not necessarily indicate that you agree
                    with this evaluation. If you do not agree with this evaluation please feel free to find another job outside of Wayne
                    Enterprises&trade;.</i>
                    </p>
        </section>



        <!-- Submitting stuff. -->
        <?php if($iAmEmployee == TRUE) { ?>

            <input type="submit" name="submit" value="Accept and Submit"/>

        <?php } else { ?>
            
            <input type="submit" name="save" value="Save"/>
            <input type="submit" name="submit"/>

        <?php } ?>
    </form>
    <?php  }   
          
                $dbConn->close();  
        }
    ?>

    <script src="js/reviewCheck.js"></script>

</body>
</html>