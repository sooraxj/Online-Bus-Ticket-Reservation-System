<html>
<head>
    <title>Admin-Panel</title>
    <link rel="stylesheet" href="Style.css">
    <link rel="stylesheet" href="Audriverbus.css">
    <script>
    function enableFormFields() {
        const formFields = document.querySelectorAll("input[type='text'], input[type='number'], input[type='email'], input[type='tel'], input[type='date'], input[type='time'], input[type='password'], select");
        formFields.forEach(field => field.removeAttribute("readonly"));
        const statusSelect = document.getElementById("Db_status");
        statusSelect.removeAttribute("disabled");
        const busSelect = document.getElementById("BusSelect");
        busSelect.removeAttribute("disabled");
        const driverSelect = document.getElementById("DriverSelect");
        driverSelect.removeAttribute("disabled");
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
                </div>
    <div class="content">
        <div class="main">
            <div class="form">
                <?php
include 'Dbconnect.php';
$row = null;
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (isset($_POST['Update'])) {
    $id = sanitizeInput($_POST['Driver_bus_id']);
    $Bus_id = sanitizeInput($_POST["BusSelect"]);
    $Driver_id = sanitizeInput($_POST["DriverSelect"]);
    $Allocation_date = sanitizeInput($_POST["Allocation_date"]);
    $Start_time = sanitizeInput($_POST["Start_time"]);
    $End_time = sanitizeInput($_POST["End_time"]);
    $Db_status = $_POST["Db_status"];

    if (empty($Bus_id)) {
        header("Location: ../Udriverbus.php?Driver_bus_id=$id&error=Bus_id is Required");
        exit;
    } else {
        $checkBusQuery = "SELECT * FROM tbl_Driver_Bus WHERE Bus_id = '$Bus_id' AND Db_status = '1' AND Driver_id <> '$Driver_id' AND Driver_bus_id <> '$id'";
        $resultBus = $conn->query($checkBusQuery);
        $checkDriverQuery = "SELECT * FROM tbl_Driver_Bus WHERE Driver_id = '$Driver_id' AND Db_status = '1' AND Bus_id <> '$Bus_id' AND Driver_bus_id <> '$id'";
        $resultDriver = $conn->query($checkDriverQuery);

        if (mysqli_num_rows($resultBus) > 0) {
            echo "<script>alert('This bus is already allocated to another driver.');</script>";
        } elseif (mysqli_num_rows($resultDriver) > 0) {
            echo "<script>alert('This driver is already allocated to another bus.');</script>";
        } else {
            $sql = "UPDATE tbl_Driver_Bus SET Bus_id = '$Bus_id', Driver_id = '$Driver_id', Allocation_date = '$Allocation_date', 
                    Start_time = '$Start_time', End_time = '$End_time', Db_status = '$Db_status' WHERE Driver_bus_id = '$id'";

            if (mysqli_query($conn, $sql)) {
                echo "<script> alert(\"Driver Assignment updated successfully\");</script>";
                echo "<script>window.location.href='Driverbus.php';</script>";
                exit;
            } else {
                echo "Error updating record: " . mysqli_error($conn);
            }
        }
    }
} elseif (isset($_GET['Driver_bus_id'])) {
    $id = sanitizeInput($_GET['Driver_bus_id']);
    $sql = "SELECT * FROM tbl_Driver_Bus WHERE Driver_bus_id=$id";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    } else {
        echo "<script> alert(\"No Driver bus found with the specified ID\");</script>";
        echo "<script>window.location.href='Udriverbus.php';</script>";
        exit;
    }
}
?>
              
                <form action="Udriverbus.php" method="post">
                <h1>Edit Driver Assignment</h1>
                <div class="form-group">
                <input type="hidden" id="Driver_bus_id" name="Driver_bus_id" value="<?php echo $row['Driver_bus_id'] ?? ''; ?>" required>
                    </div>
                      <div class="form-row">
                        <div class="form-group">
                            <label for="BusSelect">Select Bus</label>
                            <select id="BusSelect" name="BusSelect" disabled required>
                                <option value="">Select a Bus</option>
                                <?php
                                $busQuery = "SELECT Bus_id, Bus_Reg_no FROM tbl_Bus";
                                $busResult = $conn->query($busQuery);
                                while ($busRow = $busResult->fetch_assoc()) {
                                    $selected = ($busRow["Bus_id"] == ($row['Bus_id'] ?? '')) ? 'selected' : '';
                                    echo "<option value=\"" . $busRow["Bus_id"] . "\" $selected>" . $busRow["Bus_id"] . " - " . $busRow["Bus_Reg_no"] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="DriverSelect">Select Driver</label>
                            <select id="DriverSelect" name="DriverSelect" disabled required>
                                <option value="">Select a Driver</option>
                                <?php
                                $driverQuery = "SELECT Driver_id, D_fname FROM tbl_Driver";
                                $driverResult = $conn->query($driverQuery);
                                while ($driverRow = $driverResult->fetch_assoc()) {
                                    $selected = ($driverRow["Driver_id"] == ($row['Driver_id'] ?? '')) ? 'selected' : '';
                                    echo "<option value=\"" . $driverRow["Driver_id"] . "\" $selected>" . $driverRow["Driver_id"] . " - " . $driverRow["D_fname"] . "</option>";
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
                            <label for="Db_status">Status</label>
                            <select id="Db_status" name="Db_status" disabled required>
                                <option>Select Status</option>
                                <option value="1" <?php if (isset($row['Db_status']) && trim($row['Db_status']) === '1') echo 'selected'; ?>>Active</option>
                                <option value="0" <?php if (isset($row['Db_status']) && trim($row['Db_status']) === '0') echo 'selected'; ?>>Inactive</option>
                            </select>
                        </div>
                    </div>
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
