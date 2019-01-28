<?php
require('conn.php');

$userid = $_POST['userid'];
$deviceid = $_POST['device_id'];

other_logout($deviceid,$userid,$conn);

function other_logout($deviceid,$userid,$conn)
{
$logout=mysql_query("SELECT * from `salesman` where `userid`='$userid' AND `device_id`='$deviceid'");
$noofrows=mysql_num_rows($logout);
if($noofrows>0)
{
$flag['code']=1;
$flag['message'] = "same device id is present";
}else{
$flag['code']=0;
$flag['message'] = "user logged-in in other device";
}
echo json_encode($flag);
}

?>