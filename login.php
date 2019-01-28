<?php
require('conn.php');

$userid = $_POST['userid'];
$password = $_POST['password'];
$deviceid = $_POST['device_id'];

login($userid,$password,$deviceid,$conn);


function login($userid,$password,$deviceid,$conn)
{
$login=mysql_query("SELECT * from `salesman` where `userid`='$userid' AND `password`='$password'");
$noofrows=mysql_num_rows($login); 
$flag['code']=0;
$row = mysql_fetch_assoc($login); 
if ($noofrows >0)
{
mysql_query("UPDATE `salesman` SET `device_id` = '$deviceid' WHERE `userid` = '$userid'",$conn);
$flag['code']=1;
$flag['msg'] = "Username and/or Password is valid";
$flag['name'] = $row['name'];
}
else
{
$flag['code']=0;
$flag['msg'] = "Username and/or Password is Invalid";
}
echo json_encode($flag);
}

?>