<?php
include 'Dbconnect.php'; // Include your database connection
use Dompdf\Dompdf;

if (isset($_GET['ticket_id'])) {
    $ticket_id = $_GET['ticket_id'];

    // Fetch ticket details from tbl_ticket using ticket_id
    $ticketDetailsSql = "SELECT t.Booking_master_id, t.Payment_id, t.Ticket_number, t.Journey_date, t.Journey_stime, t.Journey_etime, t.Ticket_status, 
                                p.Payment_date, p.Payment_status,
                                bm.Booking_date, bm.Total_fare
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
        $payment_status = $ticketDetails['Payment_status'] == 1 ? 'Paid' : 'Not Paid';
        $booking_date = $ticketDetails['Booking_date'];
        $total_fare = $ticketDetails['Total_fare'];

        // Fetch additional details from tbl_booking_child using booking_master_id
        $additionalDetailsSql = "SELECT Source, Destination, Total_seats FROM tbl_booking_child WHERE Booking_master_id = $booking_master_id";
        $additionalDetailsResult = $conn->query($additionalDetailsSql);

        if ($additionalDetailsResult->num_rows > 0) {
            $additionalDetails = $additionalDetailsResult->fetch_assoc();
            $source = $additionalDetails['Source'];
            $destination = $additionalDetails['Destination'];
            $total_seats = $additionalDetails['Total_seats'];

            // Now generate the PDF content using Dompdf
            require_once 'vendor/autoload.php';
            // use Dompdf\Dompdf;

            $dompdf = new Dompdf();

            ob_start();
            ?>
            <!-- Customize the PDF content here -->
            <html>
            <head>
                <!-- Add your CSS styles here -->
                <link rel="stylesheet" href="ticket.css">
            </head>
            <body>
                <div class="ticket">
                    <div class="ticket-header">
                        <h3>Ticket Details</h3>
                    </div>
                    <div class="ticket-info">
                        <p><strong>Ticket Number:</strong> <?php echo $ticket_number; ?></p>
                        <p><strong>Journey Date:</strong> <?php echo $journey_date; ?></p>
                        <p><strong>Start:</strong> <?php echo $source; ?><strong> > </strong><?php echo $journey_stime; ?></p>
                        <p><strong>Destination:</strong> <?php echo $destination; ?><strong> > </strong><?php echo $journey_etime; ?></p>
                        <p><strong>Ticket Status:</strong> <span class="<?php echo ($ticket_status === 'Confirmed') ? 'success' : 'failure'; ?>"><?php echo $ticket_status; ?></span></p>
                        <p><strong>Payment Date:</strong> <?php echo $payment_date; ?></p>
                        <p><strong>Payment Status:</strong> <span class="<?php echo ($payment_status === 'Paid') ? 'success' : 'failure'; ?>"><?php echo $payment_status; ?></span></p>
                        <p><strong>Total Fare:</strong> <?php echo $total_fare; ?></p>
                        <p><strong>Total Seats:</strong> <?php echo $total_seats; ?></p>
                    </div>
                </div>
            </body>
            </html>
            <?php
            $pdfContent = ob_get_clean();

            $dompdf->loadHtml($pdfContent);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $output = $dompdf->output();

            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="ticket.pdf"');
            header('Content-Length: ' . strlen($output));

            echo $output;
        } else {
            echo 'Additional details not found.';
        }
    } else {
        echo 'Ticket details not found.';
    }
} else {
    echo 'Ticket ID not provided.';
}
?>
