<h1>Welcome to MapSlides <small>(Alpha)</small></h1>

<?php if($_SESSION['logged_in']):?>
	<?php
	//Instantiate Database object
	$database = new Database;

	//Get logged in user
	$list_user = $_SESSION['username'];

	//Query
	$database->query('SELECT * FROM lists WHERE list_user=:list_user');
	$database->bind(':list_user',$list_user);
	$rows = $database->resultset();

	echo '<h4>Here are your current lists</h4><br />';
	if($rows){
		echo '<ul class="items">';
		foreach($rows as $list){
			echo '<li><a href="?page=list&id='.$list['id'].'">'.$list['list_name'].'</a></li>';
		}
		echo '</ul>';
		
		
		
		
		
	} else {
		echo 'There are no lists available -<a href="index.php?page=new_list">Create One Now</a>';
	}	
	
	
	?>
<?php else: ?>
	<p>MapSlides is a small but helpful application where you can create and manage tasks and mark landslide events. Just register and login and you can start adding tasks and mark landslide events";</p>
<?php endif;?>

<?php

//INPUT coordinates
		$database->query('SELECT * FROM slides');
		$rows = $database->resultset();
		echo '<script>';
		echo 'var points = '.json_encode($rows).';';
		
		echo '</script>';
		
		
?>

<script type="text/javascript">

	var map;
	var markers = new Array();
	var infowindow = new google.maps.InfoWindow({
		content: "some text"
	});
	
	var min_lat=1000;
	var max_lat=-1000;
	var min_lng=1000;
	var max_lng=-1000;
	var number = points.length;
	var i=0;
	
	
	for (var i=0; i < points.length;i++)
	{
		if (parseFloat(points[i].location_lat) < min_lat) {min_lat=parseFloat(points[i].location_lat)};
		if (parseFloat(points[i].location_lat) > max_lat) {max_lat=parseFloat(points[i].location_lat)};
		if (parseFloat(points[i].location_lng) < min_lng) {min_lng=parseFloat(points[i].location_lng)};
		if (parseFloat(points[i].location_lng) > max_lng) {max_lng=parseFloat(points[i].location_lng)};
	};
	var center_lat = (min_lat+max_lat)/2.;
	var center_lng = (min_lng+max_lng)/2.;
	
	function listenMarker (marker,i){
		// so marker is associated with the closure created for the listenMarker function call
		google.maps.event.addListener(marker, 'click', function() {
			infowindow.setContent('<h5>Description:</h5>'+points[i].description+'<br/><h5>Marked by: </h5>'+points[i].user);
            infowindow.open(map,marker);
        });
	}
	
	
	function initialise(location){
		// Obtain current location
		//var currentLocation = new google.maps.LatLng(location.coords.latitude, location.coords.longitude);
		
		// Map options
		
		
		var centerLocation = new google.maps.LatLng(center_lat,center_lng);
		//var centerLocation = new google.maps.LatLng(27.7,85.3);
		
		var mapOptions = {
			center: centerLocation,
			zoom: 10
		};
		
		map = new google.maps.Map(document.getElementById("map-canvas2"), mapOptions);
		
		var new_boundary = new google.maps.LatLngBounds();
		
		for (i=0;i<points.length;i++){
			
			var pointLocation = new google.maps.LatLng(points[i].location_lat,points[i].location_lng);
			markers[i] = new google.maps.Marker({
				position: pointLocation,
				map: map,
				title:points[i].description,
				
			});
			new_boundary.extend(markers[i].position);

		};
		
		map.fitBounds(new_boundary);
		
		for (i=0;i<points.length;i++){
			
			listenMarker(markers[i],i);
		};
		
		
	};

	$(document).ready(function(){
		//navigator.geolocation.getCurrentPosition(initialise);
		
		google.maps.event.addDomListener(window, 'load', initialise);
		
		
	});
	
</script>


</br>
<h4>Locations of Landslides recorded in our database are shown below. Click on the marker to view description:</h4>
<style>
	#map-canvas2{width:700px;}
</style>
<div id="map-canvas2" class="map-canvas"></div>
<br/>
<p>Note: This website is still in its development stage. The data shown above are for demonstration purpuse only and should not be used for other purpose.</p>