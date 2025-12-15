<html>
    <head>
        <title> Customer Registration</title>
       <style>
        *{
          margin: 0%;
          padding: 0%;
        }

        .main{
      background: url("BG1.JPG");
      background-repeat: no-repeat;
      background-size: 100%;
      background-position: center;
    /* background-color: rgb(186, 186, 228); */
    height: 100%;
    width: 100%;
    padding-top: 10%;
    }
    .form-group,h1{
     margin-left: 120px;
     justify-content: center;
     /* margin-top: 20px; */
      /* margin-bottom: 15px; */
    }
  
    label {
      display: block;
      margin-bottom: 5px;
    }
  
    input[type="text"],
    input[type="number"],
    input[type="email"],
    input[type="tel"],
    input[type="date"],
    input[type="password"],
    select
    {
      width: 200px;
      padding: 8px;
      border-radius: 10px;
    
    }
  
    input[type="submit"],
    input[type="reset"] {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: #fff;
      border: none;
      cursor: pointer;
      display: flexbox;
      justify-content: space-between;
    }

    input[type="submit"]:hover,
    input[type="reset"]:hover{
      background-color: rgb(214, 0, 0);

    }

    .form{
      background-color: #ffffffbd;
      width: 55%;
      height: 90%;
      position: relative;
      left:23%;
      justify-content: center;
      padding-top: 5%;     
      border-radius: 10px;


    }

    .form-row ,h1{
  display: flex;
  justify-content: center;
  margin-bottom: 10px;
  margin-top: 10px;
  margin-left: -10%;
  padding-right: 5%;
}

h1{
  display: flex;
  justify-content: center;
  margin-bottom: 10px;
 margin-left: 3%;
 padding-bottom: 2%;
}

.form-group {
  display: flex;
  flex-direction: column;
  width: 30%;
}

.form-group label {
  text-align: left;
  margin-bottom: 5px;
}

.form-group input,
.form-group select {
  width: 100%;
}
</style> 
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
  </script>
    </head>
    <body> 
        <div class="main">
          <div class="form">
          <h1>Customer Registration</h1>
          <form>
            <div class="form-row">
              <div class="form-group">
                <label for="Username">E-mail:</label>
                <input type="email" id="username" name="Username" required>
              </div>
              <div class="form-group">
                <label for="firstName">First Name:</label>
                <input type="text" id="firstName" name="C_fname" required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="middleName">Middle Name:</label>
                <input type="text" id="middleName" name="C_mname">
              </div>
              <div class="form-group">
                <label for="lastName">Last Name:</label>
                <input type="text" id="lastName" name="C_lname" required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="city">City:</label>
                <input type="text" id="city" name="C_city" required>
              </div>
              <div class="form-group">
                <label for="district">District:</label>
                <select id="district" name="C_dist" onchange="handleDistrictChange(this.value)" required>
                  <option value="">Select District</option>
                  <option value="district1">Thiruvananthapuram</option>
                  <option value="district2">Kollam</option>
                  <option value="district3">Pathanamthitta</option>
                  <option value="district4">Alappuzha</option>
                  <option value="district5">Kottayam</option>
                  <option value="district6">Idukki</option>
                  <option value="district7">Ernakulam</option>
                  <option value="district8">Thrissur</option>
                  <option value="district9">Palakkad</option>
                  <option value="district10">Malappuram</option>
                  <option value="district11">Kozhikode</option>
                  <option value="district12">Wayanad</option>
                  <option value="district13">Kannur</option>
                  <option value="district14">Kasargod</option>
                  <option value="others">Other</option>
                </select>
              </div>
              </div>
              <div class="form-row">            
              <div class="form-group" id="otherDistrictContainer" style="display: none;">
                <label for="otherDistrict">Other District:</label>
                <input type="text" id="otherDistrict" name="C_dist">
              </div>
              <div class="form-group">
                <label for="pinCode">Pin Code:</label>
                <input type="number" id="pinCode" name="C_pin" pattern="[0-9]{6}" minlength="6" maxlength="6"  title="Maximum 6 Digits" required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="street">Street:</label>
                <input type="text" id="street" name="C_street" required>
              </div>
              <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="tel" id="phone" name="C_phone" pattern="[0-9]{10}" title="Maximum 10 Digits"  minlength="10" maxlength="10" required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="dob">Date of Birth:</label>
                <input type="date" id="dob" name="C_dob" required>
              </div>
              <div class="form-group">
                <label for="gender">Gender:</label>
                <select id="gender" name="C_gender" required>
                  <option value="M">Male</option>
                  <option value="F">Female</option>
                  <option value="O">Other</option>
                </select>
              </div>
            </div>
            <div class="form-row">
            <div class="form-group">
                <label for="status">Status:</label>
                <select id="status" name="C_status" required>
                  <option value="1">Active</option>
                  <option value="0">Inactive</option>
                </select>
              </div>
              <div class="form-group">
                <label for="login_Password	">Password:</label>
                <input type="password" id="login_Password	" name="login_Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <input type="submit" name="submit" value="Submit">
              </div>
              <div class="form-group">
                <input type="reset" value="Reset">
              </div> 
            </div>
          </form>
        </div>
      </div>          
    </body>
</html>










