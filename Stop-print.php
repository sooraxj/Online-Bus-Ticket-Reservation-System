<?php
date_default_timezone_set('UTC');
require_once 'vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf();

include 'Dbconnect.php';

$sql = "SELECT s.*, r.Route_name FROM tbl_Stop s
        JOIN tbl_Route r ON s.Route_id = r.Route_id";
$result = $conn->query($sql);

if (!$result) {
    die("Invalid query:" . $conn->error);
}
$serialNumber = 1;

$a = '';
$a .= '
<!DOCTYPE html>
<html>
<head>
    <style>
        .headers {
            margin-top: -10%;
            text-align: center;
            border-bottom: 2px solid #090979;
            padding: 5px;
        }
        .headers img {
            max-width: 150px;
        }
        .headers p {
            margin: 5px 0;
        }
        .footer {
            text-align: center;
            font-size: 10pt;
            border-top: 1px solid #000000;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #000;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #fff;
        }
        tr:nth-child(odd) {
            background-color: #f2f2f2;
        }
        .brand-logo {
            text-align: center;
            font-family: "Dancing Script", cursive;
            font-weight: bold;
            font-size: 58px;
            color: #090979;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }
        .brand-logo:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease-in-out;
            color: #001F3F;
        }
        .contact-info {
            font-size: 14px;
            font-weight: bold;
            margin: 10px 0;
        }
        .date-time {
            margin-left: 70%;
            font-size: 14px;
            font-weight: bold;
        }
        h1 {
            text-align: center;
        }
    </style>
</head>
<body>
<div class="headers">
    <img src="img/Logo.jpg" alt="Alliance" height="80" width="180">
    <div class="contact-info" style="float: left; width: 25%;">
        Kochi, 9995 993 395<br>
        info@alliance.com
    </div>
    <div class="date-time" style="float: right; width: 30%;">
        Date: ' . date('d F Y') . '<br>
        Time: ' . date('h:i A', strtotime('+5 hours 30 minutes')) . '
    </div>
    <div style="clear: both;"></div>
</div>
<h1>Stop Reports</h1>

<table>
    <tr>
    <th>Sl.No</th>
    <th>Route Name</th>
    <th>Stop Name</th>
    <th>Arrival Time</th>
    <th>Distance</th>
    <th>Stop Status</th>
    </tr>
    ';

while ($row = $result->fetch_assoc()) {
    $a .= '
        <tr>
        <td>' . $serialNumber . '</td>
        <td>' . $row['Route_name'] . '</td>
        <td>' . $row['Stop_name'] . '</td>
        <td>' . $row['Arrival_time'] . '</td>
        <td>' . $row['Distance'] . '</td>
        <td>' . ($row['Stop_status'] == 1 ? 'Active' : 'Inactive') . '</td>
        </tr>
    ';

    $serialNumber++;
}

$a .= '</table></body></html>';

$mpdf->SetHTMLHeader('<div class="header"></div>');
$mpdf->SetHTMLFooter('<div class="footer">Page {PAGENO} of {nb}</div>');

$mpdf->WriteHTML($a);
$mpdf->Output('Stop-reports.pdf', 'F');
header('Content-Type: application/pdf');
readfile('Stop-reports.pdf');
?>
