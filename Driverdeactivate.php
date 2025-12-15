<?php
include 'Dbconnect.php';
$id=$_GET["Driver_id"];
echo "$id";
$sql = "UPDATE tbl_Driver SET D_Status = 0 WHERE Driver_id = $id";
$res=mysqli_query($conn,$sql);
if($res){
    header("Location:Driver.php");
}

?>