<?php
include 'header.php';
include 'Dbconnect.php';

$busRouteId = $_GET['bus_route_id'];
$journeyDate = $_GET['journeyDate'];
$start = $_GET['start'];
$destination = $_GET['destination'];
$Sstop_no = $_GET['Sstop_no'];
$Dstop_no = $_GET['Dstop_no'];
$starttime = $_GET['starttime'];
$desttime = $_GET['desttime'];
$totalFare = $_GET['fare'];
$distance = $_GET['distance'];
$Routename = $_GET['Routename'];
$Buscomp = $_GET['Buscomp'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['seatcheckbox'])) {
    $selectedSeats = $_POST['seatcheckbox'];
    if (empty($selectedSeats)) {
        $_SESSION['seatSelectionError'] = "Please select at least one seat.";
        header("Location: seat_selection.php");
        exit();
    } elseif (count($selectedSeats) > 4) {
        $_SESSION['seatSelectionError'] = "You can only select a maximum of 4 seats.";
        header("Location: seat_selection.php");
        exit();
    }

    $_SESSION['selectedSeats'] = $selectedSeats;
    $_SESSION['bus_route_id'] = $_POST['bus_route_id'];
    $_SESSION['journeyDate'] = $_POST['journeyDate'];

    $redirectUrl = "passenger_info.php?bus_route_id=".$_POST['bus_route_id']."&Routename=".$_POST['Routename']."&starttime=" . $_POST['starttime']."&desttime=" . $_POST['desttime']."&Buscomp=".$_POST['Buscomp']."&journeyDate=".$_POST['journeyDate']."&start=".$_POST['start']."&Sstop_no=".$_POST['Sstop_no']."&Dstop_no=".$_POST['Dstop_no']."&destination=".$_POST['destination']."&distance=".$_POST['distance']."&fare=".$_POST['fare'];
    header("Location: $redirectUrl");
    exit();
}



$query = "SELECT DISTINCT bc.Seat_number,bc.Sstop_no,bc.Dstop_no
          FROM tbl_Booking_master bm
          INNER JOIN tbl_Booking_child bc ON bm.Booking_master_id = bc.Booking_master_id
          INNER JOIN tbl_ticket t ON bm.Booking_master_id = t.Booking_master_id
          WHERE bm.Bus_route_id = $busRouteId
          AND t.Journey_date = '$journeyDate'
          AND bc.Booking_status = 1";


$result = $conn->query($query);

$bookedSeats = array();
while ($row = $result->fetch_assoc()) {
    $Source_stopno = $row['Sstop_no'];
    $Destination_stopno = $row['Dstop_no'];
    if (($Sstop_no >= $Source_stopno && $Sstop_no < $Destination_stopno) || 
    ($Dstop_no > $Source_stopno && $Dstop_no <= $Destination_stopno) ||
    ($Sstop_no <= $Source_stopno && $Dstop_no >= $Destination_stopno)) {
    $bookedSeats[] = $row['Seat_number'];
}
}
    $numBookedSeats = 50 - count(array_unique($bookedSeats));

?>
 
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="seat_selection.css">
    <script>
        function validateForm() {
            var selectedSeats = document.querySelectorAll('input[name="seatcheckbox[]"]:checked');
            
            if (selectedSeats.length === 0) {
                alert("Please select at least one seat.");
                return false;
            } else if (selectedSeats.length > 4) {
                alert("You can only select a maximum of 4 seats.");
                return false;
            }
            
            return true;
        }
    </script>
</head>
<body>
    <div class="container mt-5">
  
    
    <div class="bus-info-container">
        <h2 class="h2">Journey Details</h2>
        <p class="bus-info"><span>Bus Info:</span> <?php echo $busRouteId.'-'.$Routename; ?></p>
        <p class="bus-info"><span>Journey Date:</span> <?php echo $journeyDate; ?></p>
        <p class="bus-info"><span>Route:</span> <?php echo $start.'-'.$destination; ?></p>
        <p class="bus-info"><span>Fare/head:</span> <?php echo $totalFare; ?></p>
    </div>
<br>
        <div class="bus">
        <h2>Bus Seat Selection</h2>
        <form action="seat_selection.php" method="post" onsubmit="return validateForm();">
        <input type="hidden" name="bus_route_id" value="<?php echo $busRouteId; ?>">
    <input type="hidden" name="journeyDate" value="<?php echo $journeyDate; ?>">
    <input type="hidden" name="start" value="<?php echo $start; ?>">
    <input type="hidden" name="Sstop_no" value="<?php echo $Sstop_no; ?>">
    <input type="hidden" name="Dstop_no" value="<?php echo $Dstop_no; ?>">
    <input type="hidden" name="starttime" value="<?php echo $starttime; ?>">
    <input type="hidden" name="desttime" value="<?php echo $desttime; ?>">
    <input type="hidden" name="destination" value="<?php echo $destination; ?>">
    <input type="hidden" name="fare" value="<?php echo $totalFare; ?>">
    <input type="hidden" name="distance" value="<?php echo $distance; ?>">
    <input type="hidden" name="journeytime" value="<?php echo $time; ?>">
    <input type="hidden" name="Routename" value="<?php echo $Routename; ?>">
    <input type="hidden" name="Buscomp" value="<?php echo $Buscomp; ?>">
                <ol class="bus_seat"><br>   
                    <img src="img/steering.png" alt="driver" width="30" height="30" style="margin-left:8.45em; margin-top: 0.3em;"><br><br>
                    <?php
                    $totalRows = 10;
                    $seatsPerRow = 5;
                    $gapSize = 1; 

                    for ($rowNumber = 1; $rowNumber <= $totalRows; $rowNumber++) {
                        echo '<li class="row row--' . $rowNumber . '">';
                        echo '<ol class="seats" type="A">';

                        for ($seatNumber = 1; $seatNumber <= $seatsPerRow; $seatNumber++) {
                            $seatIndex = (($rowNumber - 1) * $seatsPerRow + $seatNumber);

                            echo '<li class="seat';
                            if ($seatNumber <= 2) {
                                echo ' left-seat';
                            }
                            // Check if the seat is already booked and mark it as booked-seat
                            
                            if (in_array($seatIndex, $bookedSeats)) {
                                echo ' booked-seat';
                            }
                            echo '">';
                            echo '<input type="checkbox" name="seatcheckbox[]" value="' . $seatIndex . '" id="' . $seatIndex . '" ';

                            //Disable the checkbox if the seat is already booked
                            if (in_array($seatIndex, $bookedSeats)) {
                                echo 'disabled';
                            }
                            echo '/>';
                            echo '<label for="' . $seatIndex . '" class="seat-number"><span class="seat-no">' . $seatIndex . '</span></label>';
                            echo '</li>';                         
                            if ($seatNumber == 2 && $gapSize > 0) {
                                for ($i = 0; $i < $gapSize; $i++) {
                                    echo '<li class="seat-gap"></li>';
                                }
                            }
                        }

                        echo '</ol>';
                        echo '</li>';
                    }
                    ?>
                </ol>
                <input type="submit" name="submit" value="Submit" class="btn btn-primary book-button">
                <div class="seat-legend">
                    <div class="legend-item">
                        <div class="legend-color booked-seat-below"></div>
                        <div class="legend-text">Booked Seats</div>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color available-seat-below"></div>
                        <div class="legend-text">Available Seats</div>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color selected-seat-below"></div>
                        <div class="legend-text">Selected Seats</div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
