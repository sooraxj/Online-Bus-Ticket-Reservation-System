<?php
$successMessage = "";

include 'Dbconnect.php';
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['ticket_id'])) {
    $ticket_id = $_GET['ticket_id'];

    $ticketDetailsSql = "SELECT t.*, p.Payment_date, p.Payment_status FROM tbl_ticket t
                        JOIN tbl_payment p ON t.Payment_id = p.Payment_id
                        WHERE t.Ticket_id = $ticket_id";
    $ticketDetailsResult = $conn->query($ticketDetailsSql);

    if ($ticketDetailsResult->num_rows > 0) {
        $ticketDetails = $ticketDetailsResult->fetch_assoc();
        $ticket_number = $ticketDetails['Ticket_number'];
        $journey_date = $ticketDetails['Journey_date'];
        $ticket_status = $ticketDetails['Ticket_status'];
        $payment_date = $ticketDetails['Payment_date'];
        $payment_status = $ticketDetails['Payment_status'];

        $booking_master_id = $ticketDetails['Booking_master_id'];
        $passengerDetailsSql = "SELECT * FROM tbl_booking_child WHERE Booking_master_id = $booking_master_id";
        $passengerDetailsResult = $conn->query($passengerDetailsSql);

        $bookingMasterDetailsSql = "SELECT Total_seats FROM tbl_booking_master WHERE Booking_master_id = $booking_master_id";
        $bookingMasterDetailsResult = $conn->query($bookingMasterDetailsSql);
        $bookingMasterDetails = $bookingMasterDetailsResult->fetch_assoc();
        $total_seats = $bookingMasterDetails['Total_seats'];

        $busRouteIdSql = "SELECT Bus_route_id FROM tbl_booking_master WHERE Booking_master_id = $booking_master_id";
        $busRouteIdResult = $conn->query($busRouteIdSql);
        $busRouteIdDetails = $busRouteIdResult->fetch_assoc();
        $bus_route_id = $busRouteIdDetails['Bus_route_id'];


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $journey_date = $_POST['journey_date'];
            $ticket_status = $_POST['ticket_status'];
            $payment_date = $_POST['payment_date'];
            $payment_status = $_POST['payment_status'];
            $total_seats = $_POST['total_seats'];
            $ticket_number = $_POST['ticket_number'];

            $updateTicketSql = "UPDATE tbl_ticket SET Journey_date = '$journey_date', Ticket_status = '$ticket_status', Ticket_number = '$ticket_number'
                                WHERE Ticket_id = $ticket_id";
            $conn->query($updateTicketSql);

            $updatePaymentSql = "UPDATE tbl_payment SET Payment_date = '$payment_date', Payment_status = '$payment_status'
                                 WHERE Payment_id = {$ticketDetails['Payment_id']}";
            $conn->query($updatePaymentSql);

            $updateBookingChildSql = "UPDATE tbl_booking_master SET Total_seats = $total_seats
                                     WHERE Booking_master_id = {$ticketDetails['Booking_master_id']}";
            $conn->query($updateBookingChildSql);

            foreach ($_POST['passengers'] as $passenger_id => $passenger) {
                $passenger_name = $passenger['name'];
                $passenger_age = $passenger['age'];
                $passenger_gender = $passenger['gender'];
                $passenger_seat_number = $passenger['seat_number'];
                $passenger_source = $passenger['source'];
                $passenger_destination = $passenger['destination'];
                $Booking_status = $passenger['Booking_status'];


                $updatePassengerSql = "UPDATE tbl_booking_child SET Passenger_name = '$passenger_name', Age = $passenger_age, 
                                       Gender = '$passenger_gender', Seat_number = '$passenger_seat_number', Source = '$passenger_source',
                                       Destination = '$passenger_destination',Booking_status = '$Booking_status'
                                       WHERE Booking_child_id = $passenger_id";
                $conn->query($updatePassengerSql);
            }

         
            $busRouteIdSql = "SELECT Bus_route_id FROM tbl_booking_master WHERE Booking_master_id = $booking_master_id";
            $busRouteIdResult = $conn->query($busRouteIdSql);
            $busRouteIdDetails = $busRouteIdResult->fetch_assoc();
            $bus_route_id = $busRouteIdDetails['Bus_route_id'];

            $successMessage = "Details updated successfully!";

            echo '<script>
            setTimeout(function() {
                window.location.href = "Abooking_view.php";

            }, 2000);
        </script>';
        }
    } else {
        echo 'Ticket not found.';
        exit();
    }
} else {
    echo 'Ticket ID not provided.';
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin View Ticket</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 20px;
        }
        .passenger-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .passenger-table th {
            background-color: #343a40;
            color: white;
        }
        .passenger-table td {
            vertical-align: middle;
        }
        .status-dropdown {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">Admin View Ticket</h2>
          <?php if (!empty($successMessage)) { ?>
            <div class="alert alert-success" role="alert">
                <?php echo $successMessage; ?>
            </div>
        <?php } ?>
        <form method="post">
            <div class="form-group">
                <label for="ticket_number">Ticket Number:</label>
                <input type="text" class="form-control" name="ticket_number" value="<?php echo $ticket_number; ?>">
            </div>
            <div class="form-group">
                <label for="journey_date">Journey Date:</label>
                <input type="date" class="form-control" name="journey_date" value="<?php echo $journey_date; ?>">
            </div>
            <div class="form-group">
                <label for="ticket_status">Ticket Status:</label>
                <select class="form-control status-dropdown" name="ticket_status">
                    <option value="1" <?php if ($ticket_status === '1') echo 'selected'; ?>>Confirmed</option>
                    <option value="0" <?php if ($ticket_status === '0') echo 'selected'; ?>>Cancelled</option>
                </select>
            </div>
            <div class="form-group">
                <label for="payment_date">Payment Date:</label>
                <input type="date" class="form-control" name="payment_date" value="<?php echo $payment_date; ?>">
            </div>
            <div class="form-group">
                <label for="payment_status">Payment Status:</label>
                <select class="form-control status-dropdown" name="payment_status">
                    <option value="1" <?php if ($payment_status === '1') echo 'selected'; ?>>Paid</option>
                    <option value="0" <?php if ($payment_status === '0') echo 'selected'; ?>>Refund</option>
                </select>
            </div>
            <div class="form-group">
                <label for="total_seats">Total Seats:</label>
                <input type="number" class="form-control" name="total_seats" value="<?php echo $total_seats; ?>">
            </div>
            <div class="passenger-info">
                <h4>Passenger Information</h4>
                <table class="table passenger-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Seat Number</th>
                            <th>Source</th>
                            <th>Destination</th>
                            <th>Booking Status</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($passenger = $passengerDetailsResult->fetch_assoc()) { ?>
                            <tr>
                                <td><input type="text" class="form-control" name="passengers[<?php echo $passenger['Booking_child_id']; ?>][name]" value="<?php echo $passenger['Passenger_name']; ?>"></td>
                                <td><input type="number" class="form-control" name="passengers[<?php echo $passenger['Booking_child_id']; ?>][age]" value="<?php echo $passenger['Age']; ?>"></td>
                                <td>
                                    <select class="form-control" name="passengers[<?php echo $passenger['Booking_child_id']; ?>][gender]">
                                        <option value="M" <?php if ($passenger['Gender'] === 'M') echo 'selected'; ?>>Male</option>
                                        <option value="F" <?php if ($passenger['Gender'] === 'F') echo 'selected'; ?>>Female</option>
                                        <option value="O" <?php if ($passenger['Gender'] === 'O') echo 'selected'; ?>>Others</option>
                                    </select>
                                </td>
                                <td><input type="number" class="form-control" name="passengers[<?php echo $passenger['Booking_child_id']; ?>][seat_number]" value="<?php echo $passenger['Seat_number']; ?>"></td>
                                <td><input type="text" class="form-control" name="passengers[<?php echo $passenger['Booking_child_id']; ?>][source]" value="<?php echo $passenger['Source']; ?>"></td>
                                <td><input type="text" class="form-control" name="passengers[<?php echo $passenger['Booking_child_id']; ?>][destination]" value="<?php echo $passenger['Destination']; ?>"></td>
                                <td><input type="number" class="form-control" name="passengers[<?php echo $passenger['Booking_child_id']; ?>][Booking_status]" value="<?php echo $passenger['Booking_status']; ?>"></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <button type="submit" class="btn btn-primary">Update Ticket</button>
        </form>
    </div>
</body>
</html>
