<?php
include 'Dbconnect.php';
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Bus_id = sanitizeInput($_POST["BusSelect"]);
    $Driver_id = sanitizeInput($_POST["DriverSelect"]);
    $Allocation_date = sanitizeInput($_POST["Allocation_date"]);
    $Start_time = sanitizeInput($_POST["Start_time"]);
    $End_time = sanitizeInput($_POST["End_time"]);
    $Db_status = $_POST["Db_status"];

    // Check if the selected bus is already allocated to another driver
    $checkBusQuery = "SELECT * FROM tbl_Driver_Bus WHERE Bus_id = '$Bus_id' AND Db_status = '1' AND Driver_id <> '$Driver_id'";
    $resultBus = $conn->query($checkBusQuery);

    // Check if the selected driver is already allocated to another bus
    $checkDriverQuery = "SELECT * FROM tbl_Driver_Bus WHERE Driver_id = '$Driver_id' AND Db_status = '1' AND Bus_id <> '$Bus_id'";
    $resultDriver = $conn->query($checkDriverQuery);

    // Check if the combination of Bus_id and Driver_id already exists
    $checkDuplicateQuery = "SELECT * FROM tbl_Driver_Bus WHERE Bus_id = '$Bus_id' AND Driver_id = '$Driver_id' AND Db_status = '1'";
    $resultDuplicate = $conn->query($checkDuplicateQuery);

    if ($resultBus->num_rows > 0) {
        echo "<script>alert('This bus is already allocated to another driver.');</script>";
    } elseif ($resultDriver->num_rows > 0) {
        echo "<script>alert('This driver is already allocated to another bus.');</script>";
    } elseif ($resultDuplicate->num_rows > 0) {
        echo "<script>alert('This driver is already allocated to the selected bus.');</script>";
    } else {
        $insertQuery = "INSERT INTO tbl_Driver_Bus (Bus_id, Driver_id, Allocation_date, Start_time, End_time, Db_status)
                        VALUES ('$Bus_id', '$Driver_id', '$Allocation_date', '$Start_time', '$End_time', '$Db_status')";
        
        if ($conn->query($insertQuery) === TRUE) {
            echo "<script>alert('Driver Assigned Successfully');</script>";
            echo "<script>window.location.href='Driverbus.php';</script>";
        } else {
            echo "<script>alert('Error Adding Driver-Bus Allocation');</script>";
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

$conn->close();
?>


<html>
<head>
    <title>Admin-Panel</title>
    <link rel="stylesheet" href="Style.css">
    <link rel="stylesheet" href="Audriverbus.css">
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
                <form action="AdriverBus.php" method="post">
                <h1>Driver Assign</h1>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="BusSelect">Select Bus</label>
                            <select id="BusSelect" name="BusSelect" required>
                                <option value="">Select a Bus</option>
                                <?php
                                include 'Dbconnect.php';
                                $busQuery = "SELECT Bus_id, Bus_Reg_no FROM tbl_Bus";
                                $busResult = $conn->query($busQuery);
                                while ($row = $busResult->fetch_assoc()) {
                                    echo "<option value=\"" . $row["Bus_id"] . "\">" . $row["Bus_id"] . " - " . $row["Bus_Reg_no"] . "</option>";
                                }
                                $conn->close();
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="DriverSelect">Select Driver</label>
                            <select id="DriverSelect" name="DriverSelect" required>
                                <option value="">Select a Driver</option>
                                <?php
                                include 'Dbconnect.php';
                                $driverQuery = "SELECT Driver_id, D_fname FROM tbl_Driver";
                                $driverResult = $conn->query($driverQuery);
                                while ($row = $driverResult->fetch_assoc()) {
                                    echo "<option value=\"" . $row["Driver_id"] . "\">" . $row["Driver_id"] . " - " . $row["D_fname"] . "</option>";
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
                            <label for="Db_status">Status</label>
                            <select id="Db_status" name="Db_status" required>
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
        </div>
    </div>
</body>
</html>
