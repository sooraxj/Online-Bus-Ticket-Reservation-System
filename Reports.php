<?php
  include 'Dbconnect.php';
  session_start();
  if (!isset($_SESSION["username"])) {
      header("Location: login.php");
      exit();
  }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to top, #cfd9df 0%, #e2ebf0 100%);
        }

        .reports-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .report-box {
            width: 270px;
            height: 150px;
            background-color: #ffffff;
            border: 2px solid #090979;
            margin: 20px;
            text-align: center;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
            overflow: hidden;
        }

        .report-box:hover {
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            transform: scale(1.05);
        }

        .report-content {
            margin-top:5%;
            padding: 20px;
        }
        
        .report-content p{
            font-weight:bold;
            font-size:22px;
        }

        .report-button {
            /* display: none; */
            position: absolute;
            bottom: 25px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1;
            background: linear-gradient(111.4deg, rgba(7,7,9,1) 6.5%, rgba(27,24,113,1) 93.2%);
        }

        .report-box:hover .report-button {
            display: block;
            background-image: linear-gradient(86.3deg, rgba(0,119,182,1) 3.6%, rgba(8,24,68,1) 87.6%);

        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .header {
            position: absolute;
            margin-top:-1%;
            margin-left:90%;
            padding: 10px;
        }

        .home {
            background: linear-gradient(111.4deg, rgba(7,7,9,1) 6.5%, rgba(27,24,113,1) 93.2%);
        }

    </style>
    <title>Reports</title>
</head>
<body>
<div class="header">
   <a href="admin.php" class="btn btn-primary home">Home</a>
</div>
<div class="container mt-5">
    <h1 class="text-center mb-4">Reports Page</h1>

    <div class="reports-container">
        <div class="report-box">
            <div class="report-content">
                <p>Driver Reports</p>
            </div>
            <a href="DriverReport.php" class="btn btn-primary report-button">View Details</a>
        </div>

        <div class="report-box">
            <div class="report-content">
                <p>Customer Reports</p>
            </div>
            <a href="CustomerReports.php" class="btn btn-primary report-button">View Details</a>
        </div>

        <div class="report-box">
            <div class="report-content">
                <p>Bus Reports</p>
            </div>
            <a href="BusReports.php" class="btn btn-primary report-button">View Details</a>
        </div>

        <div class="report-box">
            <div class="report-content">
                <p>Route Reports</p>
            </div>
            <a href="RouteReports.php" class="btn btn-primary report-button">View Details</a>
        </div>

        <div class="report-box">
            <div class="report-content">
                <p>Stop Reports</p>
            </div>
            <a href="StopReports.php" class="btn btn-primary report-button">View Details</a>
        </div>

        <div class="report-box">
            <div class="report-content">
                <p>Driver Assign Reports</p>
            </div>
            <a href="DriverAssignReports.php" class="btn btn-primary report-button">View Details</a>
        </div>

        <div class="report-box">
            <div class="report-content">
                <p>Bus Schedule Reports</p>
            </div>
            <a href="BusScheduleReports.php" class="btn btn-primary report-button">View Details</a>
        </div>

        
        <div class="report-box">
            <div class="report-content">
                <p>Booking Reports</p>
            </div>
            <a href="BookingReports.php" class="btn btn-primary report-button">View Details</a>
        </div>

        
        <div class="report-box">
            <div class="report-content">
                <p>Cancellation Reports</p>
            </div>
            <a href="CancellationReports.php" class="btn btn-primary report-button">View Details</a>
        </div>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
