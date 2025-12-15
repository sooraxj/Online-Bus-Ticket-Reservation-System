<?php
include 'header.php';
include 'Dbconnect.php';

$customer_id = $_SESSION['Customer_id'];
$bookingsSql = "SELECT DISTINCT t.Ticket_id, t.Ticket_number, t.Journey_date,t.Ticket_status, bc.Source, bc.Destination, p.Refund_amount
                FROM tbl_ticket t
                JOIN tbl_booking_master bm ON t.Booking_master_id = bm.Booking_master_id
                JOIN tbl_booking_child bc ON bm.Booking_master_id = bc.Booking_master_id
                LEFT JOIN tbl_payment p ON t.Payment_id = p.Payment_id
                WHERE t.Customer_id = $customer_id";


$bookingsResult = $conn->query($bookingsSql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
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
            border-spacing: 10px; 
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
            background-color: #f2f2f2;
        }

        .view-ticket-button, .cancel-ticket-button {
            background-color: #4a6378;
            color: #fff;
            border: none;
            padding:3px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .view-ticket-button:hover {
            background-color: #53b33b;
            color: white;
            text-decoration: none;
        }
     .cancel-ticket-button:hover {
            background-color: #ba2f2f;
            color: white;
            text-decoration: none;
        }

        /* Modal */
.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
}

/* Updated Modal Styles */
.modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    border-radius: 5px;
    max-width: 400px; 
    text-align: center;
}

.close {
    color: #aaa;
    float: right;
    font-size: 62px; 
    font-weight: bold;
    color:red;
    margin-right: -10px; 
}



.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

.modal-buttons {
    margin-top: 20px;
}

.cancel-button {
    background-color: #ba2f2f;
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.confirm-button {
    background-color: #53b33b;
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.cancel-button:hover {
    background-color: #ff4444;
}

.confirm-button:hover {
    background-color: #66bb6a;
}


    </style>
</head>
<body>
<div class="container">
    <h2>My Bookings</h2>
    <table class="table table-bordered">
        <thead class="thead-light">
            <tr>
                <th>Ticket Number</th>
                <th>Journey Date</th>
                <th>Source</th>
                <th>Destination</th>
                <th>Status</th>
                <th>Ticket</th>
                <th>Edit</th>
                <th>Action</th>
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
                        <?php
                        if ($booking['Ticket_status'] == 1) {
                            echo "Confirmed";
                        } elseif ($booking['Ticket_status'] == 0) {
                            echo "Cancelled";
                        } else {
                            echo "Unknown";
                        }
                        ?>
                    </td>
                    <td><a href="ticket.php?ticket_id=<?php echo $booking['Ticket_id']; ?>" class="view-ticket-button"><i class="fas fa-ticket-alt"></i> View Ticket</a></td>
                    <td><a href="edit_request.php?ticket_id=<?php echo $booking['Ticket_id']; ?>" class="view-ticket-button"><i class="fas fa-edit"></i> Edit Ticket</a></td>
                    <td>
                    <?php
                        if ($booking['Ticket_status'] == 0 && isset($booking['Refund_amount'])) {
                            echo '<a href="refund.php?refund_amount=' . $booking['Refund_amount'] . '" class="cancel-ticket-button"><i class="fas fa-money-bill"></i> Refund</a>';
                        } else {
                            echo '<a href="javascript:void(0);" class="cancel-ticket-button" onclick="confirmCancel(' . $booking['Ticket_id'] . ');"><i class="fas fa-times-circle"></i> Cancel Ticket</a>';
                        }
                        ?>
                </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<div id="confirmationModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <p>Are you sure you want to cancel this ticket?</p>
        <div class="modal-buttons">
            <button id="cancelButton" class="cancel-button">Cancel</button>
            <button id="confirmButton" class="confirm-button">Confirm</button>
        </div>
    </div>
</div>
<script>
function confirmCancel(ticketId) {
    var modal = document.getElementById('confirmationModal');
    var cancelButton = document.getElementById('cancelButton');
    var confirmButton = document.getElementById('confirmButton');

    cancelButton.onclick = function() {
        modal.style.display = 'none';
    };

    confirmButton.onclick = function() {
        window.location.href = 'cancel_ticket.php?ticket_id=' + ticketId;
    };

    modal.style.display = 'block';
}
</script>
</body>
 </html>
