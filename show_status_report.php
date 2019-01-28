<?php
require('conn.php');
//$salesman_id = 'test';

$action = $_POST['action'];
switch($action)
{
case "show_status":
$salesman_id = $_POST['userid'];
show_status($salesman_id,$conn);
break;

case "cancel_order":
$salesman_id = $_POST['userid'];
$order_id = $_POST['order_id'];
cancel_order($salesman_id,$order_id,$conn);
break;

case "show_month_status":
$salesman_id = $_POST['userid'];
$year = $_POST['year'];
$month = $_POST['month'];
$status = $_POST['status'];
$name = $_POST['name'];
show_month_status($salesman_id,$year,$month,$status,$name,$conn);
break;

case "show_year_status":
$salesman_id = $_POST['userid'];
$year = $_POST['year'];
show_year_status($salesman_id,$year,$conn);
break;

case "show_name_status":
$salesman_id = $_POST['userid'];
$year = $_POST['year'];
$month = $_POST['month'];
$status = $_POST['status'];
$name = $_POST['name'];
show_name_status($salesman_id,$year,$month,$status,$name,$conn);
break;

case "show_status_status":
$salesman_id = $_POST['userid'];
$year = $_POST['year'];
$month = $_POST['month'];
$status = $_POST['status'];
$name = $_POST['name'];
show_status_status($salesman_id,$year,$month,$status,$name,$conn);
break;
}

function show_status($salesman_id,$conn)
{
$query = "SELECT * FROM `order` WHERE `salesman_id` = '$salesman_id'";
$order = mysql_query($query,$conn);
while($row = mysql_fetch_assoc($order))
{
$query1 = "SELECT * FROM `clients` WHERE `email` = '".$row['email']."'";
$name = mysql_query($query1,$conn);
while($r1 = mysql_fetch_Assoc($name))
{
$output[] = array("order_id" => $row['order_id'],
                          "name" => $r1['name'],
                          "total_amount" => $row['total_amount'],
                          "commission" => $row['commission'],
                          "date" => $row['date'],
                          "status" => $row['status']);
}
}
      echo json_encode($output);

}
function cancel_order($salesman_id,$order_id,$conn)
{
 $query = "UPDATE `order` SET `status` = '3' WHERE `order_id` = '$order_id'";
 if(mysql_query($query,$conn))
 {
   echo "Status Update";
 }
 else
 { 
 echo "Incorrect Order_id";
 }
}


function show_month_status($salesman_id,$year,$month,$status,$name,$conn)
{

if($month == "Select")
{
$num = "";
}
if($month == "January")
	{
		$num = "01";
	}
	if($month == "February")
	{
		$num = "02";	
	}
	if($month == "March")
	{
		$num = "03";
	}
	if($month == "April")
	{
		$num = "04";
	}
	if($month == "May")
	{
		$num = "05";
	}
	if($month =="June")
	{
		$num = "06";
	}
	if($month == "July")
	{
		$num = "07";
	}
	if($month == "August")
	{
		$num = "08";
	}
	if($month == "September")
	{
		$num = "09";
	}
	if($month == "October")
	{
		$num = "10";
	}
	if($month == "November")
	{
		$num = "11";
	}
	if($month == "December")
	{
		$num = "12";
	}
 $pattern = $year."-".$num;

if($status == "Placed")
{
   $statuspattern = "0";
}
if($status == "Pending")
 {
   $statuspattern = "1";
}
if($status == "Delivered")
{
   $statuspattern = "2";
}
if($status == "Cancelled")
{
   $statuspattern = "3"; 
}
if($name == "Select" && $status == "Select")
{
$query3 = "SELECT * FROM `order` WHERE `salesman_id` = '$salesman_id' AND `date` LIKE '$pattern%'";
 $order1 = mysql_query($query3,$conn);
while($row1 = mysql_fetch_assoc($order1))
{
$query4 = "SELECT * FROM `clients` WHERE `email` = '".$row1['email']."'";
$name1 = mysql_query($query4,$conn);
while($r2 = mysql_fetch_assoc($name1))
{
$output1[] = array("order_id" => $row1['order_id'],
                          "name" => $r2['name'],
                          "total_amount" => $row1['total_amount'],
                          "commission" => $row1['commission'],
                          "date" => $row1['date'],
                          "status" => $row1['status']);
}
}
}
if($status == "Select")
{
$query2 = "SELECT * FROM `clients` WHERE `name` = '$name'";
$namequery = mysql_query($query2,$conn);
while($r1 = mysql_fetch_assoc($namequery))
{	
$query3 = "SELECT * FROM `order` WHERE `salesman_id` = '$salesman_id' AND `date` LIKE '$pattern%' AND `email` = '".$r1['email']."'";

$orderby1 = mysql_query($query3,$conn);
while($row3 = mysql_fetch_assoc($orderby1))
{
$output1[] = array("order_id" => $row3['order_id'],
                          "name" => $r1['name'],
                          "total_amount" => $row3['total_amount'],
                          "commission" => $row3['commission'],
                          "date" => $row3['date'],
                          "status" => $row3['status']);
}
}
}
if($name == "Select")
{
$q11 = "SELECT * FROM `order` WHERE `salesman_id` = '$salesman_id' AND `status` = '$statuspattern' AND `date` LIKE '$pattern%'";
$orderto = mysql_query($q11,$conn);
while($r3 = mysql_fetch_assoc($orderto))
{
$output1[] = array("order_id" => $r3['order_id'],
                          "name" => $r2['name'],
                          "total_amount" => $r3['total_amount'],
                          "commission" => $r3['commission'],
                          "date" => $r3['date'],
                          "status" => $r3['status']);
}
}
else
{
$query2 = "SELECT * FROM `clients` WHERE `name` = '$name'";
$namequery = mysql_query($query2,$conn);
while($r1 = mysql_fetch_assoc($namequery))
{	
$query3 = "SELECT * FROM `order` WHERE `salesman_id` = '$salesman_id' AND `status` = '$statuspattern' AND `date` LIKE '$pattern%' AND `email` = '".$r1['email']."'";

$orderby1 = mysql_query($query3,$conn);
while($row3 = mysql_fetch_assoc($orderby1))
{
$output1[] = array("order_id" => $row3['order_id'],
                          "name" => $r1['name'],
                          "total_amount" => $row3['total_amount'],
                          "commission" => $row3['commission'],
                          "date" => $row3['date'],
                          "status" => $row3['status']);
}
}
}
echo json_encode($output1);
}

