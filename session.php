<?php
session_start();
// Include Dbconnect.php or any necessary files here
include 'Dbconnect.php';

// Check if the admin is logged in
if (isset($_SESSION["admin_username"])) {
    // Admin is logged in, handle admin session
    // ...
} elseif (isset($_SESSION["user_username"])) {
    // User is logged in, handle user session
    // ...
} else {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}


?>