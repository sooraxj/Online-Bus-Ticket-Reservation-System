
<html>
    <head>
        <title>Admin-Panel</title>
        <link rel="stylesheet" href="Style.css">
        <link rel="stylesheet" href="Audriver.css">
<script>
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

  function validateDateOfBirth() {
            var dobInput = document.getElementById("dob");

            var userDate = new Date(dobInput.value);

            var today = new Date();

            var minimumDateOfBirth = new Date();
            minimumDateOfBirth.setFullYear(today.getFullYear() - 25);

            if (isNaN(userDate.getTime())) {
                alert("Please select a valid date.");
                return false;
            }

            if (userDate > today) {
                alert("Date of birth cannot be in the future.");
                return false;
            }

            if (userDate > minimumDateOfBirth) {
                alert("You must be at least 25 years old.");
                return false;
            }

            return true;
        }

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
    document.getElementById('D_phone').addEventListener('input', function () {
      const phoneValue = this.value;
      if (phoneValue.length >= 10) {
        checkDuplicate('D_phone', phoneValue);
      }
    });

    document.getElementById('D_email').addEventListener('input', function () {
      const emailValue = this.value;
      if (emailValue.length > 0) {
        checkDuplicate('D_email', emailValue);
      }
    });
    document.getElementById('D_licence_no').addEventListener('input', function () {
      const licenceValue = this.value;
      if (licenceValue.length >= 10) {
        checkDuplicate('D_licence_no', licenceValue);
      }
    });
    document.getElementById('D_badge_no').addEventListener('input', function () {
      const badgeValue = this.value;
      if (badgeValue.length >= 10) {
        checkDuplicate('D_badge_no', badgeValue);
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
    $firstName = sanitizeInput($_POST["D_fname"]);
    $middleName = sanitizeInput($_POST["D_mname"]);
    $lastName = sanitizeInput($_POST["D_lname"]);
    $licenseNo = sanitizeInput($_POST["D_licence_no"]);
    $licenseExpiry = $_POST["D_licence_expiry"];
    $badgeNo = sanitizeInput($_POST["D_badge_no"]);
    $city = sanitizeInput($_POST["D_city"]);
    $district = sanitizeInput($_POST["D_dist"]);
    $state = sanitizeInput($_POST["D_state"]);
    $pinCode = sanitizeInput($_POST["D_pin"]);
    $street = sanitizeInput($_POST["D_street"]);
    $phone = sanitizeInput($_POST["D_phone"]);
    $email = sanitizeInput($_POST["D_email"]);
    $gender = $_POST["D_gender"];
    $dob = $_POST["D_dob"];
    $joinDate = $_POST["D_join"];
    $experience = sanitizeInput($_POST["D_experience"]);
    // Check if "Other District" option is selected
    if ($district === "others") {
      $otherdistrict = sanitizeInput($_POST["D_otherdist"]);
      $district = $otherdistrict;
  }

    $sql = "INSERT INTO tbl_Driver (D_fname, D_mname, D_lname, D_licence_no	, D_licence_expiry, D_badge_no, D_city, D_dist, D_state, D_pin, D_street, D_phone, D_email, D_gender, D_dob, D_join, D_experience)
            VALUES ('$firstName', '$middleName', '$lastName', '$licenseNo', '$licenseExpiry', '$badgeNo', '$city', '$district', '$state', '$pinCode', '$street', '$phone', '$email', '$gender', '$dob', '$joinDate', '$experience')";

  $checkPhoneQuery = "SELECT * FROM tbl_Driver WHERE D_phone = '$phone'";
    $resultPhone = $conn->query($checkPhoneQuery);

    // Check if the user already exists 
    $checkEmailQuery = "SELECT * FROM tbl_Driver WHERE D_email = '$email'";
    $resultEmail = $conn->query($checkEmailQuery);

    $checklicenceQuery = "SELECT * FROM tbl_Driver WHERE D_licence_no = '$licenseNo'";
    $resultlicence = $conn->query($checklicenceQuery);

    $checkbadgeQuery = "SELECT * FROM tbl_Driver WHERE D_badge_no = '$badgeNo'";
    $resultbadge = $conn->query($checkbadgeQuery);
    if ($resultPhone->num_rows > 0) {
        echo "<script>alert('Mobile number already exists. Please use a different mobile number.');</script>";
    } elseif ($resultEmail->num_rows > 0) {
        echo "<script>alert('Email already exists. Please use a different email address.');</script>";
    } elseif ($resultlicence->num_rows > 0) {
    echo "<script>alert('Licence already exists. Please use a different Licence Number.');</script>";
    } elseif ($resultbadge->num_rows > 0) {
      echo "<script>alert('Badge already exists. Please use a different Badge Number');</script>";
    } elseif ($conn->query($sql) === TRUE) {
      echo "<script> alert(\"Driver added sucessfully\");</script>";
     echo"<script>window.location.href='Driver.php';</script>"; 
    }else {
      echo "<script> alert(\"Error Adding Driver\")</script>";
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
                      <form  action="Adriver.php" method="post" onsubmit="return validateDateOfBirth()" >                   
                      <h1>Add Driver Information</h1>
                      <BR>
                        <div class="form-row">
                          <div class="form-group">
                            <label for="firstName">First Name</label>
                            <input type="text" id="firstName" name="D_fname" required>
                          </div>
                          <div class="form-group">
                            <label for="middleName">Middle Name</label>
                            <input type="text" id="middleName" name="D_mname">
                          </div>
                        </div>
                        <div class="form-row">
                          <div class="form-group">
                            <label for="lastName">Last Name</label>
                            <input type="text" id="lastName" name="D_lname">
                          </div>
                            <div class="form-group">
                              <label for="licenceNo">Licence No</label>
                              <input type="text" id="licenceNo" name="D_licence_no" maxlength="16" required>
                            </div>
                        </div>
                        <div class="form-row">
                          <div class="form-group">
                            <label for="licenceexpiry">Licence Expiry</label>
                            <input type="date" id="licence Expiry"  min="<?= date('Y-m-d'); ?>" name="D_licence_expiry" required>
                          </div>
                          <div class="form-group">
                            <label for="badgeNo">Badge No</label>
                            <input type="text" id="badgeNo" name="D_badge_no" maxlength="15"  required>
                          </div>                      
                        </div>
                        <div class="form-row">
                          <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" id="city" name="D_city" required>
                          </div>
                          <div class="form-group">
                            <label for="district">District</label>
                            <select id="district" name="D_dist" onchange="handleDistrictChange(this.value)" required>
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
                                              
                        <div class="form-group" id="otherDistrictContainer" style="display: none;">
                            <label for="otherDistrict">Other District</label>
                            <input type="text" id="otherDistrict" name="D_otherdist">
                       </div>
                        <div class="form-row">
                          <div class="form-group">
                            <label for="D_state">State</label>
                            <input type="text" id="D_state" name="D_state" required>
                          </div>
                          <div class="form-group">
                            <label for="pinCode">Pin Code</label>
                            <input type="text" id="pinCode" name="D_pin" pattern="[0-9]{6}" minlength="6" maxlength="6" required>
                          </div>
                        </div>
                        <div class="form-row">
                          <div class="form-group">
                            <label for="street">Street</label>
                            <input type="text" id="street" name="D_street" required>
                          </div>
                          <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="tel" id="phone" name="D_phone" pattern="[0-9]{10}" minlength="10" maxlength="10" required>
                          </div>
                        </div>
                        <div class="form-row">
                          <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="D_email" required>
                          </div>
                          <div class="form-group">
                            <label for="gender">Gender</label>
                            <select id="gender" name="D_gender" required>
                            <option> Select Gender</option>
                              <option value="M">Male</option>
                              <option value="F">Female</option>
                              <option value="O">Other</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-row">
                        <div class="form-group">
                        <label for="dob">Date of Birth</label>
                        <input type="date" id="dob" max="<?= date('Y-m-d'); ?>" name="D_dob" required>
                    </div>
                          <div class="form-group">
                            <label for="joinDate">Join Date</label>
                            <input type="date" id="joinDate"  min="<?= date('Y-m-d'); ?>" name="D_join" required>
                          </div>
                        </div>
                          <div class="form-group" id="select">
                            <label for="experience">Experience (in years)</label>
                            <input type="number" id="experience" name="D_experience" min="5" required>
                          </div>
                        <br>
                      
                          <div class="form-group">
                            <input type="submit"  name="submit" value="Submit">
                          </div> 
                        </div>
                      </form>
                    </div>
            </div>
        </div>
    </body>
</html>