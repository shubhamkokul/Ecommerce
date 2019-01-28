<?php
require('conn.php');
$salesman_id = $_POST['userid'];
$email = $_POST['email'];



show_cart($salesman_id,$email,$conn);


function show_cart($salesman_id,$email,$conn)
{

  $resultCart = mysql_query("SELECT * FROM `cart` WHERE `salesman_id` = '$salesman_id' AND `email` = '$email'",$conn);
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
?>