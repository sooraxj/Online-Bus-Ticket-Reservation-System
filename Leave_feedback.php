<?php
include 'header.php';
include 'Dbconnect.php';

$customer_id = $_SESSION['Customer_id'];

$bookingsSql = "SELECT DISTINCT t.Ticket_id, t.Ticket_number, t.Ticket_status, t.Journey_date, bc.Source, bc.Destination
                FROM tbl_ticket t
                JOIN tbl_booking_master bm ON t.Booking_master_id = bm.Booking_master_id
                JOIN tbl_booking_child bc ON bm.Booking_master_id = bc.Booking_master_id
                WHERE t.Customer_id = $customer_id";

$bookingsResult = $conn->query($bookingsSql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alliance</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }

        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: separate; 
            border-spacing: 10px; /* Add spacing between table cells */
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2eee2;
        }

        .feedback-button{
            background-color: #4a6378;
            color: #fff;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .feedback-button:hover {
            background-color: #53b33b;
            color: white;
            text-decoration: none;
        }

    </style>
</head>
<body>
    <div class="container">
        <h2>Feedbacks</h2>
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>Ticket Number</th>
                    <th>Journey Date</th>
                    <th>Source</th>
                    <th>Destination</th>
                    <th>feedback</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($booking = $bookingsResult->fetch_assoc()) { ?>
        <tr>
        <td><?php echo $booking['Ticket_number']; ?></td>
        <td><?php echo $booking['Journey_date']; ?></td>
        <td><?php echo $booking['Source']; ?></td>
        <td><?php echo $booking['Destination']; ?></td>
        <td>
            <?php if ($booking['Ticket_status'] == 0) { ?>
                <button class="feedback-button" disabled style="background-color: #ccc; color: #666;">Leave Feedback</button>
            <?php } else { ?>
                <a href="Feedback.php?ticket_id=<?php echo $booking['Ticket_id']; ?>" class="feedback-button">Leave Feedback</a>
            <?php } ?>
        </td>
    </tr>
<?php } ?>

            </tbody>
        </table>
    </div>
</body>
</html>
