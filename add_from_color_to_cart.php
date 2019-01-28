<?php
require('conn.php');

$all = $_POST['all'];


add_from_color_to_cart($all,$conn);

function add_from_color_to_cart($all,$conn)
{
$data = json_decode($all);
if($data =="")
{
echo "EMPTY";
}
else
{
$array_val = $data -> color;
$product_sku = $data -> product_sku;
$product_name = $data -> product_name;
$email = $data -> email;
$size = $data -> size;
$image = $data -> image;
$product_price = $data -> product_price;
$quantity = $data -> quantity;
$amount = $data -> amount;
$salesman_id = $data -> userid;
}
$remove = array('[',']');
$array_values = str_replace($remove,'',$array_val);
$array_value = explode(',',$array_values);

for($i = 0;$i<sizeof($array_value); $i++)
{
$q1 = "SELECT * FROM `cart` WHERE `product_sku` = '$product_sku' AND `salesman_id` = '$salesman_id' AND `color` = '$array_value[$i]' AND `size` = '$size'";
        $res = mysql_query($q1,$conn);
        $number = mysql_num_rows($res);
         //echo $number;
        if($number<=0)
        {
        $query = "INSERT INTO `cart` (`product_sku`,`salesman_id`,`product_name`,`email`,`color`,`size`,`image`,`product_price`,`quantity`,`amount`) VALUES ('$product_sku','$salesman_id','$product_name','$email','$array_value[$i]','$size','$image','$product_price','$quantity','$amount')";
	if(mysql_query($query,$conn))
	{
	$flag['code'] = 1;
	$flag['message'] = "New product added";
	echo json_encode($flag);	
	}
	else
	{
	$flag['code'] = 0;
	$flag['message'] = "Insufficient parameters";
	echo json_encode($flag);	
	}
        }
        else
        {
           /*$q2 = "UPDATE `cart` SET `quantity` = '$quantity' , `amount` = '$amount' WHERE `product_sku` = '$product_sku' AND `salesman_id` = '$salesman_id' AND `color` = '$color' AND `size` = '$size'";
          mysql_query($q2,$conn);*/

          $flag['code'] = 2;
          $flag['message'] = "Already in Cart";
          echo json_encode($flag);
        }
        }
}
?>
