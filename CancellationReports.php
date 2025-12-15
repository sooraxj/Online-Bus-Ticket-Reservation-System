<?php
include 'Dbconnect.php'; 
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$searchCondition = "";

if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
    $startDate = $_GET['start_date'];
    $endDate = $_GET['end_date'];
    $searchCondition = "AND t.Journey_date BETWEEN '$startDate' AND '$endDate'";
} 
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $searchCondition = "AND (t.Ticket_number LIKE '%$search%' 
                        OR bc.Passenger_name LIKE '%$search%'
                        OR t.Journey_date LIKE '%$search%')";
}

$ticketsSql = "SELECT t.*, c.C_fname, bm.Booking_date, GROUP_CONCAT(bc.Passenger_name) AS Passenger_names,
               GROUP_CONCAT(bc.Age) AS Ages, GROUP_CONCAT(bc.Gender) AS Genders,GROUP_CONCAT(bc.Seat_number) AS SeatNos,
               MIN(bc.Source) AS Start, MIN(bc.Destination) AS End
               FROM tbl_Ticket t
               INNER JOIN tbl_Customer c ON t.Customer_id = c.Customer_id
               LEFT JOIN tbl_Booking_master bm ON t.Booking_master_id = bm.Booking_master_id
               LEFT JOIN tbl_Booking_child bc ON t.Booking_master_id = bc.Booking_master_id
               WHERE t.Ticket_status = 0 $searchCondition
               GROUP BY t.Ticket_number
               ORDER BY t.Ticket_number";

$ticketsResult = $conn->query($ticketsSql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancellation Reports</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.1/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .content {
            margin: 160px;
        }

        .form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #000000;
            text-align:center;
        }

        th, td {
            text-align: center;
        }

        thead {
            background-color: #343a40;
            color: #ffffff;
        }

        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tbody tr:hover {
            background-color: #ffffff;
        }

        .button-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%; 
            background-color: #c9cfd4;
            padding: 10px;
            white-space: nowrap;
            text-align: center; 
        }

        .btn1 {
            margin-right: 10px;
            padding: 10px 20px;
            font-size: 16px;
            text-align: center;
            cursor: pointer;
            border: none;
            border-radius: 5px;
        }

        
        .btn1:hover{
            text-decoration:none;

        }

        #print-content {
            background-color: #4CAF50;
            color: white;
        }

        #download-pdf {
            background-color: #008CBA;
            color: white;
        }

        #download-excel {
            background-color: #f44336;
            color: white;
        }

        #Back {
            background-color: #000000;
            color: white;
        }


        body {
            margin: 0;
        }

        .search-bar-container {
            margin-top: 10px; 
        }

        input[type="text"],
        input[type="date"]  {
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button[type="submit"] {
            padding: 8px 12px;
            font-size: 16px;
            cursor: pointer;
            border: none;
            background-color: #008CBA;
            color: white;
            border-radius: 5px;
        }

        .button-container {
        display: flex;
        align-items: center; 
        
    }

    .button-container .search-bar-container {
        display: flex;
        align-items: center;
        justify-content: space-between; 
        margin-left:5%;

    }

    .button-container form {
        margin-right: 10px; 
    }

    .btn1 {
        margin-right: 10px;
    }

    .pspace{
        margin-top:1%;
           margin-left:11%;
        }
    .space{
        margin-left:1px;
    }

    </style>
</head>
<body>
        <div class="button-container">
        <div class="pspace">
    <form action="Cancel-pdf.php" method="POST">
    <input id="download-pdf"  class="pdf btn1" type="submit" value="Pdf">
    </form>
   </div>
        <div class="space">
        <button id="print-content" class="btn1" onclick="printBusPdfContent()">Print</button>
        <button id="download-excel" class="btn1">Excel</button>
        <a href="Reports.php" class="btn1 btn-primary home" id="Back">Back</a>
        </div>
        <div class="search-bar-container">
        <form method="get" autocomplete="off">
        <input type="text" name="search" placeholder="Search..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
        <button type="submit">🔍 Search</button>
        </form>
        <form method="get" autocomplete="off">
            <label for="date-range">Date Range:</label>
            <input type="date" name="start_date" value="<?php echo isset($_GET['start_date']) ? $_GET['start_date'] : ''; ?>">
            to
            <input type="date" name="end_date" value="<?php echo isset($_GET['end_date']) ? $_GET['end_date'] : ''; ?>">

            <button type="submit">🔍 Search</button>
        </form>
        </div>

    </div>
<div class="content">
    <div class="main">
        <div class="form">
            <h1>Cancellation Reports</h1>
            <div class="table-responsive">
                <table id="table" class="table content-table pdf">
                    <thead class="thead-dark">
                        <tr>
                    <th>Ticket Number</th>
                    <th>Passenger Name</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Seat Number</th>
                    <th>Source</th>
                    <th>Destination</th>
                    <th>Journey Date</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php                
                while ($ticket = $ticketsResult->fetch_assoc()) {
                ?>
                    <tr>
                        <td><?php echo $ticket['Ticket_number']; ?></td>
                        <td><?php echo $ticket['Passenger_names']; ?></td>
                        <td><?php echo $ticket['Ages']; ?></td>
                        <td><?php echo $ticket['Genders']; ?></td>
                        <td><?php echo $ticket['SeatNos']; ?></td>
                        <td><?php echo $ticket['Start']; ?></td>
                        <td><?php echo $ticket['End']; ?></td>
                        <td><?php echo $ticket['Journey_date']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>
<script>
      function printBusPdfContent() {
        var printWindow = window.open('Cancel-print.php', '_blank');
        printWindow.onload = function() {
            printWindow.print();
        }
    }
    document.getElementById('download-excel').addEventListener('click', function () {
    var wb = XLSX.utils.table_to_book(document.getElementById('table'));
    XLSX.writeFile(wb, 'Cancel-reports.xlsx');
});

</script>