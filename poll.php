<?php
require("opendb.php");
$total = $_GET['total'];
query($total);

function query ($total){
  $query = "SELECT * FROM stories";
  $result = mysql_query($query);
  if(mysql_num_rows($result) > $total){
    echo "true";
  } else {
    echo "false";
  } 
}

?>