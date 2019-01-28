<?php
require 'conn.php'; 
//$search ='jeans';
$search = $_POST['search'];  

$query = "SELECT * FROM `product_item`";
$result = mysql_query($query,$conn);
while($row = mysql_fetch_assoc($result)){
  $tag = $row['product_name'];
  $tags = explode(",",$tag);
  foreach($tags as $key => $value){
      if(strpos(strtoupper($value), strtoupper($search)) !== false){

$q2 = "SELECT * FROM `image` WHERE `product_sku` = '".$row['product_sku']."'";
$r2 = mysql_query($q2,$conn);

$rr1 = mysql_fetch_assoc($r2);
$image = $rr1['image'];
         $fag[] =array("product_name" => $row['product_name'],"product_image" => $image,"product_price" => $row['product_price'],"rating" => $row['rating'],"created_date" => $row['created_date'],"sale_price" => $row['sale_price'],"product_id" => $row['product_id'],"inWishList" => false);
      }
  }
}

echo json_encode($fag);

?>
 	