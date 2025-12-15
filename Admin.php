<?php
include 'Dbconnect.php';

session_start();
// Include Dbconnect.php or any necessary files here

// Check if the user is logged in
if (!isset($_SESSION["username"])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}
$uname= $_SESSION["username"];
// Initialize counts
$totalCustomers = 0;
$totalDrivers = 0;
$totalBuses = 0;
$totalRoutes = 0;
$totalBookings = 0;
$totalCancel = 0;
$totalFeedbacks = 0;



// Get total counts
$sqlCustomer = "SELECT COUNT(*) AS totalCustomers FROM tbl_customer";
$sqlDriver = "SELECT COUNT(*) AS totalDrivers FROM tbl_Driver";
$sqlBus = "SELECT COUNT(*) AS totalBuses FROM tbl_Bus";
$sqlRoute = "SELECT COUNT(*) AS totalRoutes FROM tbl_Route";
$sqlBooking = "SELECT COUNT(*) AS totalBookings FROM tbl_Ticket WHERE Ticket_status=1";
$sqlCancel = "SELECT COUNT(*) AS totalCancel FROM tbl_Ticket WHERE Ticket_status=0";
$sqlfeedback = "SELECT COUNT(*) AS totalFeedbacks FROM tbl_Feedback";



$resultCustomer = mysqli_query($conn, $sqlCustomer);
$resultDriver = mysqli_query($conn, $sqlDriver);
$resultBus = mysqli_query($conn, $sqlBus);
$resultRoute = mysqli_query($conn, $sqlRoute);
$resultBooking = mysqli_query($conn, $sqlBooking);
$resultCancel = mysqli_query($conn, $sqlCancel);
$resultFeedback = mysqli_query($conn, $sqlfeedback);



if ($resultCustomer && $resultDriver && $resultBus && $resultRoute && $resultBooking && $resultCancel && $resultFeedback) {
    $rowCustomer = mysqli_fetch_assoc($resultCustomer);
    $totalCustomers = $rowCustomer['totalCustomers'];
    
    $rowDriver = mysqli_fetch_assoc($resultDriver);
    $totalDrivers = $rowDriver['totalDrivers'];
    
    $rowBus = mysqli_fetch_assoc($resultBus);
    $totalBuses = $rowBus['totalBuses'];
    
    $rowRoute = mysqli_fetch_assoc($resultRoute);
    $totalRoutes = $rowRoute['totalRoutes'];

    $rowBooking = mysqli_fetch_assoc($resultBooking);
    $totalBookings = $rowBooking['totalBookings'];

    $rowCancel = mysqli_fetch_assoc($resultCancel);
    $totalCancel = $rowCancel['totalCancel'];

    $rowFeedback = mysqli_fetch_assoc($resultFeedback);
    $totalFeedbacks = $rowFeedback['totalFeedbacks'];
} else {
    // Handle error
    echo "Error: " . mysqli_error($conn);
}

// Get total income
$sqlTotalIncome = "SELECT SUM(Total_fare) AS totalIncome FROM tbl_Booking_master";

$sqlTotalRefund = "SELECT SUM(Refund_amount) AS totalRefund FROM tbl_payment";

$resultTotalIncome = mysqli_query($conn, $sqlTotalIncome);
$resultTotalRefund = mysqli_query($conn, $sqlTotalRefund);

$totalIncome = 0; // Initialize total income
$totalRefund = 0; // Initialize total refund

if ($resultTotalIncome) {
    $rowTotalIncome = mysqli_fetch_assoc($resultTotalIncome);
    $totalIncome = $rowTotalIncome['totalIncome'];
} else {
    // Handle error
    echo "Error: " . mysqli_error($conn);
}

if ($resultTotalRefund) {
    $rowTotalRefund = mysqli_fetch_assoc($resultTotalRefund);
    $totalRefund = $rowTotalRefund['totalRefund'];
} else {
    // Handle error
    echo "Error: " . mysqli_error($conn);
}

// Subtract the total refund from the total income
$totalIncome -= $totalRefund;


?>

<html>
    <head>
        <title>Admin-Panel</title>
        <link rel="stylesheet" href="admin.css">
    </head>
    <body>
        <div class="side-menu">
            <div class="brand-name">
                <div class="logo">
                    <img src="logo2.png">
                </div>     
            <h1>Alliance</h1>
        </div>
       

        <ul class="ui">
          <li><a href="admin.php"><img src="ui.png"> Dashboard </a></li>
          <li><a href="Driver.php"><img src="ui.png">Drivers</a></li>
          <li><a href="Cust.php"><img src="ui.png">Customers</a></li>
          <li><a href="Bus.php"><img src="ui.png">Buses</a></li>
          <li><a href="Route.php"><img src="ui.png">Routes</a></li>
          <li><a href="Stop.php"><img src="ui.png">Stops</a></li>
         <li><a href="Driverbus.php"><img src="ui.png">Driver-Assign</a></li>
          <li><a href="Busroute.php"><img src="ui.png">Bus-Schedule</a></li>
          <li><a href="Farestage.php"><img src="ui.png">Fare Stage</a></li>
          <li><a href="Abooking_view.php"><img src="ui.png">Bookings</a></li>
        </ul>
        </div>

        <div class="container">
            <div class="header">
                <div class="nav">
          <!-- <button class="toggle-button" id="menu">|||</button> -->
                <div class="search">
                    <!-- <input type="text" placeholder="search...">
                    <button type="submit">🔍</button> -->
                </div>
                <div class="user">
                <a href="#" class=""></a>
                <div class="img-case">
                    <img src="user.png" alt="user" id="userImage">
                </div>
                <div class="dropdown-container" id="dropdownContainer">
                <a href="Logout.php" class="btnsmall">Logout</a>
                </div>
            </div>
                </div>
            </div>
            <div class="content">
                        <div class="hero-content">
                          <h1>Welcome, Admin</h1>
                          <p>Manage your bus ticket reservation system with ease</p>
                         <a href="View_feedback.php" class="btn">View Feedbacks</a>
                         <a href="Reports.php" class="btn">View Reports</a>
                         <a href="view_requests.php" class="btn">View Requests</a>

                        </div>
                        <?php //echo "$uname"; ?>
                <div class="dashboard-stats">
                    <div class="stat">
                        <h2>Total Customers</h2>
                        <h3><?php echo $rowCustomer['totalCustomers']; ?></h3>
                    </div>
                    <div class="stat">
                        <h2>Total Drivers</h2>
                        <h3><?php echo $rowDriver['totalDrivers']; ?></h3>
                    </div>
                    <div class="stat">
                        <h2>Total Buses</h2>
                        <h3><?php echo $rowBus['totalBuses']; ?></h3>
                    </div>
                    <div class="stat">
                        <h2>Total Routes</h2>
                        <h3><?php echo $rowRoute['totalRoutes']; ?></h3>
                    </div>
                    <div class="stat">
                        <h2>Total Booking</h2>
                        <h3><?php echo $rowBooking['totalBookings']; ?></h3>
                    </div>
                    <div class="stat">
                        <h2>Cancelled Tickets</h2>
                        <h3><?php echo $rowCancel['totalCancel']; ?></h3>
                    </div>
                    <div class="stat">
                        <h2>Total Feedbacks</h2>
                        <h3><?php echo $rowFeedback['totalFeedbacks']; ?></h3>
                    </div>
                    <div class="stat">
                        <h2>Total Income</h2>
                        <h3><?php echo $totalIncome; ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>