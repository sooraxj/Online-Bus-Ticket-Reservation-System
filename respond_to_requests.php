<?php
// respond_to_requests.php
include 'Dbconnect.php';
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ticket_id'], $_POST['admin_comment'], $_POST['status'])) {
    $ticket_id = $_POST['ticket_id'];
    $admin_comment = $_POST['admin_comment'];
    $status = $_POST['status'];

    // Update request status and admin comment
    $updateActivitySql = "UPDATE tbl_ticket_edit_activity SET Status = '$status', Admin_comment = '$admin_comment' WHERE Ticket_id = $ticket_id AND Activity_type = 'Request'";
    $conn->query($updateActivitySql);

    // Insert admin response into tbl_ticket_edit_activity
    $insertActivitySql = "INSERT INTO tbl_ticket_edit_activity (Ticket_id, Customer_id, Activity_type, Message) VALUES ($ticket_id, NULL, 'Message', '$admin_comment')";
    $conn->query($insertActivitySql);

    header("Location: view_requests.php?ticket_id=$ticket_id");
    exit();
}
?>
