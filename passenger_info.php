<?php include 'header.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Passenger Information</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="passenger_info.css">
</head>
<body>
    <div class="container">
        <?php
        include 'Dbconnect.php';
        $busRouteId = $_GET['bus_route_id'];
        $journeyDate = $_GET['journeyDate'];
        $starttime = $_GET['starttime'];
        $desttime = $_GET['desttime'];
        $start = $_GET['start'];
        $Sstop_no = $_GET['Sstop_no'];
        $Dstop_no = $_GET['Dstop_no'];
        $destination = $_GET['destination'];
        $totalFare = $_GET['fare'];
        $distance = $_GET['distance'];
        $Routename = $_GET['Routename'];
        $Buscomp = $_GET['Buscomp'];
        $selectedSeats = $_SESSION['selectedSeats'] ?? [];

        if (empty($selectedSeats)) {
            echo '<p>No seats were selected.</p>';
        } else {
            echo '<form method="post" action="Booking_summary.php" autocomplete="off">';
            echo '<div><h2>Passenger Details</h2></div>';

            echo '<div class="passenger-table">';
            foreach ($selectedSeats as $i => $selectedSeat) {
                echo '<div class="passenger-row">';
                echo '<div class="passenger-cell">';
                echo '<h4><div class="psno">Passenger&nbsp' . ($i + 1) . '<br></div><br>'."<div class='seatno'>Seat No:" . htmlspecialchars($selectedSeat) . '</div></h4>';
                echo '<input type="hidden" name="passenger_seat_' . $i . '" value="' . htmlspecialchars($selectedSeat) . '">';
                echo '</div>';
                echo '<div class="passenger-cell">';
                echo '<label for="passenger_name_' . $i . '">Passenger Name:</label>';
                echo '<input type="text" name="passenger_name_' . $i . '" required><br>';
                echo '</div>';
                echo '<div class="passenger-cell">';
                echo '<label for="passenger_age_' . $i . '">Age:</label>';
                echo '<input type="number" name="passenger_age_' . $i . '"  min="1" max="99" required><br>';
                echo '</div>';
                echo '<div class="passenger-cell">';
                echo '<label for="passenger_gender_' . $i . '">Gender:</label>';
                echo '<select name="passenger_gender_' . $i . '" required>';
                echo '<option value="M">Male</option>';
                echo '<option value="F">Female</option>';
                echo '<option value="O">Other</option>';
                echo '</select><br>';
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
            
            // Hidden input fields for passing values to Booking_summary.php
            echo '<input type="hidden" name="bus_route_id" value="' . $busRouteId . '">';
            echo '<input type="hidden" name="journeyDate" value="' . $journeyDate . '">';
            echo '<input type="hidden" name="starttime" value="' . $starttime . '">';
            echo '<input type="hidden" name="desttime" value="' . $desttime . '">';
            echo '<input type="hidden" name="start" value="' . $start . '">';
            echo '<input type="hidden" name="destination" value="' . $destination . '">';
            echo '<input type="hidden" name="Sstop_no" value="' . $Sstop_no . '">';
            echo '<input type="hidden" name="Dstop_no" value="' . $Dstop_no . '">';
            echo '<input type="hidden" name="fare" value="' . $totalFare . '">';
            echo '<input type="hidden" name="distance" value="' . $distance . '">';
            echo '<input type="hidden" name="Routename" value="' . $Routename . '">';
            echo '<input type="hidden" name="Buscomp" value="' . $Buscomp . '"><br>';
            echo '<input type="submit" name="submit" value="Proceed" class="btn btn-primary book-button">';
            echo '</form>';
        }
        ?>
    </div>
</body>
</html>
