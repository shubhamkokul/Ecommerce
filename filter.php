<?php
require('conn.php');
error_reporting(-1);
$salesman_id = $_POST['userid'];
$filter_data = $_POST['filter_data'];

$mainObject = json_decode($filter_data);

$query = "SELECT * FROM `product_item`";

$brands = $mainObject->brand_name;

$product_name = $mainObject->product_name;

$size = $mainObject->size;

$color = $mainObject->color;

$sub_category_name = $mainObject->sub_category_name;

$categories_name = $mainObject->categories_name;

$fit = $mainObject->fit;

$price = $mainObject->price;

if((sizeof($brands->value)!=0) || (sizeof($product_name->value)!=0) || (sizeof($size->value)!=0) || (sizeof($color->value)!=0) || (sizeof($sub_category_name->value)!=0) || (sizeof($categories_name->value)!=0) || (sizeof($fit->value)!=0) || (sizeof($price->value)!=0)){

$query .=" WHERE ";

if(sizeof($brands->value)!=0){
$query .=" (";
 foreach($brands->value as $key =>$value1){
   if((sizeof($brands->value)-1)!=$key){
      $q  = mysql_fetch_assoc(mysql_query("SELECT * FROM `brand` WHERE `brand_name` ='$value1'",$conn));
      $query .= "(`brand_id`='".$q['brand_id']."') OR";
   }else{
      $q  = mysql_fetch_assoc(mysql_query("SELECT * FROM `brand` WHERE `brand_name` ='$value1'",$conn));
      $query .= "(`brand_id`='".$q['brand_id']."')";
      
   }
 }
$query .=") ";
}


if(sizeof($product_name->value)!=0){
  if(sizeof($brands->value)!=0){
    $query .=" AND ";
  }
$query .=" (";
  foreach($product_name->value as $key =>$value1){
   if((sizeof($product_name->value)-1)!=$key){
      $query .= "(`product_name`='".$value1."') OR";
   }else{
      $query .= "(`product_name`='".$value1."')"; 
   }
 }
 
  $query .=" )";
}

if(sizeof($size->value)!=0){
  if((sizeof($brands->value)!=0) || (sizeof($product_name->value)!=0)){
    $query .=" AND ";
  }
$query .=" (";
  foreach($size->value as $key =>$value1){
   if((sizeof($size->value)-1)!=$key){
      $query .= "(`size`='".$value1."') OR";
   }else{
      $query .= "(`size`='".$value1."')"; 
   }
 }
  
  $query .=" )";
}

if(sizeof($color->value)!=0){
  if((sizeof($brands->value)!=0) || (sizeof($product_name->value)!=0) || (sizeof($size->value)!=0)){
    $query .=" AND ";
  }
$query .=" (";
  foreach($color->value as $key =>$value1){
   if((sizeof($color->value)-1)!=$key){
      $query .= "(`color`='".$value1."') OR";
   }else{
      $query .= "(`color`='".$value1."')"; 
   }
 }

  
  $query .=" )";
}

if(sizeof($sub_category_name->value)!=0){
  if((sizeof($brands->value)!=0) || (sizeof($product_name->value)!=0) || (sizeof($size->value)!=0) || (sizeof($color->value)!=0)){
    $query .=" AND ";
  }
$query .=" (";
  foreach($sub_category_name->value as $key =>$value1){
   if((sizeof($sub_category_name->value)-1)!=$key){
$q  = mysql_fetch_assoc(mysql_query("SELECT * FROM `product_sub_category` WHERE `name` ='$value1'",$conn));
      $query .= "(`sub_category_id`=".$q['sub_category_id'].") OR";
   }else{
$q  = mysql_fetch_assoc(mysql_query("SELECT * FROM `product_sub_category` WHERE `name` ='$value1'",$conn));
      $query .= "(`sub_category_id`=".$q['sub_category_id'].")"; 
   }
 }

  $query .=" )";
}


if(sizeof($fit->value)!=0){
  if((sizeof($brands->value)!=0) || (sizeof($product_name->value)!=0) || (sizeof($size->value)!=0) || (sizeof($color->value)!=0) || (sizeof($sub_category_name->value)!=0)){
    $query .=" AND ";
  }
$query .=" (";
  foreach($fit->value as $key =>$value1){
   if((sizeof($fit->value)-1)!=$key){
      $query .= "(`fit`='".$value1."') OR";
   }else{
      $query .= "(`fit`='".$value1."')"; 
   }
 }

  $query .=" )";
}


if(sizeof($price->value)!=0){
  if((sizeof($brands->value)!=0) || (sizeof($product_name->value)!=0) || (sizeof($size->value)!=0) || (sizeof($color->value)!=0) || (sizeof($sub_category_name->value)!=0) || (sizeof($fit->value)!=0)){
    $query .=" AND ";
  }
$query .=" (";
  foreach($price->value as $key =>$value1){
   if((sizeof($price->value)-1)!=$key){
      $prices = explode("-",$value1);
      $query .= "(`product_price` BETWEEN ".$prices[0]." AND ".$prices[1].") OR";
   }else{
      $prices = explode("-",$value1);
      $query .= "(`product_price` BETWEEN ".$prices[0]." AND ".$prices[1].")";
   }
 }

  $query .=" )";
}
}


echo $query;


?>