<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Cancelled</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(to bottom, #f8f9fa, #e2e6ea);
            font-family: Arial, sans-serif;
        }
       
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
             margin-top:-5%;
        }
        .card {
            background-color: #fff;
            border-radius: 15px;
            padding: 30px;
            width: 400px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.2);
        }
        .card-body {
            text-align: center;
            font-size: 20px;
        }
        .btn-primary {
            background-color: #dc3545;
            border: none;
            transition: background-color 0.3s ease-in-out;
            color: #fff;
        }
        .btn-primary:hover {
            background-color: #c82333;
        }
        .btn-primary:focus {
            background-color: #c82333;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.5);
        }
        h1 {
            font-size: 36px;
            margin-bottom: 20px;
        }
        img {
            max-width: 100%;
            height: auto;
            margin-bottom: 20px;
            border: 2px solid #dc3545;
            border-radius: 10px;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

    <div class="container">
        <div class="card">
            <div class="card-body">
                <img src="img/cancelled-ticket-image.png" alt="Cancelled Ticket">
                <p>We apologize, but the ticket you are trying to cancel has already been cancelled.</p>
                <a href="Home.php" class="btn btn-danger">Go Back</a>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
