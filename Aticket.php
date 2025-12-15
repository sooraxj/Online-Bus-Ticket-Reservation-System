<?php
include 'Dbconnect.php';
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}
// Get the ticket_id from the URL parameter
if (isset($_GET['ticket_id'])) {
    $ticket_id = $_GET['ticket_id'];

    // Fetch ticket details from tbl_ticket using ticket_id
    $ticketDetailsSql = "SELECT t.Booking_master_id, t.Payment_id, t.Ticket_number, t.Journey_date, t.Journey_stime, t.Journey_etime, t.Ticket_status, 
                                p.Payment_date, p.Payment_status,
                                bm.Booking_date, bm.Total_fare, bm.Distance,bm.Total_seats
                         FROM tbl_ticket t
                         JOIN tbl_payment p ON t.Payment_id = p.Payment_id
                         JOIN tbl_booking_master bm ON t.Booking_master_id = bm.Booking_master_id
                         WHERE t.Ticket_id = $ticket_id";
    $ticketDetailsResult = $conn->query($ticketDetailsSql);

    if ($ticketDetailsResult->num_rows > 0) {
        $ticketDetails = $ticketDetailsResult->fetch_assoc();
        // Extract ticket details into variables
        $booking_master_id = $ticketDetails['Booking_master_id'];
        $payment_id = $ticketDetails['Payment_id'];
        $ticket_number = $ticketDetails['Ticket_number'];
        $journey_date = $ticketDetails['Journey_date'];
        $journey_stime = date('H:i', strtotime($ticketDetails['Journey_stime']));
        $journey_etime = date('H:i', strtotime($ticketDetails['Journey_etime']));
        $ticket_status = $ticketDetails['Ticket_status'] == 1 ? 'Confirmed' : 'Cancelled';
        $payment_date = $ticketDetails['Payment_date'];
        $payment_status = $ticketDetails['Payment_status'] == 1 ? 'Paid' : 'Refund';
        $booking_date = $ticketDetails['Booking_date'];
        $total_fare = $ticketDetails['Total_fare'];
        $distance = $ticketDetails['Distance'];
        $total_seats = $ticketDetails['Total_seats'];

        // Fetch additional details from tbl_booking_child using booking_master_id
        $additionalDetailsSql = "SELECT Source, Destination FROM tbl_booking_child WHERE Booking_master_id = $booking_master_id";
        $additionalDetailsResult = $conn->query($additionalDetailsSql);

        if ($additionalDetailsResult->num_rows > 0) {
            $additionalDetails = $additionalDetailsResult->fetch_assoc();
            $source = $additionalDetails['Source'];
            $destination = $additionalDetails['Destination'];
        }

        // Fetch bus-related information
        $busInfoSql = "SELECT bd.Driver_id, bd.Bus_id, r.Route_name
                       FROM tbl_booking_master bm
                       JOIN tbl_Bus_route br ON bm.Bus_route_id = br.Bus_route_id
                       JOIN tbl_Route r ON br.Route_id = r.Route_id
                       JOIN tbl_driver_bus bd ON br.Driver_bus_id = bd.Driver_bus_id
                       WHERE bm.Booking_master_id = $booking_master_id";
        $busInfoResult = $conn->query($busInfoSql);

        if ($busInfoResult->num_rows > 0) {
            $busInfo = $busInfoResult->fetch_assoc();
            $driver_id = $busInfo['Driver_id'];
            $bus_id = $busInfo['Bus_id'];
            $route_name = $busInfo['Route_name'];

            // Fetch driver information
            $driverInfoSql = "SELECT D_fname, D_phone FROM tbl_driver WHERE Driver_id = $driver_id";
            $driverInfoResult = $conn->query($driverInfoSql);

            if ($driverInfoResult->num_rows > 0) {
                $driverInfo = $driverInfoResult->fetch_assoc();
                $driver_name = $driverInfo['D_fname'];
                $driver_phone = $driverInfo['D_phone'];
            }

            // Fetch bus information
            $busInfoSql = "SELECT Bus_Reg_no, Bus_Comp FROM tbl_bus WHERE Bus_id = $bus_id";
            $busInfoResult = $conn->query($busInfoSql);

            if ($busInfoResult->num_rows > 0) {
                $busInfo = $busInfoResult->fetch_assoc();
                $bus_reg_no = $busInfo['Bus_Reg_no'];
                $bus_comp = $busInfo['Bus_Comp'];
            }
        } else {
            echo 'Bus information not found.';
            exit(); // Stop further execution
        }
    } else {
        echo 'Ticket not found.';
        exit(); // Stop further execution
    }
} else {
    echo 'Ticket ID not provided.';
    exit(); // Stop further execution
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Ticket</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="ticket.css">   

</head>
<body>
    <br>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="ticket">
                <div class="bus-company-details">
                    <?php include 'tkthdr.php'; ?>
                </div>
                <div class="ticket-header">
                    <h3>Ticket Details</h3>
                </div>
                <div class="Bus-info">
                <h3>Bus Info</h3>
                <div class="bus-info-details">
                    <p><strong>Bus info:</strong> <?php echo $route_name; ?>: <?php echo $bus_comp; ?> A/C</p>
                    <p><strong>Reg No:</strong> <?php echo $bus_reg_no; ?></p>
                    <p><strong>Driver Name:</strong> <?php echo $driver_name; ?></p>
                    <p><strong>Driver Phone:</strong> <?php echo $driver_phone; ?></p>
                </div>
            </div>

            <div class="ticket-info">
                <h3>Ticket Info</h3>
                <div class="ticket-info-details">
                    <p><strong>Ticket Number:</strong> <?php echo $ticket_number; ?></p>
                    <p><strong>Journey Date:</strong> <?php echo $journey_date; ?></p>
                    <p><strong>Start:</strong> <?php echo $source; ?><strong> > </strong><?php echo $journey_stime; ?></p>
                    <p><strong>Destination:</strong> <?php echo $destination; ?><strong> > </strong><?php echo $journey_etime; ?></p>
                    <p><strong>Total Distance:</strong> <?php echo $distance; ?> km</p>
                    <p><strong>Ticket Status:</strong> <span class="<?php echo ($ticket_status === 'Confirmed') ? 'success' : 'failure'; ?>"><?php echo $ticket_status; ?></span></p>
                    <p><strong>Payment Date:</strong> <?php echo $payment_date; ?></p>
                    <p><strong>Payment Status:</strong> <span class="<?php echo ($payment_status === 'Paid') ? 'success' : 'failure'; ?>"><?php echo $payment_status; ?></span></p>
                    <p><strong>Total Seats:</strong> <?php echo $total_seats; ?></p>
                </div>
            </div>

            <div class="fare-info">
                <div class="fare-info-details">
                <p><strong class="total-fare">Total Fare:</strong> <span class="total-fare">₹<?php echo $total_fare; ?></span></p>
                </div>
            </div>                
                <div class="passenger-info">
                    <h4>Passenger Information</h4>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Age</th>
                                <th>Gender</th>
                                <th>Seat Number</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                             $passengerDetailsSql = "SELECT * FROM tbl_booking_child WHERE Booking_master_id = $booking_master_id";
                             $passengerDetailsResult = $conn->query($passengerDetailsSql);

                            while ($passenger = $passengerDetailsResult->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . $passenger['Passenger_name'] . '</td>';
                                echo '<td>' . $passenger['Age'] . '</td>';
                                echo '<td>' . $passenger['Gender'] . '</td>';
                                echo '<td>' . $passenger['Seat_number'] . '</td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                        
                    </table>
                    <!-- <?php// if (isset($ticket_id)) { ?>
    <a href="generate_pdf.php?ticket_id=<//?php echo $ticket_id; ?>" class="btn btn-primary">Print Ticket</a>
    <?//php } ?> -->
                </div>
            </div>
            <center><button id="print-ticket" class="btn btn-primary">Print Ticket</button></center>
        </div>
    </div>
</div>
</body>
</html>
<script>
document.getElementById('print-ticket').addEventListener('click', function() {
    var printWindow = window.open('', '_blank');
    
    var printContent = '<html><head><title>Ticket</title>' +
                       '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">' +
                       '<link rel="stylesheet" href="ticket.css"></head><body>' +
                       document.querySelector('.ticket').outerHTML + '</body></html>';
    
    printWindow.document.write(printContent);
    printWindow.document.close();
    
    printWindow.onload = function() {
        printWindow.print();
        printWindow.close();
    };
});
</script>
