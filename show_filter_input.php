<?php
require('conn.php');
$salesman_id = $_POST['userid'];
//$salesman_id = 'test';

filterdata($salesman_id,$conn);


function filterdata($salesman_id,$conn){
     $basket = mysql_query("SELECT * FROM `buscket` WHERE `salesman_id` = '$salesman_id'",$conn);
      while($row = mysql_fetch_assoc($basket)){
          $sku[]=$row['product_sku'];
      }
  
      $data['brand_name'] = getBrands($sku,$conn);
      $data['product_name'] = getProductName($sku,$conn);
      $data['size'] = getProductSize($sku,$conn);
      $data['color'] = getProductColor($sku,$conn);
      list($subcat,$cat)= getSubCategory($sku,$conn);
      $data['sub_category_name'] = $subcat;
      $data['categories_name'] =  $cat;
      $data['fit'] = getFit($sku,$conn);
      $data['price'] = getPrice($sku,$conn);
      echo json_encode($data);
}

function getBrands($sku,$conn){
if(sizeof($sku)>0){
$count = sizeof($sku)-1;
     $q = "SELECT DISTINCT `brand_id` FROM `product_item` WHERE ";
      foreach($sku as $key=>$value){
           if($key<$count){
              $q .= "(`product_sku` = '$value') OR";
           }else{
               $q .= "(`product_sku` = '$value')";
           }
      }
$brand_id_row = mysql_query($q,$conn);
while($row2 = mysql_fetch_assoc($brand_id_row))
{
    $brand_id[] = $row2['brand_id'];
} 
}
$data = getBrandsName($brand_id,$conn);
return $data;
}

function getBrandsName($brand_id,$conn)
{
if(sizeof($brand_id)>0)
{
$count = sizeof($brand_id)-1;
$q1 = "SELECT `brand_name` FROM `brand` WHERE";
foreach($brand_id as $key=>$value)
{
if($key<$count)
{
$q1 .= "(`brand_id`= '$value') OR ";
}
else
{
$q1 .= "(`brand_id` = '$value')";
}
}
$brand_name_row = mysql_query($q1,$conn);
$data['type'] = 0;
while($row3 = mysql_fetch_assoc($brand_name_row))
{

$data['value'][] = $row3['brand_name'];
} 
}
return $data;
}

function getProductName($sku,$conn)
{
if(sizeof($sku)>0)
{
$count = sizeof($sku)-1;
$q2 = "SELECT DISTINCT `product_name` FROM `product_item` WHERE";
foreach($sku as $key => $value)
{
if($key<$count)
{
$q2 .= "(`product_sku` = '$value') OR ";
}
else
{
$q2 .= "(`product_sku` = '$value')";
}
}
$product_name_row = mysql_query($q2,$conn);
$data['type'] = 0;
while($row4 = mysql_fetch_assoc($product_name_row))
{
$data['value'][] = $row4['product_name'];
}
}
return $data;
}


function getProductSize($sku,$conn)
{
if(sizeof($sku)>0)
{
$count = sizeof($sku)-1;
$q3 = "SELECT DISTINCT `size` FROM `product_item` WHERE";
foreach($sku as $key => $value)
{
if($key<$count)
{
$q3 .= "(`product_sku` = '$value') OR ";
}
else
{
$q3 .= "(`product_sku` = '$value')";
}
}
$product_size_row = mysql_query($q3,$conn);
$data['type'] = 0;
while($row5 = mysql_fetch_assoc($product_size_row))
{
$data['value'][] = $row5['size'];
}
}
return $data;
}



function getProductColor($sku,$conn)
{
if(sizeof($sku)>0)
{
$count = sizeof($sku)-1;
$q3 = "SELECT DISTINCT `color` FROM `product_item` WHERE";
foreach($sku as $key => $value)
{
if($key<$count)
{
$q3 .= "(`product_sku` = '$value') OR ";
}
else
{
$q3 .= "(`product_sku` = '$value')";
}
}
$product_color_row = mysql_query($q3,$conn);
$data['type'] = 0;
while($row5 = mysql_fetch_assoc($product_color_row))
{
$data['value'][] = $row5['color'];
}
}
return $data;
}



