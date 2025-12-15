<html>
    <head>
        <title>Admin-Panel</title>
        <link rel="stylesheet" href="Style.css">
        <link rel="stylesheet" href="Aufarestage.css">
      <script>
    function enableFormFields() {
    const formFields = document.querySelectorAll("input[type='text'], input[type='number'], input[type='email'], input[type='tel'], input[type='date'], input[type='password'], select");
    formFields.forEach(field => field.removeAttribute("readonly"));
    const statusSelect = document.getElementById("F_status");
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
                        $id = sanitizeInput($_POST['Farestage_id']);
                        $Bf = sanitizeInput($_POST['Base_fare']);
                        $Af = sanitizeInput($_POST['Additional_fare']);
                        $status = $_POST['F_status'];

                                $sql = "UPDATE tbl_Farestage SET  Base_fare='$Bf', Additional_fare='$Af',
                                       F_status='$status' WHERE Farestage_id=$id";

                                if (mysqli_query($conn, $sql)) {
                                    echo "<script> alert(\"Fare updated successfully\");</script>";
                                    echo "<script>window.location.href='Farestage.php';</script>";
                                    exit;
                                } else {
                                    echo "Error updating record: " . mysqli_error($conn);
                                }
                            }
                    
                               elseif (isset($_GET['Farestage_id'])) {
                        $id = sanitizeInput($_GET['Farestage_id']);
                        $sql = "SELECT * FROM tbl_farestage WHERE Farestage_id=$id";
                        $result = mysqli_query($conn, $sql);

                        if (mysqli_num_rows($result) > 0) {
                            $row = mysqli_fetch_assoc($result);
                        } else {
                            echo "<script> alert(\"No fare found with the specified ID\");</script>";
                            echo "<script>window.location.href='Ufarestage.php';</script>";
                            exit;
                        }
                    }
                    ?>
                            <form action="Ufarestage.php" method="post">
                            <h1>Edit Fare Stage</h1> 
                            <div class="form-row">
                            <div class="form-group">
                                <input type="hidden" id="Farestage_id" name="Farestage_id" value="<?php echo $row['Farestage_id'] ?? ''; ?>" required>
                            </div>
                        </div>
                              <div class="form-row">
                                <div class="form-group">
                                  <label for="Base_fare">Base fare</label>
                                  <input type="number" id="Base_fare" name="Base_fare" value="<?php echo $row['Base_fare'] ?? ''; ?>" readonly required>
                                </div>
                                <div class="form-group">
                                  <label for="Additional_fare">Additional Fare/km</label>
                                  <input type="number" id="Additional_fare" name="Additional_fare" value="<?php echo $row['Additional_fare'] ?? ''; ?>" readonly  required>
                                </div>
                                </div>
                                <div class="form-group" id="select">
                                <label for="F_status">Status</label>
                                    <select id="F_status" name="F_status" disabled required>
                                        <option value="">Select Status</option>
                                        <option value="1" <?php if (isset($row['F_status']) && trim($row['F_status']) === '1') echo 'selected'; ?>>Active</option>
                                        <option value="0" <?php if (isset($row['F_status']) && trim($row['F_status']) === '0') echo 'selected'; ?>>Inactive</option>
                                    </select>
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
        </div>
    </body>
</html>
