
<html>
    <head>
        <title>Admin-Panel</title>
        <link rel="stylesheet" href="Style.css">
        <link rel="stylesheet" href="Audriver.css">
      <script>

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
  

  function enableFormFields() {
    const formFields = document.querySelectorAll("input[type='text'], input[type='number'], input[type='email'], input[type='tel'], input[type='time'], input[type='date'], input[type='password'], select");
    formFields.forEach(field => field.removeAttribute("readonly"));
    const genderSelect = document.getElementById("D_gender");
    genderSelect.removeAttribute("disabled");
    const districtSelect = document.getElementById("D_dist");
    districtSelect.removeAttribute("disabled");
    // const statusSelect = document.getElementById("D_Status");
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
                          $Driverid =sanitizeInput($_POST['Driver_id']);
                          $firstName=sanitizeInput($_POST['D_fname']);
                          $middleName=sanitizeInput($_POST["D_mname"]);
                          $lastName=sanitizeInput($_POST["D_lname"]);
                          $licenseNo=sanitizeInput($_POST["D_licence_no"]);
                          $licenseExpiry=sanitizeInput($_POST["D_licence_expiry"]);
                          $badgeNo=sanitizeInput($_POST["D_badge_no"]);
                          $city=sanitizeInput($_POST["D_city"]);
                          $district=sanitizeInput($_POST["D_dist"]);
                          $state = sanitizeInput($_POST["D_state"]);
                          $pinCode=sanitizeInput($_POST["D_pin"]);
                          $street=sanitizeInput($_POST["D_street"]);
                          $phone=sanitizeInput($_POST["D_phone"]);
                          $email=sanitizeInput($_POST["D_email"]);
                          $gender=sanitizeInput($_POST["D_gender"]);
                          $dob=sanitizeInput($_POST["D_dob"]);
                          $joinDate=sanitizeInput($_POST["D_join"]);
                          $experience=sanitizeInput($_POST["D_experience"]);
                          $district = sanitizeInput($_POST["D_dist"]);
                          if ($district === 'others') {
                              $otherDistrict = sanitizeInput($_POST["otherDistrict"]);
                              $district = $otherDistrict;
                          }
                          if (empty($licenseNo)) {
                              header("Location: ../Udriver.php?Driver_id=$id&error=D_licence_no is Required");
                              exit;
                          } else {

                                $duplicateFields = array();

                                // Check for duplicate Licence Number
                                $checkLicenceQuery = "SELECT * FROM tbl_Driver WHERE D_licence_no = '$licenseNo' AND Driver_id != $Driverid";
                                $resultLicence = mysqli_query($conn, $checkLicenceQuery);
                                if (mysqli_num_rows($resultLicence) > 0) {
                                    $duplicateFields[] = "Licence Number";
                                }

                                // Check for duplicate Badge Number
                                $checkBadgeQuery = "SELECT * FROM tbl_Driver WHERE D_badge_no = '$badgeNo' AND Driver_id != $Driverid";
                                $resultBadge = mysqli_query($conn, $checkBadgeQuery);
                                if (mysqli_num_rows($resultBadge) > 0) {
                                    $duplicateFields[] = "Badge Number";
                                }

                                // Check for duplicate Phone
                                $checkPhoneQuery = "SELECT * FROM tbl_Driver WHERE D_phone = '$phone' AND Driver_id != $Driverid";
                                $resultPhone = mysqli_query($conn, $checkPhoneQuery);
                                if (mysqli_num_rows($resultPhone) > 0) {
                                    $duplicateFields[] = "Phone";
                                }

                                // Check for duplicate Email
                                $checkEmailQuery = "SELECT * FROM tbl_Driver WHERE D_email = '$email' AND Driver_id != $Driverid";
                                $resultEmail = mysqli_query($conn, $checkEmailQuery);
                                if (mysqli_num_rows($resultEmail) > 0) {
                                    $duplicateFields[] = "Email";
                                }

                                if (count($duplicateFields) > 0) {
                                    $duplicateFieldsStr = implode(", ", $duplicateFields);
                                    echo "<script>alert('$duplicateFieldsStr Already Exists: Please use different $duplicateFieldsStr');</script>";
                                } else {

                                    $sql = "UPDATE tbl_Driver SET
                                    D_fname='$firstName',
                                    D_mname='$middleName',
                                    D_lname='$lastName',
                                    D_licence_no='$licenseNo',
                                    D_licence_expiry='$licenseExpiry',
                                    D_badge_no='$badgeNo',
                                    D_city='$city',
                                    D_dist='$district',
                                    D_state='$state',
                                    D_pin='$pinCode',
                                    D_street='$street',
                                    D_phone='$phone',
                                    D_email='$email',
                                    D_gender='$gender',
                                    D_dob='$dob',
                                    D_join='$joinDate',
                                    D_experience='$experience'
                                    WHERE Driver_id=$Driverid";
                                  if (mysqli_query($conn, $sql)) {
                                      echo "<script> alert(\"Driver updated successfully\");</script>";
                                      echo "<script>window.location.href='Driver.php';</script>";
                                      exit;
                                  } else {
                                      echo "Error updating record: " . mysqli_error($conn);
                                  }
                              }
                          }
                      } elseif (isset($_GET['Driver_id'])) {
                          $Driverid = sanitizeInput($_GET['Driver_id']);
                          $sql = "SELECT * FROM tbl_Driver WHERE Driver_id=$Driverid";
                          $result = mysqli_query($conn, $sql);

                          if (mysqli_num_rows($result) > 0) {
                              $row = mysqli_fetch_assoc($result);
                          } else {
                              echo "<script> alert(\"No Driver found with the specified ID\");</script>";
                              echo "<script>window.location.href='Udriver.php';</script>";
                              exit;
                          }
                      }
                      ?>

                      <form action="Udriver.php" method="post" onsubmit="return validateDateOfBirth()">               
                      <h1>Edit Driver Information</h1>
                      <div class="form-row">
                            <div class="form-group">
                            <input type="hidden" id="Driver_id" name="Driver_id" value="<?php echo $row['Driver_id'] ?? ''; ?>">
                            </div>
                          </div>
                        <div class="form-row">
                          <div class="form-group">
                            <label for="firstName">First Name</label>
                            <input type="text" id="firstName" name="D_fname" value="<?php echo $row['D_fname'] ?? ''; ?>" readonly required>
                          </div>
                          <div class="form-group">
                            <label for="middleName">Middle Name</label>
                            <input type="text" id="middleName" name="D_mname" value="<?php echo $row['D_mname'] ?? ''; ?>" readonly>
                          </div>
                        </div>
                        <div class="form-row">
                          <div class="form-group">
                            <label for="lastName">Last Name</label>
                            <input type="text" id="lastName" name="D_lname" value="<?php echo $row['D_lname'] ?? ''; ?>" readonly>
                          </div>
                            <div class="form-group">
                              <label for="licenceNo">Licence No</label>
                              <input type="text" id="licenceNo" name="D_licence_no" maxlength="16"  value="<?php echo $row['D_licence_no'] ?? ''; ?>" readonly required>
                            </div>
                        </div>
                        <div class="form-row">
                          <div class="form-group">
                            <label for="licenceexpiry">Licence Expiry</label>
                            <input type="date" id="licence Expiry" name="D_licence_expiry"  min="<?= date('Y-m-d'); ?>" value="<?php echo $row['D_licence_expiry'] ?? ''; ?>" readonly required>
                          </div>
                          <div class="form-group">
                            <label for="badgeNo">Badge No</label>
                            <input type="text" id="badgeNo" name="D_badge_no" maxlength="15"  value="<?php echo $row['D_badge_no'] ?? ''; ?>" readonly required>
                          </div>                      
                        </div>
                        <div class="form-row">
                          <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" id="city" name="D_city" value="<?php echo $row['D_city'] ?? ''; ?>" readonly required>
                          </div>
                          <div class="form-group">
                            <label for="district">District</label>
                            <select id="D_dist" name="D_dist" onchange="handleDistrictChange(this.value)" disabled required>
                            <option>Select District</option>
                            <?php
                            $districtOptions = array(
                                "Thiruvananthapuram", "Kollam", "Pathanamthitta", "Alappuzha",
                                "Kottayam", "Idukki", "Ernakulam", "Thrissur", "Palakkad",
                                "Malappuram", "Kozhikode", "Wayanad", "Kannur", "Kasargod", "others"
                            );
                            
                            foreach ($districtOptions as $districtOption) {
                                $selected = (isset($row['D_dist']) && trim($row['D_dist']) === $districtOption) ? 'selected' : '';
                                echo "<option value=\"$districtOption\" $selected>$districtOption</option>";
                            }
                            ?>
                        </select>
                        
                          </div>                          
                        </div>
                        <div class="form-group" id="otherDistrictContainer" style="display: none;">
                          <label for="otherDistrict">Other District</label>
                          <input type="text" id="otherDistrict" name="otherDistrict" value="<?php echo (isset($row['D_dist']) && !in_array(trim($row['D_dist']), $districtOptions)) ? $row['D_dist'] : ''; ?>" readonly>
                      </div>
                        <div class="form-row">
                          <div class="form-group">
                            <label for="D_state">State</label>
                            <input type="text" id="D_state" name="D_state" value="<?php echo $row['D_state'] ?? ''; ?>" readonly required>
                          </div>
                          <div class="form-group">
                            <label for="pinCode">Pin Code</label>
                            <input type="text" id="pinCode" name="D_pin" pattern="[0-9]{6}" minlength="6" maxlength="6" value="<?php echo $row['D_pin'] ?? ''; ?>" readonly required>
                          </div>
                        </div>
                        <div class="form-row">
                          <div class="form-group">
                            <label for="street">Street</label>
                            <input type="text" id="street" name="D_street" value="<?php echo $row['D_street'] ?? ''; ?>" readonly required>
                          </div>
                          <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="tel" id="phone" name="D_phone" pattern="[0-9]{10}" minlength="10" maxlength="10" value="<?php echo $row['D_phone'] ?? ''; ?>" readonly required>
                          </div>
                        </div>
                        <div class="form-row">
                          <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="D_email" value="<?php echo $row['D_email'] ?? ''; ?>" readonly required>
                          </div>
                          <div class="form-group">
                            <label for="gender">Gender</label>
                            <select id="D_gender" name="D_gender" disabled required>
                              <option>Select Gender</option>
                              <option value="M" <?php if (isset($row['D_gender']) && trim($row['D_gender']) === 'M') echo 'selected'; ?>>Male</option>
                              <option value="F" <?php if (isset($row['D_gender']) && trim($row['D_gender']) === 'F') echo 'selected'; ?>>Female</option>
                              <option value="O" <?php if (isset($row['D_gender']) && trim($row['D_gender']) === 'O') echo 'selected'; ?>>Others</option>
                            </select>  
                          </div>
                        </div>
                        <div class="form-row">
                          <div class="form-group">
                            <label for="dob">Date of Birth</label>
                            <input type="date" id="dob" name="D_dob"  max="<?= date('Y-m-d'); ?>" value="<?php echo $row['D_dob'] ?? ''; ?>" readonly required>
                          </div>
                          <div class="form-group">
                            <label for="joinDate">Join Date</label>
                            <input type="date" id="joinDate" name="D_join"  min="<?= date('Y-m-d'); ?>" value="<?php echo $row['D_join'] ?? ''; ?>" readonly required>
                          </div>
                        </div>
                          <div class="form-group" id="select">
                            <label for="experience">Experience (in years)</label>
                            <input type="number" id="experience" name="D_experience" value="<?php echo $row['D_experience'] ?? ''; ?>" min="5" readonly required>
                          </div>
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
    </body>
</html>