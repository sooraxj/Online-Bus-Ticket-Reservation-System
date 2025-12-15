<?php
include "Dbconnect.php";
$CardId = $_GET['cardId'];

$del = "DELETE FROM tbl_Card WHERE Card_id = '$CardId'";
if($res = mysqli_query($conn,$del)){
    // header("Location:Payment_page.php");
}else
{
    echo "Record Not Deleted ";
}

?>