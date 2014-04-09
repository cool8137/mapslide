<?php

	require 'config.php';
	//Database Class
	require 'classes/database.php';

	$database = new Database;


	//Set Timezone
	date_default_timezone_set('Asia/Kathmandu');
	$location_lat = $_POST['location_lat'];
	$location_lng = $_POST['location_lng'];
        $accuracy = $_POST['accuracy'];
	$slide_date = $_POST['slide_date'];
	$slide_time = $_POST['slide_time'];
	$description = $_POST['description'];
        $user = $_POST['user'];



	//Instantiate Database object
	$database = new Database;

	$database->query('INSERT INTO slides (location_lat,location_lng,slide_date,slide_time,description,user) VALUES(:location_lat,:location_lng,:slide_date,:slide_time,:description,:user)');
	$database->bind(':location_lat',$location_lat);
	$database->bind(':location_lng',$location_lng);
	$database->bind(':slide_date',$slide_date);
	$database->bind(':slide_time',$slide_time);
	$database->bind(':description',$description);
	$database->bind(':user',$user);
	$database->execute();
	


	if($database->lastInsertId()){
		echo '<p class="msg">Landslide has been recorded</p>';
	}

?>