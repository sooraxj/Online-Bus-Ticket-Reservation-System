<!-- UPDATES ALL DATES WHEN WE CHANGE ONE DATE -->
<html>
<head>
    <title>Admin-Panel</title>
    <link rel="stylesheet" href="Style.css">
    <link rel="stylesheet" href="Aubusroute.css">
    <script>
    function enableFormFields() {
        const formFields = document.querySelectorAll("input[type='text'], input[type='number'], input[type='email'], input[type='tel'], input[type='date'], input[type='time'], input[type='password'], select");
        formFields.forEach(field => field.removeAttribute("readonly"));
        const statusSelect = document.getElementById("Br_status");
        statusSelect.removeAttribute("disabled");
        const driverbusSelect = document.getElementById("DriverBusSelect");
        driverbusSelect.removeAttribute("disabled");
        const routeSelect = document.getElementById("RouteSelect");
        routeSelect.removeAttribute("disabled");
        const editButton = document.querySelector("input[value='Edit']");
        const updateButton = document.querySelector("input[name='Update']");
        editButton.style.display = "none";
        updateButton.style.display = "block";
    }
    </script>
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

        </ul></div>
<div class="container">
<div class="header">
    <div class="nav">
                <div class="user">
                    <div class="img-case">
                        <img src="user.png" alt="user">
                    </div>
                </div>
                </div>
                </div>    <div class="content">
        <div class="main">
            <div class="form">
                <?php
include 'Dbconnect.php';
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}
$row = null;

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['Update'])) {
        $id = sanitizeInput($_POST['Bus_route_id']);
        $DriverBus_id = sanitizeInput($_POST["DriverBusSelect"]);
        $Route_id = sanitizeInput($_POST["RouteSelect"]);
        $Allocation_date = sanitizeInput($_POST["Allocation_date"]);
        $Start_time = sanitizeInput($_POST["Start_time"]);
        $Br_status = $_POST["Br_status"];

        if (empty($DriverBus_id)) {
            header("Location: ../Ubusroute.php?Bus_route_id=$id&error=DriverBus_id is Required");
            exit;
        } else {
            // Check if the selected driver-bus is already allocated to another route on the same date
            $checkDriverBusQuery = "SELECT * FROM tbl_Bus_route WHERE Driver_bus_id = '$DriverBus_id' AND Br_status = '1' AND Allocation_date = '$Allocation_date' AND Route_id <> '$Route_id'";
            $resultDriverBus = $conn->query($checkDriverBusQuery);

            // Check if the driver is already allocated to a different route on the same date
            $checkExistingAllocation = "SELECT * FROM tbl_Bus_route WHERE Driver_bus_id = '$DriverBus_id' AND Br_status = '1' AND Allocation_date = '$Allocation_date' AND Route_id <> '$Route_id'";
            $resultExistingAllocation = $conn->query($checkExistingAllocation);

            if (mysqli_num_rows($resultDriverBus) > 0) {
                echo "<script>alert('This driver-bus is already allocated to another route.');</script>";
            } elseif (mysqli_num_rows($resultExistingAllocation) > 0) {
                echo "<script>alert('Allocation Already Exists for this driver-bus on the same date. It cannot be re-allocated.');</script>";
            } else {
                // Calculate the gap between new and previous allocation dates
                $getPreviousAllocationDateQuery = "SELECT Allocation_date FROM tbl_Bus_route WHERE Bus_route_id = '$id'";
                $resultPreviousAllocationDate = $conn->query($getPreviousAllocationDateQuery);
                $previousAllocationDate = mysqli_fetch_assoc($resultPreviousAllocationDate)['Allocation_date'];
                $allocationDateGap = strtotime($Allocation_date) - strtotime($previousAllocationDate);

                // Update existing Bus-Route allocation
                $sql = "UPDATE tbl_Bus_route SET Driver_bus_id = '$DriverBus_id', Route_id = '$Route_id', Allocation_date = '$Allocation_date', 
                        Start_time = '$Start_time', End_time = '$End_time', Br_status = '$Br_status' WHERE Bus_route_id = '$id'";
                
                if (mysqli_query($conn, $sql)) {
                    // Update subsequent allocation dates with the calculated gap
                    $updateSubsequentDates = "UPDATE tbl_Bus_route SET Allocation_date = DATE_ADD(Allocation_date, INTERVAL $allocationDateGap SECOND) 
                                              WHERE Bus_route_id > '$id' AND Br_status = '1'";
                    mysqli_query($conn, $updateSubsequentDates);
                    
                    echo "<script>alert('Bus Schedule Updated Successfully');</script>";
                    echo "<script>window.location.href='Busroute.php';</script>";
                    exit;
                } else {
                    echo "<script>alert('Error Updating Bus-Route Allocation');</script>";
                    echo "Error updating record: " . mysqli_error($conn);
                }
            }
        }
    }
}

