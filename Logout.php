<?php
session_start();
include 'Dbconnect.php';

// Check if the user is logged in (i.e., session exists)
if (isset($_SESSION["username"])) {
    $Username = $_SESSION["username"];

    // Update the login_status field to 'inactive'
    $updateStatusQuery = "UPDATE tbl_Login SET login_status = 'inactive' WHERE Username = '$Username'";
    $conn->query($updateStatusQuery);

    // Destroy the session and redirect to the login page
    session_unset();
    session_destroy();
}

// Redirect to the login page after logout
header("Location: index.php");
exit();
?>
