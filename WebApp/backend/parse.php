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
<<<<<<< HEAD
    

    //echo file_get_contents($string);
    if($d<$radius){
      //echo "within city";
      $query = "INSERT INTO `client` (`message`,`time`) VALUES (?,?)";
      $stmt = $db->prepare($query);
      $stmt->execute([$message,$date]);
      echo '<script language="javascript">';
      echo 'alert("message successfully sent")';
      echo '</script>';
=======

    if($d<$radius){
      echo "within city";
      $id = mt_rand();
      $geolocation = $user_lat . "," . $user_long;
      $query = "INSERT INTO `client` (`id`, `issue_id`, `message`, `geolocation`, `time`) VALUES (?, ?, ?, ?, ?)";
      $stmt = $db->prepare($query);
      $stmt->execute([$id, $id, $message, $geolocation, $date]);
      //echo '<script language="javascript">';
      //echo 'alert("message successfully sent")';
      //echo '</script>';

      //Call python to check if keywords are legit
      $command = 'python python.py ';
      $python = `$command`;
      $accuracy = substr($python, 0, 2);

      if($accuracy === "ok"){
        echo "thank you for the report! \n";

        //Add keyword to priority
        $user_keyword = "theft";

        $keywords = array();

        $keywords[0] = "fire,tsunami,earthquake,flood,explosion,bomb";
        $keywords[1] = "murder,assassination";
        $keywords[2] = "theft,mugging,fight";
        $keywords[3] = "traffic";

        $i=0;
        $priority = -1;
        while($i<sizeof($keywords)){
          $keyword_row = explode(',', $keywords[$i]);
          
          foreach($keyword_row as $value){
            if (strcmp($user_keyword, $value) == 0){
              $priority = $i;
            }
          }
          $i++;
        }

        echo "priority after keyword: " . $priority . "\n";

        //Add location to priority
        $query = "SELECT `id`, `geolocation` from client";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $all_loc = $stmt->fetchAll();
        
        foreach($all_loc as $value){
          $other_loc = explode(',', $value['geolocation']);
          $other_id = $value['id'];
          $other_lat = $other_loc['0'];
          $other_long = $other_loc['1'];

          $distance_lat = deg2rad($other_lat - $user_lat);
          $distance_long = deg2rad($other_long - $user_long);

          $a = sin($distance_lat/2) * sin($distance_lat/2) + cos(deg2rad($other_lat)) * cos(deg2rad($user_lat)) * sin($distance_long/2) * sin($distance_long/2);
          $c = 2 * asin(sqrt($a));
          $d = $earth * $c;

          //echo $d;

          if($d<0.1){
            echo $id . " " . $other_id . "\n";
            $priority = $priority - 0.1;
            $query = "UPDATE `client` SET `issue_id`=? WHERE `id`=?";
            $stmt = $db->prepare($query);
            $stmt->execute([$id, $other_id]);
          }

          
        }

        echo "priority after geo: " . $priority . "\n";


      }
      else{
        echo "inaccurate report";
      }
>>>>>>> master
    }


    else{
      echo "not within city";
    }

    

  }
  catch(Exception $e) {
    echo "Error";
  }
?>