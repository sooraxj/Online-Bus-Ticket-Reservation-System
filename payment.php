<?php
include 'header.php';
include 'Dbconnect.php';
$customerID = $_SESSION['Customer_id'];
$busRouteID = $_GET['bus_route_id'];
$journeyDate = $_GET['journeyDate'];
$starttime = $_GET['starttime'];
$desttime = $_GET['desttime'];
$totalFare = $_GET['fare'];
$start = $_GET['start'];
$distance = $_GET['distance'];
$destination = $_GET['dest'];
$totalprice = $_GET['cost'];
$totalPassengers = $_GET['totalpassengers'];
$customer_id = $_SESSION['Customer_id'];

$existingCardsSql = "SELECT Card_id, Card_holder, Exp_date, Card_no, Cvv FROM tbl_Card WHERE Customer_id = '$customer_id'";
$existingCardsResult = $conn->query($existingCardsSql);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $paymentOption = $_POST['paymentOption'];

    if ($paymentOption === 'new') {
        $cardNumber = $_POST['cardNumber'];
        $cardHolder = $_POST['cardHolder'];
        $cvv = $_POST['cvv'];
        $expDate = $_POST['expDate'];

        $query = "SELECT Farestage_id FROM tbl_farestage";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $fareStageID = $row['Farestage_id'];
        }
        $passcount = $totalPassengers;
        $totalCost = $totalFare * $totalPassengers;
        $stopsBetween = getStopsBetween($conn, $busRouteID, $start, $destination);

        $insertMasterQuery = "INSERT INTO tbl_Booking_master (Customer_id, Bus_route_id, Farestage_id, Booking_date, Total_fare, Distance)
        VALUES ('$customerID', '$busRouteID', '$fareStageID', '$journeyDate', '$totalCost', '$distance')";

        $result = mysqli_query($conn, $insertMasterQuery);

        if ($result) {
            $booking_master_id = mysqli_insert_id($conn);

            $passengers = $_SESSION['passengers'];

            foreach ($passengers as $passengerInfo) {
                $passengerName = $passengerInfo['name'];
                $passengerAge = $passengerInfo['age'];
                $passengerGender = $passengerInfo['gender'];
                $passengerSeat = $passengerInfo['seat'];
                $totalPassengers++;

                foreach ($stopsBetween as $stop) {
                    $sourceStop = $stop['source'];
                    $destinationStop = $stop['destination'];

                    $insertChildQuery = "INSERT INTO tbl_Booking_child (Booking_master_id, Passenger_name, Age, Gender, Seat_number, Source, Destination, Total_seats, Booking_status)
                    VALUES ('$booking_master_id', '$passengerName', '$passengerAge', '$passengerGender', '$passengerSeat', '$sourceStop', '$destinationStop', '$passcount', 1)";

                    $resultChild = mysqli_query($conn, $insertChildQuery);

                    if (!$resultChild) {
                        echo 'Error inserting passenger details: ' . mysqli_error($conn);
                    }
                }
            }
        } else {
            echo 'Error inserting booking master details: ' . mysqli_error($conn);
        }

        $existingCardQuery = "SELECT Card_id FROM tbl_Card WHERE Card_no = '$cardNumber'";
        $existingCardResult = $conn->query($existingCardQuery);

        if ($existingCardResult->num_rows > 0) {
            echo '<div id="cardExistsAlert" class="alert alert-danger">Card number already exists.</div>';
        } else {
            if (strtotime($expDate) < strtotime(date('Y-m-d'))) {
                echo '<div class="alert alert-danger">Card is expired.</div>';
            } else {
                $cardInsertSql = "INSERT INTO tbl_Card (Customer_id, Card_no, Card_holder, Cvv, Exp_date) VALUES ('$customer_id', '$cardNumber', '$cardHolder', '$cvv', '$expDate')";
                if ($conn->query($cardInsertSql) === TRUE) {
                    $card_id = $conn->insert_id;

                    $payment_date = date('Y-m-d');
                    $payment_status = true;
                    $paymentInsertSql = "INSERT INTO tbl_Payment (Booking_master_id, Card_id, Payment_date, Payment_status)
                        VALUES ('$booking_master_id', '$card_id', '$payment_date', '$payment_status')";

                    if ($conn->query($paymentInsertSql) === TRUE) {
                        $payment_id = $conn->insert_id;

                        $ticket_number = generateTicketNumber($conn);
                        $journey_time = $_GET['journeytime'];
                        $seat_number = 1;
                        $ticket_status = true;

                        $ticketInsertSql = "INSERT INTO tbl_Ticket (Customer_id,Booking_master_id, Payment_id, Ticket_number, Journey_date,Journey_stime,Journey_etime,Ticket_status)
                            VALUES ('$customer_id','$booking_master_id', '$payment_id', '$ticket_number', '$journeyDate', '$starttime', '$desttime','$ticket_status')";

                        if ($conn->query($ticketInsertSql) === TRUE) {
                            $Ticket_id = $conn->insert_id;

                            $reduceSeatsQuery = "UPDATE tbl_bus_route SET Avl_seats = Avl_seats - $totalPassengers WHERE Bus_route_id = '$busRouteID'";
                            mysqli_query($conn, $reduceSeatsQuery);

                            echo '<div class="alert alert-success">Booking successful. Your Ticket Number is ' . $ticket_number . '</div>';
                        } else {
                            echo 'Error creating ticket: ' . $conn->error;
                        }
                    } else {
                        echo 'Error creating payment: ' . $conn->error;
                    }
                } else {
                    echo 'Error creating card: ' . $conn->error;
                }
            }
        }
    } elseif ($paymentOption === 'existing') {
        $existingCardID = $_POST['existingCardSelect'];
        $enteredCvv = $_POST['ecvv'];

        $query = "SELECT Farestage_id FROM tbl_farestage";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $fareStageID = $row['Farestage_id'];
        }
        $passcount = $totalPassengers;
        $totalCost = $totalFare * $totalPassengers;
        $stopsBetween = getStopsBetween($conn, $busRouteID, $start, $destination);

        $insertMasterQuery = "INSERT INTO tbl_Booking_master (Customer_id, Bus_route_id, Farestage_id, Booking_date, Total_fare, Distance)
        VALUES ('$customerID', '$busRouteID', '$fareStageID', '$journeyDate', '$totalCost', '$distance')";

        $result = mysqli_query($conn, $insertMasterQuery);

        if ($result) {
            $booking_master_id = mysqli_insert_id($conn);

            $passengers = $_SESSION['passengers'];

            foreach ($passengers as $passengerInfo) {
                $passengerName = $passengerInfo['name'];
                $passengerAge = $passengerInfo['age'];
                $passengerGender = $passengerInfo['gender'];
                $passengerSeat = $passengerInfo['seat'];
                $totalPassengers++;

                foreach ($stopsBetween as $stop) {
                    $sourceStop = $stop['source'];
                    $destinationStop = $stop['destination'];

                    $insertChildQuery = "INSERT INTO tbl_Booking_child (Booking_master_id, Passenger_name, Age, Gender, Seat_number, Source, Destination, Total_seats, Booking_status)
                    VALUES ('$booking_master_id', '$passengerName', '$passengerAge', '$passengerGender', '$passengerSeat', '$sourceStop', '$destinationStop', '$passcount', 1)";

                    $resultChild = mysqli_query($conn, $insertChildQuery);

                    if (!$resultChild) {
                        echo 'Error inserting passenger details: ' . mysqli_error($conn);
                    }
                }
            }
        } else {
            echo 'Error inserting booking master details: ' . mysqli_error($conn);
        }

        $existingCardQuery = "SELECT Card_id, Cvv FROM tbl_Card WHERE Card_id = '$existingCardID' AND Customer_id = '$customer_id'";
        $existingCardResult = $conn->query($existingCardQuery);

        if ($existingCardResult->num_rows > 0) {
            $cardRow = $existingCardResult->fetch_assoc();
            $card_id = $cardRow['Card_id'];
            $storedCvv = $cardRow['Cvv'];
            if ($enteredCvv == $storedCvv) {
                $payment_date = date('Y-m-d');
                $payment_status = true;
                $paymentInsertSql = "INSERT INTO tbl_Payment (Booking_master_id, Card_id, Payment_date, Payment_status)
                    VALUES ('$booking_master_id', '$card_id', '$payment_date', '$payment_status')";

                if ($conn->query($paymentInsertSql) === TRUE) {
                    $payment_id = $conn->insert_id;

                    $ticket_number = generateTicketNumber($conn);
                    $seat_number = 1;
                    $ticket_status = true;

                    $ticketInsertSql = "INSERT INTO tbl_Ticket (Customer_id,Booking_master_id, Payment_id, Ticket_number, Journey_date,Journey_stime,Journey_etime,Ticket_status)
                        VALUES ('$customer_id','$booking_master_id', '$payment_id', '$ticket_number', '$journeyDate', '$starttime', '$desttime','$ticket_status')";

                    if ($conn->query($ticketInsertSql) === TRUE) {
                        $Ticket_id = $conn->insert_id;

                        $reduceSeatsQuery = "UPDATE tbl_bus_route SET Avl_seats = Avl_seats - $totalPassengers WHERE Bus_route_id = '$busRouteID'";
                        mysqli_query($conn, $reduceSeatsQuery);

                        echo '<div class="alert alert-success">Booking successful. Your Ticket Number is ' . $ticket_number . '</div>';
                    } else {
                        echo 'Error creating ticket: ' . $conn->error;
                    }
                } else {
                    echo 'Error creating payment: ' . $conn->error;
                }
            } else {
                echo '<div class="alert alert-danger">Invalid CVV.</div>';
            }
        } else {
            echo '<div class="alert alert-danger">Card not found.</div>';
        }
    }
}