function show_year_status($salesman_id,$year,$conn)
{
 $pattern1 = $year;
 $query5 = "SELECT * FROM `order` WHERE `salesman_id` = '$salesman_id' AND `date` LIKE '$pattern1%'";
 $order2 = mysql_query($query5,$conn);
while($row3 = mysql_fetch_assoc($order2))
{
$query6 = "SELECT * FROM `clients` WHERE `email` = '".$row3['email']."'";
$name2 = mysql_query($query6,$conn);
while($r3 = mysql_fetch_assoc($name2))
{
$output2[] = array("order_id" => $row3['order_id'],
                          "name" => $r3['name'],
                          "total_amount" => $row3['total_amount'],
                          "commission" => $row3['commission'],
                          "date" => $row3['date'],
                          "status" => $row3['status']);
}
}
      echo json_encode($output2);
}

function show_name_status($salesman_id,$year,$month,$status,$name,$conn)
{
if($month == "Select")
{
$num = "";
}
if($month == "January")
	{
		$num = "01";
	}
	if($month == "February")
	{
		$num = "02";	
	}
	if($month == "March")
	{
		$num = "03";
	}
	if($month == "April")
	{
		$num = "04";
	}
	if($month == "May")
	{
		$num = "05";
	}
	if($month =="June")
	{
		$num = "06";
	}
	if($month == "July")
	{
		$num = "07";
	}
	if($month == "August")
	{
		$num = "08";
	}
	if($month == "September")
	{
		$num = "09";
	}
	if($month == "October")
	{
		$num = "10";
	}
	if($month == "November")
	{
		$num = "11";
	}
	if($month == "December")
	{
		$num = "12";
	}
 $pattern = $year."-".$num;

if($status == "Placed")
{
   $statuspattern = "0";
}
if($status == "Pending")
 {
   $statuspattern = "1";
}
if($status == "Delivered")
{
   $statuspattern = "2";
}
if($status == "Cancelled")
{
   $statuspattern = "3"; 
}
if($status == "Select")
{
$query2 = "SELECT * FROM `clients` WHERE `name` = '$name'";
$namequery = mysql_query($query2,$conn);
while($r1 = mysql_fetch_assoc($namequery))
{	
$query3 = "SELECT * FROM `order` WHERE `salesman_id` = '$salesman_id' AND `date` LIKE '$pattern%' AND `email` = '".$r1['email']."'";
 $order1 = mysql_query($query3,$conn);
while($row1 = mysql_fetch_assoc($order1))
{
$query4 = "SELECT * FROM `clients` WHERE `email` = '".$row1['email']."'";
$name1 = mysql_query($query4,$conn);
while($r2 = mysql_fetch_assoc($name1))
{
$output1[] = array("order_id" => $row1['order_id'],
                          "name" => $r2['name'],
                          "total_amount" => $row1['total_amount'],
                          "commission" => $row1['commission'],
                          "date" => $row1['date'],
                          "status" => $row1['status']);
}
}
}
}
if($name == "Select" && $status == "Select")
{
$query3 = "SELECT * FROM `order` WHERE `salesman_id` = '$salesman_id' AND `date` LIKE '$pattern%'";
 $order1 = mysql_query($query3,$conn);
while($row1 = mysql_fetch_assoc($order1))
{
$query6 = "SELECT * FROM `clients` WHERE `email` = '".$row1['email']."'";
$name2 = mysql_query($query6,$conn);
while($r2 = mysql_fetch_assoc($name2))
{
$output1[] = array("order_id" => $row1['order_id'],
                          "name" => $r2['name'],
                          "total_amount" => $row1['total_amount'],
                          "commission" => $row1['commission'],
                          "date" => $row1['date'],
                          "status" => $row1['status']);
}
}
}
else if($name == "Select")
{
$query3 = "SELECT * FROM `order` WHERE `salesman_id` = '$salesman_id' AND `status` = '$statuspattern' AND `date` LIKE '$pattern%'";
 $order1 = mysql_query($query3,$conn);
while($row1 = mysql_fetch_assoc($order1))
{
$query6 = "SELECT * FROM `clients` WHERE `email` = '".$row1['email']."'";
$name2 = mysql_query($query6,$conn);
while($r2 = mysql_fetch_assoc($name2))
{
$output1[] = array("order_id" => $row1['order_id'],
                          "name" => $r2['name'],
                          "total_amount" => $row1['total_amount'],
                          "commission" => $row1['commission'],
                          "date" => $row1['date'],
                          "status" => $row1['status']);
}
}
} 
else
{
 $query4 = "SELECT * FROM `order` WHERE `salesman_id` = '$salesman_id' AND `status` = '$statuspattern' AND `date` LIKE '$pattern%'";
$order2 = mysql_query($query4,$conn);
while($row2 = mysql_fetch_assoc($order2))
{
$query7 = "SELECT * FROM `clients` WHERE `email` = '".$row2['email']."'";
$name2 = mysql_query($query7,$conn);
while($r2 = mysql_fetch_assoc($name2))
{
$output1[] = array("order_id" => $row2['order_id'],
                          "name" => $r2['name'],
                          "total_amount" => $row2['total_amount'],
                          "commission" => $row2['commission'],
                          "date" => $row2['date'],
                          "status" => $row2['status']);
}
}
}
echo json_encode($output1);
}



