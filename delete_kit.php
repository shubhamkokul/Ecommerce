<?php
require('conn.php');


$product_sku = $_POST['product_sku']; 
$salesman_id = $_POST['userid'];


delete_kit($salesman_id,$product_sku,$conn);

function delete_kit($salesman_id,$product_sku,$conn)
{
	$query = "DELETE FROM `buscket` WHERE `product_sku` = '$product_sku' AND `salesman_id` = '$salesman_id'";
	if(mysql_query($query,$conn))
	{
	$flag['code'] = 1;
	$flag['message'] = "Deleted from Busket";
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