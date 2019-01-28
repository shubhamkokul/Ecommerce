<?php
require('conn.php');
//error_reporting(-1);
$action = getRealEscape($_POST['action']);
$flag['code'] = 0;
$flag['msg'] = "No Parameter is Passed";
switch($action)
{
case "login":
$userid = getRealEscape($_POST['userid']);
$password = getRealEscape($_POST['password']);
$deviceid = getRealEscape($_POST['device_id']);
login($userid,$password,$deviceid);
break;

case "getslider":
getslider();
break;

case "category":
category();
break;

case "forgot_password":
forgot_password(getRealEscape($_POST['userid']));
break;

case "other_logout":
other_logout(getRealEscape($_POST['device_id']),getRealEscape($_POST['userid']));
break;

case "show_kit":
$salesman_id = getRealEscape($_POST['userid']);
//$email = getRealEscape($_POST['email']);
show_kit($salesman_id,$conn);
break;

case "add_to_kit":
$salesman_id = getRealEscape($_POST['userid']);
$product_sku = getRealEscape($_POST['product_sku']);
add_to_kit($product_sku,$salesman_id,$conn);
break;

case "add_to_cart":
$product_sku = getRealEscape($_POST['product_sku']);
$salesman_id = getRealEscape($_POST['userid']);
$email = getRealEscape($_POST['email']);
$color = getRealEscape($_POST['color']);
$size = getRealEscape($_POST['size']);
$product_name = getRealEscape($_POST['product_name']);
$image = getRealEscape($_POST['image']);
$product_price = getRealEscape($_POST['product_price']);
$quantity = getRealEscape($_POST['quantity']);
$amount = getRealEscape($_POST['amount']);
add_to_cart($product_sku,$salesman_id,$product_name,$email,$color,$size,$image,$product_price,$quantity,$amount,$conn);
break;

case "add_to_cart_update":
$product_sku = getRealEscape($_POST['product_sku']);
$salesman_id = getRealEscape($_POST['userid']);
$email = getRealEscape($_POST['email']);
$color = getRealEscape($_POST['color']);
$size = getRealEscape($_POST['size']);
$product_name = getRealEscape($_POST['product_name']);
$image = getRealEscape($_POST['image']);
$product_price = getRealEscape($_POST['product_price']);
$quantity = getRealEscape($_POST['quantity']);
$amount = getRealEscape($_POST['amount']);
add_to_cart_update($product_sku,$salesman_id,$product_name,$email,$color,$size,$image,$product_price,$quantity,$amount,$conn);
break;

case "show_cart":
$salesman_id = $_POST['userid'];

show_cart($salesman_id,$conn);
break;

case "scan_barcode":
$barcode = getRealEscape($_POST['barcode']);
scan_barcode($barcode,$conn);
break;

case "show_user_edit":
$email = getRealEscape($_POST['email']);
$salesman_id = getRealEscape($_POST['userid']);
show_user_edit($salesman_id,$email,$conn);
break;

case "edit_user":
$name = getRealEscape($_POST['name']);
$email = getRealEscape($_POST['email']);
$address = getRealEscape($_POST['address']);
$pan_no = getRealEscape($_POST['pan_no']);
$transport = getRealEscape($_POST['transport']);
$reference = getRealEscape($_POST['reference']);
$number = getRealEscape($_POST['number']);
edit_user($name,$email,$address,$pan_no,$transport,$reference,$number,$conn);
break;

case "add_new_user":
$name = getRealEscape($_POST['name']);
$email = getRealEscape($_POST['email']);
$address = getRealEscape($_POST['address']);
$salesman_id = getRealEscape($_POST['userid']);
$pan_no = getRealEscape($_POST['pan_no']);
$transport = getRealEscape($_POST['transport']);
$reference = getRealEscape($_POST['reference']);
$number = getRealEscape($_POST['number']);
check_email($name,$email,$address,$salesman_id,$pan_no,$transport,$reference,$number,$conn);
break;

case "show_user":
show_user($conn);
break;

case "cartUser":
$salesman_id = $_POST['userid'];
$email = $_POST['email'];
cartUser($salesman_id,$email,$conn);
break;

default:
echo json_encode($flag);
break; 

}

