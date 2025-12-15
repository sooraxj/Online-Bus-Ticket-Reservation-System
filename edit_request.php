<?php
include 'Dbconnect.php';
include 'header.php';

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ticket_id'], $_POST['message'])) {
    $ticket_id = $_POST['ticket_id'];
    $message = $_POST['message'];

    $insertActivitySql = "INSERT INTO tbl_messages (Ticket_id, message) VALUES ('$ticket_id', '$message')";

    if ($conn->query($insertActivitySql)) {
        echo '<script>alert("Message sent successfully!");</script>';
    } else {
        echo "Error: " . $conn->error;
    }
}

// Check if a specific ticket_id is provided in the URL
if (isset($_GET['ticket_id'])) {
    $ticket_id = $_GET['ticket_id'];

    $selectMessagesSql = "SELECT * FROM tbl_messages WHERE Ticket_id = $ticket_id";
    $result = $conn->query($selectMessagesSql);

    // Display messages and replies
    if ($result->num_rows > 0) {
        echo '<div class="container">';
        echo '<h2>Messages and Replies</h2>';
        echo '<table class="table table-bordered">';
        echo '<thead class="thead-dark">';
        echo '<tr><th>Message ID</th><th>Ticket ID</th><th>Message</th><th>Reply</th></tr>';
        echo '</thead><tbody>';

        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo "<td>{$row['message_id']}</td>";
            echo "<td>{$row['Ticket_id']}</td>";
            echo "<td>{$row['message']}</td>";
            echo "<td>{$row['reply']}</td>";
            echo '</tr>';
        }

        echo '</tbody></table>';
        echo '</div>';
    } else {
        echo '<p class="alert alert-info">No messages found for this ticket.</p>';
    }

    echo '<div class="container">';
    echo '<h2>Submit New Message</h2>';
    echo '<form method="post" action="edit_request.php">';
    echo '<input type="hidden" name="ticket_id" value="' . $ticket_id . '">';
    echo '<div class="form-group">';
    echo '<textarea class="form-control" name="message" id="message" placeholder="Enter your message" required></textarea>';
    echo '</div>';
    echo '<button type="submit" class="btn btn-secondary">Submit</button>';
    echo '</form>';
    echo '</div>';
} else {
    echo '<p class="alert alert-warning">No ticket_id provided.</p>';
}
?>
