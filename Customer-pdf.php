<?php
date_default_timezone_set('UTC');
require_once 'vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf();

$a = '';

$a .= '
<!DOCTYPE html>
<html>
<head>
    <style>
    .headers {
        margin-top:-10%;
        text-align: center;
        border-bottom:2px solid #090979;

        padding:5px;
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
            text-align: center;
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
        h1 {
            text-align: center;
        }
        /* Other CSS styles for your PDF report */
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
<h1>Customer Reports</h1>

<table>
    <tr>
        <th>Sl.No</th>
        <th>Name</th>
        <th>E-mail</th>
        <th>Mobile No</th>
        <th>City</th>
        <th>State</th>
        <th>Gender</th>
        <th>Dob</th>
    </tr>
';

include 'Dbconnect.php';

$sql = "SELECT * FROM tbl_Customer";
$result = $conn->query($sql);

if (!$result) {
    die("Invalid query:" . $conn->error);
}
$serialNumber = 1;

while ($row = $result->fetch_assoc()) {
    $status = ($row['C_Status'] == 1) ? 'Active' : 'Inactive';
    $gender = $row['C_gender'];

    if ($gender === 'M') {
        $genderDisplay = 'Male';
    } elseif ($gender === 'F') {
        $genderDisplay = 'Female';
    } else {
        $genderDisplay = 'Others';
    }
    $a .= '
        <tr>
            <td>' . $serialNumber . '</td>
            <td>' . $row['C_fname'] . ' ' . $row['C_mname'] . ' ' . $row['C_lname'] . '</td>
            <td>' . $row['Username'] . '</td>
            <td>' . $row['C_phone'] . '</td>
            <td>' . $row['C_city'] . '</td>
            <td>' . $row['C_state'] . '</td>
            <td>' . $genderDisplay . '</td>
            <td>' . $row['C_dob'] . '</td>
        </tr>
    ';

    $serialNumber++;
}

$a .= '</table></body></html>';

$mpdf->SetHTMLHeader('<div class="header"></div>');
$mpdf->SetHTMLFooter('<div class="footer">Page {PAGENO} of {nb}</div>');

$mpdf->WriteHTML($a);
$mpdf->Output('Customer-reports.pdf', 'F');
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="Customer-reports.pdf"');
readfile('Customer-reports.pdf');
?>
