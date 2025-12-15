<?php
include 'Dbconnect.php';
include 'header.php';

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply'], $_POST['message_id'])) {
    $reply = $_POST['reply'];
    $message_id = $_POST['message_id'];

    $insertReplySql = "UPDATE tbl_messages SET reply='$reply' WHERE message_id=$message_id";

    if ($conn->query($insertReplySql)) {
        echo '<script>alert("Reply added successfully!");</script>';
    } else {
        echo "Error adding reply: " . $conn->error . "<br>";
    }
}

$selectRequestsSql = "SELECT * FROM tbl_messages";
$result = $conn->query($selectRequestsSql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Requests</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    

        h2 {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #dee2e6;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: #ffffff;
        }

        button {
            padding: 5px 10px;
        }
        h2{
            text-align:center;
            font-weight:bold;
        }
    </style>
</head>
<body>

<div class="container">
    <?php
    if ($result->num_rows > 0) {
        echo "<h2>Requests</h2>";
        echo "<form method='post' action='view_requests.php'>";
        echo "<table class='table table-bordered'>";
        echo "<thead class='thead-dark'>";
        echo "<tr><th>Message ID</th><th>Ticket ID</th><th>Message</th><th>Reply</th><th>Action</th></tr>";
        echo "</thead><tbody>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['message_id']}</td>";
            echo "<td>{$row['Ticket_id']}</td>";
            echo "<td>{$row['message']}</td>";
            echo "<td><input type='text' class='form-control' name='reply' value='{$row['reply']}'></td>";
            echo "<td><button type='submit' class='btn btn-danger' name='message_id' value='{$row['message_id']}'>Submit Reply</button></td>";
            echo "</tr>";
        }

        echo "</tbody></table>";
        echo "</form>";
    } else {
        echo "<p class='alert alert-info'>No requests found.</p>";
    }
    ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
