<html>
    <head>
        <title>Admin-Panel</title>
        <link rel="stylesheet" href="Style.css">
        <link rel="stylesheet" href="Auroute.css">
    <script>
    function enableFormFields() {
    const formFields = document.querySelectorAll("input[type='text'], input[type='number'], input[type='email'], input[type='tel'], input[type='date'], input[type='password'], select");
    formFields.forEach(field => field.removeAttribute("readonly"));
    // const statusSelect = document.getElementById("Route_status");
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
                    $id = sanitizeInput($_POST['Route_id']);
                    $Routeno = sanitizeInput($_POST["Route_no"]);
                    $Routename = sanitizeInput($_POST["Route_name"]);
                    $Start = sanitizeInput($_POST["Starting_point"]);
                    $Dest = sanitizeInput($_POST["Destination"]);
                    $Distance = sanitizeInput($_POST["Distance"]);

                    if (empty($Routeno)) {
                        header("Location: ../Uroute.php?Route_id=$id&error=Route_no is Required");
                        exit;
                    } else {
                        // Check if the Route Number already exists and belongs to a different route
                        $checkRouteQuery = "SELECT * FROM tbl_Route WHERE Route_no = '$Routeno' AND Route_id != $id";
                        $resultRoute = mysqli_query($conn, $checkRouteQuery);

                        if (mysqli_num_rows($resultRoute) > 0) {
                            echo "<script>alert('Route Number already exists for another route. Please use a different Route Number.');</script>";
                        } else {
                            $sql = "UPDATE tbl_Route SET Route_no='$Routeno', Route_name='$Routename', Starting_point='$Start',
                                    Destination='$Dest', Distance='$Distance' WHERE Route_id=$id";

                            if (mysqli_query($conn, $sql)) {
                                echo "<script> alert(\"Route updated successfully\");</script>";
                                echo "<script>window.location.href='Route.php';</script>";
                                exit;
                            } else {
                                echo "Error updating record: " . mysqli_error($conn);
                            }
                        }
                    }
                } elseif (isset($_GET['Route_id'])) {
                    $id = sanitizeInput($_GET['Route_id']);
                    $sql = "SELECT * FROM tbl_Route WHERE Route_id=$id";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                    } else {
                        echo "<script> alert(\"No Route found with the specified ID\");</script>";
                        echo "<script>window.location.href='Uroute.php';</script>";
                        exit;
                    }
                }
                ?>
                            <form  action="Uroute.php" method="post" >
                            <h1>Edit Route</h1>
                            <div class="form-row">
                            <div class="form-group">
                                <input type="hidden" id="Route_id" name="Route_id" value="<?php echo $row['Route_id'] ?? ''; ?>" required>
                            </div>
                             </div>
                              <div class="form-row">
                              <div class="form-group">
                                  <label for="Route_no">Route Number</label>
                                  <input type="number" id="Route_no" name="Route_no" value="<?php echo $row['Route_no'] ?? ''; ?>" readonly required>
                                </div>
                                <div class="form-group">
                                  <label for="Route_name">Route Name</label>
                                  <input type="text" id="Route_name" name="Route_name" value="<?php echo $row['Route_name'] ?? ''; ?>" readonly required>
                                </div>
                              </div>
                              <div class="form-row">
                                <div class="form-group">
                                  <label for="Starting_point">Starting Point</label>
                                  <input type="text" id="Starting_point" name="Starting_point" value="<?php echo $row['Starting_point'] ?? ''; ?>" readonly required>
                                </div>
                                <div class="form-group">
                                  <label for="Destination">Destination</label>
                                  <input type="text" id="Destination" name="Destination" value="<?php echo $row['Destination'] ?? ''; ?>" readonly required>
                                </div>
                              </div>
                              <div class="form-group" id="select">
                                  <label for="Distance">Total Distance</label>
                                  <input type="number" id="Distance" name="Distance" value="<?php echo $row['Distance'] ?? ''; ?>" readonly required>
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
        </div>
    </body>
</html>
