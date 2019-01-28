<?php
require('conn.php');

$sql=mysql_fetch_assoc(mysql_query("SELECT COUNT(*) AS 'count',SUM(`amount`) AS `amount`  FROM `cart` WHERE  `email`= 'udhay@gmail.com'",$conn));

$value['count']=$sql['count'];
$value['amount']=$sql['amount'];







print json_encode($value);
?>