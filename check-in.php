<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
<title>Student Records</title>
<?php
$studentid = $_GET['studentid'];
$programID = $_GET['prg'];
$checkindate = date("Y-m-d");
$checkintime = time();
// connect to the database
include('connect-db.php');
// get results from database
$sql = "SELECT lastRGJJlesson FROM settings LIMIT 1";
$result = mysqli_query($connection, $sql)
or die(mysqli_error());
$row = mysqli_fetch_array( $result );
$rgjjlesson = $row['lastRGJJlesson'];

//get program from database
$sql = "SELECT * FROM programs WHERE programID='" . $programID ."'";
$result = mysqli_query($connection, $sql)
or die(mysqli_error());
$row = mysqli_fetch_array( $result );
$programName = $row['ProgramName'];
$rgjj360 = $row['rgjj360'];


if ($rgjj360 == 'Yes') {
  $sql = "SELECT lastRGJJlesson FROM settings LIMIT 1";
  $result = mysqli_query($connection, $sql)
  or die(mysqli_error());
  $row = mysqli_fetch_array( $result );
  $rgjjlesson = $row['lastRGJJlesson'];

}
else {
  $rgjjlesson = "na";

}

$sql = "SELECT * FROM students WHERE id='" . $studentid ."'";
$result = mysqli_query($connection, $sql)
or die(mysqli_error());
$row = mysqli_fetch_array( $result );
$studentname = $row['first'];

$PaidtoDate = $row['paidtodate'];

$subscription = $row['subscription'];
$punches = $row['punches'];

if ($subscription == "") {
  header( 'location: pmtrequired.php' );
}

if ($subscription == "Monthly") {
   	if ($PaidtoDate < date('Y-m-d')) {
 	  header( 'location: pmtrequired.php' );
    exit(0);
	}
  //check if alreadfy checked in.
  $sql = "SELECT * FROM attendance WHERE studentID='" . $studentid . "' AND programID='" . $programID . "' AND classDate='" . $checkindate . "'";
  $result = mysqli_query($connection, $sql)
  or die(mysqli_error());
  $rows = mysqli_num_rows($result);
  if ($rows != 0) {
    header( 'location: alreadysignedin.php' );
    exit(0);
  }
}

if ($subscription == "PunchCard") {
   if ( $punches == 0 ) {
   	  header( 'location: pmtrequiredpunch.php' );
      exit(0);
   }
   //check if alreadfy checked in.
   $sql = "SELECT * FROM attendance WHERE studentID='" . $studentid . "' AND programID='" . $programID . "' AND classDate='" . $checkindate . "'";
   $result = mysqli_query($connection, $sql)
   or die(mysqli_error());
   $rows = mysqli_num_rows($result);
   if ($rows != 0) {
     header( 'location: alreadysignedin.php' );
     exit(0);
   }
   if ( $punches > 0 ) {
   	$punches = $punches - 1;
	 $sql = "UPDATE students SET punches='$punches' WHERE id='$studentid'";
	 $result = mysqli_query($connection, $sql)
	 or die(mysqli_error());
	}
}



$sql = "INSERT attendance SET studentID='$studentid', classDate='$checkindate', classTime='$checkintime', programID='$programID', lessonNumber='$rgjjlesson'";
$result = mysqli_query($connection, $sql)
or die(mysqli_error());

?>
</head>

<body style="text-align: center; color: white; background-color: black; font-family: Helvetica,Arial,sans-serif; color: rgb(204, 255, 255);" alink="#ccffff" link="#66ffff" vlink="#cccccc">

	<?php
	echo "<p></p>";
	echo "<h1>Thank you $studentname!</h1>";
	if ($subscription == "PunchCard") {
	   echo "<h1>You have $punches punches left on your virtual Punch Card.</h1>";
	}
	echo "<h1>You're signed in for today's<p> $programName class. <p> Enjoy!</h1>";
 header( "refresh:7;url=index.php" );
	?>

  <span style="float: left;">
    <a href="index.php"><img src="images/home_button.png"></a>
  </span>

</body>
</html>
