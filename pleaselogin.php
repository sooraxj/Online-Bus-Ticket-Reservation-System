<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Please Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: #f2f4f6;
            color: #333;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .container {
            text-align: center;
            padding: 40px;
            border-radius: 10px;
            width:40%;
            background: #fff;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
            color: #333;
        }

        .icon {
            font-size: 80px;
            color: #ff6b6b;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 32px;
            margin-bottom: 20px;
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
            background-color: darkblue;
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

        .btn-success {
            background-color: darkgreen;
            border-color: #6c757d;
            padding: 12px 24px;
            font-size: 18px;
            letter-spacing: 1px;
            transition: background-color 0.3s, border-color 0.3s;
            font-weight: 600;
            text-transform: uppercase;
            color: #fff;
        }

        i{
            color:red;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">
            <i class="fas fa-sign-in-alt"></i>
        </div>
        <h1>Please Login to Book a Ticket</h1>
        <p>Welcome to our booking platform. To access and book bus tickets, please log in to your account.</p>
        <p>If you don't have an account yet, you can create one quickly and easily.</p>
        <a href="login.php" class="btn btn-primary">Login</a>
        <a href="Customer.php" class="btn btn-success">Sign Up</a>
    </div>
</body>
</html>
