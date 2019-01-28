<?php
require('conn.php');
$salesman_id = 'test';
$email = 'udhay@gmail.com';
$brand_id = 'BR208';

show_discount($salesman_id,$email,$brand_id,$conn);

function show_discount($salesman_id,$email,$brand_id,$conn)
{
$q1 = "SELECT * FROM `brand` WHERE `brand_id` = '$brand_id'";
$brand123 = mysql_query($q1,$conn);
$r = mysql_num_rows($brand123);
$row = mysql_fetch_assoc($brand123);
$discount = $row['discount'];
$client_id = $row['clients_id'];
$array2 = explode(",",$discount);
$array4 = explode(",",$client_id);
array_pop($array4);
array_pop($array2);
print_r($array2)."<br>";
print_r($array4);
}
?>