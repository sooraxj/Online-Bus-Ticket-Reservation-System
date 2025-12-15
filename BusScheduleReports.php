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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Schedule Reports</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.1/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .content {
            margin: 105px;
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

        input[type="text"] {
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
           margin-left:25%;
        }
    .space{
        margin-left:1px;
    }

    </style>
</head>
<body>
<div class="button-container">
<div class="pspace">
    <form action="BSchedule-pdf.php" method="POST">
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
        <button type="submit">🔍</button>
        </form>
        </div>

    </div>
<div class="content">
    <div class="main">
        <div class="form">
            <h1>Bus Schedule Reports</h1>
            <div class="table-responsive">
                <table id="table" class="table content-table pdf">
                    <thead class="thead-dark">
                        <tr>
                        <th>Sl.No</th>
                        <th>Bus Details</th>
                        <th>Driver Details</th>
                        <th>Route Id & Name</th>
                        <th>Allocation Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Bus-Route Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
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
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>
<script>
      function printBusPdfContent() {
        var printWindow = window.open('BSchedule-print.php', '_blank');
        printWindow.onload = function() {
            printWindow.print();
        }
    }
    document.getElementById('download-excel').addEventListener('click', function () {
    var wb = XLSX.utils.table_to_book(document.getElementById('table'));
    XLSX.writeFile(wb, 'BSchedule-reports.xlsx');
});

</script>