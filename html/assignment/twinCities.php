<!DOCTYPE html>
<html>
<head>
    <title>Twin Cities</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            text-align: center;
	}

        .map-container {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
	}

        .map {
            width: 47.5vw;
            height: 40vw;
            background-color: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-radius: 5px;
	}

        .explore-button {
            margin-top: 10px;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px; cursor: pointer;
	}

        .explore-button:hover {
            background-color: #0056b3;
	}

        .info-bubble {
            background-color: white;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            font-size: 14px;
	}

        .info-bubble h4 {
            margin: 0;
            font-size: 16px;
            font-weight: normal;
	}

        .info-bubble strong {
            font-weight: bold;
	}

        .info-bubble p {
            margin: 5px 0;
	}

    </style>
</head>
<body>
    <h1>Twin Cities - Liverpool and Cologne</h1>
    <div class="map-container">
        <div>
            <h2>Liverpool</h2>
            <div id="mapLiverpool" class="map"></div>
            <button class="explore-button" onclick="zoomOutLiverpool()">Explore Liverpool</button>
	</div>

        <div>
            <h2>Köln</h2>
            <div id="mapCologne" class="map"></div>
            <button class="explore-button" onclick="zoomOutCologne()">Explore Köln</button>
        </div>
    </div>

<?php
	require_once 'config.php';
	$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

    	// Fetch map centers
	$mapCentersSql = "SELECT Name, Latitude, Longitude FROM Cities WHERE Name IN ('Liverpool', 'Cologne')";
    	$mapCentersResult = $conn->query($mapCentersSql);
    	$mapCenters = [];

	while($row = $mapCentersResult->fetch_assoc()) {
        	$mapCenters[$row['Name']] = $row;
    	}

	$placesSql = "SELECT p.PlaceID, p.CityID, p.Name, p.Type, p.OpeningHours, p.Description, p.Latitude, p.Longitude,
        	      GROUP_CONCAT(c.CategoryName ORDER BY c.CategoryID DESC SEPARATOR ', ') AS Categories,
        	      (SELECT IconURL FROM Category WHERE CategoryID = (SELECT MAX(CategoryID) FROM Place_Category WHERE PlaceID = p.PlaceID)) AS IconURL
        	      FROM Places_of_Interest p
        	      INNER JOIN Place_Category pc ON p.PlaceID = pc.PlaceID
        	      INNER JOIN Category c ON pc.CategoryID = c.CategoryID
        	      GROUP BY p.PlaceID";

	$placesResult = $conn->query($placesSql);
    	$places = [];

	while($row = $placesResult->fetch_assoc()) {
        	$places[] = $row;
    	}

    	$conn->close();
  ?>


  <script>
        var mapLiverpool, mapCologne;
        var mapCenters = <?php echo json_encode($mapCenters); ?>;
        var places = <?php echo json_encode($places); ?>;

	function initMap() {
		mapLiverpool = new google.maps.Map(document.getElementById('mapLiverpool'), {
        	zoom: 18,
        	center: {lat: parseFloat(mapCenters['Liverpool'].Latitude), lng: parseFloat(mapCenters['Liverpool'].Longitude)},
        	mapTypeId: 'satellite',
        	minZoom: 12
    	});

		mapCologne = new google.maps.Map(document.getElementById('mapCologne'), {
        	zoom: 18,
        	center: {lat: parseFloat(mapCenters['Cologne'].Latitude), lng: parseFloat(mapCenters['Cologne'].Longitude)},
        	mapTypeId: 'satellite',
        	minZoom: 12
	});

	places.forEach(function(place) {
        var mapToUse = place.CityID == 1 ? mapLiverpool : mapCologne;
        var marker = new google.maps.Marker({
            position: {lat: parseFloat(place.Latitude), lng: parseFloat(place.Longitude)},
            map: mapToUse,
            title: place.Name,
            icon: place.IconURL
        });

        var infoBubbleContent = '<div class="info-bubble">' +
                                '<h4>' + place.Categories + '</h4>' +
                                '<strong>' + place.Name + '</strong><br>' +
                                place.OpeningHours + '<br>' +
                                '<p>' + place.Description + '</p>' +
                                '<p>' + place.Latitude + ', ' + place.Longitude + '</p>' +
                                '</div>';

        var infoBubble = new google.maps.InfoWindow({
		content: infoBubbleContent
        });

        marker.addListener('mouseover', function() {
        	infoBubble.open(mapToUse, marker);
        });

        marker.addListener('mouseout', function() {
        	infoBubble.close();
        });
    });
}

	function zoomOutLiverpool() {
	    var liverpoolCenter = mapCenters['Liverpool'];
	    mapLiverpool.setZoom(12);
	    mapLiverpool.setCenter({lat: parseFloat(liverpoolCenter.Latitude), lng: parseFloat(liverpoolCenter.Longitude)});
	}

	function zoomOutCologne() {
	    var cologneCenter = mapCenters['Cologne'];
	    mapCologne.setZoom(12);
	    mapCologne.setCenter({lat: parseFloat(cologneCenter.Latitude), lng: parseFloat(cologneCenter.Longitude)});
	}

  </script>

	<script async defer
		src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAPS_API_KEY; ?>&callback=initMap">
	</script>

	<!-- Debugging: Print the variables -->
	<div style="text-align: left; margin-top: 20px;">
		<h3>Debugging Information:</h3>
		<pre>Map Centers: <?php echo htmlspecialchars(json_encode($mapCenters, JSON_PRETTY_PRINT)); ?></pre>
		<pre>Places: <?php echo htmlspecialchars(json_encode($places, JSON_PRETTY_PRINT)); ?></pre>
	</div>
</body>
</html>
