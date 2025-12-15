<?php include 'Dbconnect.php'; 
session_start();
if (!isset($_SESSION["username"])) {
header("Location: login.php");
exit();
}
 if ($_SERVER["REQUEST_METHOD"] == "POST") {

$Routeno = sanitizeInput($_POST["Route_no"]);
$Routename = sanitizeInput($_POST["Route_name"]);
$Start = sanitizeInput($_POST["Starting_point"]);
$Dest = sanitizeInput($_POST["Destination"]);
$Distance = sanitizeInput($_POST["Distance"]);
$sql = "INSERT INTO tbl_Route (Route_no, Route_name, Starting_point, Destination, Distance)
        VALUES ('$Routeno', '$Routename', '$Start', '$Dest','$Distance')";

$checkRouteQuery = "SELECT * FROM tbl_Route WHERE Route_no  = '$Routeno'";
$resultRoute = $conn->query($checkRouteQuery);

if ($resultRoute->num_rows > 0) {
    echo "<script>alert('Route Already Exists. Please Use a Different Route Number.');</script>";
} elseif ($conn->query($sql) === TRUE) {
  echo "<script> alert(\"Route Added Sucessfully\");</script>";
 echo"<script>window.location.href='Route.php';</script>";
}else {
  echo "<script> alert(\"Error Adding Route\")</script>";
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
        <link rel="stylesheet" href="Auroute.css">
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
    document.getElementById('Route_no').addEventListener('input', function () {
      const Routeno = this.value;
      if (Routeno.length >= 10) {
        checkDuplicate('Route_no', Routeno);
      }
    });
  });

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
                            <form  action="Aroute.php" method="post" >
                            <h1>Add Route</h1>
                              <div class="form-row">
                              <div class="form-group">
                                  <label for="Route_no">Route Number</label>
                                  <input type="number" id="Route_no" name="Route_no" required>
                                </div>
                                <div class="form-group">
                                  <label for="Route_name">Route Name</label>
                                  <input type="text" id="Route_name" name="Route_name" required>
                                </div>
                              </div>
                              <div class="form-row">
                                <div class="form-group">
                                  <label for="Starting_point">Starting Point</label>
                                  <input type="text" id="Starting_point" name="Starting_point" required>
                                </div>
                                <div class="form-group">
                                  <label for="Destination">Destination</label>
                                  <input type="text" id="Destination" name="Destination" required>
                                </div>
                              </div>
                              <div class="form-group" id="select">
                                  <label for="Distance">Total Distance</label>
                                  <input type="number" id="Distance" name="Distance" required>
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
