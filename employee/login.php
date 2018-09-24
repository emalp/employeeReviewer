<!-- Coded by Sushant Adhikari -->
<!-- Student id : 19485485 -->

<?php
  
  session_start();
  error_reporting(0);
  $error = "";

  if(isset($_POST["submit"])){

    $employeeId = $_POST['employeeID'];
    $password = $_POST['password'];

    if(!empty($employeeId) && !empty($password)){
      require_once('dbconn.php');   
      $sql = "SELECT employee_id, password, surname, firstname FROM employee WHERE employee_id='$employeeId'";
      $results = $dbConn->query($sql)
      or die ('Problem with query: ' . $dbConn->error);

      if (mysqli_num_rows($results) == 0) { 
        //results are empty, do something here 
        // employee id not found.
       
        $error = "Employee Id or Password incorrect, please try again.";
     } else{
        $row = $results->fetch_assoc();
      
        if($row["password"] == hash('sha256', $password)){
          $_SESSION["employeeID"] = $employeeId;
          $_SESSION["loggedIn"] = TRUE;
          $_SESSION["fullName"] = $row["firstname"] . " " . $row["surname"];

          header("Location: choosereview.php");

        } else {
          // password incorrect.
          $error = "Employee Id or Password incorrect, please try again.";
        }
     }  
    }
    else{
          $error = "The User ID and/or passwords fields cannot be empty.";
    }
  }


  if(isset($_GET["Error"])){
    $error = $_GET["Error"];
  }

?>

<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>Login - Wayne Enterprises</title>
  <meta name="description" content="The final project">

  <link rel="stylesheet" href="stylesheets/loginStyle.css">
</head>
<body>
 
    <div class="mainDiv">
      <div class="leftDiv"></div>
      <div class="rightDiv">

        <div id="topPara">
                The performance planning and review process is intended to assist supervisors to review
                  the performance of staff during a given period (at least annually) and develop agreed
                  performance plans based on workload agreements and the strategic direction of <i>Wayne
                  Enterprises&trade;</i>.
                  <br/><br/>
                  The Performance Planning and Review system covers both results (what was accomplished), and
                  behaviours (how those results were achieved). The most important aspect is what will be
                  accomplished in the future and how this will be achieved within a defined period. The process
                  is continually working towards creating improved performance and behaviours that align and
                  contribute to the mission and values of <i>Wayne Enterprises&trade;</i>.
          </div>


          <div id="rightContainer">
              <div id="inputContainer">
                <form action="login.php" method="post">
                  <input class="mainInput" type="text" placeholder="Employee ID" name="employeeID"/><br/>
                  <input class="mainInput" type="password" placeholder="Password" name="password"/><br/>
                  <input class="mainSubmit" type="submit" value="LOGIN" name="submit" /><br/>
                  <span id="error" class="errorDisplay"><?php echo $error ?></span>
              </form>
            </div>
        </div>
      </div>
    </div>


</body>
</html>