function show_status_status($salesman_id,$year,$month,$status,$name,$conn)
{
if($month == "Select")
{
$num = "";
}
if($month == "January")
	{
		$num = "01";
	}
	if($month == "February")
	{
		$num = "02";	
	}
	if($month == "March")
	{
		$num = "03";
	}
	if($month == "April")
	{
		$num = "04";
	}
	if($month == "May")
	{
		$num = "05";
	}
	if($month =="June")
	{
		$num = "06";
	}
	if($month == "July")
	{
		$num = "07";
	}
	if($month == "August")
	{
		$num = "08";
	}
	if($month == "September")
	{
		$num = "09";
	}
	if($month == "October")
	{
		$num = "10";
	}
	if($month == "November")
	{
		$num = "11";
	}
	if($month == "December")
	{
		$num = "12";
	}
 $pattern = $year."-".$num;

if($status == "Placed")
{
   $statuspattern = "0";
}
if($status == "Pending")
 {
   $statuspattern = "1";
}
if($status == "Delivered")
{
   $statuspattern = "2";
}
if($status == "Cancelled")
{
   $statuspattern = "3"; 
}
if($name == "Select")
{
$query3 = "SELECT * FROM `order` WHERE `salesman_id` = '$salesman_id' AND `status` = '$statuspattern' AND `date` LIKE '$pattern%'";
 $order1 = mysql_query($query3,$conn);
while($row1 = mysql_fetch_assoc($order1))
{
$query6 = "SELECT * FROM `clients` WHERE `email` = '".$row1['email']."'";
$name2 = mysql_query($query6,$conn);
while($r2 = mysql_fetch_assoc($name2))
{
$output1[] = array("order_id" => $row1['order_id'],
                          "name" => $r2['name'],
                          "total_amount" => $row1['total_amount'],
                          "commission" => $row1['commission'],
                          "date" => $row1['date'],
                          "status" => $row1['status']);
}
}
}
else
{
$query2 = "SELECT * FROM `clients` WHERE `name` = '$name'";
$namequery = mysql_query($query2,$conn);
while($r1 = mysql_fetch_assoc($namequery))
{	
$query3 = "SELECT * FROM `order` WHERE `salesman_id` = '$salesman_id' AND `status` = '$statuspattern' AND `date` LIKE '$pattern%' AND `email` = '".$r1['email']."'";

$orderby1 = mysql_query($query3,$conn);
while($row3 = mysql_fetch_assoc($orderby1))
{
$output1[] = array("order_id" => $row3['order_id'],
                          "name" => $r1['name'],
                          "total_amount" => $row3['total_amount'],
                          "commission" => $row3['commission'],
                          "date" => $row3['date'],
                          "status" => $row3['status']);
}
}
}
echo json_encode($output1);
}
?>	