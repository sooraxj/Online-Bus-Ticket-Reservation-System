<html>
<head>
    <title>Admin-Panel</title>
    <link rel="stylesheet" href="Farestage.css">
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
<a href="Afarestage.php" class=""></a>
<div class="img-case">
<img src="user.png" alt="user">
</div>
</div>            </div>
        </div>
        <div class="content">
            <div class="main">
                <div class="form">
                    <h1>Fare List</h1>
                    <table id="table" class="content-table pdf">
                        <thead>
                        <tr>
                            <th>Sl.No</th>
                            <th>Base Fare</th>
                            <th>Additional Fare per Km</th>
                            <th>Farestage Status</th>
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
                        $sql = "SELECT * FROM tbl_Farestage WHERE Farestage_id LIKE '%$search%' OR Base_fare LIKE '%$search%'";
                        $result = $conn->query($sql);

                        if (!$result) {
                            die("Invalid query:" . $conn->error);
                        }
                        $serialNumber=1;
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            "<td>" . $row["Farestage_id"] . "</td>";
                            echo "<td>" . $serialNumber . "</td>"; 
                            echo "<td>" . $row["Base_fare"] . "</td>";
                            echo "<td>" . $row["Additional_fare"] . "</td>";
                            echo "<td>" . ($row["F_status"] == 1 ? 'Active' : 'Inactive') . "</td>";
                            echo '<td><a href="Ufarestage.php?Farestage_id=' . $row["Farestage_id"] . '"><button>Edit</button></a></td>';
                            echo "</tr>";
                        }
                        $serialNumber++;
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

    document.getElementById('download-excel').addEventListener('click', function () {
        var wb = XLSX.utils.table_to_book(document.getElementById('table'));
        XLSX.writeFile(wb, 'Farestage.xlsx');
    });

    document.getElementById('download-pdf').addEventListener('click', function () {
        var element = document.querySelector('table'); 

        html2pdf(element, {
            margin: 10,
            filename: 'Farestage.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
        });
    });
</script>