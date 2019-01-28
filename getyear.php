<?php
require('conn.php');

getyear($conn);
function getyear($conn)
{
  $query = "SELECT * FROM `year`";
  $year = mysql_query($query,$conn);
  while($row = mysql_fetch_assoc($year))
  {
     $output[] = array("year" => $row['year']);
  }
   echo json_encode($output);
}
?>