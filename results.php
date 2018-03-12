<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
<title>Student Records</title>
</head>
<body style="color: white; background-color: black; font-family: Helvetica,Arial,sans-serif; color: rgb(204, 255, 255); text-align: center;" alink="#ccffff" link="#66ffff" vlink="#cccccc">


<table align="center" style="margin: 0px auto;">
<tr>
  <h1>PLEASE SELECT YOUR NAME.</h1>
</tr>
  <?php
    $firstinitial = $_GET['firstinitial'];
    // connect to the database
    include('connect-db.php');
    // get results from database
    $sql = "SELECT * FROM students WHERE first LIKE '" . $firstinitial . "%'";
    $result = mysqli_query($connection, $sql);

    while($row = mysqli_fetch_array( $result, MYSQLI_ASSOC )) {
      echo '<tr>';
      echo "<td>
              <h1>" . $row['first'] . " " . $row['last'] . "&nbsp;&nbsp;</h1>
            </td>
            <td>
              <a href=program.php?studentid=" . $row['id'] . "><img src=images/black_glass_r26_c2.png></a>
            </td>";
      echo '</tr>';
    }

mysqli_free_result($result);
?>

</table>
<br />
<span style="float: left;">
  <a href="index.php"><img src="images/home_button.png"></a>
</span>

<span style="float: right;">
  <a href="search.php"><img src="images/back_button.png"></a>
</span>

</body>

</html>
