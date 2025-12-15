<?php 
include 'Dbconnect.php'; 
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

 if ($_SERVER["REQUEST_METHOD"] == "POST") {

$Busno = sanitizeInput($_POST["Bus_Reg_no"]);
$Buscomp = sanitizeInput($_POST["Bus_Comp"]);
$SeatingCapacity = sanitizeInput($_POST["Seating_capacity"]);
$Year = sanitizeInput($_POST["Yearofmanufacture"]);
$sql = "INSERT INTO tbl_Bus (Bus_Reg_no, Bus_Comp, Seating_capacity, Yearofmanufacture)
        VALUES ('$Busno', '$Buscomp', '$SeatingCapacity', '$Year')";

$checkBusQuery = "SELECT * FROM tbl_Bus WHERE Bus_Reg_no  = '$Busno'";
$resultBus = $conn->query($checkBusQuery);

if ($resultBus->num_rows > 0) {
    echo "<script>alert('Bus Already Exists. Please Use a Different Registration Number.');</script>";
} elseif ($conn->query($sql) === TRUE) {
  echo "<script> alert(\"Bus Added Sucessfully\");</script>";
 echo"<script>window.location.href='Bus.php';</script>";
}else {
  echo "<script> alert(\"Error Adding Bus\")</script>";
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
        <link rel="stylesheet" href="Aubus.css">
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
    document.getElementById('Bus_Reg_no').addEventListener('input', function () {
      const Busreg = this.value;
      if (Busreg.length >= 10) {
        checkDuplicate('Bus_Reg_no', Busreg);
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
                </head>
                    <div class="main">
                        <div class="form">
                            <form  action="Abus.php" method="post" >  
                            <h1>Add Bus</h1>
                              <div class="form-row">
                                <div class="form-group">
                                  <label for="Bus_Reg_no">Bus Registration Number</label>
                                  <input type="text" id="Bus_Reg_no" name="Bus_Reg_no" placeholder="KL-00-AA-XXXX" maxlength="13" required>
                                </div>
                                <div class="form-group">
                                  <label for="Bus_Comp">Bus Company</label>
                                  <input type="text" id="Bus_Comp" name="Bus_Comp" required>
                                </div>
                              </div>
                              <div class="form-row">
                                <div class="form-group">
                                  <label for="Seating_capacity">Seating Capacity</label>
                                  <input type="number" id="Seating_capacity" name="Seating_capacity" min="1" max="50" required>
                                </div>
                                <div class="form-group">
                                  <label for="Yearofmanufacture">Year Of Manufacture</label>
                                  <input type="date" id="Yearofmanufacture" name="Yearofmanufacture" max="<?= date('Y-m-d'); ?>" required>
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
