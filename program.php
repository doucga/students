<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

<head>
    <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Program</title>
<?php
$id = $_GET['studentid'];
 // connect to the database
include('connect-db.php');
// get results from database
$result = mysqli_query($connection,"SELECT first, last FROM students WHERE id='" . $id . "'")
or die(mysqli_error());
$row = mysqli_fetch_array( $result );
$studentname = $row['first'] . " " . $row['last'];
$dayofweek    = date('l');
$currenttime = date('H:i:s');

?>

</head>

<body style="text-align: center; color: white; background-color: black; font-family: Helvetica,Arial,sans-serif; color: rgb(204, 255, 255);" alink="#ccffff" link="#66ffff" vlink="#cccccc">

  <a style="text-align: center;"><h1>Hello <?php echo $studentname; ?></h1></a>

                <?php

                  //Check if there any upcoming classes today
                  //$sql = "SELECT * FROM programs WHERE (weekday='" . $dayofweek . "') LIMIT 1";
                  $sql = "SELECT * FROM programs WHERE (weekday='" . $dayofweek . "')";
                  $result = mysqli_query($connection, $sql) or die(mysqli_error());
                  $row = mysqli_num_rows($result);
                  //If no upcoming classes say so and end.
                  if ($row == 0) {
                      echo "<a style=\"text-align: center;\"><h1>There are no classes scheduled for today.</h1></a>";
                      echo '<span style="float: left;"><a href="index.php"><img src="images/home_button.png"></a></span>';
                      header( "refresh:7;url=index.php" );
                      die();
                  }
                  //If there is an upcoming class lets see if it's time to check in.
                  else {
                  //$sql = "SELECT * FROM programs WHERE ( check_in_closed > '" . $currenttime . "') AND (weekday='" . $dayofweek . "')";
                  
                  $sql = "SELECT * FROM programs WHERE (weekday='" . $dayofweek . "') AND ( '" . $currenttime . "' < check_in_closed ) ORDER BY check_in_time ASC LIMIT 1";
                  $result = mysqli_query($connection, $sql) or die(mysqli_error());
                  $rows = mysqli_num_rows($result);
                  //If its not time to check in say so.
                  if ($rows == 0) {
                    echo "<a style=\"text-align: center;\"><h1>There are no more classes scheduled for today.</h1></a>";
                      echo '<span style="float: left;"><a href="index.php"><img src="images/home_button.png"></a></span>';
                      header( "refresh:7;url=index.php" );
                      die();
                  }

                  $row = mysqli_fetch_array( $result );
                  
                  $next_class_check_until = $row['check_in_closed'];
                  $next_class_check_time = $row['check_in_time'];
                  $next_class_name = $row['ProgramName'];
                  $next_class_id = $row['programID'];

                 
                  
                  //Check if we can check in
                  if ($currenttime > $next_class_check_time and $currenttime < $next_class_check_until)
                  {
                    echo '<a style=\"text-align: center;\">';
                    echo '<h2>You are checking into</h2>';
                    echo '</a>';
                    //echo '<br/>';


                    
                      echo '<p></p>';
                      echo '<a style="text-align: center;"><h1>' . $next_class_name . " class.</h1></a>";

                      echo '<p></p>';
                      echo '<a style="text-align: center;"><a href="check-in.php?studentid=' . $id . '&prg=' . $next_class_id . '"><img style="width: 86px; height: 61px;" alt="Go" src="images/black_glass_r26_c2.png"></a></a>';
                      echo '<p></p>';

                    
                    echo '<a style="text-align: center;"><h2>Touch the button to check in.</h2></a>';
                  }
                  //Too early to check in
                  else{
                    echo "<a style=\"text-align: center;\"><h1>It's not time to check in yet.";
                    echo "<p></p>";
                    echo "The next class is " . $next_class_name . "<p></p>";
                    $next_class_check_time = strtotime($next_class_check_time);
                    $next_class_check_time = date('g:i A', $next_class_check_time);
                    echo "Check in time is " . $next_class_check_time . "<p></p>";
                    echo "Please try again later.</h1></a>";
                    header( "refresh:7;url=index.php" );
                    die();
                  }
                  



                  
                  }
                mysqli_free_result($result);
                ?>
                <br />
                <span style="float: left;">
                  <a href="index.php"><img src="images/home_button.png"></a>
                </span>





</body>

</html>
