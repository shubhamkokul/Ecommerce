<?php
require('conn.php');
$action = $_POST['action'];
$flag['code'] = 0;
$flag['msg'] = "No Parameter is Passed";
switch($action)
{
case "category":
category();
break;

default:
echo json_encode($flag);
break; 

}



function category(){
require('conn.php');

$r=mysql_query("select * from product_categories",$conn);
while($row=mysql_fetch_array($r))
{    
$s = mysql_query("SELECT * FROM `product_sub_category` WHERE `product_category_id` = ".$row['product_category_id']."",$conn);
while($row1 = mysql_fetch_array($s))
{
$ss = mysql_fetch_array(mysql_query("SELECT * FROM `brand` WHERE `sub_category_id` = ".$row1['sub_category_id']."",$conn));
  $flag1[] = array('brand_name' => $ss['brand_name'],'flag'=> 1); 
$query=mysql_query("SELECT * FROM `product_item` WHERE `brand_id` = '".$ss['brand_id']."'",$conn);
while($row2 = mysql_fetch_array($query))
 {
$query2 = "SELECT * FROM `image` WHERE `product_sku` = '".$row2['product_sku']."'";
$r2 = mysql_query($query2,$conn);
$rr1 = mysql_fetch_assoc($r2);
$image = $rr1['image'];

$flag1[]=array('product_name'=>$row2['product_name'],'product_image'=>$image,'product_sku' => $row2['product_sku'],'flag'=>0);


}
$flag2[]=array('sub_category_name' => $row1['name'],'sub_category_id' => $row1['sub_category_id'],'products' => $flag1);
unset($flag1);
}
$flag3[]=array('category_name' => $row['name'], 'category_id' => $row['product_category_id'] ,'subcategories'=>$flag2);  
unset($flag2);
}
print(json_encode($flag3));
}


?>
