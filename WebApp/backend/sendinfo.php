<?php
  require 'database.php';
  $db = getDB();

  $command = 'python python.py ';
  $python = `$command`;
  $match = substr($python, 0, 2);

  if($match === "ok"){
    echo "redirected \n";
  }
  else{
    echo "no";
  }


?>