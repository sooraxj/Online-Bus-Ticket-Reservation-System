<html>
<head>
    <title>Admin-Panel</title>
    <link rel="stylesheet" href="Busroute.css"> 
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
<!-- <button class="toggle-button" id="menu">|||</button> -->
                <div class="search">
                    <form method="GET" action="">
                        <input type="text" name="search" placeholder="Search..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                        <button type="submit">🔍</button>
                    </form>
                </div>
                <div class="user">
<a href="Abusroute.php" class="btn">Schedule</a>
<div class="img-case">
<img src="user.png" alt="user">
</div>
</div>
 </div>
        </div>
        <div class="content">
            <div class="main">
                <div class="form">
                    <h1>Bus Schedules</h1>
       
                    <table id="table" class="content-table pdf">
        <thead>
            <tr>
                <th>Sl.No</th>
                <th>Bus Details</th>
                <th>Driver Details</th>
                <th>Route Id & Name</th>
                <th>Allocation Date</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Bus-Route Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include 'Dbconnect.php';
            session_start();
            if (!isset($_SESSION["username"])) {
                header("Location: login.php");
                exit();
            }


            $search = isset($_GET['search']) ? $_GET['search'] : '';
            $sql = "SELECT Bus_route_id, br.Driver_bus_id, CONCAT('Id-',b.Bus_id, '<BR>', b.Bus_Reg_no) AS BusAndRegNo, CONCAT('Id-',db.Driver_id, '<BR>', d.D_fname, ' ', d.D_lname) AS DriverInfo, br.Route_id, r.Route_name, br.Allocation_date, br.Start_time, br.End_time, br.Br_status
                    FROM tbl_Bus_route br
                    INNER JOIN tbl_Driver_Bus db ON br.Driver_bus_id = db.Driver_bus_id
                    INNER JOIN tbl_Bus b ON db.Bus_id = b.Bus_id
                    INNER JOIN tbl_Driver d ON db.Driver_id = d.Driver_id
                    INNER JOIN tbl_route r ON br.Route_id = r.Route_id
                    WHERE Bus_route_id LIKE '%$search%'
                    OR br.Driver_bus_id LIKE '%$search%'
                    OR b.Bus_Reg_no LIKE '%$search%'
                    OR db.Driver_id LIKE '%$search%'
                    OR d.D_fname LIKE '%$search%'
                    OR d.D_lname LIKE '%$search%'
                    OR r.Route_id LIKE '%$search%'
                    OR r.Route_name LIKE '%$search%'";
                    $result = $conn->query($sql);

            if (!$result) {
                echo "<script> alert(\"No Result Found!....\");</script>";
            }
               $serialNumber=1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                "<td>" . $row["Bus_route_id"] . "</td>";
                 "<td>" . $row["Driver_bus_id"] . "</td>";
                 echo "<td>" . $serialNumber . "</td>"; 
                echo "<td>" . $row["BusAndRegNo"] ."</td>";
                echo "<td>" . $row["DriverInfo"] . "</td>";
                echo "<td>" ."Id-". $row["Route_id"] . "<br>" . $row["Route_name"] . "</td>";
                echo "<td>" . $row["Allocation_date"] . "</td>";
                echo "<td>" . $row["Start_time"] . "</td>";
                echo "<td>" . $row["End_time"] . "</td>";
                echo "<td>" . ($row["Br_status"] == 1 ? 'Active' : 'Inactive') . "</td>";
                echo '<td><a href="Ubusroute.php?Bus_route_id=' . $row["Bus_route_id"] . '"><button>Edit</button></a></td>';
                echo "</tr>";
                $serialNumber++;
            }
            ?>
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