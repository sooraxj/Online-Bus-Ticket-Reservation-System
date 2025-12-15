<?php
include 'header.php';
include 'Dbconnect.php';

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$busFound = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $start = $_POST['start'];
    $destination = $_POST['destination'];
    $journeyDate = $_POST['journeyDate'];

    // Check if there are buses scheduled for the selected journey date
    $busRouteSql = "SELECT * FROM tbl_Bus_route WHERE Allocation_date = '$journeyDate'";
    $busRouteResult = $conn->query($busRouteSql);

    if ($busRouteResult->num_rows > 0) {
        // Display the available buses for the selected journey date
        $sql = "SELECT br.*, r.Route_name, r.Route_no, b.Bus_Comp,
                s1.Stop_name AS start_stop, TIME_FORMAT(s1.Arrival_time, '%H:%i') AS start_time, s1.Distance AS start_distance, s1.Stop_no AS Sorstop_no,
                s2.Stop_name AS dest_stop, TIME_FORMAT(s2.Arrival_time, '%H:%i') AS dest_time, s2.Distance AS dest_distance, s2.Stop_no AS Deststop_no,
                fs.Base_fare, fs.Additional_fare
                FROM tbl_Bus_route br
                INNER JOIN tbl_Route r ON br.Route_id = r.Route_id
                INNER JOIN tbl_Driver_bus db ON br.Driver_bus_id = db.Driver_bus_id
                INNER JOIN tbl_Bus b ON db.Bus_id = b.Bus_id
                INNER JOIN tbl_Stop s1 ON r.Route_id = s1.Route_id AND s1.Stop_name = '$start'
                INNER JOIN tbl_Stop s2 ON r.Route_id = s2.Route_id AND s2.Stop_name = '$destination'
                INNER JOIN tbl_Farestage fs ON fs.Farestage_id = 1
                WHERE br.Allocation_date = '$journeyDate' 
                AND s1.Stop_no < s2.Stop_no
                ORDER BY s1.Arrival_time ASC, s2.Arrival_time ASC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <title>Bus Stop Search Results</title>
                <link rel='stylesheet' href='Search_stop.css'>
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
            </head>
            <body>
                <div class="main">
                    <h1>Available Buses...</h1>
                    <div class="container bus-container">
                        <?php
                        while ($row = $result->fetch_assoc()) {
                            $distance = $row['dest_distance'] - $row['start_distance'];

                            $baseFare = $row['Base_fare'];
                            $additionalFare = $row['Additional_fare'] * $distance;
                            $totalFare = $baseFare + $additionalFare;

                            $Sstop_no = $row['Sorstop_no'];
                            $Dstop_no = $row['Deststop_no'];

                            date_default_timezone_set('Asia/Kolkata');
                            $currentTime = date('H:i');
                            $currentDate = date('Y-m-d');
                            $st = $row['start_time'];
                            $busRouteId = $row['Bus_route_id'];
                            $RouteId = $row['Route_id'];


                            $query = "SELECT DISTINCT bc.Seat_number, bc.Sstop_no, bc.Dstop_no
                                FROM tbl_Booking_master bm
                                INNER JOIN tbl_Booking_child bc ON bm.Booking_master_id = bc.Booking_master_id
                                INNER JOIN tbl_ticket t ON bm.Booking_master_id = t.Booking_master_id
                                WHERE bm.Bus_route_id = $busRouteId
                                AND t.Journey_date = '$journeyDate'
                                AND bc.Booking_status = 1";

                            $resultSeats = $conn->query($query);

                            $bookedSeats = array();
                            while ($rowSeats = $resultSeats->fetch_assoc()) {
                                $Source_stopno = $rowSeats['Sstop_no'];
                                $Destination_stopno = $rowSeats['Dstop_no'];
                                if (($Sstop_no >= $Source_stopno && $Sstop_no < $Destination_stopno) ||  // 3 to 4 booked (available from 4)
                                ($Dstop_no > $Source_stopno && $Dstop_no <= $Destination_stopno) ||      // 3 to 4 booked (available upto 3)
                                ($Sstop_no <= $Source_stopno && $Dstop_no >= $Destination_stopno)) {     // full booked
                                $bookedSeats[] = $rowSeats['Seat_number'];
                            }
                            }

                            $numBookedSeats = 50 - count(array_unique($bookedSeats));

                            if ($st < $currentTime && $journeyDate == $currentDate) {
                                continue; 
                            }

                            $busFound = true;

                            $sql = "SELECT GROUP_CONCAT(Stop_name) AS Cities
                            FROM tbl_Stop WHERE Route_id = '$RouteId'AND Main=1";
                            $resultcity = mysqli_query($conn,$sql);
                            $city = mysqli_fetch_assoc($resultcity);
                            ?>
                            <div class="row bus-row align-items-center">
                                <div class="col-md-8 bus-details">
                                    <span class="badge badge-warning"><?php echo $row['Route_no'] . ' - ' . $row['Route_name']; ?></span><br>
                                    <?php echo $row['Bus_Comp'].'<span class="space"></span>'; ?>
                                    <?php echo '<span class="space"></span>'. $row['start_stop'] . ' - ' . $row['start_time']; ?>
                                    <?php echo '<span class="space"></span>'.$row['dest_stop'] . ' - ' . $row['dest_time']; ?>
                                    <?php echo '<span class="space"></span>'.$distance; ?> km     
                                    <!-- <br><span class="badge badge-info">A/C</span> -->
                                    <div class="col-md-1">
                                <button class="badge badge-info" onclick="toggleCities(this)">Route</button>
                                </div>
                                <div class="city" style="display: none;">
                                <?php 
                                    $cities = $city["Cities"];
                                    echo "<span class='via-text'>Via ".$cities."</span>"; 
                                ?>
                            </div>  
                                </div>
                                <div class="col-md-1 bus-details">
                                    <span class="badge badge-danger"><?php echo $numBookedSeats . ' Seats Left'; ?></span>
                                </div>
                                <div class="col-md-1">
                                    <div class="fare">&#8377;<?php echo $totalFare; ?></div>
                                </div>
                                <div class="col-md-1">
                                    <?php 
                                    $farePerPerson = $totalFare;
                                    echo '<a href="seat_selection.php?bus_route_id=' . $row['Bus_route_id'] .'&Routename=' .$row['Route_name'].'&Buscomp=' .$row['Bus_Comp'].'&starttime=' . $row['start_time'].'&Sstop_no=' . $Sstop_no.'&Dstop_no=' . $Dstop_no.'&desttime=' . $row['dest_time']. '&journeyDate=' . $journeyDate . '&start=' . $start . '&destination=' . $destination . '&distance=' . $distance . '&fare=' . $totalFare . '" class="btn btn-primary book-button">Book</a>';
                                    ?>      
                                                          
                                </div>
                            </div>
                            <?php 
                        }

                        if (!$busFound) {
                            header("Location: Nobusfound.php");
                        }
                        ?>
                    </div>
                </div>
            </body>
            </html>
            <?php
        } else {
            header("Location: Nobusfound.php");
            exit;       
        }
    } else {
        header("Location: Nobusfound.php");
        exit;   
    }
}
?>
<script>
    function toggleCities(button) {
        var cityContainer = button.closest('.bus-row').querySelector('.city');

        if (cityContainer.style.display === 'none') {
            cityContainer.style.display = 'block';
        } else {
            cityContainer.style.display = 'none';
        }
    }
</script>