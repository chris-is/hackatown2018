<?php
use \Psr\Http\Message\ServerRequestInterface as Request;

require '../vendor/autoload.php';

$app = new \Slim\App;

$app->post('/posted', function ($request) {
	require 'database.php';
	$db = getDB();
	$message = $request->getParam('message');
	echo '<script language="javascript">';
	echo 'alert("message successfully sent")';
	echo '</script>';

	try {
		//Add user input to table's message field.
		$query = "INSERT INTO `client` (`message`,`geolocation`,`time`,`priority`,`status`) VALUES ($message,?,?,?,?)";
		$stmt = $db->prepare($query);
		$stmt->execute();
		echo '<script language="javascript">';
		echo 'alert("message successfully sent")';
		echo '</script>';
	}
	catch(Exception $e) {
		echo "Error while updating message";
	}
});

$app->run();
?>