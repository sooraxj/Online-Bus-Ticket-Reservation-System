<?php
// Include the mPDF library
// require_once 'vendor/autoload.php'; // Adjust the path as needed
require_once 'vendor/autoload.php'; // Adjust the path as needed


// Create a new mPDF instance
$mpdf = new \Mpdf\Mpdf();

// Your HTML content goes here (replace with the content you want to print)

$a = '';
$a .= '
<body>
<img src="lo.jpg" width="100px" height ="100px" style="position:absolute;left:50%; transform: translateX(280%);">
<h1 style="text-align: center;">Online Laptop Store</h1>
<hr>

<h1 style="text-align:center;">Driver Reports</h1>

<table style="width:100%;border-collapse: collapse;">
<tr style="border: 1px solid black;">
<th style="border: 1px solid black;">Sl.No</th>
<th style="border: 1px solid black;">Name</th>
<th style="border: 1px solid black;">E-mail</th>
<th style="border: 1px solid black;">Mobile No</th>
<th style="border: 1px solid black;">Licence No</th>
<th style="border: 1px solid black;">Licence Expiry</th>
<th style="border: 1px solid black;">Badge No</th>
<th style="border: 1px solid black;">Street</th>
<th style="border: 1px solid black;">City</th>
<th style="border: 1px solid black;">District</th>
<th style="border: 1px solid black;">State</th>
<th style="border: 1px solid black;">Pin Code</th>
<th style="border: 1px solid black;">Gender</th>
<th style="border: 1px solid black;">Dob</th>
<th style="border: 1px solid black;">Join Date</th>
<th style="border: 1px solid black;">Experience</th>
</tr>
';
include "Dbconnect.php";

$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT * FROM tbl_Driver WHERE Driver_id LIKE '%$search%' OR D_fname LIKE '%$search%' OR D_mname LIKE '%$search%' OR D_lname LIKE '%$search%'";
$result = $conn->query($sql);

if (!$result) {
    die("Invalid query:" . $conn->error);
}
$serialNumber = 1;

while ($row = $result->fetch_assoc()) {
    $a .= '
    <tr style="border: 1px solid black;">
        <td style="border: 1px solid black;">'. $serialNumber .'</td>
        <td style="border: 1px solid black;">'.$row["D_fname"].' '.$row["D_mname"].' '.$row["D_lname"].'</td>
        <td style="border: 1px solid black;">'.$row["D_email"].'</td>
        <td style="border: 1px solid black;">'.$row["D_phone"].'</td>
        <td style="border: 1px solid black;">'.$row["D_licence_no"].'</td>
        <td style="border: 1px solid black;">'.$row["D_licence_expiry"].'</td>
        <td style="border: 1px solid black;">'.$row["D_badge_no"].'</td>
        <td style="border: 1px solid black;">'.$row["D_street"].'</td>
        <td style="border: 1px solid black;">'.$row["D_city"].'</td>
        <td style="border: 1px solid black;">'.$row["D_dist"].'</td>
        <td style="border: 1px solid black;">'.$row["D_state"].'</td>
        <td style="border: 1px solid black;">'.$row["D_pin"].'</td>
        <td style="border: 1px solid black;">'.$row["D_gender"].'</td>
        <td style="border: 1px solid black;">'.$row["D_dob"].'</td>
        <td style="border: 1px solid black;">'.$row["D_join"].'</td>
        <td style="border: 1px solid black;">'.$row["D_experience"].'</td>
    </tr>
    ';

    $serialNumber++;
}

$a .= '</table></body></html>';
$mpdf->SetHTMLFooter('<div style="text-align: center; font-size: 10pt; border-top: 1px solid #000000;">Page {PAGENO} of {nb}</div>');

// Write HTML content to the PDF
$mpdf->WriteHTML($a);

// Output the PDF for printing
$mpdf->Output('driver-reports.pdf', 'I'); // 'D' sends the file for download
?>
