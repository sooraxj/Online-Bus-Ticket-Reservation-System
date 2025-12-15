<html>
<head>
    <title>Admin-Panel</title>
    <link rel="stylesheet" href="Style.css">
    <link rel="stylesheet" href="Aubus.css">
<script>
    function enableFormFields() {
    const formFields = document.querySelectorAll("input[type='text'], input[type='number'], input[type='time'], input[type='email'], input[type='tel'], input[type='date'], input[type='password'], select");
    formFields.forEach(field => field.removeAttribute("readonly"));
    // const statusSelect = document.getElementById("Bus_status");
    // statusSelect.removeAttribute("disabled");
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
                        $id = sanitizeInput($_POST['Bus_id']);
                        $Busno = sanitizeInput($_POST['Bus_Reg_no']);
                        $Buscomp = sanitizeInput($_POST['Bus_Comp']);
                        $SeatingCapacity = sanitizeInput($_POST['Seating_capacity']);
                        $Year = sanitizeInput($_POST['Yearofmanufacture']);
                        if (empty($Busno)) {
                            header("Location: ../Ubus.php?Bus_id=$id&error=Bus_Reg_no is Required");
                            exit;
                        } else {
                            // Check if the Bus Registration Number already exists in the database
                            $checkBusQuery = "SELECT * FROM tbl_Bus WHERE Bus_Reg_no = '$Busno' AND Bus_id != $id";
                            $resultBus = mysqli_query($conn, $checkBusQuery);

                            if (mysqli_num_rows($resultBus) > 0) {
                                echo "<script>alert('Bus Registration Number already exists. Please use a different Bus Registration Number.');</script>";
                            } else {
                                $sql = "UPDATE tbl_Bus SET Bus_Reg_no='$Busno', Bus_Comp='$Buscomp', Seating_capacity='$SeatingCapacity',
                                        Yearofmanufacture='$Year' WHERE Bus_id=$id";

                                if (mysqli_query($conn, $sql)) {
                                    echo "<script> alert(\"Bus updated successfully\");</script>";
                                    echo "<script>window.location.href='bus.php';</script>";
                                    exit;
                                } else {
                                    echo "Error updating record: " . mysqli_error($conn);
                                }
                            }
                        }
                    } elseif (isset($_GET['Bus_id'])) {
                        $id = sanitizeInput($_GET['Bus_id']);
                        $sql = "SELECT * FROM tbl_Bus WHERE Bus_id=$id";
                        $result = mysqli_query($conn, $sql);

                        if (mysqli_num_rows($result) > 0) {
                            $row = mysqli_fetch_assoc($result);
                        } else {
                            echo "<script> alert(\"No bus found with the specified ID\");</script>";
                            echo "<script>window.location.href='Ubus.php';</script>";
                            exit;
                        }
                    }
                    ?>
                    <form action="Ubus.php" method="post"> 
                    <h1>Edit Bus</h1>
                        <div class="form-row">
                            <div class="form-group">
                                <input type="hidden" id="Bus_id" name="Bus_id" value="<?php echo $row['Bus_id'] ?? ''; ?>" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="Bus_Reg_no">Bus Registration Number</label>
                                <input type="text" id="Bus_Reg_no" name="Bus_Reg_no" maxlength="13" value="<?php echo $row['Bus_Reg_no'] ?? ''; ?>" readonly required>
                            </div>
                            <div class="form-group">
                                <label for="Bus_Comp">Bus Company</label>
                                <input type="text" id="Bus_Comp" name="Bus_Comp" value="<?php echo $row['Bus_Comp'] ?? ''; ?>" readonly required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="Seating_capacity">Seating Capacity</label>
                                <input type="number" id="Seating_capacity" name="Seating_capacity" min="1" max="50" value="<?php echo $row['Seating_capacity'] ?? ''; ?>" readonly required>
                            </div>
                            <div class="form-group">
                                <label for="Yearofmanufacture">Year Of Manufacture</label>
                                <input type="date" id="Yearofmanufacture" name="Yearofmanufacture" max="<?= date('Y-m-d'); ?>" value="<?php echo isset($row['Yearofmanufacture']) ? date('Y-m-d', strtotime($row['Yearofmanufacture'])) : ''; ?>" readonly required>

                            </div>
                        </div>
                        <br>
                          <div class="form-group">
                        <input type="button" value="Edit" onclick="enableFormFields()">
                      </div>
                      <div class="form-group">
                        <input type="submit" name="Update" value="Update" style="display: none;">
                      </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
