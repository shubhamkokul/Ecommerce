<?php
require('conn.php');
$email = $_POST['email'];
$salesman_id = $_POST['userid'];



show_user_edit($salesman_id,$email,$conn);

function show_user_edit($salesman_id,$email,$conn)
{
 $query = "SELECT * FROM `clients` WHERE `salesman_id` = '$salesman_id' AND `email` = '$email'";
	$client = mysql_query($query,$conn);
      $row = mysql_fetch_assoc($client);
        
        $output[] = array("name" => $row['name'],
                          "email" => $row['email'],
                          "address" => $row['address'],
                          "salesman_id" => $row['salesman_id'],
                          "pan_no" => $row['pan_no'],
                          "transport" => $row['transport'],
                          "reference" => $row['reference'],
                           "number" => $row['number']);
        
        
	echo json_encode($output);	
}
?>