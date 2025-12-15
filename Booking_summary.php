
<?php
include 'header.php';
include 'Dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming you have already established the database connection
    $customerID = $_SESSION['Customer_id'];

    // Sample query to retrieve Farestage_id
    $query = "SELECT Farestage_id FROM tbl_farestage";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $fareStageID = $row['Farestage_id'];
    }

    $busRouteID = $_POST['bus_route_id'];
    $journeyDate = $_POST['journeyDate'];
    $starttime = $_POST['starttime'];
    $desttime = $_POST['desttime'];
    $start = $_POST['start'];
    $destination = $_POST['destination'];
    $Sstop_no = $_POST['Sstop_no'];
    $Dstop_no = $_POST['Dstop_no'];
    $totalFare = $_POST['fare'];
    $distance = $_POST['distance'];
    $routeName = $_POST['Routename'];
    $busCompany = $_POST['Buscomp'];
    

    // Loop through passengers and insert into tbl_Booking_child
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'passenger_seat_') === 0) {
            $passengerNumber = substr($key, strlen('passenger_seat_'));
            $passengerSeat = intval($value);
            // $passengerSeat = htmlspecialchars($value);
            $passengerName = htmlspecialchars($_POST['passenger_name_' . $passengerNumber]);
            $passengerAge = htmlspecialchars($_POST['passenger_age_' . $passengerNumber]);
            $passengerGender = htmlspecialchars($_POST['passenger_gender_' . $passengerNumber]);
            // $passengerSeat = intval(substr($passengerSeat, strlen('seat_')));
            $totalPassengers = 0;
            foreach ($_POST as $key => $value) {
                if (strpos($key, 'passenger_seat_') === 0) {
                    $totalPassengers++;
                }
            }
            $totalCost = $totalFare * $totalPassengers;
           // Initialize an array to store passenger data
$passengers = array();

foreach ($_POST as $key => $value) {
    if (strpos($key, 'passenger_seat_') === 0) {
        $passengerNumber = substr($key, strlen('passenger_seat_'));
        $passengerSeat = htmlspecialchars($value);
        $passengerName = htmlspecialchars($_POST['passenger_name_' . $passengerNumber]);
        $passengerAge = htmlspecialchars($_POST['passenger_age_' . $passengerNumber]);
        $passengerGender = htmlspecialchars($_POST['passenger_gender_' . $passengerNumber]);

        // Create an array to store passenger details
        $passengerInfo = array(
            'seat' => $passengerSeat,
            'name' => $passengerName,
            'age' => $passengerAge,
            'gender' => $passengerGender
        );

        // Add passenger data to the $passengers array
        $passengers[] = $passengerInfo;
    }
}

// Store the passengers array in a session variable
$_SESSION['passengers'] = $passengers;
        }
    }
  
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Summary</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="Bookingsummary.css">
</head>
<body>
    <br>
    <div class="container">
        <div class="header1">
            <h1>Booking Details</h1>
        </div>

        <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
            <table class="booking-table">
                <thead>
                    <tr>
                        <th>Passenger Seat</th>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Gender</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loop to display passenger details -->
                    <?php foreach ($_POST as $key => $value) {
                        if (strpos($key, 'passenger_seat_') === 0) {
                            $passengerNumber = substr($key, strlen('passenger_seat_'));
                            $passengerSeat = htmlspecialchars($value);
                            $passengerName = htmlspecialchars($_POST['passenger_name_' . $passengerNumber]);
                            $passengerAge = htmlspecialchars($_POST['passenger_age_' . $passengerNumber]);
                            $passengerGender = htmlspecialchars($_POST['passenger_gender_' . $passengerNumber]);
                            echo '<tr>';
                            echo '<td>' . $passengerSeat . '</td>';
                            echo '<td>' . $passengerName . '</td>';
                            echo '<td>' . $passengerAge . '</td>';
                            echo '<td>' . ($passengerGender === 'M' ? 'Male' : ($passengerGender === 'F' ? 'Female' : 'Other')) . '</td>';
                            echo '</tr>';
                        }
                    } ?>
                </tbody>
            </table>

              <div class="total-fare">
                Total Fare: <?php echo $totalCost; ?>
            </div>

            <div class="bus-details-box">
                <h2 class="bus-details-title">Bus Details</h2>
                <div class="bus-details-item"><strong>Bus Info:</strong> <?php echo $busRouteID . '-' . $routeName . ' : ' . $busCompany . ' A/C'; ?></div>
                <div class="bus-details-item"><strong>Journey Date:</strong> <?php echo $journeyDate; ?></div>
                <div class="bus-details-item"><strong>Source:</strong> <?php echo $start . ' : ' . $starttime; ?></div>
                <div class="bus-details-item"><strong>Destination:</strong> <?php echo $destination . ' : ' . $desttime; ?></div>
                <div class="bus-details-item"><strong>Distance:</strong> <?php echo $distance . ' Km'; ?></div>
            </div>
                <a href="Payment_page.php?bus_route_id=<?php echo $busRouteID; ?>&Routename=<?php echo $routeName; ?>&starttime=<?php echo $starttime; ?>&desttime=<?php echo $desttime; ?>&start=<?php echo $start; ?>&Sstop_no=<?php echo $Sstop_no; ?>&Dstop_no=<?php echo $Dstop_no; ?>&dest=<?php echo $destination; ?>&Buscomp=<?php echo $busCompany; ?>&journeyDate=<?php echo $journeyDate; ?>&start=<?php echo $start; ?>&destination=<?php echo $destination; ?>&distance=<?php echo $distance; ?>&cost=<?php echo $totalCost; ?>&fare=<?php echo $totalFare; ?>&totalpassengers=<?php echo $totalPassengers; ?>" class="btn btn-payment">Proceed to Payment</a>
            </div>

        <?php else: ?>
            <p>No data submitted.</p>
        <?php endif; ?>
        </div>
    </div>
</body>
</html>

