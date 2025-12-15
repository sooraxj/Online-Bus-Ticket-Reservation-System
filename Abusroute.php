<?php
include 'Dbconnect.php';
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $DriverBus_id = sanitizeInput($_POST["DriverBusSelect"]);
    $Route_id = sanitizeInput($_POST["RouteSelect"]);
    $Allocation_date = sanitizeInput($_POST["Allocation_date"]);
    $Start_time = sanitizeInput($_POST["Start_time"]);
    $End_time = sanitizeInput($_POST["End_time"]);
    $Br_status = $_POST["Br_status"];

    // Check if the selected driver-bus is already allocated to another route on the same date
    $checkDriverBusQuery = "SELECT * FROM tbl_Bus_route WHERE Driver_bus_id = '$DriverBus_id' AND Br_status = '1' AND Allocation_date = '$Allocation_date' AND Route_id <> '$Route_id'";
    $resultDriverBus = $conn->query($checkDriverBusQuery);

    // Check if the driver is already allocated to a different route on the same date
    $checkExistingAllocation = "SELECT * FROM tbl_Bus_route WHERE Driver_bus_id = '$DriverBus_id' AND Br_status = '1' AND Allocation_date = '$Allocation_date' AND Route_id <> '$Route_id'";
    $resultExistingAllocation = $conn->query($checkExistingAllocation);

    if ($resultDriverBus->num_rows > 0) {
        echo "<script>alert('This driver-bus is already allocated to another route on the same date.');</script>";
    } elseif ($resultExistingAllocation->num_rows > 0) {
        echo "<script>alert('Allocation Already Exists for this driver-bus on the same date. It cannot be re-allocated.');</script>";
    } else {
        // Insert new bus-route allocation if no conflicts found
        $insertQuery = "INSERT INTO tbl_Bus_route (Driver_bus_id, Route_id, Allocation_date, Start_time, End_time,Br_status)
                        VALUES ('$DriverBus_id', '$Route_id', '$Allocation_date', '$Start_time', '$End_time', '$Br_status')";

        if ($conn->query($insertQuery) === TRUE) {
            echo "<script>alert('Bus Scheduled Successfully');</script>";

            // Calculate and insert allocations for the next 2 months
            $nextAllocationDate = date('Y-m-d', strtotime($Allocation_date . ' +4 days'));
            $endOfAutomationPeriod = date('Y-m-d', strtotime('+2 months'));

            while ($nextAllocationDate <= $endOfAutomationPeriod) {
                $insertQuery = "INSERT INTO tbl_Bus_route (Driver_bus_id, Route_id, Allocation_date, Start_time, End_time, Br_status)
                                VALUES ('$DriverBus_id', '$Route_id', '$nextAllocationDate', '$Start_time', '$End_time', '$Br_status')";

                $conn->query($insertQuery);

                // Move to the next allocation date
                $nextAllocationDate = date('Y-m-d', strtotime($nextAllocationDate . ' +4 days'));
            }

            echo "<script>window.location.href='Busroute.php';</script>";
        } else {
            echo "<script>alert('Error Adding Bus-Route Allocation');</script>";
            echo "Error: " . $insertQuery . "<br>" . $conn->error;
        }
    }
}

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


?>




<html>
<head>
<link rel="stylesheet" href="Style.css">
<link rel="stylesheet" href="Aubusroute.css">
<title>Admin-Panel</title>
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
        <form action="Abusroute.php" method="post">
        <h1>Schedule Bus</h1>
            <div class="form-row">
                <div class="form-group">
                    <label for="DriverBusSelect">Select Driver-Bus</label>
                    <select id="DriverBusSelect" name="DriverBusSelect" required>
                        <option value="">Select a Driver-Bus</option>
                        <?php
                        include 'Dbconnect.php';
                        $driverBusQuery = "SELECT Driver_bus_id, Driver_id, Bus_id FROM tbl_Driver_Bus";
                        $driverBusResult = $conn->query($driverBusQuery);
                        while ($row = $driverBusResult->fetch_assoc()) {
                            // Fetch driver first name
                            $driverId = $row["Driver_id"];
                            $driverQuery = "SELECT D_fname FROM tbl_Driver WHERE Driver_id = '$driverId'";
                            $driverResult = $conn->query($driverQuery);
                            $driverRow = $driverResult->fetch_assoc();

                            // Fetch bus registration number
                            $busId = $row["Bus_id"];
                            $busQuery = "SELECT Bus_Reg_no FROM tbl_Bus WHERE Bus_id = '$busId'";
                            $busResult = $conn->query($busQuery);
                            $busRow = $busResult->fetch_assoc();

                            echo "<option value=\"" . $row["Driver_bus_id"] . "\">" . $row["Driver_bus_id"] . " - Bus: " . $busRow["Bus_Reg_no"] . ", Driver: " . $driverRow["D_fname"] . "</option>";
                        }
                        $conn->close();
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="RouteSelect">Select Route</label>
                    <select id="RouteSelect" name="RouteSelect" required>
                        <option value="">Select a Route</option>
                        <?php
                        include 'Dbconnect.php';
                        $routeQuery = "SELECT Route_id, Route_name FROM tbl_route";
                        $routeResult = $conn->query($routeQuery);
                        while ($row = $routeResult->fetch_assoc()) {
                            echo "<option value=\"" . $row["Route_id"] . "\">" . $row["Route_id"] . " - " . $row["Route_name"] . "</option>";
                        }
                        $conn->close();
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="Allocation_date">Allocation Date</label>
                    <input type="date" id="Allocation_date" name="Allocation_date" min="<?= date('Y-m-d'); ?>" required>
                </div>
                <div class="form-group">
                    <label for="Start_time">Start Time</label>
                    <input type="time" id="Start_time" name="Start_time" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="End_time">End Time</label>
                    <input type="time" id="End_time" name="End_time" required>
                </div>
                <div class="form-group">
                    <label for="Br_status">Status</label>
                    <select id="Br_status" name="Br_status" required>
                        <option>Select Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>             
 
            </div>
           
            <br>
            <div class="form-group">
                <input type="submit" value="Submit">
            </div>
        </form>
    </div>
</body>
</html>
