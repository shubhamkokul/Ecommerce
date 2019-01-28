<?php
require('conn.php');
$salesman_id = $_POST['userid'];

show_kit($salesman_id,$conn);

function show_kit($salesman_id,$conn)
{
	$query = "SELECT * FROM `buscket` WHERE `salesman_id` = '$salesman_id'";
	$kit = mysql_query($query,$conn);
    while($row = mysql_fetch_assoc($kit))
        {

        $product['product_sku'] = $row['product_sku'];
        //$product = size($product,$row['product_sku'],$conn);
		$product = show_size_color($product,$row['product_sku'],$conn);
		$product = show_image($product,$row['product_sku'],$conn);
		$product = show_price($product,$row['product_sku'],$conn);
		$product = show_description($product,$row['product_sku'],$conn);
		$product = show_product_name($product,$row['product_sku'],$conn);
		$product = show_product_sku($product,$row['product_sku'],$conn);
                $data[] = $product;
                unset($product);
		
        }
        echo json_encode($data);
    }
	
function show_image($buscket,$product_sku,$conn)
{
	$q3 = "SELECT `image` FROM `image` WHERE `product_sku`= '$product_sku'";
	$image = mysql_query($q3,$conn);
	while($row = mysql_fetch_assoc($image))
	{
	$buscket['image']=$row['image'];
	}
	return $buscket;
}
function show_price($buscket,$product_sku,$conn)
{
	$q4 = "SELECT `product_price` FROM `product_item` WHERE `product_sku`= '$product_sku'";
	$product_price = mysql_query($q4,$conn);
	while($row = mysql_fetch_assoc($product_price))
	{
	$buscket['product_price'] = $row['product_price'];
        //$disc = getproductemail($salesman_id,$product_sku,$email,$conn);
        //$discount_rate = ($disc/100)*(int)$row['product_price'];
        //$discount_amount = (int)($row['product_price']) - $discount_rate;
        //$buscket['product_price_discount'] = $discount_amount;
	}
	return $buscket;
}
function show_description($buscket,$product_sku,$conn)
{
	$q5 = "SELECT `description` FROM `product_item` WHERE `product_sku`= '$product_sku'";
	$description = mysql_query($q5,$conn);
	while($row = mysql_fetch_assoc($description))
	{
	$buscket['description'] =  $row['description'];
	}
	return $buscket;
}
function show_product_name($buscket,$product_sku,$conn)
{
	$q6 = "SELECT `product_name` FROM `product_item` WHERE `product_sku`= '$product_sku'";
	$name = mysql_query($q6,$conn);
	while($row = mysql_fetch_assoc($name))
	{
	$buscket['name'] =  $row['product_name'];
	}
	return $buscket;
}
function show_product_sku($buscket,$product_sku)
{
$buscket['product_sku'] =  $product_sku;
return $buscket;
}
function show_size_color($buscket,$product_sku,$conn)
{
	$q7 = "SELECT * FROM `product_item` WHERE `product_sku` = '$product_sku'";
	$size_color = mysql_query($q7,$conn);
	while($r1 = mysql_fetch_assoc($size_color))
	{
	$buscket['color'] = $r1['color'];
	$buscket['size'] = $r1['size'];
    }
	return $buscket;
}

?>