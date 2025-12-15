<html>
    <head>
        <title>Alliance</title>
        <link rel="stylesheet" href="profile.css">

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

  function enableFormFields() {
    const formFields = document.querySelectorAll("input[type='text'], input[type='number'], input[type='email'], input[type='tel'], input[type='time'], input[type='date'], input[type='password'], select");
    formFields.forEach(field => field.removeAttribute("readonly"));
    
    // Remove the "disabled" attribute from the gender select field
    const genderSelect = document.getElementById("C_gender");
    genderSelect.removeAttribute("disabled");

    // Remove the "disabled" attribute from the district select field
    const districtSelect = document.getElementById("C_dist");
    districtSelect.removeAttribute("disabled");

    // Hide the "Edit" button and show the "Update" button
    const editButton = document.querySelector("input[value='Edit']");
    const updateButton = document.querySelector("input[name='Update']");
    editButton.style.display = "none";
    updateButton.style.display = "block";
  }




</script>

    </head>
    <body>

            <div class="content">
            <div class="main">
            <div class="form">
            <?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

include 'Dbconnect.php';
$row = null;

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$loggedInUsername = $_SESSION["username"];
$sql = "SELECT * FROM tbl_Customer WHERE Username = '$loggedInUsername'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
} else {
    echo "<script> alert(\"No Customer found for the logged-in user\");</script>";
    echo "<script>window.location.href='profile.php';</script>";
    exit;
}

if (isset($_POST['Update'])) {
    $id = $row['Customer_id'];
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
    if ($district === 'others') {
        $otherDistrict = sanitizeInput($_POST["otherDistrict"]);
        $district = $otherDistrict;
    }
    $duplicateFields = array();

    // Check for duplicate phone number
    $checkPhoneQuery = "SELECT * FROM tbl_Customer WHERE C_phone = '$phone' AND Customer_id != $id";
    $resultPhone = mysqli_query($conn, $checkPhoneQuery);
    if (mysqli_num_rows($resultPhone) > 0) {
        $duplicateFields[] = "Phone Number";
    }


    if (count($duplicateFields) > 0) {
        $duplicateFieldsStr = implode(", ", $duplicateFields);
        echo "<script>alert('$duplicateFieldsStr Already Exists: Please use different $duplicateFieldsStr');</script>";
        echo "<script>window.location.href='profile.php';</script>"; 
        exit;
    } else {
        // Update customer information in the database
        $sql = "UPDATE tbl_Customer SET
            C_fname='$firstName',
            C_mname='$middleName',
            C_lname='$lastName',
            C_city='$city',
            C_dist='$district',
            C_state='$state',
            C_pin='$pinCode',
            C_street='$street',
            C_phone='$phone',
            C_gender='$gender',
            C_dob='$dob'
            WHERE Customer_id=$id";
 
        if (mysqli_query($conn, $sql)) {
            echo "<script> alert(\"Profile Updated successfully\");</script>";
            echo "<script>window.location.href='profile.php';</script>";
            exit;
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
    }
}
?>
            <form  action="profile.php" method="post" > 
            <h1>My Profile</h1>
            <a href="forgot_pass.php" class="button-link">Change Password</a>

            <div class="form-row">
              <div class="form-group">
                <input type="hidden" id="Customer_id" name="Customer_id" value="<?php echo $row['Customer_id'] ?? ''; ?>">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="firstName">First Name</label>
                <input type="text" id="firstName" name="C_fname" value="<?php echo $row['C_fname'] ?? ''; ?>" readonly required>
              </div>
              <div class="form-group">
                <label for="middleName">Middle Name</label>
                <input type="text" id="middleName" name="C_mname" value="<?php echo $row['C_mname'] ?? '';  ?>" readonly>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="lastName">Last Name</label>
                <input type="text" id="lastName" name="C_lname" value="<?php echo $row['C_lname'] ?? ''; ?>" readonly>
              </div>
              <div class="form-group">
                <label for="city">City</label>
                <input type="text" id="city" name="C_city" value="<?php echo $row['C_city'] ?? ''; ?>" readonly required>
              </div>
            </div>
            <div class="form-row">
            <div class="form-group">
        <label for="district">District</label>
        <select id="C_dist" name="C_dist" onchange="handleDistrictChange(this.value)" disabled required>
            <option>Select District</option>
            <?php
            $districtOptions = array(
                "Thiruvananthapuram", "Kollam", "Pathanamthitta", "Alappuzha",
                "Kottayam", "Idukki", "Ernakulam", "Thrissur", "Palakkad",
                "Malappuram", "Kozhikode", "Wayanad", "Kannur", "Kasargod", "others"
            );
            
            foreach ($districtOptions as $districtOption) {
                $selected = (isset($row['C_dist']) && trim($row['C_dist']) === $districtOption) ? 'selected' : '';
                echo "<option value=\"$districtOption\" $selected>$districtOption</option>";
            }
            ?>
        </select>
         </div>
              <div class="form-group">
            <label for="C_state">State</label>
            <input type="text" id="C_state" name="C_state" value="<?php echo $row['C_state'] ?? ''; ?>" readonly required>
            </div>
            </div>
            <div class="form-group" id="otherDistrictContainer" style="display: none;">
        <label for="otherDistrict">Other District</label>
        <input type="text" id="otherDistrict" name="otherDistrict" value="<?php echo (isset($row['C_dist']) && !in_array(trim($row['C_dist']), $districtOptions)) ? $row['C_dist'] : ''; ?>" readonly>
    </div>
            <div class="form-row">
              <div class="form-group">
                <label for="pinCode">Pin Code</label>
                <input type="text" id="pinCode" name="C_pin" pattern="[0-9]{6}" title="Maximum 6 Digits" minlength="6" maxlength="6" value="<?php echo $row['C_pin'] ?? ''; ?>" readonly required>
              </div>
              <div class="form-group">
                <label for="street">Street</label>
                <input type="text" id="street" name="C_street" value="<?php echo $row['C_street'] ?? ''; ?>" readonly required>
              </div>
              </div>           
            <div class="form-row">
              <div class="form-group">
                <label for="phone">Phone</label>
                <input type="tel" id="phone" name="C_phone" pattern="[0-9]{10}" title="Maximum 10 Digits"  minlength="10" maxlength="10" value="<?php echo $row['C_phone'] ?? ''; ?>" readonly required>
              </div>
              <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" name="C_dob"  max="<?= date('Y-m-d'); ?>" value="<?php echo $row['C_dob'] ?? ''; ?>" readonly required>
              </div>
            </div>
              <div class="form-group">
                <label for="gender">Gender</label>
                <select id="C_gender" name="C_gender" disabled required>
                 <option>Select Gender</option>
                 <option value="M" <?php if (isset($row['C_gender']) && trim($row['C_gender']) === 'M') echo 'selected'; ?>>Male</option>
                 <option value="F" <?php if (isset($row['C_gender']) && trim($row['C_gender']) === 'F') echo 'selected'; ?>>Female</option>
                 <option value="O" <?php if (isset($row['C_gender']) && trim($row['C_gender']) === 'O') echo 'selected'; ?>>Others</option>
                </select>  
              </div>
            <br><br>
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