<?php
/*

CONNECT-DB.PHP

Allows PHP to connect to your database

*/


// Database Variables (edit with your own server information)
$server = '127.0.0.1';
$user = 'dma';
$pass = 'li2dma';
$db = 'dragon';

// Connect to Database
$connection = mysqli_connect($server, $user, $pass, $db)
or die ("Could not connect to server ... \n" . mysqli_connect_errno());
?>