function getStopsBetween($conn, $routeId, $source, $destination) {
    $query = "SELECT Stop_no, Stop_name FROM tbl_Stop
              WHERE Route_id = '$routeId' AND Stop_no BETWEEN (
                SELECT Stop_no FROM tbl_Stop WHERE Route_id = '$routeId' AND Stop_name = '$source'
              ) AND (
                SELECT Stop_no FROM tbl_Stop WHERE Route_id = '$routeId' AND Stop_name = '$destination'
              )";

    $result = mysqli_query($conn, $query);

    $stops = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $stops[] = array('source' => $row['Stop_name'], 'destination' => $destination);
    }

    return $stops;
}

function generateTicketNumber($conn) {
    $lastTicketNumberSql = "SELECT Ticket_number FROM tbl_Ticket ORDER BY Ticket_id DESC LIMIT 1";
    $lastTicketNumberResult = $conn->query($lastTicketNumberSql);

    if ($lastTicketNumberResult->num_rows > 0) {
        $lastTicketNumberData = $lastTicketNumberResult->fetch_assoc();
        $lastTicketNumber = intval(substr($lastTicketNumberData['Ticket_number'], 5));
        $newTicketNumber = sprintf("AL-23%03d", $lastTicketNumber + 1);
    } else {
        $newTicketNumber = "AL-23001";
    }

    return $newTicketNumber;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="payment.css">
</head>
<body>
    <div class="container">
        <h1>Payment Details</h1>
        <hr>
        <div class="total-fare text-center">
            <h3>Total Fare: ₹<?php echo number_format($totalprice, 2); ?></h3>
        </div>
        <form action="" method="post">
            <div class="form-group payment-options">
                <!-- <label>Select Payment Option:</label> -->
                <div class="custom-control custom-radio existing-card-label">
                    <input type="radio" id="existingOption" name="paymentOption" value="existing" class="custom-control-input">
                    <label class="custom-control-label" for="existingOption">Use Existing Card</label>
                </div>
                <div class="custom-control custom-radio new-card-label">
                    <input type="radio" id="newOption" name="paymentOption" value="new" class="custom-control-input">
                    <label class="custom-control-label" for="newOption">Add New Card</label>
                </div>
            </div>

                     <!-- Existing Cards Section -->
<div id="existingCardForm" style="display: none;">
<div class="form-group">
        <input type="text" class="form-control" name="ecvv" maxlength="3" placeholder="CVV">
        </div>   

    <?php
    while ($row = $existingCardsResult->fetch_assoc()) {
        $last4Digits = substr($row['Card_no'], -4);
      

        echo '<div class="card mb-3">
                <div class="card-body">
                    <div class="custom-control custom-radio">
                        <input type="radio" id="existingCard_' . $row['Card_id'] . '" name="existingCardSelect" value="' . $row['Card_id'] . '" class="custom-control-input">
                        <label class="custom-control-label" for="existingCard_' . $row['Card_id'] . '">Select this card</label>
                    </div>
                    <h5 class="card-title">xxx-xxxx-xxxx ' . $last4Digits . '</h5><br>
                    
                    
                    <p class="card-text">Cardholder Name: ' . $row['Card_holder'] . '</p><br><br><br>
                    <p class="card-text">Expiry Date: ' . $row['Exp_date'] . '</p>
                </div>
            </div>';
    }
    ?>
    <!-- <a href="cardremove.php?cardId=' . $row['Card_id'] . '"><i class="fas fa-times"></i></a> -->

</div>




<!-- New Card Section -->
<div id="newCardForm" style="display: none;">
    <div class="credit-card">
        <div class="credit-card-front">
            <div class="chip"></div>
            <input type="text" class="form-control card-number" name="cardNumber" maxlength="20" placeholder="Card Number">
            <input type="text" class="form-control card-holder" name="cardHolder" maxlength="20" placeholder="Card Holder">
            <div class="date-cvv-container">
                <input type="date" class="form-control exp-date" name="expDate" placeholder="MM/YY" maxlength="5">
                <span class="placeholder-text">Expiry Date</span>
                <div class="cvv-container">
                    <input type="text" class="form-control cvv-input" name="cvv" placeholder="CVV" maxlength="3" placeholder="XXX">
                    <div class="card-type"><img src=""></div>
                </div>
            </div>
        </div>
    </div>
</div>


            <div id="cardExistsAlert" class="alert alert-danger" style="display: none;">Card already exists.</div>
            <input type="hidden" name="submit" value="true">
            <button type="submit" name="submit" class="btn btn-primary payment-button">Pay</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const existingOption = document.getElementById('existingOption');
            const newOption = document.getElementById('newOption');
            const existingCardForm = document.getElementById('existingCardForm');
            const newCardForm = document.getElementById('newCardForm');
            const cardExistsAlert = document.getElementById('cardExistsAlert');

            existingOption.addEventListener('change', function () {
                existingCardForm.style.display = this.checked ? 'block' : 'none';
                newCardForm.style.display = this.checked ? 'none' : 'block';
                cardExistsAlert.style.display = 'none';
            });

            newOption.addEventListener('change', function () {
                newCardForm.style.display = this.checked ? 'block' : 'none';
                existingCardForm.style.display = this.checked ? 'none' : 'block';
                cardExistsAlert.style.display = 'none';
            });
        });
    </script>
</body>
</html>
o