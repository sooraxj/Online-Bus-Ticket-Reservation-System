<?php
include 'header.php';
include 'Dbconnect.php';
$successMessage = ""; // Initialize the success message

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = $_SESSION['Customer_id'];
    $ticket_id = $_POST['ticket_id'];
    $bus_rating = $_POST['bus_rating'];
    $driver_rating = $_POST['driver_rating'];
    $comments = $_POST['comments'];
    $complaint = isset($_POST['complaint']) ? 1 : 0; // Convert checkbox value to 1 or 0

    // Check if a feedback record with the given Ticket_id already exists
    $checkFeedbackSql = "SELECT * FROM tbl_Feedback WHERE Ticket_id = '$ticket_id'";
    $checkFeedbackResult = $conn->query($checkFeedbackSql);

    if ($checkFeedbackResult->num_rows > 0) {
        // Feedback record exists, perform an UPDATE query
        $updateFeedbackSql = "UPDATE tbl_Feedback SET Bus_rating = '$bus_rating', Driver_rating = '$driver_rating', Comment = '$comments', Complaint = '$complaint' WHERE Ticket_id = '$ticket_id'";

        if ($conn->query($updateFeedbackSql) === TRUE) {
            // Feedback successfully updated
    
            header("Location: Leave_feedback.php"); 
            exit();
        } else {
            // Handle update error
        }
    } else {
        // Feedback record doesn't exist, perform an INSERT query
        $insertFeedbackSql = "INSERT INTO tbl_Feedback (Customer_id, Ticket_id, Bus_rating, Driver_rating, Comment, Complaint) VALUES ('$customer_id', '$ticket_id', '$bus_rating', '$driver_rating', '$comments', '$complaint')";

        if ($conn->query($insertFeedbackSql) === TRUE) {
            // Feedback successfully inserted

            header("Location: Leave_feedback.php");
            exit();
        } else {
        }
    }
}

$ticket_id = $_GET['ticket_id'];
$existingFeedbackSql = "SELECT * FROM tbl_Feedback WHERE Ticket_id = '$ticket_id'";
$existingFeedbackResult = $conn->query($existingFeedbackSql);
$existingFeedbackData = $existingFeedbackResult->fetch_assoc();

$conn->close();
?>


<!DOCTYPE html>
<html>
<head>
    <title>Feedback Form</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
.container {
    max-width: 600px;
    margin: 0 auto;
    background-color: #f5f5f5;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
}

h1 {
    text-align: center;
    color: #333;
}

.form-group {
    margin-bottom: 20px;
}

label {
    font-weight: bold;
    color: #333;
}

.form-check-label {
    color: #333;
}

.btn-primary {
    background-color: #007BFF;
    border-color: #007BFF;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
}

/* Style the checkbox */
.form-check-input:checked + .form-check-label::before {
    background-color: #007BFF;
    border-color: #007BFF;
}

        </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Feedback Form</h1>

        <form action="Feedback.php" method="POST">
            <input type="hidden" name="ticket_id" value="<?php echo $_GET['ticket_id']; ?>">

            <div class="form-group">
                <label for="bus_rating">Rate Bus Experience (1-5):</label>
                <input type="number" class="form-control" id="bus_rating" name="bus_rating" min="1" max="5" required value="<?php echo isset($existingFeedbackData['Bus_rating']) ? $existingFeedbackData['Bus_rating'] : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="driver_rating">Rate Driver (1-5):</label>
                <input type="number" class="form-control" id="driver_rating" name="driver_rating" min="1" max="5" required value="<?php echo isset($existingFeedbackData['Driver_rating']) ? $existingFeedbackData['Driver_rating'] : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="comments">Additional Comments (Max 50 characters):</label>
                <textarea class="form-control" id="comments" name="comments" rows="4" maxlength="50"><?php echo isset($existingFeedbackData['Comment']) ? $existingFeedbackData['Comment'] : ''; ?></textarea>
            </div>
            
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="complaint" name="complaint" <?php echo isset($existingFeedbackData['Complaint']) && $existingFeedbackData['Complaint'] == 1 ? 'checked' : ''; ?>>
                <label class="form-check-label" for="complaint">Include Complaint</label>
            </div>
            
            <button type="submit" class="btn btn-primary" name="submit">Submit Feedback</button>
        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
