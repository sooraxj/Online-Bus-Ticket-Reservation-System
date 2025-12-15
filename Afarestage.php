<?php
include 'Dbconnect.php';
include 'Dbconnect.php';
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Bf = sanitizeInput($_POST["Base_fare"]);
    $Af = sanitizeInput($_POST["Additional_fare"]);
    $status = $_POST["F_status"];
        // Insert new stop if no duplicates found
        $sql = "INSERT INTO tbl_Farestage (Base_fare, Additional_fare, F_status)
                VALUES ('$Bf', '$Af', '$status')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert(\"Fare Stage Added Successfully\");</script>";
            echo "<script>window.location.href='Farestage.php';</script>";
        } else {
            echo "<script>alert(\"Error Adding Fare Stage\")</script>";
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
}

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$conn->close();
?>

<html>
    <head>
        <title>Admin-Panel</title>
        <link rel="stylesheet" href="Style.css">
        <link rel="stylesheet" href="Aufarestage.css">
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
                <div class="user">
                    <div class="img-case">
                        <img src="user.png" alt="user">
                    </div>
                </div>
                </div>
            </div>
            <div class="content">
            
                    <div class="main">
                        <div class="form">
                            <form action="Afarestage.php" method="post">
                            <h1>Add Fare Stage</h1>
                              <div class="form-row">
                                <div class="form-group">
                                  <label for="Base_fare">Base fare</label>
                                  <input type="number" id="Base_fare" name="Base_fare" required>
                                </div>
                                <div class="form-group">
                                  <label for="Additional_fare">Additional Fare/km</label>
                                  <input type="number" id="Additional_fare" name="Additional_fare"  required>
                                </div>
                                </div>
                                <div class="form-group" id="select">
                                <label for="F_status">Status</label>
                                <select id="F_status" name="F_status" required>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                                <br>
                              <div class="form-group">
                                  <input type="submit" value="Submit">
                                </div>
                            </form>
                          </div>
                       
                              </div>
                    
                          </div>
            </div>
        </div>
    </body>
</html>
