<?php
require('conn.php');
$salesman_id = $_POST['userid'];
$product_sku = $_POST['product_sku'];


add_to_kit($product_sku,$salesman_id,$conn);

function add_to_kit($product_sku,$salesman_id,$conn)
{
insert_into_buscket($product_sku,$salesman_id,$conn);
}
function insert_into_buscket($product_sku,$salesman_id,$conn)
{
        $q1 = "SELECT * FROM `buscket` WHERE `product_sku` = '$product_sku' AND `salesman_id` = '$salesman_id'";
        $res = mysql_query($q1,$conn);
        $number = mysql_num_rows($res);
        $output['code'] = "0";
        $output['message'] = "Already in the Kit";
        if($number<=0)
        {
         $query = "INSERT INTO `buscket` (`product_sku`,`salesman_id`) VALUES ('$product_sku','$salesman_id')";
	  mysql_query($query,$conn);
         $output['code'] = "1";
         $output['message'] = "Added to Kit";  
        }
        else
        {
        }

	echo json_encode($output);	
}

function image($output,$product_sku,$conn)
{
	$q3 = "SELECT `image` FROM `image` WHERE `product_sku`= '$product_sku'";
	$image = mysql_query($q3,$conn);
	while($row = mysql_fetch_assoc($image))
	{
	$output['image']=$row['image'];
	}
	return $output;
}
function price($output,$product_sku,$conn)
{
	$q4 = "SELECT `product_price` FROM `product_item` WHERE `product_sku`= '$product_sku'";
	$product_price = mysql_query($q4,$conn);
	while($row = mysql_fetch_assoc($product_price))
	{
	$output['product_price']=  $row['product_price'];
	}
	return $output;
}
function description($output,$product_sku,$conn)
{
	$q5 = "SELECT `description` FROM `product_item` WHERE `product_sku`= '$product_sku'";
	$description = mysql_query($q5,$conn);
	while($row = mysql_fetch_assoc($description))
	{
	$output['description'] =  $row['description'];
	}
	return $output;
}
function product_name($output,$product_sku,$conn)
{
	$q6 = "SELECT `product_name` FROM `product_item` WHERE `product_sku`= '$product_sku'";
	$name = mysql_query($q6,$conn);
	while($row = mysql_fetch_assoc($name))
	{
	$output['name'] =  $row['product_name'];
	}
	return $output;
}
function product_sku($output,$product_sku)
{
$output['product_sku'] =  $product_sku;
return $output;
}
?>