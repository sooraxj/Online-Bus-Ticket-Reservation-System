<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$db = 'obtrs';

// Create a connection to the database
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $db);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
<link rel="icon" type="image/ico" href="favicon.ico">

<style>
    
body {
    user-select: none;
}

    </style>