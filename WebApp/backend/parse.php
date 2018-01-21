<?php 
  require 'database.php';
  $db = getDB();
  $user_lat = $request->getParam('latitude');
  $user_long = $request->getParam('longitude');
  $user_time = $request->getParam('timestamp');
  $message = $request->getParam('message');

  try{
    $mtl_lat = 45.5017;
    $mtl_long = -73.5673;

    $earth = 6371; //Earth radius
    $radius = 100; //radius of MTL island

    // Harversine Formula
    $distance_lat = deg2rad($mtl_lat - $user_lat);
    $distance_long = deg2rad($mtl_long - $user_long);

    $a = sin($distance_lat/2) * sin($distance_lat/2) + cos(deg2rad($mtl_lat)) * cos(deg2rad($user_lat)) * sin($distance_long/2) * sin($distance_long/2);
    $c = 2 * asin(sqrt($a));
    $d = $earth * $c;

    // Call Google TimeZone API and retrieve file contents
    $string = 'https://maps.googleapis.com/maps/api/timezone/json?location=' . $user_lat . ',' . $user_long . '&timestamp=1458000000&key=AIzaSyAcT_M6NuZAfszwSSV5mvmw9zD9ut7FwYo';
    //echo $string;

    
    $output = file_get_contents($string);

    $json_a = json_decode($output, true);
    foreach($json_a as $key => $value) {
      if($key == "timeZoneId")
      {
        $city=$value;
      }
    }

    /*$date = new DateTime(null, new DateTimeZone($city));
    echo $timestamp = $date->format('U');*/
    date_default_timezone_set($city);

    $date = date('h:i:s a', time());
    

    //echo file_get_contents($string);
    if($d<$radius){
      //echo "within city";
      $query = "INSERT INTO `client` (`message`,`time`) VALUES (?,?)";
      $stmt = $db->prepare($query);
      $stmt->execute([$message,$date]);
      echo '<script language="javascript">';
      echo 'alert("message successfully sent")';
      echo '</script>';
    }


    else{
      echo "not within city";
    }

    

  }
  catch(Exception $e) {
    echo "Error";
  }
?>