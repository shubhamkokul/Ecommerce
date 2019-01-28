<?php
require('conn.php');


$salesman_id = $_POST['userid'];

show_name($salesman_id,$conn);

function show_name($salesman_id,$conn)
{
   $query = "SELECT * FROM `order` WHERE `salesman_id` = '$salesman_id' GROUP BY `email`";
   $order1 = mysql_query($query,$conn);
while($row1 = mysql_fetch_assoc($order1))
{
$query4 = "SELECT * FROM `clients` WHERE `email` = '".$row1['email']."'";
$name1 = mysql_query($query4,$conn);
while($r2 = mysql_fetch_Assoc($name1))
{
$output1[] = array("name" => $r2['name']);                        
}
}
      echo json_encode($output1);
}

?>