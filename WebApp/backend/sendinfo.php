<?php
  echo "Your message was successfully sent. Thanks for the tip!\n";
  require 'database.php';
  $db = getDB();

  $command = 'python python.py ';
  $python = `$command`;
  $match = substr($python, 0, 2);

  if($match === "ok"){
    $service = array(
      "fire" => "fire department",
      /*
      "tsunami" =>,
      "earthquake" =>,
      "flood" =>,
      "blizzard" =>,
      "snowstorm" =>,
      "hurricane" =>,
      "tornado" =>,
      "wildfire" =>,
      "avalance" =>,
      "thunderstorm" =>,
      "eruption" =>,
      "murder" =>,
      "assassination" =>,
      "homicide" =>,
      "assault" =>,
      "rape" =>,
      "suicide" =>,
      "terrorist" =>,*/
      "robbery" => "police",
      "terrorist" => "police",
      "tsunami" => "weather department",
      "fight" => "abc",
    );

    $status = 2;

    //See which issues should be alerted
    $query = "SELECT keyword from client where status=?";
    $stmt = $db->prepare($query);
    $stmt->execute([$status]);
    $keywords = $stmt->fetchAll();

    //Check which department should be alerted
    foreach($keywords as $value){
      $val = $value['keyword'];
      echo $service[$val] . "was alerted about the issue.";
    }


  }
  else{
    echo "Issue reported was inaccurate.";
  }


?>