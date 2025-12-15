<?php
session_start();
include 'Dbconnect.php';

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$loggedInUsername = $_SESSION["username"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $enteredUsername = $_POST["username"];

    if ($enteredUsername != $loggedInUsername) {
        echo "<script>
        alert('Invalid username. Please enter your own username.');
        setTimeout(function(){
            window.location.href = 'forgot_pass.php';
        }, 000);
        </script>";

    } else {
        $sql = "SELECT Username FROM tbl_login WHERE Username = '$enteredUsername' AND Login_type = 'user'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Alliance</title>
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
                <style>
                    body {
                        background-color: #CDDCDC;
                        background-image: radial-gradient(at 50% 100%, rgba(255,255,255,0.50) 0%, rgba(0,0,0,0.50) 100%), linear-gradient(to bottom, rgba(255,255,255,0.25) 0%, rgba(0,0,0,0.25) 100%);
                        background-blend-mode: screen, overlay;
                    }

                    .container {
                        margin-top: 100px;
                    }

                    .card {
                        border: 1px solid #dcdcdc;
                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                        background-image: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
                        color: #1B394D;
                    }

                    .card-header {
                        background: linear-gradient( 111.4deg,  rgba(7,7,9,1) 6.5%, rgba(27,24,113,1) 93.2% );
                        color: #fff;
                    }

                    .btn-primary {
                        background: linear-gradient( 111.4deg,  rgba(7,7,9,1) 6.5%, rgba(27,24,113,1) 93.2% );
                        border: none;
                    }

                    .btn-primary:hover {
                        background-image: linear-gradient( 86.3deg,  rgba(0,119,182,1) 3.6%, rgba(8,24,68,1) 87.6% );
                    }

                    .alert {
                        margin-top: 20px;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">Change Password</div>
                                <div class="card-body">
                                    <form method="post" action="">
                                        <div class="form-group">
                                            <label for="new_password">New Password:</label>
                                            <input type="password" class="form-control" name="new_password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" minlength="8" maxlength="8" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"  required>
                                        </div>
                                        <div class="form-group">
                                            <label for="confirm_password">Confirm Password:</label>
                                            <input type="password" class="form-control" name="confirm_password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" minlength="8" maxlength="8" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required>
                                        </div>
                                        <input type="hidden" name="username" value="<?php echo $enteredUsername; ?>">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </body>
            </html>
            <?php

            // Process the new password if the form is submitted
            if (isset($_POST["new_password"]) && isset($_POST["confirm_password"])) {
                $new_password = $_POST["new_password"];
                $confirm_password = $_POST["confirm_password"];

                if ($new_password === $confirm_password) {
                    // Hash the new password
                    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
                    
                    // Update the hashed password in the database
                    $update_sql = "UPDATE tbl_login SET login_Password = '$hashed_password' WHERE Username = '$enteredUsername' AND Login_type = 'user'";
                    if ($conn->query($update_sql)) {
                        ?>
                        <div class="container mt-3">
                            <div class="alert alert-success">Password reset successful!</div>
                        </div>
                        <?php
                        // Redirect to the login page after 2 seconds
                        header("refresh:2;url=login.php");
                        exit(); // Stop further execution
                    } else {
                        ?>
                        <div class="container mt-3">
                            <div class="alert alert-danger">Password reset failed.</div>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="container mt-3">
                        <div class="alert alert-danger">Passwords do not match. Please try again.</div>
                    </div>
                    <?php
                }
            }
        } else {
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Forgot Password</title>
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
                <style>
                    body {
                        background-color: #CDDCDC;
                        background-image: radial-gradient(at 50% 100%, rgba(255,255,255,0.50) 0%, rgba(0,0,0,0.50) 100%), linear-gradient(to bottom, rgba(255,255,255,0.25) 0%, rgba(0,0,0,0.25) 100%);
                        background-blend-mode: screen, overlay;
                    }

                    .container {
                        margin-top: 100px;
                    }

                    .card {
                        border: 1px solid #dcdcdc;
                        background-image: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
                        color: #1B394D;
                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                    }

                    .card-header {
                        background: linear-gradient( 111.4deg,  rgba(7,7,9,1) 6.5%, rgba(27,24,113,1) 93.2% );
                        color: #fff;
                    }

                    .btn-primary {
                        background: linear-gradient( 111.4deg,  rgba(7,7,9,1) 6.5%, rgba(27,24,113,1) 93.2% );
                        border: none;
                    }

                    .btn-primary:hover {
                        background-image: linear-gradient( 86.3deg,  rgba(0,119,182,1) 3.6%, rgba(8,24,68,1) 87.6% );
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">Change Password</div>
                                <div class="card-body">
                                    <form method="post" action="">
                                        <div class="form-group">
                                            <label for="username">Username:</label>
                                            <input type="text" class="form-control" name="username" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </body>
            </html>
            <?php
            echo '<div class="container mt-3"><div class="alert alert-danger">Username not found in our records.</div></div>';
        }
    }
} else {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Alliance</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <style>
            body {
                background-color: #CDDCDC;
                background-image: radial-gradient(at 50% 100%, rgba(255,255,255,0.50) 0%, rgba(0,0,0,0.50) 100%), linear-gradient(to bottom, rgba(255,255,255,0.25) 0%, rgba(0,0,0,0.25) 100%);
                background-blend-mode: screen, overlay;
            }

            .container {
                margin-top: 100px;
            }

            .card {
                border: 1px solid #dcdcdc;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                background-image: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
                color: #1B394D;
            }

            .card-header {
                background: linear-gradient( 111.4deg,  rgba(7,7,9,1) 6.5%, rgba(27,24,113,1) 93.2% );
                color: #fff;
            }

            .btn-primary {
                background: linear-gradient( 111.4deg,  rgba(7,7,9,1) 6.5%, rgba(27,24,113,1) 93.2% );
                border: none;
            }

            .btn-primary:hover {
                background-image: linear-gradient( 86.3deg,  rgba(0,119,182,1) 3.6%, rgba(8,24,68,1) 87.6% );
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Change Password</div>
                        <div class="card-body">
                            <form method="post" action="">
                                <div class="form-group">
                                    <label for="username">Username:</label>
                                    <input type="text" class="form-control" name="username" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
}
?>
