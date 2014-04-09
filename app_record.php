<?php
	$secrete = $_POST['secrete'];
	$username = $_POST['username'];
	$enc_password = $_POST['enc_password'];
	

	if ($secrete == "LatteCafe"){

  
		require 'config.php';
		//Database Class
		require 'classes/database.php';

		$database = new Database;
	  
		//Query to check username and password
		$database->query("SELECT * FROM users WHERE username = :username AND password = :password");
		$database->bind(':username',$username);
		$database->bind(':password',$enc_password);
		$rows = $database->resultset();
		$count = count($rows);
		if($count > 0){
			// Username and password found so
			// Data may be stored
			$database = new Database;

			//Set Timezone
			date_default_timezone_set('Asia/Kathmandu');
			$location_lat = $_POST['location_lat'];
			$location_lng = $_POST['location_lng'];
			$accuracy = $_POST['accuracy'];
			$slide_date = $_POST['slide_date'];
			$slide_time = $_POST['slide_time'];
			$description = $_POST['description'];
			$user = $username;



			//Instantiate Database object
			$database = new Database;

			$database->query('INSERT INTO slides (location_lat,location_lng, accuracy, slide_date,slide_time,description,user) VALUES(:location_lat,:location_lng,:accuracy, :slide_date,:slide_time,:description,:user)');
			$database->bind(':location_lat',$location_lat);
			$database->bind(':location_lng',$location_lng);
			$database->bind(':accuracy',$accuracy);
			$database->bind(':slide_date',$slide_date);
			$database->bind(':slide_time',$slide_time);
			$database->bind(':description',$description);
			$database->bind(':user',$user);
			$database->execute();
			


			if($database->lastInsertId()){
				echo 'DataWasRecorded';
			}
		} else {
			echo 'youMayNotPass';
		}
	}
	
?>