<?php
require('conn.php');
$salesman_id = $_POST['userid'];
//$salesman_id = 'test';
$email = $_POST['email'];
//$email = 'shubhamkokul@gmail.com';
$total_cart_amount = 0;
$total_commission = $_POST['total_commission'];
//$total_commission = '1000';
$total_taxes = 0;
$total_amount = $_POST['total_amount'];
//$total_amount = '2000';
$discount_total = 0;
$amount_before_discount = 0;
$date = new DateTime();
$order_id = $date-> getTimestamp();
$today_date = $date-> format('Y-m-d');

order_insert($salesman_id,$email,$total_cart_amount,$total_commission,$total_taxes,$total_amount,$discount_total,$amount_before_discount,$order_id,$today_date,$conn);

function order_insert($salesman_id,$email,$total_cart_amount,$total_commission,$total_taxes,$total_amount,$discount_total,$amount_before_discount,$order_id,$today_date,$conn)
{
//list($data1,$data2) = calculate_amount_order($salesman_id,$total_cart_amount,$total_commission,$total_taxes,$total_amount,$discount_total,$amount_before_discount,$conn);
$query = "SELECT * FROM `cart` WHERE `salesman_id` = '$salesman_id' AND `email` = '$email'";
  $order_insert = mysql_query($query,$conn);
  while($row = mysql_fetch_assoc($order_insert))
{
  $q1 = "INSERT INTO `order_contents` (`order_id`, `product_sku`, `color`, `size`,`product_price`, `quantity`, `amount`) VALUES ('$order_id','".$row['product_sku']."','".$row['color']."','".$row['size']."','".$row['product_price']."','".$row['quantity']."','".$row['amount']."')";
  mysql_query($q1,$conn);
}
 $q2 = "INSERT INTO `order`(`order_id`, `salesman_id`, `email`, `total_amount`, `commission`, `date`, `status`) VALUES ('$order_id','$salesman_id','$email','$total_amount','$total_commission','$today_date','0')";
mysql_query($q2,$conn);

   
$q7 = "SELECT * FROM `cart` WHERE `salesman_id` = '$salesman_id' AND `email` = '$email'";
$cartfinalquantity = mysql_query($q7,$conn);
while($row7 = mysql_fetch_assoc($cartfinalquantity))
{
StockUpdate($row7['product_sku'],$row7['quantity'],$conn);
}

  $q3 = "DELETE FROM `cart` WHERE `salesman_id` = '$salesman_id' AND `email` = '$email'";
  mysql_query($q3,$conn);

$flag['code'] = 0;
$flag['message'] = "Your order is processed";

/*
$orderconte=mysql_query("SELECT * FROM `order_contents` WHERE `order_id`='$order_id'");
while($ordercontwe=mysql_fetch_assoc($orderconte)){
$itemmm=mysql_fetch_assoc(mysql_query("SELECT * FROM `product_item` WHERE `product_sku`='".$ordercontwe['product_sku']."' "));
$itemmm['product_name'];
$out = array("product_name" => '$itemmm',"color" => '".$ordercontwe['color']."',"size" => '".$ordercontwe['size']."',"product_price" => '".$ordercontwe['product_price']."',"quantity" => '".$ordercontwe['quantity']."',"amount" => '".$ordercontwe['amount']."');
}
echo json_encode($out);
*/

$subject = 'Order  Status!';
$message = '<html><body>';
$message .= '<h1 style="color:#f40;">Hi '.$salesman_id.'</h1>';
$message .= '<p style="color:#080;font-size:18px;">Your order is processed </p>';
$message .= '</body></html>';

$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= 'From: testing@innowrap.com' . "\r\n" .
    'Reply-To: testing@innowrap.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

if(mail($email, $subject, $message,$headers)){
$flag['code'] = 1;
$flag['message'] = "Order has been send Your Mail Id";
}else{
$flag['message'] = "Unable to send mail";
}
echo json_encode($flag);

}


function calculate_amount_order($salesman_id,$total_cart_amount,$total_commission,$total_taxes,$total_amount,$discount_total,$amount_before_discount,$conn)
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
print_r($output);
return array($total_amount,$total_commission);
}



function getproductemail($salesman_id,$product_sku,$email,$conn)
{
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


function StockUpdate($product_sku,$quantity,$conn)
{
$new_stock = $quantity;
$q6 = "SELECT * FROM `product_item` WHERE `product_sku` = '$product_sku'";
$updatestock = mysql_query($q6,$conn);
$row6 = mysql_fetch_assoc($updatestock);


$oldstock = (int)$row6['stock_units'];
$new_stock = $oldstock - $new_stock;


$q7 = "UPDATE `product_item` SET `stock_units`= '$new_stock' WHERE `product_sku` = '$product_sku'";
mysql_query($q7,$conn);
}

?>
