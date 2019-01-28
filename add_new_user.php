<?php 
require ('conn.php');

$name = $_POST['name'];
$email = $_POST['email'];
$address = $_POST['address'];
$salesman_id = $_POST['userid'];
$pan_no = $_POST['pan_no'];
$transport = $_POST['transport'];
$reference = $_POST['reference'];
$number = $_POST['number'];
$tin_number = $_POST['tin_number'];
$distributor_number = $_POST['distributor_number'];
$distributor_email = $_POST['distributor_email'];


check_email($name,$email,$address,$salesman_id,$pan_no,$transport,$reference,$number,$conn);

function check_email($name,$email,$address,$salesman_id,$pan_no,$transport,$reference,$number,$conn)
{
       $query = "SELECT `email` FROM `clients` WHERE `email` = '$email'";
       if(mysql_query($query,$conn)<0)
    {
        $flag['code'] = 3;
	$flag['message'] = "Already Used Email";
	echo json_encode($flag);	
    }
       else
    {
       add_new_user($name,$email,$address,$salesman_id,$pan_no,$transport,$reference,$number,$conn);
    }
}
function add_new_user($name,$email,$address,$salesman_id,$pan_no,$transport,$reference,$number,$conn)
    {
	$query = "INSERT INTO clients (`name`,`email`,`address`,`salesman_id`,`pan_no`,`transport`,`reference`,`number`) VALUES ('$name','$email','$address','$salesman_id','$pan_no','$transport','$reference','$number')";

	if(mysql_query($query,$conn))
	{
	$flag['code'] = 1;
	$flag['message'] = "New User added";
	echo json_encode($flag);	
	}
	else
	{
	$flag['code'] = 0;
	$flag['message'] = "Check Your Internet Connection";
	echo json_encode($flag);	
	}
	
}
?>
