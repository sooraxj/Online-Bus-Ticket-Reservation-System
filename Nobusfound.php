<?php include 'header.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>No Buses Available</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: #f5f5f5;
            color: #333;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .container {
            text-align: center;
            margin-top:5%;
            padding: 40px;
            border-radius: 10px;
            width:40%;
            background: #fff;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
            color: #333;
        }

        .icon {
            font-size: 100px;
            color: #ff5733;
            margin-bottom: 20px;
        }

        .container h1 {
            font-size: 36px;
            margin-bottom: 20px;
            text-transform: uppercase;
            font-weight: 700;
            color: #333;
        }

        p {
            font-size: 18px;
            margin-bottom: 20px;
            opacity: 0.8;
            color: #555;
        }

        .btn-primary {
            background-color: ;
            border-color: #007bff;
            padding: 12px 24px;
            font-size: 18px;
            letter-spacing: 1px;
            transition: background-color 0.3s, border-color 0.3s;
            font-weight: 600;
            text-transform: uppercase;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        i{
            color:red;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">
            <i class="fas fa-exclamation-circle"></i>
        </div>
        <h1>No Buses Available</h1>
        <p>Unfortunately, there are no buses scheduled on the selected journey date.</p>
        <p>Please choose a different date or route.</p>
        <a href="home.php" class="btn btn-dark">Go Back</a>
    </div>
</body>
</html>
