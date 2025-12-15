
<?php include 'header.php';

?>

<!DOCTYPE html>
<html>
<head>
    <title>Refund</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #080156;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
            text-align: left;
        }

        .rules {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin-top: 300px;
            text-align: left;
        }

        h2 {
            color: #080156;
            font-size: 28px;
            margin-bottom: 20px;
        }

        p {
            color: #333;
            font-size: 18px;
            margin: 10px 0;
        }

        h3 {
            color: #080156;
            font-size: 24px;
            margin-top: 20px;
        }

        ul {
            list-style: disc;
            margin-left: 20px;
        }

        .btn {
            background-color: #080156;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #333;
        }

        .pbtn {
            margin-left:43%;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 18px;
            background-color: green;
            color: #fff;
            transition: background-color 0.3s;
        }

        .pbtn:hover{
            background-color:white;
            text-decoration: none;
            color:green;
        }

        /* Add this CSS to style the refund amount */
.refund-amount {
    font-size: 24px; /* Adjust the font size as needed */
    background-color: #4CAF50; /* Background color */
    color: #fff; /* Text color */
    padding: 10px 20px; /* Padding */
    border-radius: 5px; /* Rounded corners */
    display: inline-block; /* Display as inline block */
    margin-top: 10px; /* Top margin */
}

    </style>
</head>
<body>
    <header>
        <h1>Refund Information</h1>
    </header>
    <div class="container text-center">
    <h2 style="color: #080156; font-size: 32px; margin-top: 20px;">Cancelled!</h2>
    <div style="background-color: #f7f7f7; padding: 20px; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); margin-top: 20px;">
        <h3 style="color: #080156; font-size: 28px; margin-bottom: 20px;">Your ticket has been cancelled successfully.</h3>
        <p style="color: #333; font-size: 20px; margin: 10px 0;">The refund will be credited to your account within 7 working days.</p>
        <?php
            if (isset($_GET['refund_amount'])) {
                $refundAmount = $_GET['refund_amount'];
                echo '<div class="refund-amount">Refund Amount: ₹' . number_format($refundAmount, 2) . '</div>';
            }else {
                echo "Refund amount not available.";
            }
            ?>
            <br>
        <a href="Home.php" class="btn" style="text-decoration: none; display: inline-block; margin-top: 20px; background-color: #080156; color: #fff; border: none; padding: 12px 30px; border-radius: 5px; font-size: 18px; transition: background-color 0.3s;">Back to Home</a>
    </div>
</div>
<br>
<a class="pbtn"href="#rules"> View Cancellation Policy</a><br>

    <div class="rules" id="rules">
        <h3 align="center">Cancellation Policy</h3><br>
        <p>Cancellation of tickets can be done up to two hours before the scheduled departure time from the starting place or before the charting time, whichever is earlier.</p>
        <ul>
            <li>a.) Refund in failed transactions will be made within seven banking days.</li>
            <li>b.) If any service having advance reservation facility is canceled, the same will be intimated to all reserved passengers from the concerned unit from where the bus operates. In case of service cancellation, alternate traveling facility will be arranged by KSRTC. Under any circumstance, if alternate arrangements cannot be made, the same will be intimated to all reserved passengers. In such a case, the passenger is eligible for a hundred percent refund of the ticket fare (Excluding reservation/payment gateway charges). After cancellation, the amount will be credited back only to the bank account from which payment was made. Change of account number is not possible while effecting a refund.</li>
            <li>c.) Cancellation Fees: The cancellation slabs (excluding the reservation fee, which is not refundable) are as shown below:</li>
        </ul>
        <h3>Refund Rules:</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Cancellation Fee</th>
                    <th>Duration</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>No Cancellation Fee</td>
                    <td>Before 72 Hrs</td>
                </tr>
                <tr>
                    <td>Fee at 10% of the Fare</td>
                    <td>Between 72 hours and 48 hours before the departure time.</td>
                </tr>
                <tr>
                    <td>Fee at 25% of the Fare</td>
                    <td>Between 48 hours and 24 hours before the departure time.</td>
                </tr>
                <tr>
                    <td>Fee at 40% of the Fare</td>
                    <td>Between 24 hours and 12 hours before the departure time.</td>
                </tr>
                <tr>
                    <td>Fee at 50% of the Fare</td>
                    <td>Between 12 hours and 2 hours before the departure time.</td>
                </tr>
                <tr>
                    <td>No refund</td>
                    <td>Less than two hours before the departure time and at/after the departure time.</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