function login($userid,$password,$deviceid){
require('conn.php');
$login=mysql_query("SELECT * from `salesman` where `userid`='$userid' AND `password`='$password'");
$noofrows=mysql_num_rows($login); 
$flag['code']=0;
$row = mysql_fetch_assoc($login); 
 
if ($noofrows >0){

mysql_query("UPDATE `salesman` SET `device_id` = '$deviceid' WHERE `userid` = '$userid'",$conn);

$flag['code']=1;
$flag['msg'] = "Username and/or Password is valid";
$flag['name'] = $row['name'];
}else{
$flag['code']=0;
$flag['msg'] = "Username and/or Password is Invalid";
}
echo json_encode($flag);
}


function getRealEscape($value){
return mysql_real_escape_string($value);
}

function getslider(){
require('conn.php');
$result = mysql_query("SELECT * FROM `slider` ORDER BY `id` DESC" ,$conn);
while($row = mysql_fetch_assoc($result)){
$flag['slides'][] = array('id' => $row['id'] , 'images' => $row['image']);
}

echo json_encode($flag);
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


function forgot_password($userid){
require('conn.php');
$flag['code'] = 0;

$query="SELECT `email`,`password` FROM `salesman` WHERE  `userid` ='$userid' ";
$r2 = mysql_query($query,$conn);
if (mysql_num_rows($r2)!=0) {
	$row = mysql_fetch_assoc($r2);

$email=$row['email'];
$pass=$row['password'];

$subject = 'Password Reset!';
$message = "Your Password $pass";
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers = 'From: testing@innowrap.com' . "\r\n" .
    'Reply-To: testing@innowrap.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

if(mail($email, $subject, $message,$headers)){
$flag['code'] = 1;
$flag['message'] = "Password has been send Your Mail Id";
}else{
$flag['message'] = "Unable to send mail";
}
}else{
$flag['message'] = "Invalid userid";	
}
echo json_encode($flag);
}

function other_logout($deviceid,$userid){
require('conn.php');
$logout=mysql_query("SELECT * from `salesman` where `userid`='$userid' AND `device_id`='$deviceid'");
$noofrows=mysql_num_rows($logout);
if($noofrows>0){
$flag['code']=1;
$flag['message'] = "same device id is present";
}else{
$flag['code']=0;
$flag['message'] = "user logged-in in other device";
}
echo json_encode($flag);
}


function show_kit($salesman_id,$conn)
{
	$query = "SELECT * FROM `buscket` WHERE `salesman_id` = '$salesman_id'";
	$kit = mysql_query($query,$conn);
    while($row = mysql_fetch_assoc($kit))
        {

        $product['product_sku'] = $row['product_sku'];
        //$product = size($product,$row['product_sku'],$conn);
		$product = show_size_color($product,$row['product_sku'],$conn);
		$product = show_image($product,$row['product_sku'],$conn);
		$product = show_price($product,$row['product_sku'],$conn);
		$product = show_description($product,$row['product_sku'],$conn);
		$product = show_product_name($product,$row['product_sku'],$conn);
		$product = show_product_sku($product,$row['product_sku'],$conn);
                $data[] = $product;
                unset($product);
		
        }
        echo json_encode($data);
    }
	
function show_image($buscket,$product_sku,$conn)
{
	$q3 = "SELECT `image` FROM `image` WHERE `product_sku`= '$product_sku'";
	$image = mysql_query($q3,$conn);
	while($row = mysql_fetch_assoc($image))
	{
	$buscket['image']=$row['image'];
	}
	return $buscket;
}
function show_price($buscket,$product_sku,$conn)
{
	$q4 = "SELECT `product_price` FROM `product_item` WHERE `product_sku`= '$product_sku'";
	$product_price = mysql_query($q4,$conn);
	while($row = mysql_fetch_assoc($product_price))
	{
	$buscket['product_price'] = $row['product_price'];
        //$disc = getproductemail($salesman_id,$product_sku,$email,$conn);
        //$discount_rate = ($disc/100)*(int)$row['product_price'];
        //$discount_amount = (int)($row['product_price']) - $discount_rate;
        //$buscket['product_price_discount'] = $discount_amount;
	}
	return $buscket;
}
function show_description($buscket,$product_sku,$conn)
{
	$q5 = "SELECT `description` FROM `product_item` WHERE `product_sku`= '$product_sku'";
	$description = mysql_query($q5,$conn);
	while($row = mysql_fetch_assoc($description))
	{
	$buscket['description'] =  $row['description'];
	}
	return $buscket;
}
function show_product_name($buscket,$product_sku,$conn)
{
	$q6 = "SELECT `product_name` FROM `product_item` WHERE `product_sku`= '$product_sku'";
	$name = mysql_query($q6,$conn);
	while($row = mysql_fetch_assoc($name))
	{
	$buscket['name'] =  $row['product_name'];
	}
	return $buscket;
}
function show_product_sku($buscket,$product_sku)
{
$buscket['product_sku'] =  $product_sku;
return $buscket;
}
function show_size_color($buscket,$product_sku,$conn)
{
	$q7 = "SELECT * FROM `product_item` WHERE `product_sku` = '$product_sku'";
	$size_color = mysql_query($q7,$conn);
	while($r1 = mysql_fetch_assoc($size_color))
	{
	$buscket['color'] = $r1['color'];
	$buscket['size'] = $r1['size'];
    }
	return $buscket;
}

function add_to_kit($product_sku,$salesman_id,$conn)
{
insert_into_buscket($product_sku,$salesman_id,$conn);
//$output = image($output,$product_sku,$conn);
//$output = price($output,$product_sku,$conn);
//$output = description($output,$product_sku,$conn);
//$output = product_name($output,$product_sku,$conn);
//$output = product_sku($output,$product_sku);
//$output['code'] = "1";
}
function insert_into_buscket($product_sku,$salesman_id,$conn)
{
        $q1 = "SELECT * FROM `buscket` WHERE `product_sku` = '$product_sku' AND `salesman_id` = '$salesman_id'";
        $res = mysql_query($q1,$conn);
        $number = mysql_num_rows($res);
        $output['code'] = "0";
        $output['message'] = "Already in the Kit";
        if($number<=0)
        {
         $query = "INSERT INTO `buscket` (`product_sku`,`salesman_id`) VALUES ('$product_sku','$salesman_id')";
	  mysql_query($query,$conn);
         $output['code'] = "1";
         $output['message'] = "Added to Kit";  
        }
        else
        {
        }

	echo json_encode($output);	
}

function image($output,$product_sku,$conn)
{
	$q3 = "SELECT `image` FROM `image` WHERE `product_sku`= '$product_sku'";
	$image = mysql_query($q3,$conn);
	while($row = mysql_fetch_assoc($image))
	{
	$output['image']=$row['image'];
	}
	return $output;
}
function price($output,$product_sku,$conn)
{
	$q4 = "SELECT `product_price` FROM `product_item` WHERE `product_sku`= '$product_sku'";
	$product_price = mysql_query($q4,$conn);
	while($row = mysql_fetch_assoc($product_price))
	{
	$output['product_price']=  $row['product_price'];
	}
	return $output;
}
function description($output,$product_sku,$conn)
{
	$q5 = "SELECT `description` FROM `product_item` WHERE `product_sku`= '$product_sku'";
	$description = mysql_query($q5,$conn);
	while($row = mysql_fetch_assoc($description))
	{
	$output['description'] =  $row['description'];
	}
	return $output;
}
function product_name($output,$product_sku,$conn)
{
	$q6 = "SELECT `product_name` FROM `product_item` WHERE `product_sku`= '$product_sku'";
	$name = mysql_query($q6,$conn);
	while($row = mysql_fetch_assoc($name))
	{
	$output['name'] =  $row['product_name'];
	}
	return $output;
}
function product_sku($output,$product_sku)
{
$output['product_sku'] =  $product_sku;
return $output;
}



function add_to_cart($product_sku,$salesman_id,$product_name,$email,$color,$size,$image,$product_price,$quantity,$amount,$conn)
{
	$q1 = "SELECT * FROM `cart` WHERE `product_sku` = '$product_sku' AND `salesman_id` = '$salesman_id' AND `color` = '$color' AND `size` = '$size'";
        $res = mysql_query($q1,$conn);
        $number = mysql_num_rows($res);
        if($number<=0)
        {
        $query = "INSERT INTO `cart` (`product_sku`,`salesman_id`,`product_name`,`email`,`color`,`size`,`image`,`product_price`,`quantity`,`amount`) VALUES ('$product_sku','$salesman_id','$product_name','$email','$color','$size','$image','$product_price','$quantity','$amount')";
	if(mysql_query($query,$conn))
	{
        $cartNumber = cartCount($salesman_id,$email,$conn);
        list($data1,$data2) = AmountQuality($salesman_id,$email,$conn);
	$flag['code'] = 1;
	$flag['message'] = "New product added";
        $flag['cartCount'] = $cartNumber;
        $flag['quntityNumber'] = $data1;
        $flag['amount'] = $data2;
	echo json_encode($flag);	
	}
	else
	{
        $cartNumber = cartCount($salesman_id,$email,$conn);
        list($data1,$data2) = AmountQuality($salesman_id,$email,$conn);
	$flag['code'] = 0;
	$flag['message'] = "Insufficient parameters";
        $flag['cartCount'] = $cartNumber;
        $flag['quntityNumber'] = $data1;
        $flag['amount'] = $data2;
	echo json_encode($flag);	
	}
        }
        else
        {
if($quantity == "0")
            {
              $q6 = "DELETE FROM `cart` WHERE `salesman_id` = '$salesman_id' AND `product_sku` = '$product_sku'";
                  mysql_query($q6,$conn);  
                  $flag['code'] = 3;
                  $flag['message'] = "Deleted From Cart";
            }
else
{
          $cartNumber = cartCount($salesman_id,$email,$conn);
          list($data1,$data2) = AmountQuality($salesman_id,$email,$conn);
          $flag['code'] = 2;
          $flag['message'] = "Already in Cart";
          $flag['cartCount'] = $cartNumber;
          $flag['quntityNumber'] = $data1;
          $flag['amount'] = $data2;
}
         
          echo json_encode($flag);
        }
}

function add_to_cart_update($product_sku,$salesman_id,$product_name,$email,$color,$size,$image,$product_price,$quantity,$amount,$conn)
{
$q1 = "SELECT * FROM `cart` WHERE `product_sku` = '$product_sku' AND `salesman_id` = '$salesman_id' AND `color` = '$color' AND `size` = '$size'";
        $res = mysql_query($q1,$conn);
        $number = mysql_num_rows($res);
        if($number<=0)
        {
        $query = "INSERT INTO `cart` (`product_sku`,`salesman_id`,`product_name`,`email`,`color`,`size`,`image`,`product_price`,`quantity`,`amount`) VALUES ('$product_sku','$salesman_id','$product_name','$email','$color','$size','$image','$product_price','$quantity','$amount')";
	if(mysql_query($query,$conn))
	{
        $cartNumber = cartCount($salesman_id,$email,$conn);
        list($data1,$data2) = AmountQuality($salesman_id,$email,$conn);
	$flag['code'] = 1;
	$flag['message'] = "New product added";
        $flag['cartCount'] = $cartNumber;
        $flag['quntityNumber'] = $data1;
        $flag['amount'] = $data2;
	echo json_encode($flag);	
	}
	else
	{
        $cartNumber = cartCount($salesman_id,$email,$conn);
        list($data1,$data2) = AmountQuality($salesman_id,$email,$conn);
	$flag['code'] = 0;
	$flag['message'] = "Insufficient parameters";
        $flag['cartCount'] = $cartNumber;
        $flag['quntityNumber'] = $data1;
        $flag['amount'] = $data2;
	echo json_encode($flag);	
	}
        }
        else
        {
if($quantity == "0")
            {
              $q6 = "DELETE FROM `cart` WHERE `salesman_id` = '$salesman_id' AND `product_sku` = '$product_sku'";
                  mysql_query($q6,$conn);  
                  $flag['code'] = 3;
                  $flag['message'] = "Deleted From Cart";
            }
else
{         
$q2 = "UPDATE `cart` SET `quantity` = '$quantity' , `amount` = '$amount' WHERE `product_sku` = '$product_sku' AND `salesman_id` = '$salesman_id' AND `color` = '$color' AND `size` = '$size'";
          mysql_query($q2,$conn);
          $cartNumber = cartCount($salesman_id,$email,$conn);
          list($data1,$data2) = AmountQuality($salesman_id,$email,$conn);
          $flag['code'] = 2;
          $flag['message'] = "Quantity Updated";
          $flag['cartCount'] = $cartNumber;
          $flag['quntityNumber'] = $data1;
          $flag['amount'] = $data2;
}
          echo json_encode($flag);
}
}

function show_cart($salesman_id,$conn)
{
	/*$query = "SELECT * FROM `cart` WHERE `salesman_id` = '$salesman_id'";
	$cart = mysql_query($query,$conn);
        while($row = mysql_fetch_assoc($cart))
        {
        $output = array("product_sku" => $row['product_sku'],
                          "salesman_id" => $row['salesman_id'],
                          "color" => $row['color'],
                          "product_name" => $row['product_name'],
                          "product_price" => $row['product_price'],
                          "image" => $row['image'],
                          "quantity" => $row['quantity'],
                          "amount" => $row['amount']);
        
        $output = show_size_cart($row['product_sku'],$output,$conn);
         		$data['elements'][] = $output;
                unset($output);
        }
        
	echo json_encode($data);*/


  $resultCart = mysql_query("SELECT * FROM `cart` WHERE `salesman_id` = '$salesman_id'",$conn);
  if (mysql_num_rows($resultCart)>0) {
    $output['code'] = 1;

    

    while ($row = mysql_fetch_assoc($resultCart)) {
    	
      if (isParent($row['product_sku'],$conn)==1) {

         $resultTrue = mysql_query("SELECT * FROM `product_item` WHERE `parent_product_sku` = '".$row['product_sku']. "' AND `color` = '".$row['color']."'",$conn);

		 $list['childs'][] = array('product_sku' => $row['product_sku'],'quantity'=>getQuantity($row['product_sku'],$conn),'size' => $row['size'],'cost'=>$row['product_price']);

          if (mysql_num_rows($resultTrue)>0) {
              
               while ($row1 = mysql_fetch_assoc($resultTrue)) {
                  if (isExistInCart($row1['product_sku'],$conn)==1) {
                      $list['childs'][] = array('product_sku' => $row1['product_sku'],'quantity'=>getQuantity($row1['product_sku'],$conn),'size' => $row1['size'],'cost'=>$row1['product_price']);
                  }else{
                      $data = getChilds($row['product_sku'],$row['color'],$conn);
                      for($i=0;$i<sizeof($data);$i++){
                        $list['childs'][] = array('product_sku' => $data[$i]['product_sku'],'quantity'=>0,'size' =>$data[$i]['size'] ,'cost'=>$data[$i]['product_price']);
                      }
                  }
               }
          }else{
            
          }
          $output['products'][] = array('product_sku' =>$row['product_sku'] ,'image' => $row['image'],'product_name' => $row['product_name'],'color' => $row['color'],'product_list'=>$list);
      }else{
      	 $parent_product_sku = getParentProductSku($row['product_sku'],$conn);

      	 	if ($row['color'] == getParentColor($parent_product_sku,$conn)) {
      	 		if (isExistInCart($parent_product_sku,$conn)!=1) {
      	 	 		$list['childs'][] = array('product_sku' => $row['product_sku'],'quantity'=>getQuantity($row['product_sku'],$conn),'size' => $row['size'],'cost'=>$row['product_price']);
      	 		$data = getProductChildsUsingParentSku($parent_product_sku,$row['product_sku'],$conn);
                      for($i=0;$i<sizeof($data);$i++){
                      	if ($data[$i]['product_sku'] != $row['product_sku']) {
                      		$list['childs'][] = array('product_sku' => $data[$i]['product_sku'],'quantity'=>0,'size' =>$data[$i]['size'] ,'cost'=>$data[$i]['product_price']);
                      	}
             
                      }
      	 		$output['products'][] = array(
      	 			'product_sku' =>$row['product_sku'] ,
      	 			'image' => $row['image'],
      	 			'product_name' => $row['product_name'],
      	 			'color' => $row['color'],
      	 			'product_list'=>$list
      	 			);
      	 		}
      	 	}else{
      	 		$list['childs'][] = array('product_sku' => $row['product_sku'],'quantity'=>getQuantity($row['product_sku'],$conn),'size' => $row['size'],'cost'=>$row['product_price']);
      	 		$data = getProductChildsUsingParentSku($parent_product_sku,$row['product_sku'],$conn);
                      for($i=0;$i<sizeof($data);$i++){
                       if ($data[$i]['product_sku'] != $row['product_sku']) {
                      		$list['childs'][] = array('product_sku' => $data[$i]['product_sku'],'quantity'=>0,'size' =>$data[$i]['size'] ,'cost'=>$data[$i]['product_price']);
                      	}
                      }
      	 		$output['products'][] = array(
      	 			'product_sku' =>$row['product_sku'] ,
      	 			'image' => $row['image'],
      	 			'product_name' => $row['product_name'],
      	 			'color' => $row['color'],
      	 			'product_list'=>$list
      	 			);
      	 	}

      	 /*if (isExistInCart($parent_product_sku,$conn)!=1) {
      	 	 
      	 }else{
      	 	if ($row['color'] == getParentColor($parent_product_sku,$conn)) {
      	 		
      	 	}else{
      	 		$list['childs'][] = array('product_sku' => $row['product_sku'],'quantity'=>getQuantity($row['product_sku'],$conn),'size' => $row['size'],'cost'=>$row['product_price']);
      	 		$data = getChilds($row['product_sku'],$row['color'],$conn);
                      for($i=0;$i<sizeof($data);$i++){
                        $list['childs'][] = array('product_sku' => $data[$i]['product_sku'],'quantity'=>0,'size' =>$data[$i]['size'] ,'cost'=>$data[$i]['product_price']);
                      }
      	 		$output['products'][] = array(
      	 			'product_sku' =>$row['product_sku'] ,
      	 			'image' => $row['image'],
      	 			'product_name' => $row['product_name'],
      	 			'color' => $row['color'],
      	 			'product_list'=>$list
      	 			);
      	 	}
      	 }*/

      }
      unset($list);

    }

  }else{
    $output['code'] = 0;
  }
        echo json_encode($output);


}

function getProductChildsUsingParentSku($parent_product_sku,$product_sku,$conn){
	
	$row = mysql_fetch_assoc(mysql_query("SELECT * FROM `product_item` WHERE `product_sku`='$product_sku'",$conn));
	$color = $row['color'];

	$resultParentChilds = mysql_query("SELECT * FROM `product_item` WHERE `parent_product_sku` = '$parent_product_sku'",$conn);
	while ($row1 = mysql_fetch_assoc($resultParentChilds)) {
		if ($row1['color']==$color) {
			$data[] = $row;
		}
	}

	return $data;

}

function getParentColor($parent_product_sku,$conn){
	$row = mysql_fetch_assoc(mysql_query("SELECT * FROM `product_item` WHERE `product_sku` = '$parent_product_sku'",$conn));
	return $row['color'];
}

function getParentProductSku($product_sku,$conn){
	$row = mysql_fetch_assoc(mysql_query("SELECT * FROM `product_item` WHERE `product_sku` = '$product_sku'",$conn));
	return $row['parent_product_sku'];
}

function getChilds($product_sku,$color,$conn){
  $resultChilds = mysql_query("SELECT * FROM `product_item` WHERE `parent_product_sku` = '$product_sku' AND `color` = '$color'",$conn);
  while ($row = mysql_fetch_assoc($resultChilds)) {
      $data[] = $row;
  }
  return $data;
}

function getQuantity($product_sku,$conn){
  $row = mysql_fetch_assoc(mysql_query("SELECT * FROM `cart` WHERE `product_sku` = '$product_sku'",$conn));
  return $row['quantity'];
}

function isExistInCart($product_sku,$conn){
  if (mysql_num_rows(mysql_query("SELECT * FROM `cart` WHERE `product_sku` = '$product_sku'",$conn))>0) {
        return 1;
  }else{
        return 0;
  }
}



function isParent($product_sku,$conn){
    if (mysql_num_rows(mysql_query("SELECT * FROM `product_item` WHERE `parent_product_sku` = '$product_sku'",$conn))) {
      return 1;
    }else{
      return 0;
    }
}

function show_size_cart($product_sku,$output,$conn)
{
	$query = "SELECT * FROM `size` WHERE `product_sku` LIKE '$product_sku%'";
		$size_cart = mysql_query($query,$conn);
		while($rw1 = mysql_fetch_assoc($size_cart))
		{
			$output['size'][] = $rw1['size'];
		}	
		return $output;
}

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

function show_user_edit($salesman_id,$email,$conn)
{
 $query = "SELECT * FROM `clients` WHERE `salesman_id` = '$salesman_id' AND `email` = '$email'";
	$client = mysql_query($query,$conn);
      $row = mysql_fetch_assoc($client);
        
        $output[] = array("name" => $row['name'],
                          "email" => $row['email'],
                          "address" => $row['address'],
                          "salesman_id" => $row['salesman_id'],
                          "pan_no" => $row['pan_no'],
                          "transport" => $row['transport'],
                          "reference" => $row['reference'],
                           "number" => $row['number']);
        
        
	echo json_encode($output);	
}

function edit_user($name,$email,$address,$pan_no,$transport,$reference,$number,$conn)
{
	$query = "UPDATE clients SET `name` = '$name',`address` = '$address',`pan_no` = '$pan_no', `transport` = '$transport',`reference` = '$reference', `number` = '$number' WHERE `email` = '$email'";
	if(mysql_query($query,$conn))
	{
	$flag['code'] = 1;
	$flag['message'] = "Details Updated";
	echo json_encode($flag);	
	}
	else
	{
	$flag['code'] = 0;
	$flag['message'] = "User Details not updated";
	echo json_encode($flag);	
	}
	
}


function add_new_user($name,$email,$address,$salesman_id,$pan_no,$transport,$reference,$number,$conn)
    {
	$query = "INSERT INTO clients (`name`,`email`,`address`,`salesman_id`,`pan_no`,`transport`,`reference`,`number`) VALUES ('$name','$email','$address','$salesman_id','$pan_no','$transport','$reference','$number')";

	if(mysql_query($query,$conn))
	{
	$flag['code'] = 1;
	$flag['message'] = "New User added";
	echo json_encode($flag);	
	}
	else
	{
	$flag['code'] = 0;
	$flag['message'] = "Invalid Parameters";
	echo json_encode($flag);	
	}
	
}

function show_user($conn)
{
 $query = "SELECT * FROM `clients`";
	$cart = mysql_query($query,$conn);
        while($row = mysql_fetch_assoc($cart))
        {
        $output[] = array("name" => $row['name'],
                          "email" => $row['email'],
                          "address" => $row['address'],
                          "salesman_id" => $row['salesman_id'],
                          "pan_no" => $row['pan_no'],
                          "transport" => $row['transport'],
                          "reference" => $row['reference'],
                          "number" => $row['number']);
        }
        
	echo json_encode($output);	
}

function cartCount($salesman_id,$email,$conn)
{
      $q1 = "SELECT * FROM `cart` WHERE `salesman_id` = '$salesman_id' AND `email` = '$email'";
      $cartcountnumber = mysql_query($q1,$conn);
      $cartnumber = mysql_num_rows($cartcountnumber);
      return $cartnumber;
}
function cartUser($salesman_id,$email,$conn)
{
      //$q2 = "DELETE FROM `cart` WHERE `salesman_id` = '$salesman_id'";
      //mysql_query($q2,$conn);
      $q1 = "SELECT * FROM `cart` WHERE `salesman_id` = '$salesman_id' AND `email` = '$email'";
      $cartcountnumber = mysql_query($q1,$conn);
      list($data1,$data2) = AmountQuality($salesman_id,$email,$conn);
      $cartnumber = mysql_num_rows($cartcountnumber);
      $flag['quantity'] = $data1;
      $flag['amount'] = $data2;
      $flag['cartnumber'] = $cartnumber;
      echo json_encode($flag);

}


function AmountQuality($salesman_id,$email,$conn)
{
   $quantity = 0;
   $amount =0;
   $q1 = "SELECT * FROM `cart` WHERE `salesman_id` = '$salesman_id' AND `email` = '$email'";
   $amountquantity = mysql_query($q1,$conn);
   while($row = mysql_fetch_assoc($amountquantity))
{
   $quantity = $quantity + ((int)$row['quantity']);
   
   $disc = (int)getproductemail($salesman_id,$row['product_sku'],$email,$conn);

   $intrimamount = ($disc/100) * (int)$row['amount'];
 
   $amount2 = (int)$row['amount'] - $intrimamount;

   $amount = $amount + $amount2;
}
return array($quantity,$amount);
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