<?php
session_start();
// Include Dbconnect.php or any necessary files here
include 'Dbconnect.php';

// Check if the user is logged in
if (!isset($_SESSION["username"])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" type="image/ico" href="favicon.ico">
    <title>Alliance</title>
    <link rel="stylesheet" href="header.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha384-KyZXEAg3QhqLMpG8r+V8GA5R6f5cAwUO580m1frLjvuC4CZ4p4XvCwOaPJ7j8qnp" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

 
</head>
<body>
<header class="header">
<div class="brand-name">  
            <h1>Alliance</h1>
            <!-- <p>We will lead your way<p> -->
        </div>
        <!-- <div class="logo">Alliance</div> -->
        <nav class="navigation">
            <ul>
                <li><a href="Home.php">Home</a></li>
                <li><a href="view_bookings.php">Bookings</a></li>
                <li><a href="Leave_feedback.php">Feedback</a></li>
                <li><a href="Home.php#about">About&nbsp;Us</a></li>
                <li><a href="Home.php#contact">Contact</a></li>
            </ul>
        </nav>
        <div class="user-actions">
    <div class="profile-dropdown">
        <button class="profile-button" id="profileButton" data-bs-toggle="dropdown" aria-expanded="false">
        <?php
                if (isset($_SESSION["username"])) {
                    // Fetch customer's first name from tbl_Customer
                    $loggedInUsername = $_SESSION["username"];
                    $getCustomerNameQuery = "SELECT C_fname FROM tbl_Customer WHERE Username = '$loggedInUsername'";
                    $nameResult = $conn->query($getCustomerNameQuery);

                    if ($nameResult->num_rows > 0) {
                        $nameRow = $nameResult->fetch_assoc();
                        $customerFirstName = $nameRow["C_fname"];

                        // Display the customer's first name in a different color
                        echo "<span class='customer-welcome'>Welcome <span class='name-highlight'>$customerFirstName</span></span>";
                    }
                }
        ?>
            <span class="profile-icon fas fa-user"></span>
        </button>
        <ul class="dropdown-menu" aria-labelledby="profileButton">
            <li><a class="dropdown-item" href="profile.php">Profile</a></li>
            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
        </ul>
    </div>
</div>

    </header>
            </body>
            </html>