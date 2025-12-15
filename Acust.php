<html>
    <head>
        <title>Admin-Panel</title>
        <link rel="stylesheet" href="Style.css">
        <link rel="stylesheet" href="Acust.css">
<script>
// Add this JavaScript code inside the <head> tag of your HTML

function handleDistrictChange(value) {
  const otherDistrictContainer = document.getElementById('otherDistrictContainer');
  const otherDistrictInput = document.getElementById('otherDistrict');
  
  if (value === 'others') {
    otherDistrictContainer.style.display = 'block';
    otherDistrictInput.required = true;
  } else {
    otherDistrictContainer.style.display = 'none';
    otherDistrictInput.required = false;
  }
}

function validatePasswords() {
  const password = document.getElementById('login_Password').value;
  const confirmPassword = document.getElementById('cnf_login_Password').value;

  if (password !== confirmPassword) {
    alert('Passwords do not match. Please make sure your passwords match.');
    return false;
  }

  return true;
}


function checkDuplicate(field, value) {
  const xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        eval(xhr.responseText); // Execute the JavaScript variables

        if (field === 'Username') {
          if (usernameExists) {
            alert(`The Username already exists!`);
            document.getElementById('Username').value = '';
            document.getElementById('Username').focus();
          }
        } else if (field === 'C_phone') {
          if (phoneExists) {
            alert(`The Mobile number already exists. Please use a different mobile number.`);
            document.getElementById('C_phone').value = '';
            document.getElementById('C_phone').focus();
          }
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

document.getElementById('Username').addEventListener('input', function () {
  const UsernameValue = this.value;
  if (UsernameValue.length > 0) {
    checkDuplicate('Username', UsernameValue);
  }
});

document.addEventListener('DOMContentLoaded', function () {
  document.getElementById('C_phone').addEventListener('input', function () {
    const phoneValue = this.value;
    if (phoneValue.length >= 10) {
      checkDuplicate('C_phone', phoneValue);
    }
  });
});

</script>

    </head>
    <body> 
    <?php
include 'Dbconnect.php';
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data and sanitize inputs
    $Username = sanitizeInput($_POST["Username"]);
    $firstName = sanitizeInput($_POST["C_fname"]);
    $middleName = sanitizeInput($_POST["C_mname"]);
    $lastName = sanitizeInput($_POST["C_lname"]);
    $city = sanitizeInput($_POST["C_city"]);
    $district = sanitizeInput($_POST["C_dist"]);
    $state = sanitizeInput($_POST["C_state"]);
    $pinCode = sanitizeInput($_POST["C_pin"]);
    $street = sanitizeInput($_POST["C_street"]);
    $phone = sanitizeInput($_POST["C_phone"]);
    $gender = $_POST["C_gender"];
    $dob = $_POST["C_dob"];
    $pass = $_POST["login_Password"];
    $cpass = $_POST["cnf_login_Password"];
    $status = $_POST["C_Status"];
    if ($district === 'others') {
      $otherDistrict = sanitizeInput($_POST["C_otherdist"]);
      $district = $otherDistrict;
  }
      // Hash the password
    $hashedPassword = password_hash($pass, PASSWORD_BCRYPT);

    // Check if the user already exists based on Username (email) in tbl_Login
    $checkUsernameQuery = "SELECT * FROM tbl_Login WHERE Username = '$Username'";
    $resultUsername = $conn->query($checkUsernameQuery);

    $checkPhoneQuery = "SELECT * FROM tbl_Customer WHERE C_phone = '$phone'";
    $resultPhone = $conn->query($checkPhoneQuery);

    if ($resultUsername->num_rows > 0) {
        echo "<script>alert('The Username already exists.');</script>";
    } elseif ($resultPhone->num_rows > 0) {
        echo "<script>alert('The Mobile number already exists. Please use a different mobile number.');</script>";
    }  else {
      $loginType = 'user';
        $insertLoginQuery = "INSERT INTO tbl_Login (Username, login_Password, login_type) VALUES ('$Username', '$hashedPassword', '$loginType')";
        if ($conn->query($insertLoginQuery) === TRUE) {
            $insertCustomerQuery = "INSERT INTO tbl_Customer (Username, C_fname, C_mname, C_lname, C_city, C_dist, C_state, C_pin, C_street, C_phone, C_gender, C_dob, C_Status)
            VALUES ('$Username', '$firstName', '$middleName', '$lastName', '$city', '$district', '$state', '$pinCode', '$street', '$phone', '$gender', '$dob', ' $status ')";
            if ($conn->query($insertCustomerQuery) === TRUE) {
                echo "<script>alert('Customer registered successfully.');</script>";
            } else {
                echo "<script>alert('Customer Registration Failed.');</script>";
            }
        } else {
            echo "<script>alert('Customer Registration Failed.');</script>";
        }
    }
    echo "<script>window.location.href='Cust.php';</script>";
}

// Function to sanitize user input
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Close the database connection
$conn->close();
?>



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
            <div class="form" >
            <form action="Acust.php" method="post" onsubmit="return validatePasswords();">
            <h1>Add Customer Information</h1><br>
            <div class="form-row">
              <div class="form-group">
                <label for="Username">Username</label>
                <input type="email" id="Username" name="Username" required>
              </div>
              <div class="form-group">
                <label for="firstName">First Name</label>
                <input type="text" id="firstName" name="C_fname" required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="middleName">Middle Name</label>
                <input type="text" id="middleName" name="C_mname">
              </div>
              <div class="form-group">
                <label for="lastName">Last Name</label>
                <input type="text" id="lastName" name="C_lname">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="city">City</label>
                <input type="text" id="city" name="C_city" required>
              </div>
              <div class="form-group">
                <label for="district">District</label>
                <select id="district" name="C_dist" onchange="handleDistrictChange(this.value)" required>
                  <option value="">Select District</option>
                  <option value="Thiruvananthapuram">Thiruvananthapuram</option>
                  <option value="Kollam">Kollam</option>
                  <option value="Pathanamthitta">Pathanamthitta</option>
                  <option value="Alappuzha">Alappuzha</option>
                  <option value="Kottayam">Kottayam</option>
                  <option value="Idukki">Idukki</option>
                  <option value="Ernakulam">Ernakulam</option>
                  <option value="Thrissur">Thrissur</option>
                  <option value="Palakkad">Palakkad</option>
                  <option value="Malappuram">Malappuram</option>
                  <option value="Kozhikode">Kozhikode</option>
                  <option value="Wayanad">Wayanad</option>
                  <option value="Kannur">Kannur</option>
                  <option value="Kasargod">Kasargod</option>
                  <option value="others">Other</option>
                </select>
              </div>
              </div>
              <div class="form-group"  id="otherDistrictContainer" style="display: none;">
                <label for="C_otherdist">Other District</label>
                <input type="text" id="C_otherdist" name="C_otherdist">
              </div>
              <div class="form-row">            
              <div class="form-group">
              <label for="C_state">State</label>
              <input type="text" id="C_state" name="C_state" required>
              </div>
              <div class="form-group">
                <label for="pinCode">Pin Code</label>
                <input type="text" id="pinCode" name="C_pin" pattern="[0-9]{6}" title="Maximum 6 Digits" minlength="6" maxlength="6" required>
              </div>
            </div>          
            <div class="form-row">
              <div class="form-group">
                <label for="street">Street</label>
                <input type="text" id="street" name="C_street" required>
              </div>
              <div class="form-group">
                <label for="C_phone">Phone</label>
                <input type="tel" id="C_phone" name="C_phone" pattern="[0-9]{10}" title="Maximum 10 Digits"  minlength="10" maxlength="10" required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" name="C_dob"  max="<?= date('Y-m-d'); ?>" required>
              </div>
              <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="C_gender" required>
                <option> Select Gender</option>
                  <option value="M">Male</option>
                  <option value="F">Female</option>
                  <option value="O">Other</option>
                </select>
              </div>
            </div>
            <div class="form-row">
            <div class="form-group">
                <label for="login_Password	">Password</label>
                <input type="password" id="login_Password" name="login_Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" minlength="8" maxlength="8" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required>
              </div>
              <div class="form-group">
                <label for="cnf_login_Password">Confirm Password</label>
                <input type="password" id="cnf_login_Password" name="cnf_login_Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" minlength="8" maxlength="8" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required>
              </div>
            </div>
            <div class="form-group" id="select">
                <label for="status">Status</label>
                <select id="status" name="C_status" required>
                <option >Select Status</option>
                  <option value="1">Active</option>
                  <option value="0">Inactive</option>
                </select>
              </div>
            <br>
              <div class="form-group">
                <input type="submit" name="submit" value="Submit">
              </div>
            </div>
          </form>
                    
                    </div>
            </div>
        </div>
    </body>
</html>