if (isset($_GET['Bus_route_id'])) {
    $id = sanitizeInput($_GET['Bus_route_id']);
    $sql = "SELECT * FROM tbl_Bus_route WHERE Bus_route_id=$id";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $selectedDriverBusId = $row['Driver_bus_id'];
        $selectedRouteId = $row['Route_id'];
    } else {
        echo "<script> alert(\"No Bus Route found with the specified ID\");</script>";
        echo "<script>window.location.href='Ubusroute.php';</script>";
        exit;
    }
}
?>



                <form action="Ubusroute.php" method="post">
                <h1>Edit Bus Schedules</h1>
                    <div class="form-group">
                        <input type="hidden" id="Bus_route_id" name="Bus_route_id" value="<?php echo $row['Bus_route_id'] ?? ''; ?>" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="DriverBusSelect">Select Driver-Bus</label>
                            <select id="DriverBusSelect" name="DriverBusSelect" disabled required>
                                <option value="">Select a Driver-Bus</option>
                                <?php
                                $driverBusQuery = "SELECT Driver_bus_id, Driver_id, Bus_id FROM tbl_Driver_Bus";
                                $driverBusResult = $conn->query($driverBusQuery);
                                while ($driverBusRow = $driverBusResult->fetch_assoc()) {
                                    $driverId = $driverBusRow["Driver_id"];
                                    $driverQuery = "SELECT D_fname FROM tbl_Driver WHERE Driver_id = '$driverId'";
                                    $driverResult = $conn->query($driverQuery);
                                    $driverRow = $driverResult->fetch_assoc();

                                    $busId = $driverBusRow["Bus_id"];
                                    $busQuery = "SELECT Bus_Reg_no FROM tbl_Bus WHERE Bus_id = '$busId'";
                                    $busResult = $conn->query($busQuery);
                                    $busRow = $busResult->fetch_assoc();

                                    $selected = ($driverBusRow["Driver_bus_id"] == $selectedDriverBusId) ? 'selected' : '';
                                    echo "<option value=\"" . $driverBusRow["Driver_bus_id"] . "\" $selected>" . $driverBusRow["Driver_bus_id"] . " - Bus: " . $busRow["Bus_Reg_no"] . ", Driver: " . $driverRow["D_fname"] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="RouteSelect">Select Route</label>
                            <select id="RouteSelect" name="RouteSelect" disabled required>
                                <option value="">Select a Route</option>
                                <?php
                                $routeQuery = "SELECT Route_id, Route_name FROM tbl_route";
                                $routeResult = $conn->query($routeQuery);
                                while ($routeRow = $routeResult->fetch_assoc()) {
                                    $selected = ($routeRow["Route_id"] == $selectedRouteId) ? 'selected' : '';
                                    echo "<option value=\"" . $routeRow["Route_id"] . "\" $selected>" . $routeRow["Route_id"] . " - " . $routeRow["Route_name"] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="Allocation_date">Allocation Date</label>
                            <input type="date" id="Allocation_date" name="Allocation_date" min="<?= date('Y-m-d'); ?>" value="<?php echo $row['Allocation_date'] ?? ''; ?>" readonly required>
                        </div>
                        <div class="form-group">
                            <label for="Start_time">Start Time</label>
                            <input type="time" id="Start_time" name="Start_time" value="<?php echo $row['Start_time'] ?? ''; ?>" readonly required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="End_time">End Time</label>
                            <input type="time" id="End_time" name="End_time" value="<?php echo $row['End_time'] ?? ''; ?>" readonly required>
                        </div>
                        <div class="form-group">
                            <label for="Br_status">Status</label>
                            <select id="Br_status" name="Br_status" disabled required>
                                <option>Select Status</option>
                                <option value="1" <?php if (isset($row['Br_status']) && trim($row['Br_status']) === '1') echo 'selected'; ?>>Active</option>
                                <option value="0" <?php if (isset($row['Br_status']) && trim($row['Br_status']) === '0') echo 'selected'; ?>>Inactive</option>
                            </select>
                        </div>
                       
                    </div>
                 <br>
                    <div class="form-group">
                        <input type="button" value="Edit" onclick="enableFormFields()">
                    </div>
                    <div class="form-group">
                        <input type="submit" name="Update" value="Update" style="display: none;">
                    </div>

                    

                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
