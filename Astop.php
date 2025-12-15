<?php
include 'Dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Routeid = sanitizeInput($_POST["RouteSelect"]);
    $Stopno = sanitizeInput($_POST["Stop_no"]);
    $Stopname = sanitizeInput($_POST["Stop_name"]);
    $Time = sanitizeInput($_POST["Arrival_time"]);
    $Distance = sanitizeInput($_POST["Distance"]);
    $status = $_POST["Stop_status"];
    
    // Check for duplicate stop numbers within the same route
    $checkStopQuery = "SELECT * FROM tbl_Stop WHERE Route_id = '$Routeid' AND Stop_no = '$Stopno'";
    $resultStopno = $conn->query($checkStopQuery);

    // Check for duplicate arrival times
    $checkTimeQuery = "SELECT * FROM tbl_Stop WHERE Route_id = '$Routeid' AND Arrival_time = '$Time'";
    $resultTime = $conn->query($checkTimeQuery);

    if ($resultStopno->num_rows > 0) {
        echo "<script>alert('Stop Number Already Exists for this Route. Please Use a Different Stop Number.');</script>";
    } elseif ($resultTime->num_rows > 0) {
        echo "<script>alert('Time Already Exists. No same time for the stops.');</script>";
    } else {
        // Insert new stop if no duplicates found
        $sql = "INSERT INTO tbl_Stop (Route_id, Stop_no, Stop_name, Arrival_time, Distance, Stop_status)
                VALUES ('$Routeid', '$Stopno', '$Stopname', '$Time', '$Distance', '$status')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert(\"Stop Added Successfully\");</script>";
            echo "<script>window.location.href='Stop.php';</script>";
        } else {
            echo "<script>alert(\"Error Adding Stop\")</script>";
            echo "Error: " . $sql . "<br>" . $conn->error;
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

<script>

function checkDuplicate(field, value) {
  const xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        const response = JSON.parse(xhr.responseText);
        if (response.exists) {
          alert(`The ${field} already exists!`);
          document.getElementById(field).value = '';
          document.getElementById(field).focus();
        }
      } else {
        console.error('Error:', xhr.status, xhr.statusText);
      }
    }
  };
  xhr.open('POST', 'check_duplicate.php', true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.send(`${field}=${value}`);
}

document.addEventListener('DOMContentLoaded', function () {
  document.getElementById('Stop_no').addEventListener('input', function () {
    const Routeno = this.value;
    if (Routeno.length >= 10) {
      checkDuplicate('Route_no', Routeno);
    }
  });
  document.getElementById('Arrival_time').addEventListener('input', function () {
      const licenceValue = this.value;
      if (licenceValue.length >= 10) {
        checkDuplicate('Arrival_time', licenceValue);
      }
    });
  
});

</script>
</script>  



<html>
    <head>
        <title>Admin-Panel</title>
        <link rel="stylesheet" href="Style.css">
        <link rel="stylesheet" href="Austop.css">
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
                            <form action="Astop.php" method="post">
                            <h1>Add Stop</h1>
                    <div class="form-row">
                        <div class="form-group">
                        <label for="RouteSelect">Select Route</label>
                      <select id="RouteSelect" name="RouteSelect" required>
                          <option value="">Select a Route</option>
                          <?php
                          include 'Dbconnect.php';
                          session_start();
                          if (!isset($_SESSION["username"])) {
                              header("Location: login.php");
                              exit();
                          }
                          $routeQuery = "SELECT Route_no, Route_name FROM tbl_Route";
                          $routeResult = $conn->query($routeQuery);
                          while ($row = $routeResult->fetch_assoc()) {
                              echo "<option value=\"" . $row["Route_no"] . "\">" . $row["Route_no"] . " - " . $row["Route_name"] . "</option>";
                          }
                          $conn->close();
                          ?>
                      </select>

                        </div>
                        <div class="form-group">
                        <label for="Stop_no">Stop Number</label>
                        <input type="number" id="Stop_no" name="Stop_no" min="1" max="99"  required>
                        </div>
                     </div>

    <div class="form-row">
        <div class="form-group">
            <label for="Stop_name">Stop Name</label>
            <input type="text" id="Stop_name" name="Stop_name" required>
        </div>
        <div class="form-group">
            <label for="Time">Time</label>
            <input type="Time" id="Arrival_time" name="Arrival_time" required>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="Distance">Distance</label>
            <input type="number" id="Distance" name="Distance" min="0" max="99999" required>
        </div>
        <div class="form-group">
            <label for="Stop_status">Status</label>
            <select id="Stop_status" name="Stop_status" required>
            <option>Select Status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>
    </div>
    <br>
    <div class="form-group">
            <input type="submit" value="Submit" name="submitStop">
        </div>

</form>
</div>
                       
</div>                    
    </div>
   </div>
  </div>
    </body>
</html>
