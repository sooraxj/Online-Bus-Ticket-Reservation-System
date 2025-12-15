<?php
include 'Dbconnect.php';
$id=$_GET["Customer_id"];
echo "$id";
$sql = "UPDATE tbl_Customer SET C_Status = 0 WHERE Customer_id = $id";
$res=mysqli_query($conn,$sql);
if($res){
    header("Location:Cust.php");
}

?>