<?php
require('conn.php');
$total_cart_amount = 0;
$total_commission = 0;
$total_taxes = 0;
$total_amount = 0;
$discount_total = 0;
$amount_before_discount = 0;
$salesman_id = $_POST['userid'];

calculate_amount($salesman_id,$total_cart_amount,$total_commission,$total_taxes,$total_amount,$discount_total,$amount_before_discount,$conn);

function calculate_amount($salesman_id,$total_cart_amount,$total_commission,$total_taxes,$total_amount,$discount_total,$amount_before_discount,$conn)
{
$query1 = "SELECT * FROM `cart` WHERE `salesman_id` = '$salesman_id'";
$cart_amount = mysql_query($query1,$conn);
while($row1 = mysql_fetch_assoc($cart_amount))
{
$disc = (int)getproductemail($salesman_id,$row1['product_sku'],$row1['email'],$conn);

$discount_rate = ($disc/100)*(int)$row1['amount'];

$discount_total = $discount_total + $discount_rate;

$discount_amount = (int)($row1['amount']) - $discount_rate;

$amount_before_discount = $amount_before_discount + (int)($row1['amount']);

$total_cart_amount = $total_cart_amount + $discount_amount;
}
$query = "SELECT * FROM `taxation`";
$amount_calculate = mysql_query($query,$conn);

   while($row = mysql_fetch_assoc($amount_calculate))
     {
$total_taxes = $total_taxes + ( ($total_cart_amount * $row['tax_value'])/100 );
     }
$total_amount = $total_taxes + $total_cart_amount;
$query2 = "SELECT * FROM `salesman` WHERE `userid` = '$salesman_id'";
$com = mysql_query($query2,$conn);
$r1 = mysql_fetch_assoc($com);
$total_commission = ($total_cart_amount * (int)$r1['commission'])/100;



$output = array("total_amount" => $total_amount,
               "commission" => $total_commission,
               "total_taxes" => $total_taxes,
               "total_cart_amount" => $total_cart_amount,
               "discount_total" => $discount_total,
               "amount_before_discount" => $amount_before_discount);

echo json_encode($output);
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