<?php
require('conn.php');
//error_reporting(-1);
$salesman_id = $_POST['userid'];
$email = $_POST['email'];
//$salesman_id = 'test';
//$email = 'udhay@gmail.com';



show_kit($salesman_id,$email,$conn);


function show_kit($salesman_id,$email,$conn)
{
	$query = "SELECT * FROM `buscket` WHERE `salesman_id` = '$salesman_id'";
	$kit = mysql_query($query,$conn);
    while($row = mysql_fetch_assoc($kit))
        {

        $product['product_sku'] = $row['product_sku'];
        //$product = size($product,$row['product_sku'],$conn);
		$product = show_size_color($product,$row['product_sku'],$conn);
		$product = show_image($product,$row['product_sku'],$conn);
		$product = show_price($salesman_id,$email,$product,$row['product_sku'],$conn);
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
function show_price($salesman_id,$email,$buscket,$product_sku,$conn)
{
	$q4 = "SELECT `product_price` FROM `product_item` WHERE `product_sku`= '$product_sku'";
	$product_price = mysql_query($q4,$conn);
	while($row = mysql_fetch_assoc($product_price))
	{
	$buscket['product_price'] = $row['product_price'];
        $disc = getproductemail($salesman_id,$product_sku,$email,$conn);
        $discount_rate = ($disc/100)*(int)$row['product_price'];
        $discount_amount = (int)($row['product_price']) - $discount_rate;
        $buscket['product_price_discount'] = $discount_amount;
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


function getproductemail($salesman_id,$product_sku,$email,$conn)
{
	    $discount;
		$q2 = "SELECT * FROM `product_item` WHERE `product_sku` = '$product_sku'";
		$product = mysql_query($q2,$conn);
		$r2 = mysql_fetch_assoc($product);
		$brand = $r2['brand_id'];
		$q3 = "SELECT * FROM `clients` WHERE `email` = '$email'";
		$client = mysql_query($q3,$conn);
		$r3 = mysql_fetch_assoc($client);
		$client_number = $r3['id'];
		
		$q3 = "SELECT * FROM `brand` WHERE `brand_id` = '$brand'";
		$branddata = mysql_query($q3,$conn);
		$r4 = mysql_fetch_assoc($branddata);
		$brand_client = $r4['clients_id'];
		if($brand_client == "all")
		{
			 $discount = $r4['discount'];
		}
		else
		{       
			$fetch_client_id = explode(",", $brand_client);
			for($i = 0; $i<sizeof($fetch_client_id);$i++)
			{ 
				if($client_number == $fetch_client_id[$i])
				{
					$discount = $r4['discount'];
				}
			}
		}
		return $discount;
}
?>