<?php
include 'Dbconnect.php';
$id=$_GET["Driver_bus_id"];
echo "$id";
$sql = "UPDATE tbl_Driver_bus SET Db_status = 0 WHERE Driver_bus_id = $id";
$res=mysqli_query($conn,$sql);
if($res){
    header("Location:Driverbus.php");
}

?>