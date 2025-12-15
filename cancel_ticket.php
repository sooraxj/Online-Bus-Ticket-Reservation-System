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

    $currentDateTime = date("Y-m-d H:i:s");

    $journeyDateTimeStr = $row['Journey_date'] . ' ' . $row['Journey_stime'];

    $journeyDateTime = strtotime($journeyDateTimeStr);

    $currentTimestamp = strtotime($currentDateTime);

    $timeDifferenceHours = ($journeyDateTime - $currentTimestamp) / 3600;

    $timeDifferenceDays = floor($timeDifferenceHours / 24);

    $refundAmount = 0;

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

    if ($timeDifferenceDays >= 3) {
        // Before 72 Hours (No Cancellation Fee)
        $refundAmount = $totalFare;
    } elseif ($timeDifferenceDays >= 2) {
        // Between 72 and 48 Hours (10% Cancellation Fee)
        $refundAmount = $totalFare * 0.90;
    } elseif ($timeDifferenceDays >= 1) {
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

    $updateBookingChildSql = "UPDATE tbl_booking_child SET Booking_status = 0 WHERE booking_master_id = $booking_master_id";

    $conn->begin_transaction();

    try {
        $conn->query($updatePaymentSql);
        $conn->query($updateBookingChildSql);

        $updateTicketSql = "UPDATE tbl_ticket SET Ticket_status = 0 WHERE Ticket_id = $ticket_id";
        $conn->query($updateTicketSql);

        $getBusRouteIdSql = "SELECT br.Bus_route_id FROM tbl_booking_master bm
                             INNER JOIN tbl_bus_route br ON bm.bus_route_id = br.Bus_route_id
                             WHERE bm.booking_master_id = $booking_master_id";

        $busRouteResult = $conn->query($getBusRouteIdSql);


        $conn->commit();

        header("Location: refund.php?refund_amount=$refundAmount");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Ticket not found.";
}
$conn->close();
?>