function getSubCategory($sku,$conn)
{
if(sizeof($sku)>0)
{
$count = sizeof($sku)-1;
$q4 = "SELECT DISTINCT `sub_category_id` FROM `product_item` WHERE";
foreach($sku as $key => $value)
{
if($key<$count)
{
$q4 .= "(`product_sku` = '$value') OR ";
}
else
{
$q4 .= "(`product_sku` = '$value')";
}
}
$product_sub_category_id_row = mysql_query($q4,$conn);
while($row6 = mysql_fetch_assoc($product_sub_category_id_row))
{
$sub_category_id[] = $row6['sub_category_id'];
}
}
$data1 = getSubCategoryName($sub_category_id,$conn);
$data2 = getCategoryName($sub_category_id,$conn);
return array($data1,$data2);
}

function getSubCategoryName($sub_category_id,$conn)
{
if(sizeof($sub_category_id)>0)
{
$count = sizeof($sub_category_id)-1;
$q5 = "SELECT `name` FROM `product_sub_category` WHERE";
foreach($sub_category_id as $key => $value)
{
if($key<$count)
{
$q5 .= "(`sub_category_id` = '$value') OR ";
}
else
{
$q5 .= "(`sub_category_id` = '$value')";
}
}
$sub_category_row_id = mysql_query($q5,$conn);
$data['type'] = 0;
while($row7 = mysql_fetch_assoc($sub_category_row_id))
{
$data['value'][] = $row7['name'];
}
}
return $data;
}

function getCategoryName($sub_category_id,$conn)
{
if(sizeof($sub_category_id)>0)
{
$count = sizeof($sub_category_id)-1;
$q6 = "SELECT DISTINCT `product_category_id` FROM `product_sub_category` WHERE";
foreach($sub_category_id as $key => $value)
{
if($key<$count)
{
$q6 .= "(`sub_category_id` = '$value') OR ";
}
else
{
$q6 .= "(`sub_category_id` = '$value')";
}
}
$name_categories_row = mysql_query($q6,$conn);
while($row7 = mysql_fetch_assoc($name_categories_row))
{
$name_categories_id[] = $row7['product_category_id'];
}
}
return categoryName($name_categories_id,$conn);
}

function categoryName($name_category_id,$conn)
{
if(sizeof($name_category_id)>0)
{
$count = sizeof($name_category_id)-1;
$q8 = "SELECT DISTINCT `name` FROM `product_categories` WHERE";
foreach($name_category_id as $key => $value)
{
if($key<$count)
{
$q8 .= "(`product_category_id` = '$value') OR ";
}
else
{
$q8 .= "(`product_category_id` = '$value')";
}
}

$name_row = mysql_query($q8,$conn);
$data['type'] = 0;
while($row8 = mysql_fetch_assoc($name_row))
{
$data['value'][] = $row8['name']; 
}
}
return $data;
}

function getFit($sku,$conn)
{
if(sizeof($sku)>0)
{
$count = sizeof($sku)-1;
$q7 = "SELECT DISTINCT `fit` FROM `product_item` WHERE";
foreach($sku as $key => $value)
{
if($key<$count)
{
$q7 .= "(`product_sku` = '$value') OR ";
}
else
{
$q7 .= "(`product_sku` = '$value')";
}
}
$product_fit_row = mysql_query($q7,$conn);
$data['type'] = 0;
while($row7 = mysql_fetch_assoc($product_fit_row))
{
$data['value'][] = $row7['fit'];
}
}
return $data;
}


function getPrice($sku,$conn)
{
if(sizeof($sku)>0)
{
$count = sizeof($sku)-1;
$q8 = "SELECT DISTINCT `product_price` FROM `product_item` WHERE";
foreach($sku as $key => $value)
{
if($key<$count)
{
$q8 .= "(`product_sku` = '$value') OR ";
}
else
{
$q8 .= "(`product_sku` = '$value')";
}
}
$product_price_row = mysql_query($q8,$conn);
while($row8 = mysql_fetch_assoc($product_price_row))
{
$price[] = $row8['product_price'];
}
}
$maxdata = max($price);
$mindata = min($price);
$total = $maxdata - $mindata;
$subtotal = $total/5;
$temp['type'] =1;
for($i = 0; $i<5; $i++)
{
$temp['value'][] =$mindata."-".($mindata+$subtotal);
$mindata = ($mindata+$subtotal);
}
return $temp;
}
?>