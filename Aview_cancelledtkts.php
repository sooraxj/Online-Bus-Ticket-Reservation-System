

<?php
include 'Dbconnect.php'; 
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$searchCondition = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $searchCondition = "AND (t.Ticket_number LIKE '%$search%' 
                        OR Passenger_name LIKE '%$search%'
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
<html>
<head>
    <title>Admin-Panel</title>
    <link rel="stylesheet" href="Abooking_view.css">
    <link rel="stylesheet" href="table.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.1/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.js"></script>
</head>
<body>

<div class="side-menu">
    <div class="brand-name">
        <div class="logo">
            <img src="logo2.png">
        </div>     
    <h1>Alliance</h1>
</div>
<ul class="ui">
  <li><a href="admin.php"><img src="ui.png"> Dashboard </a></li>
  <li><a href="Driver.php"><img src="ui.png">Drivers</a></li>
  <li><a href="Cust.php"><img src="ui.png">Customers</a></li>
  <li><a href="Bus.php"><img src="ui.png">Buses</a></li>
  <li><a href="Route.php"><img src="ui.png">Routes</a></li>
  <li><a href="Stop.php"><img src="ui.png">Stops</a></li>
  <li><a href="Driverbus.php"><img src="ui.png">Driver-Assign</a></li>
  <li><a href="Busroute.php"><img src="ui.png">Bus-Schedule</a></li>
  <li><a href="Farestage.php"><img src="ui.png">Fare Stage</a></li>
  <li><a href="Abooking_view.php"><img src="ui.png">Bookings</a></li>
</ul>
</div>
    <div class="container">
        <div class="header">
            <div class="nav">
            <button id="print-driver" class="btn1">Print</button> &nbsp;&nbsp;
<!-- <button id="download-pdf" class="btn1">Download PDF</button>&nbsp;&nbsp;
<button id="download-excel" class="btn1">Download Excel</button>                 -->
<div class="search">
                    <form method="get">
                        <input type="text" name="search" placeholder="Search..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                        <button type="submit">🔍</button>
                    </form>
                </div>
                <div class="user">
<a href="#" class=""></a>
<div class="img-case">
<img src="user.png" alt="user">
</div>
</div>            </div>
        </div>
        <div class="content">
            <div class="main">
                <div class="form">
                    <h1>Cancelled Bookings</h1>
                    <table id="table" class="content-table pdf">
                    <thead>
                <tr>
                <th>Ticket Number</th>
                    <th>Passenger Name</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Seat Number</th>
                    <th>Source</th>
                    <th>Destination</th>
                    <th>Journey Date</th>
                    <th>Options</th>
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
                        <td class="dropdown">
                        <button class="dropbtn">Actions</button>
                        <div class="dropdown-content">
                            <a href="Aticket.php?ticket_id=<?php echo $ticket["Ticket_id"];?>">View Ticket</button></a>
                            <a href="admin_view_ticket.php?ticket_id=<?php echo $ticket["Ticket_id"];?>">Edit Ticket</a>
                        </div>
                    </td>
                    </tr>
                <?php } ?>
            </tbody>
                       
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<script>
    document.getElementById('print-driver').addEventListener('click', function () {
        var printWindow = window.open('', '_blank');

        var printContent = '<html><head><title>Driver List</title>' +
            '<link rel="stylesheet" href="table.css">' +
            '</head><body>' +
            document.querySelector('.form').outerHTML + '</body></html>';

        printWindow.document.write(printContent);
        printWindow.document.close();

        printWindow.onload = function () {
            printWindow.print();
            printWindow.close();
        };
    });

</script>