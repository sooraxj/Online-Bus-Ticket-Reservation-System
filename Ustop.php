
<html>
<head>
    <title>Edit Stop</title>
    <link rel="stylesheet" href="Style.css">
    <link rel="stylesheet" href="Austop.css">
    <script>
        function enableFormFields() {
            const formFields = document.querySelectorAll("input[type='text'], input[type='number'], input[type='email'], input[type='time'], input[type='tel'], input[type='date'], input[type='password'], select");
            formFields.forEach(field => field.removeAttribute("readonly"));
            const routeSelect = document.getElementById("RouteSelect");
            routeSelect.removeAttribute("disabled");
            const statusSelect = document.getElementById("Stop_status");
            statusSelect.removeAttribute("disabled");
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

                    if (isset($_POST['Update'])) {
                        $id = sanitizeInput($_POST['Stop_id']);
                        $Routeid = sanitizeInput($_POST["RouteSelect"]);
                        $Stopno = sanitizeInput($_POST["Stop_no"]);
                        $Stopname = sanitizeInput($_POST["Stop_name"]);
                        $Time = sanitizeInput($_POST["Arrival_time"]);
                        $Distance = sanitizeInput($_POST["Distance"]);
                        $status = $_POST["Stop_status"];
                        if (empty($Stopno)) {
                            header("Location: ../Ustop.php?Stop_id=$id&error=Stop_no is Required");
                            exit;
                        } else {
                            $checkStopQuery = "SELECT * FROM tbl_Stop WHERE Route_id = '$Routeid' AND Stop_no = '$Stopno' AND Stop_id <> $id";
                            $resultStopno = $conn->query($checkStopQuery);

                            // Check for duplicate arrival times
                            $checkTimeQuery = "SELECT * FROM tbl_Stop WHERE Route_id = '$Routeid' AND Arrival_time = '$Time' AND Stop_id <> $id";
                            $resultTime = $conn->query($checkTimeQuery);

                            if ($resultStopno->num_rows > 0) {
                                echo "<script>alert('Stop Number Already Exists for this Route. Please Use a Different Stop Number.');</script>";
                            } elseif ($resultTime->num_rows > 0) {
                                echo "<script>alert('Time Already Exists. No same time for the stops.');</script>";
                            } else {
                                $sql = "UPDATE tbl_Stop SET Route_id='$Routeid', Stop_no='$Stopno', Stop_name='$Stopname',
                                        Arrival_time='$Time', Distance='$Distance', Stop_status='$status' WHERE Stop_id=$id";

                                if (mysqli_query($conn, $sql)) {
                                    echo "<script> alert(\"Stop updated successfully\");</script>";
                                    echo "<script>window.location.href='Stop.php';</script>";
                                    exit;
                                } else {
                                    echo "Error updating record: " . mysqli_error($conn);
                                }
                            }
                        }
                    } elseif (isset($_GET['Stop_id'])) {
                        $id = sanitizeInput($_GET['Stop_id']);
                        $sql = "SELECT * FROM tbl_Stop WHERE Stop_id=$id";
                        $result = mysqli_query($conn, $sql);

                        if (mysqli_num_rows($result) > 0) {
                            $row = mysqli_fetch_assoc($result);
                        } else {
                            echo "<script> alert(\"No Stop found with the specified ID\");</script>";
                            echo "<script>window.location.href='Ustop.php';</script>";
                            exit;
                        }
                    }
                    ?>
                    <form action="Ustop.php" method="post">
                    <h1>Edit Stop</h1>
                        <div class="form-row">
                            <div class="form-group">
                                <input type="hidden" id="Stop_id" name="Stop_id" value="<?php echo $row['Stop_id'] ?? ''; ?>" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="RouteSelect">Select Route</label>
                                <select id="RouteSelect" name="RouteSelect" disabled required>
                                    <option value="">Select a Route</option>
                                    <?php
                                    $routeQuery = "SELECT Route_id, Route_no, Route_name FROM tbl_Route";
                                    $routeResult = $conn->query($routeQuery);

                                    while ($routeRow = $routeResult->fetch_assoc()) {
                                        $selected = ($routeRow["Route_id"] == ($row['Route_id'] ?? '')) ? 'selected' : '';
                                        echo "<option value=\"" . $routeRow["Route_id"] . "\" $selected>" . $routeRow["Route_no"] . " - " . $routeRow["Route_name"] . "</option>";
                                    }

                                    $conn->close();
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="Stop_no">Stop Number</label>
                                <input type="number" id="Stop_no" name="Stop_no" min="1" max="99" value="<?php echo $row['Stop_no'] ?? ''; ?>" readonly required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="Stop_name">Stop Name</label>
                                <input type="text" id="Stop_name" name="Stop_name" value="<?php echo $row['Stop_name'] ?? ''; ?>" readonly required>
                            </div>
                            <div class="form-group">
                                <label for="Arrival_time">Time</label>
                                <input type="time" id="Arrival_time" name="Arrival_time" value="<?php echo $row['Arrival_time'] ?? ''; ?>" readonly required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="Distance">Distance</label>
                                <input type="number" id="Distance" name="Distance" min="0" max="9999" value="<?php echo $row['Distance'] ?? ''; ?>" readonly required>
                            </div>
                            <div class="form-group">
                                <label for="Stop_status">Status</label>
                                <select id="Stop_status" name="Stop_status" disabled required>
                                    <option>Select Status</option>
                                    <option value="1" <?php if (isset($row['Stop_status']) && trim($row['Stop_status']) === '1') echo 'selected'; ?>>Active</option>
                                    <option value="0" <?php if (isset($row['Stop_status']) && trim($row['Stop_status']) === '0') echo 'selected'; ?>>Inactive</option>
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="form-group">
                            <input type="button" value="Edit" onclick="enableFormFields()">
                            <input type="submit" name="Update" value="Update" style="display: none;">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
