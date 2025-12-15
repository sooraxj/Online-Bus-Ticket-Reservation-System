<html>
<head>
    <title>Admin-Panel</title>
    <link rel="stylesheet" href="Stop.css">
    <link rel="stylesheet" href="table.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.1/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.js"></script>
    <style>
     
    </style>
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
<button id="download-excel" class="btn1">Download Excel</button>               
 <button class="toggle-button" id="menu">|||</button> -->
                <div class="search">
                    <form method="GET" action="">
                        <input type="text" placeholder="Enter Route ID" name="search_route_id">
                        <button type="submit">🔍</button>
                    </form>
                </div>
                <div class="user">
                <a href="Astop.php" class="btn">Add Stop</a>
                    <div class="img-case">
                        <img src="user.png" alt="user">
                    </div>    
                 </div>
            </div>
        </div>
        <div class="content">
            <?php
            include 'Dbconnect.php';
            session_start();
            if (!isset($_SESSION["username"])) {
                header("Location: login.php");
                exit();
            }

            if (isset($_GET['search_route_id'])) {
                $search_route_id = $_GET['search_route_id'];
                $sql = "SELECT s.*, r.Route_name FROM tbl_Stop s
                        JOIN tbl_Route r ON s.Route_id = r.Route_id
                        WHERE s.Route_id = '$search_route_id'";

                $result = $conn->query($sql);
                if (!$result) {
                    die("Invalid query: " . $conn->error);
                }
            }
            ?>

            <div class="main">
                <div class="form">
                    <?php if (isset($_GET['search_route_id']) && $result->num_rows > 0): ?>
                    <h1>Stop List</h1>
                    <table id="table" class="content-table pdf">
                        <thead>
                        <tr>
                        <th>Stop Number</th>
                            <th>Route Name</th>
                            <th>Stop Name</th>
                            <th>Arrival Time</th>
                            <th>Distance</th>
                            <th>Stop Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            "<td>" . $row["Stop_id"] . "</td>";
                            echo "<td>" . $row["Stop_no"] . "</td>";
                            echo "<td>" . $row["Route_name"] . "</td>";
                            echo "<td>" . $row["Stop_name"] . "</td>";
                            echo "<td>" . $row["Arrival_time"] . "</td>";
                            echo "<td>" . $row["Distance"] . "</td>";
                            echo "<td>" . ($row["Stop_status"] == 1 ? 'Active' : 'Inactive') . "</td>";
                            echo '<td><a href="Ustop.php?Stop_id=' . $row["Stop_id"] . '"><button>Edit</button></a></td>';
                            echo "</tr>";
                        }
                        ?>
                     </tbody>
                    </table>
                    <?php else: ?>
                    <h1>Route List</h1>
                    <table class="content-table">
                        <thead>
                        <tr>
                            <th>Route Id</th>
                            <th>Route Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $routeQuery = "SELECT * FROM tbl_Route";
                        $routeResult = $conn->query($routeQuery);
                        if (!$routeResult) {
                            die("Invalid query: " . $conn->error);
                        }
                        while ($routeRow = $routeResult->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $routeRow["Route_id"] . "</td>";
                            echo "<td>" . $routeRow["Route_name"] . "</td>";
                            echo "</tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
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