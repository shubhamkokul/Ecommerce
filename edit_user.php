<?php 
require ('conn.php');

$name = $_POST['name'];
$email = $_POST['email'];
$address = $_POST['address'];
$salesman_id = $_POST['agent'];
$pan_no = $_POST['pan_no'];
$transport = $_POST['transport'];
$reference = $_POST['reference'];
$number = $_POST['number'];

edit_user($name,$email,$address,$salesman_id,$pan_no,$transport,$reference,$number,$conn);

function edit_user($name,$email,$address,$salesman_id,$pan_no,$transport,$reference,$number,$conn)
{
	$query = "UPDATE clients SET `name` = '$name',`address` = '$address',`pan_no` = '$pan_no', `transport` = '$transport',`reference` = '$reference', `number` = '$number' WHERE `email` = '$email'";
	if(mysql_query($query,$conn))
	{
	$flag['code'] = 1;
	$flag['message'] = "Details Updated";
	echo json_encode($flag);	
	}
	else
	{
	$flag['code'] = 0;
	$flag['message'] = "User Details not updated";
	echo json_encode($flag);	
	}
	
}

?>