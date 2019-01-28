<?php
require ('conn.php');

$product_sku = $_POST['product_sku'];

$salesman_id = $_POST['client_id'];

delete_from_cart($product_sku,$salesman_id,$conn);


function delete_from_cart($product_sku,$salesman_id,$conn)
{
	$query = "DELETE FROM `cart` WHERE `product_sku` = '$product_sku' AND `salesman_id` = '$salesman_id'";
	if(mysql_query($query,$conn))
	{
	$flag['code'] = 1;
	$flag['message'] = "Product Removed";
	echo json_encode($flag);	
	}
 
	{
	$flag['code'] = 0;
	$flag['message'] = "Wrong ID";
	echo json_encode($flag);	
	}
	}


?>