<?php
include("conn.php");
$query="SELECT * FROM `salesman`";
$res = mysql_query($query,$conn);
?>
<table border="1">
<?php
while($row=mysql_fetch_array($res)){
?>
<tr>
<td><?php echo $row['userid']; ?></td>
<td><?php echo $row['password']; ?></td>
<td><?php echo $row['Address']; ?></td>
<td><?php echo $row['email']; ?></td>
</tr>
<?php
}
?>
</table>