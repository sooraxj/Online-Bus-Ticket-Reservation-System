<?php
include "Dbconnect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $start = $_POST['start'];
    $destination = $_POST['destination'];
    $journeyDate = $_POST['journeyDate'];

    // Check if there are buses scheduled for the selected journey date
    $busRouteSql = "SELECT * FROM tbl_Bus_route WHERE Allocation_date = '$journeyDate'";
    $busRouteResult = $conn->query($busRouteSql);

    if ($busRouteResult->num_rows > 0) {
        // Display the available buses for the selected journey date
        $sql = "SELECT br.*, r.Route_name, r.Route_no
                FROM tbl_Bus_route br
                INNER JOIN tbl_Route r ON br.Route_id = r.Route_id
                WHERE br.Allocation_date = '$journeyDate'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Display search results
            echo "<html>";
            echo "<head>";
            echo "<title>Bus Stop Search Results</title>";
            // echo "<link rel='stylesheet' href='Search_stop.css'>"; // Link to an external CSS file
            echo "</head>";
            echo "<body>";
            echo "<div class='bus-container'>";

            while ($row = $result->fetch_assoc()) {
                // Display bus details here
                // You can use $row['Route_name'], $row['Route_no'], etc. to display the details

                // Example display:
                echo "<div class='bus-row'>";
                echo "<p class='bus-details'>";
                echo $row['Route_no'] . ' - ' . $row['Route_name'];
                // Display other details here
                echo "</p>";
                echo "<button class='book-button'>Book Ticket</button>";
                echo "</div>";
            }

            echo "</div>";
            echo "</body>";
            echo "</html>";
        } else {
            echo "No buses available on the selected journey date.";
        }
    } else {
        echo "No buses scheduled on the selected journey date.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bus Ticket Reservation</title>
    <link rel="stylesheet" href="Search.css">
</head>
<body>
    <h1>Bus Ticket Reservation</h1>
    <form id="searchForm" method="post" action="search_stops.php">
        <input type="text" id="start" name="start" list="startStops" placeholder="Starting From" required>
        <datalist id="startStops">
            <?php
            include "Dbconnect.php";
            $sql = "SELECT DISTINCT Stop_name FROM tbl_Stop ORDER BY Stop_name ASC"; // Order by stop names in ascending order
            $result = $conn->query($sql);
            $currentLetter = '';

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $stopName = $row['Stop_name'];
                    $firstLetter = strtoupper(substr($stopName, 0, 1));
                    
                    if ($firstLetter !== $currentLetter) {
                        // Display a letter heading
                        echo "<option value=''>$firstLetter</option>";
                        $currentLetter = $firstLetter;
                    }
                    
                    echo "<option value='$stopName'>$stopName</option>";
                }
            }
            ?>
        </datalist><br><br>

        <input type="text" id="destination" name="destination" list="destinationStops" placeholder="Destination" required>
        <datalist id="destinationStops">
            <?php
            include "Dbconnect.php";
            $sql = "SELECT DISTINCT Stop_name FROM tbl_Stop ORDER BY Stop_name ASC"; // Order by stop names in ascending order
            $result = $conn->query($sql);
            $currentLetter = '';

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $stopName = $row['Stop_name'];
                    $firstLetter = strtoupper(substr($stopName, 0, 1));
                    
                    if ($firstLetter !== $currentLetter) {
                        // Display a letter heading
                        echo "<option value=''>$firstLetter</option>";
                        $currentLetter = $firstLetter;
                    }
                    
                    echo "<option value='$stopName'>$stopName</option>";
                }
            }
            ?>
        </datalist><br><br>

        <input type="date" id="journeyDate" name="journeyDate" required placeholder="date"><br><br>

        <button type="submit">Search</button>
    </form>
</body>
</html>
