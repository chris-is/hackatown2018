<?php
  require 'database.php';
  $db = getDB();

  $command = 'python python.py ';
  $python = `$command`;
  $match = substr($python, 0, 2);

  if($match === "ok"){
    $service = array(
      "fire" => "fire department",
      "robbery" => "police",
      "terrorist" => "police",
      "tsunami" => "weather department",
      "fight" => "abc",
    );


    echo "redirected \n";
    $status = 2;

    //See which issues should be alerted
    $query = "SELECT keyword from client where status=?";
    $stmt = $db->prepare($query);
    $stmt->execute([$status]);
    $keywords = $stmt->fetchAll();

    //Check which department should be alerted
    foreach($keywords as $value){
      $val = $value['keyword'];
      echo $service[$val];
    }


  }
  else{
    echo "no";
  }


?>