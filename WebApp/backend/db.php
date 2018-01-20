<?php
use \Psr\Http\Message\ServerRequestInterface as Request;

require '../../vendor/autoload.php';

$app = new \Slim\App;


<<<<<<< HEAD:WebApp/db.php

$app->post('/parse', function ($request) {
	require 'parse.php';
=======
	try {
		//Add user input to table's message field.
		$query = "INSERT INTO `client` (`message`) VALUES (?)";
		$stmt = $db->prepare($query);
		$stmt->execute([$message]);
		echo '<script language="javascript">';
		echo 'alert("message successfully sent")';
		echo '</script>';
	}
	catch(Exception $e) {
		echo "Error while updating message";
	}
>>>>>>> master:WebApp/backend/db.php
});

$app->run();
?>