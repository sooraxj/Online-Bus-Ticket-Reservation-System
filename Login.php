<?php
session_start();
include 'Dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Username = sanitizeInput($_POST["username"]);
    $pass = $_POST["password"];

    // Retrieve user info and hashed password from the database
    $getUserInfoQuery = "SELECT login_Password, login_type FROM tbl_Login WHERE Username = '$Username'";
    $result = $conn->query($getUserInfoQuery);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row["login_Password"];
        $loginType = $row["login_type"];

        if (password_verify($pass, $hashedPassword)) {
            // Password is correct

            if ($loginType === 'admin') {
                $updateStatusQuery = "UPDATE tbl_Login SET login_status = 1 WHERE Username = '$Username'";
                            $conn->query($updateStatusQuery);
                // Store the Username in the session for admin
                $_SESSION["username"] = $Username;
                // Redirect to admin.php for admin user
                header("Location: Welcomeadmin.php");
                exit();
            } else {
                // For normal users
                if ($loginType === 'user') {
                    // Check account status
                    $getUserStatusQuery = "SELECT C_Status,Customer_id FROM tbl_Customer WHERE Username = '$Username'";
                    $statusResult = $conn->query($getUserStatusQuery);

                    if ($statusResult->num_rows > 0) {
                        $statusRow = $statusResult->fetch_assoc();
                        $cStatus = $statusRow["C_Status"];
                        $customerID = $statusRow["Customer_id"];

                        if ($cStatus == 1) {
                            // Account is active
                            $_SESSION["Customer_id"] = $statusRow["Customer_id"];

                            // Fetch customer's first name from tbl_Customer
                            $getCustomerNameQuery = "SELECT C_fname FROM tbl_Customer WHERE Username = '$Username'";
                            $nameResult = $conn->query($getCustomerNameQuery);

                            if ($nameResult->num_rows > 0) {
                                $nameRow = $nameResult->fetch_assoc();
                                $customerFirstName = $nameRow["C_fname"];

                                // Store the customer's first name in the session
                                $_SESSION["customer_fname"] = $customerFirstName;
                            }

                            // Update login status
                            $updateStatusQuery = "UPDATE tbl_Login SET login_status = 1 WHERE Username = '$Username'";
                            $conn->query($updateStatusQuery);

                            // Store the Username in the session for normal user
                            $_SESSION["username"] = $Username;

                            // Redirect to Home.php for normal user
                            header("Location: Home.php");
                            exit();
                        } else {
                            // Account is inactive
                            displayErrorMessage("Account is inactive. Please contact administrator.");
                        }
                    }
                } else {
                    // Invalid user type
                    displayErrorMessage("Invalid user type. Please contact administrator.");
                }
            }

        } else {
            // Password is incorrect
            displayErrorMessage("Invalid credentials. Please try again.");
        }

    } else {
        // User not found
        displayErrorMessage("Invalid credentials. Please try again.");
    }
}

// Function to display error message and redirect
function displayErrorMessage($message) {
    echo "<script>alert('$message');</script>";
    echo "<script>window.location.href='login.php';</script>";
    exit();
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


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="loginstyle.css">
</head>
<body>

<div class="form-bg">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-4 col-md-4 col-sm-offset-3 col-sm-6">
                <div class="form-container">
                    <h3 class="title">Login Here</h3>
                    <form class="form-horizontal" action="login.php" method="post" >
                        <div class="form-icon">
                            <i class="fa fa-user-circle"></i>
                        </div>
                        <div class="form-group">
                            <span class="input-icon"><i class="fa fa-user"></i></span>
                            <input type="email" id="username" name="username"  class="form-control" placeholder="Username" required>
                        </div>
                        <div class="form-group">
                            <span class="input-icon"><i class="fa fa-lock"></i></span>
                            <input type="password"  class="form-control" id="password" name="password" placeholder="Password" minlength="8" maxlength="8" required>
                            <!-- <span class="forgot"><a href="forgot_pass.php">Forgot Password?</a></span> -->
                        </div>
                        <button class="btn signin">Login</button>
                        <p>Not a member? <a href="Customer.php">Create Account</a><p>
                            
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
