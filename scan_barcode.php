<?php
require('conn.php');

$barcode = $_POST['barcode'];

scan_barcode($barcode,$conn);

function scan_barcode($barcode,$conn)
{     
$query = "SELECT * FROM `product_item` WHERE `barcode` = '$barcode'";
    $kit = mysql_query($query,$conn);
         if(mysql_num_rows($kit)<=0)
          {
            $flag['code'] = "3";
            $flag['message'] = "No such Product";
            echo json_encode($flag);
          }
         else
         {
    while($row = mysql_fetch_assoc($kit))
        {
        $product_barcode['product_sku'] = $row['product_sku'];
        $product_barcode = size_barcode($product_barcode,$row['product_sku'],$conn);
        $product_barcode = color_barcode($product_barcode,$row['product_sku'],$conn);
        $product_barcode = image_barcode($product_barcode,$row['product_sku'],$conn);
        $product_barcode = price_barcode($product_barcode,$row['product_sku'],$conn);
        $product_barcode = description_barcode($product_barcode,$row['product_sku'],$conn);
        $product_barcode = product_name_barcode($product_barcode,$row['product_sku'],$conn);
        $product_barcode['code'] = "1"; 
        } 
         echo json_encode($product_barcode);
        }
        }
	function size_barcode($buscket,$product_sku,$conn)
        {
	$q1 = "SELECT * FROM `product_item` WHERE `product_sku` = '$product_sku'";
        $sizes = mysql_query($q1,$conn);
        while($r1 = mysql_fetch_assoc($sizes))
        {
             $buscket['size'] = $r1['size'];
        }
	return $buscket;
        }

function color_barcode($buscket,$product_sku,$conn)
        {
	$q2 = "SELECT `color` FROM `product_item` WHERE `product_sku`= '$product_sku'";
	$color = mysql_query($q2,$conn);
	while($row = mysql_fetch_assoc($color))
	{
	$buscket['color'] =  $row['color'];
	}
	return $buscket;
        }
function image_barcode($buscket,$product_sku,$conn)
{
	$q3 = "SELECT `image` FROM `image` WHERE `product_sku`= '$product_sku'";
	$image = mysql_query($q3,$conn);
	while($row = mysql_fetch_assoc($image))
	{
	$buscket['image']=$row['image'];
	}
	return $buscket;
}
function price_barcode($buscket,$product_sku,$conn)
{
	$q4 = "SELECT `product_price` FROM `product_item` WHERE `product_sku`= '$product_sku'";
	$product_price = mysql_query($q4,$conn);
	while($row = mysql_fetch_assoc($product_price))
	{
	$buscket['product_price']=  $row['product_price'];
	}
	return $buscket;
}
function description_barcode($buscket,$product_sku,$conn)
{
	$q5 = "SELECT `description` FROM `product_item` WHERE `product_sku`= '$product_sku'";
	$description = mysql_query($q5,$conn);
	while($row = mysql_fetch_assoc($description))
	{
	$buscket['description'] =  $row['description'];
	}
	return $buscket;
}
function product_name_barcode($buscket,$product_sku,$conn)
{
	$q6 = "SELECT `product_name` FROM `product_item` WHERE `product_sku`= '$product_sku'";
	$name = mysql_query($q6,$conn);
	while($row = mysql_fetch_assoc($name))
	{
	$buscket['name'] =  $row['product_name'];
	}
	return $buscket;
}

?>