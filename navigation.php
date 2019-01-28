<?php
require('conn.php');
//$action = $_POST['action'];
$action = "1";
$flag['code'] = 0;
$flag['msg'] = "No Parameter is Passed";

switch($action)
{
case "category":
require('conn.php');
$query = "SELECT * FROM `product_categories`";
$product= mysql_query($query,$conn);
while($row = mysql_fetch_assoc($product))
{
$output[] = array("product_category_id" => $row['product_category_id'],
                  "name" => $row['name']);
}
echo json_encode($output);
break;
case "1":
require('conn.php');
$s = mysql_query("SELECT * FROM `product_sub_category` WHERE `product_category_id` = "1"",$conn);
while($row1 = mysql_fetch_assoc($s))
{
$ss = mysql_query("SELECT * FROM `brand` WHERE `sub_category_id` = ".$row1['sub_category_id']."",$conn);
while($row11 = mysql_fetch_assoc($ss))
{    
$query=mysql_query("SELECT * FROM `product_item` WHERE `brand_id` = '".$row11['brand_id']."'",$conn);
echo "SELECT * FROM `product_item` WHERE `brand_id` = ".$row11['brand_id']."";
while($row2 = mysql_fetch_assoc($query))
{
$query2 = "SELECT * FROM `image` WHERE `product_sku` = '".$row2['product_sku']."'";
$r2 = mysql_query($query2,$conn);
$rr1 = mysql_fetch_assoc($r2);
$image = $rr1['image'];
$flag1[]=array('product_name'=>$row2['product_name'],'product_image'=>$image,'product_sku' => $row2['product_sku']);
}
$flag11[]=array('brand_id' => $row11['brand_id'],'brand_name' => $row11['brand_name'],'products' => $flag1);
unset($flag1);
}
$flag2[]=array('sub_category_name' => $row1['name'],'sub_category_id' => $row1['sub_category_id'],'brand' => $flag11);
unset($flag11);
}
$flag3[]=array('category_name' => $row['name'], 'category_id' => $row['product_category_id'] ,'subcategories'=>$flag2);  
unset($flag2);
}
print(json_encode($flag3));
break;
case "2":
break;
case "3":
break;
}


?>
