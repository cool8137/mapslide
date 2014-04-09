<?php
	if($_POST['slide_submit']){
		
		$location_lat = $_POST['location_lat'];
		$location_lng = $_POST['location_lng'];
		$slide_date = $_POST['slide_date'];
		$slide_time = $_POST['slide_time'];
		$description = $_POST['description'];
		$user = $_SESSION['username'];

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

	}

?>

<?php
//Instantiate Database object
$database = new Database;

//Get logged in user
$user = $_SESSION['username'];

//Query
$database->query('SELECT * FROM slides WHERE user = :user');
$database->bind(':user',$user);
$rows = $database->resultset();
?>




<script type="text/javascript">
	var map;
	var marker;
	
	function initialise(currentLocation){
				
		// Map options
		var mapOptions = {
			center: currentLocation,
			zoom: 13
		};
		
		map = new google.maps.Map(document.getElementById("map-canvas1"), mapOptions);
		
		marker = new google.maps.Marker({
			position: currentLocation,
			map: map
		});
		
		$('#Lat').val(marker.position.d.toString());
		$('#Lng').val(marker.position.e.toString());
					
		// this ensures we wait until the map bound are initialized before we perform the search
		//google.maps.event.addListenerOnce(map,'bounds_changed',performSearch);
		
		// add listener
		google.maps.event.addListener(map, 'click', function(event) {
			// update marker position
			marker.setPosition(event.latLng);
			
			//update input fields
			$('#Lat').val(marker.position.d.toString());
			$('#Lng').val(marker.position.e.toString());
			
		});
		
	};

	$(document).ready(function(){
		navigator.geolocation.getCurrentPosition(
		function (location){
			var currentLocation = new google.maps.LatLng(location.coords.latitude, location.coords.longitude);
			initialise(currentLocation);
		},
		function(){
			var currentLocation = new google.maps.LatLng(27.7,85.35);
			initialise(currentLocation);
		});
		
		$('#btn_update').click(function(){
			marker.setPosition(new google.maps.LatLng($('#Lat').val(),$('#Lng').val()));
			map.panTo(marker.getPosition());
		});
	});
	
</script>

<div><h3>Mark the location of landslide on the map and fill other information about the landslide below:</h3></div>
<div class="container">
	<div class="row">
		
		
		<div class="col-md-4">
			
			<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
				<div class="form-group">
					<label>Coordinates:</label>
					<input class="form-control" type="text" id="Lat" name="location_lat"/>
					<input class="form-control" type="text" id="Lng" name="location_lng"/>
					
				</div>
				
				<button type="button" id="btn_update">Update on Map</button>
				
				<br/>
				
				<br/>
				<div class="form-group">
					<label>Date: </label>
					<input type="date" name="slide_date"/><br/>
				</div>
				<div class="form-group">
					<label>Time: </label>
					<input type="time" name="slide_time"/><br/>
				</div>
				<div class="form-group">
					<label>Description: </label>
					<textarea rows="5" cols="50"  name="description"></textarea><br/>
				</div>
				
				<input type="submit" value="Record" name="slide_submit" />
			</form>
		</div>
		<div class="col-md-8">
			<div class = "map-canvas" id="map-canvas1"></div>
		</div>
	</div>
</div>




	