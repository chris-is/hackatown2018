<?php 
  require 'database.php';
  $db = getDB();
  $user_lat = $request->getParam('latitude');
  $user_long = $request->getParam('longitude');
  $user_time = $request->getParam('timestamp');
  $message = $request->getParam('message');

  try{
    //Verify captcha
    /*
    $post_data = http_build_query(
      array(
        'secret' => "6LcSvDsUAAAAADrn86cZ6nfGAFWn_tgxUogddin9",
        'response' => $_POST['g-recaptcha-response'],
        'remoteip' => $_SERVER['REMOTE_ADDR']
      )
    );
    $opts = array('http' =>
      array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => $post_data
      )
    );
    $context  = stream_context_create($opts);
    $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
    $result = json_decode($response);
    if (!$result->success) {
        echo "captcha";
    }
    else{*/

    $mtl_lat = 45.5017;
    $mtl_long = -73.5673;

    $earth = 6371; //Earth radius
    $radius = 10; //radius of MTL island

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
    echo $date . ". ";


    if($d<$radius){
      //echo "within city";

      //Insert issue into DB
      $id = mt_rand();
      $geolocation = $user_lat . "," . $user_long;
      $query = "INSERT INTO `client` (`id`, `issue_id`, `message`, `geolocation`, `time`) VALUES (?, ?, ?, ?, ?)";
      $stmt = $db->prepare($query);
      $stmt->execute([$id, $id, $message, $geolocation, $date]);

      //Call python to check keywords
      //$command = 'python3 mainNLP.py';
      //$python = `$command`;
      //echo $python;
      //$accuracy = substr($python, 0, 2);
      echo "before python";

      //exec('mainNLP.py', $result);
      //print_r($result);
      echo "after python";
      $accuracy ="ok";
      $python = "avalnache";

      if($accuracy === "ok"){
        //Get issue keyword from DB
        $query = "SELECT keyword from client where id=?";
        $stmt = $db->prepare($query);
        $stmt->execute([$id]);
        $key = $stmt->fetch(PDO::FETCH_ASSOC);
        $user_keyword = $key['keyword'];
        $user_keyword = "fire";

        $keywords = array();

        $keywords[0] = "fire,tsunami,earthquake,flood,blizzard,snowstorm,hurricane,tornado,wildfire,avalanche,thunderstorm,eruption";
        $keywords[1] = "murder,assassination,homicide,assault,rape,suicide,terrorism,terrorist,stabbing";
        $keywords[2] = "kidnap,kidnapping,hostage,stabbing,";
        $keywords[3] = "theft,mugging,fight,robbery,harassment,threat,arms,armed,gun,accident";
        $keywords[4] = "mischief,disturbance,disturb,assault,";
        $keywords[5] = "traffic,";


        //Check keyword priority
        $i=0;
        $j=99;
        $priority = -1;
        while($i<sizeof($keywords)){
          $keyword_row = explode(',', $keywords[$i]);
          
          foreach($keyword_row as $value){
            if (strcmp($user_keyword, $value) == 0){
              $priority = $i + $j;
              $j=$j-2;
            }
          }
          $i++;
        }

        //Check if issue has been reported, if yes delete issue
        $query = "SELECT `id`, `geolocation`, `keyword` from client";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $all_loc = $stmt->fetchAll();
        
        foreach($all_loc as $value){
          $other_loc = explode(',', $value['geolocation']);
          $other_lat = $other_loc['0'];
          $other_long = $other_loc['1'];
          $other_id = $value['id'];
          $other_keyword = $value['keyword'];

          $distance_lat = deg2rad($other_lat - $user_lat);
          $distance_long = deg2rad($other_long - $user_long);

          $a = sin($distance_lat/2) * sin($distance_lat/2) + cos(deg2rad($other_lat)) * cos(deg2rad($user_lat)) * sin($distance_long/2) * sin($distance_long/2);
          $c = 2 * asin(sqrt($a));
          $d = $earth * $c;

          //echo $d;

          if($d<0.2){

            if(strcmp($user_keyword, $other_keyword) == 0){
              //echo $id . " " . $other_id . "\n";
              //$query = "UPDATE `client` SET `issue_id`=? WHERE `id`=?";
              //$stmt = $db->prepare($query);
              //$stmt->execute([$id, $other_id]);

              //Delete similar past issues
              $query = "DELETE FROM client where id=?";
              $stmt = $db->prepare($query);
              $stmt->execute([$other_id]);
            }
        
          }
        }

        //Redirect to php file for image checking

        //header("Location: http://localhost/mtlwatch/WebApp/backend/sendinfo.php");
        //exit();

      }
      else{
        $query = "DELETE FROM client where id=?";
        $stmt = $db->prepare($query);
        $stmt->execute([$id]);
        echo "Your report was detected to be inaccurate. Please try again.";
      }
    }


    else{
      echo "You are not reporting from within city bounds. Please try again.";
    }

    
  //}
  }
  catch(Exception $e) {
    echo "Error";
  }
?>