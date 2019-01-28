<?php 
require ('conn.php');
$product_sku = $_POST['product_sku'];
$salesman_id = $_POST['userid'];
$email = $_POST['email'];
$color = $_POST['color'];
$product_price = $_POST['product_price'];
$image = $_POST['image'];
$product_name = $_POST['product_name'];
$size = $_POST['size'];
$quantity = $_POST['quantity'];
$amount = $_POST['amount'];

add_to_cart($product_sku,$salesman_id,$product_name,$email,$color,$size,$image,$product_price,$quantity,$amount,$conn);

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