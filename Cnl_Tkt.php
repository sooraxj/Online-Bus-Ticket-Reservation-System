<?php
include 'header.php';
include 'Dbconnect.php';

$ticket_id = $_GET['ticket_id'];

$selectSql = "SELECT Payment_id, booking_master_id, Ticket_status, Journey_date, Journey_stime FROM tbl_ticket WHERE Ticket_id = $ticket_id";
$result = $conn->query($selectSql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    if ($row['Ticket_status'] == 0) {
        header("Location: ticket_cancelled.php");
        exit();
    }

    // Fetch journey date and journey start time separately from the database
    $journeyDate = $row['Journey_date'];
    $journeyStartTime = $row['Journey_stime'];

    // Combine journey date and journey start time to create a datetime string
    $journeyDateTimeStr = $journeyDate . ' ' . $journeyStartTime;

    // Convert journey date and time to a Unix timestamp
    $journeyDateTime = strtotime($journeyDateTimeStr);

    // Get the current time
    $currentTime = time();

    // Calculate the time difference in hours
    $timeDifferenceHours = ($journeyDateTime - $currentTime) / 3600;

    // Initialize the refund amount
    $refundAmount = 0;

    // Fetch Total_fare from tbl_booking_master based on booking_master_id
    $booking_master_id = $row['booking_master_id'];
    $getTotalFareSql = "SELECT Total_fare FROM tbl_booking_master WHERE booking_master_id = $booking_master_id";
    $totalFareResult = $conn->query($getTotalFareSql);

    if ($totalFareResult->num_rows > 0) {
        $totalFareRow = $totalFareResult->fetch_assoc();
        $totalFare = $totalFareRow['Total_fare'];
    } else {
        echo "Total fare not found.";
        exit();
    }

    // Fetch passenger count
    $countPassengersSql = "SELECT COUNT(*) AS passenger_count FROM tbl_booking_child WHERE booking_master_id = $booking_master_id";
    $passengerCountResult = $conn->query($countPassengersSql);

    if ($passengerCountResult->num_rows > 0) {
        $passengerCountRow = $passengerCountResult->fetch_assoc();
        $passengerCount = $passengerCountRow['passenger_count'];
    } else {
        echo "Passenger count not found.";
        exit();
    }

    // Calculate refund based on the time difference, Total_fare, and passenger count
    if ($timeDifferenceHours >= 72) {
        // Before 72 Hours (No Cancellation Fee)
        $refundAmount = $totalFare;
    } elseif ($timeDifferenceHours >= 48) {
        // Between 72 and 48 Hours (10% Cancellation Fee)
        $refundAmount = $totalFare * 0.90;
    } elseif ($timeDifferenceHours >= 24) {
        // Between 48 and 24 Hours (25% Cancellation Fee)
        $refundAmount = $totalFare * 0.75;
    } elseif ($timeDifferenceHours >= 12) {
        // Between 24 and 12 Hours (40% Cancellation Fee)
        $refundAmount = $totalFare * 0.60;
    } elseif ($timeDifferenceHours >= 2) {
        // Between 12 and 2 Hours (50% Cancellation Fee)
        $refundAmount = $totalFare * 0.50;
    } else {
        // Less than 2 Hours or After Departure Time (No Refund)
        $refundAmount = 0;
    }

    $payment_id = $row['Payment_id'];

    $updatePaymentSql = "UPDATE tbl_payment SET Payment_status = 0, Refund_amount = $refundAmount WHERE Payment_id = $payment_id";

    $updateBookingChildSql = "UPDATE tbl_booking_child SET Seat_number = 0, Total_seats = 0 WHERE booking_master_id = $booking_master_id";

    $conn->begin_transaction();

    try {
        // Execute the update queries
        $conn->query($updatePaymentSql);
        $conn->query($updateBookingChildSql);
        $updateTicketSql = "UPDATE tbl_ticket SET Ticket_status = 0 WHERE Ticket_id = $ticket_id";

        // Update the Ticket_status to 0 in tbl_ticket
        $conn->query($updateTicketSql);

        // Get the Bus_route_id associated with the canceled ticket
        $getBusRouteIdSql = "SELECT br.Bus_route_id FROM tbl_booking_master bm
                             INNER JOIN tbl_bus_route br ON bm.bus_route_id = br.Bus_route_id
                             WHERE bm.booking_master_id = $booking_master_id";

        $busRouteResult = $conn->query($getBusRouteIdSql);

        if ($busRouteResult->num_rows > 0) {
            $busRouteRow = $busRouteResult->fetch_assoc();
            $busRouteId = $busRouteRow['Bus_route_id'];

            // Update the available seats for the specific bus route
            $updateBusRouteSeatsSql = "UPDATE tbl_bus_route SET Avl_seats = Avl_seats + $passengerCount WHERE Bus_route_id = $busRouteId";
            $conn->query($updateBusRouteSeatsSql);
        }

        // Commit the transaction if all queries are successful
        $conn->commit();

        // Ticket successfully canceled, pass refund amount through the URL
        header("Location: refund.php?refund_amount=$refundAmount");
        exit();
    } catch (Exception $e) {
        // Rollback the transaction in case of any error
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Ticket not found.";
}

// Close the database connection
$conn->close();
?>
