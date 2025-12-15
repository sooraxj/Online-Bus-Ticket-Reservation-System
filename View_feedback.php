<?php
include 'Dbconnect.php';
include 'Dbconnect.php';
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$feedbackSql = "SELECT f.*, c.C_fname, t.Ticket_number 
                FROM tbl_Feedback f
                JOIN tbl_customer c ON f.Customer_id = c.Customer_id
                JOIN tbl_ticket t ON f.Ticket_id = t.Ticket_id";
$feedbackResult = $conn->query($feedbackSql);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Feedback View</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.1/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.js"></script>
    <style>
        body {
            /* background: linear-gradient(to top, #cfd9df 0%, #e2ebf0 100%); */
            background-color: #fff;

            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        .feedback-table {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-weight:bold;
        }
        .table-responsive {
            overflow-x: auto;
        }
        .table {
            margin-top:6%;
            margin-left:10%;
            width: 80%;
            border-collapse: collapse;
        }
        .table th, .table td {
            padding: 12px;
            text-align: center;
            vertical-align: middle;
        }
        .table thead th {
            background: radial-gradient( rgba(7,7,9,1),  rgba(27,24,113,1));
            color: #fff;
        }
        .table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

 

       .buttons {
            /* position: absolute; */
            margin-left:32%;
        }

        .home {
            margin-top:-2%;
            margin-left:90%;
            background: linear-gradient(111.4deg, rgba(7,7,9,1) 6.5%, rgba(27,24,113,1) 93.2%);
        }        
        .btn2 {
            margin-right: 10px;
            padding: 10px 20px;
            font-size: 16px;
            text-align: center;
            cursor: pointer;
            border: none;
            border-radius: 5px;
        }
        .btn2:hover{
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
        .button-container {
        display: flex;
        align-items: center; 
        
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
    .table h1{
        margin-top:3%;
    }

    </style>
</head>
<body>
<div class="button-container">
        <div class="pspace">
    <form action="Feedback-pdf.php" method="POST">
    <input id="download-pdf"  class="pdf btn1" type="submit" value="Pdf">
    </form>
   </div>
        <div class="space">
        <button id="print-content" class="btn1" onclick="printBusPdfContent()">Print</button>
        <button id="download-excel" class="btn1">Excel</button>
        <a href="admin.php" class="btn1 btn-primary home" id="Back">Home</a>  
        </div>                  
    </div>    
    
        <div class="table">


        <h1>Feedbacks</h1>

        <div class="table-responsive feedback-table">

            <table id="table" class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Customer ID</th>
                        <th>Customer Name</th>                       
                        <th>Ticket Number</th>
                        <th>Bus Rating</th>
                        <th>Driver Rating</th>
                        <th>Comment</th>
                        <th>Complaint</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $feedbackResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['Customer_id']; ?></td>
                        <td><?php echo $row['C_fname']; ?></td>
                        <td><?php echo $row['Ticket_number']; ?></td>
                        <td><?php echo $row['Bus_rating']; ?></td>
                        <td><?php echo $row['Driver_rating']; ?></td>
                        <td><?php echo $row['Comment']; ?></td>
                        <td><?php echo $row['Complaint'] == 1 ? 'Yes' : 'No'; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
<script>
      function printBusPdfContent() {
        var printWindow = window.open('Feedback-print.php', '_blank');
        printWindow.onload = function() {
            printWindow.print();
        }
    }
    document.getElementById('download-excel').addEventListener('click', function () {
    var wb = XLSX.utils.table_to_book(document.getElementById('table'));
    XLSX.writeFile(wb, 'Feedback-reports.xlsx');
});

</script>