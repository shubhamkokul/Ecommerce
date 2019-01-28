<?php
require('conn.php');
$order_id = $_POST['order_id'];
show_detail_order($order_id,$conn);

function show_detail_order($order_id,$conn)
{
$query = "SELECT * FROM `order_contents` WHERE `order_id` = '$order_id'";
//echo "SELECT * FROM `order_contents` WHERE `order_id` = '$order_id'";
$order = mysql_query($query,$conn);
while($row = mysql_fetch_assoc($order))
{
$q1 = "SELECT * FROM `product_item` WHERE `product_sku` = '".$row['product_sku']."'";
$product = mysql_query($q1,$conn);
while($r1 = mysql_fetch_assoc($product))
{
$output[] = array("product_name" => $r1['product_name'],
                  "color" => $row['color'],
                  "size" => $row['size'],
                  "product_price" => $row['product_price'],
                  "quantity" => $row['quantity'],
                  "amount" => $row['amount']);
}
}
echo json_encode($output);
}

?>