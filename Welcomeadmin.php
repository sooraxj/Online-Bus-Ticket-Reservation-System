<?php
session_start();
// Include Dbconnect.php or any necessary files here
include 'Dbconnect.php';

// Check if the user is logged in
if (!isset($_SESSION["username"])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome Admin</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background-color: #f1f1f1;
    }

    .container {
      text-align: center;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      background-color: #fff;
      max-width: 400px;
    }

    h1 {
      color: #333;
    }

    p {
      color: #777;
      margin-top: 10px;
    }

    .get-started-btn {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 20px;
      background-color: #007bff;
      color: #fff;
      border: none;
      border-radius: 5px;
      text-decoration: none;
      font-size: 16px;
      cursor: pointer;
    }

    .get-started-btn:hover {
      background-color: #0056b3;
    }

    .hero {
    background-image: url('hero-image.jpg');
    padding-left: 1%;
    padding-top: 15%;
    background-size: cover;
    background-position: center;
    height: 400px;
    display: flex;
    align-items: normal;
    justify-content: center;
  }
  
  .hero-content {
    text-align: center;
    color: #fff;
  }
  
  .hero-content h1 {
    font-size: 36px;
    margin-bottom: 20px;
  }
  
  .hero-content p {
    font-size: 18px;
    margin-bottom: 30px;
  }
  
  .hero-content .btn {
    display: inline-block;
    padding: 10px 20px;
    font-size: 18px;
    text-decoration: none;
    color: #fff;
    background-color: #333;
    border-radius: 5px;
  }

  h1,h2{
    color:rgb(0, 32, 32);
}
.btn{
    background: linear-gradient(90deg, #020024 0%, #090979 35%, #00d4ff 100%);
    color: white;
    padding: 5px 10px;
    text-align: center;
}
.btn:hover{
    color: #05060A;
    background: white;
    padding: 8px 12px;
    border: 2px solid  #090979;
}



  </style>
</head>
<body>
  <div class="container">
  <div class="content">
                    <section class="hero">
                        <div class="hero-content">
                          <h1>Welcome, Admin</h1>
                          <p>Manage your bus ticket reservation system with ease</p>
                          <p>you have now access the admin panel.</p>
                          <a href="Admin.php" class="btn">Get Started</a>
                        </div>
                      </section>
            </section>
    <p>I'M DONE.</p>
    <a href="logout.php" class="get-started-btn">Logout</a>
  </div>
</body>
</html>
