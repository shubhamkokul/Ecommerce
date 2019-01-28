<?php
require('conn.php');


show_user($conn);

function show_user($conn)
{
 $query = "SELECT * FROM `clients`";
	$cart = mysql_query($query,$conn);
        while($row = mysql_fetch_assoc($cart))
        {
        $output[] = array("name" => $row['name'],
                          "email" => $row['email'],
                          "address" => $row['address'],
                          "salesman_id" => $row['salesman_id'],
                          "pan_no" => $row['pan_no'],
                          "transport" => $row['transport'],
                          "reference" => $row['reference'],
                          "number" => $row['number'],
                          "tin_number" => $row['tin_number'],
                          "distributor_number" => $row['distributor_number'],
                          "distributor_email" => $row['distributor_email']);
        }
        
	echo json_encode($output);	
}

?>