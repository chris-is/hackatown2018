<?php 
  $user_lat = $request->getParam('latitude');
  $user_long = $request->getParam('longitude');
  $user_time = $request->getParam('timestamp');

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

    if($d<100){
      echo "within city";
    }
    else{
      echo "not within city";
    }

    

  }
  catch(Exception $e) {
    echo "Error";
  }
?>