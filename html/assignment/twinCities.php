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
	.forecast-container {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            flex-wrap: wrap;
            gap: 20px; /* This will create a gap between the two tables */
            align-items: flex-start; /* Align tables to the top */
        }

        .forecast {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 20px;
            background-color: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-radius: 5px;
            flex: 1; /* Each table will take up equal space */
            max-width: calc(50% - 20px); /* Subtract the gap from the 50% width */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed; /* This will prevent the table from expanding beyond its container */
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            word-wrap: break-word; /* Ensures text wraps and doesn't overflow */
	}
	th {
	    background-color: #f2f2f2;
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
        .day-label {
            background-color: #e7e7e7;
            padding: 5px;
            font-weight: bold;
        }
        a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        a:hover {
            background-color: #555;
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
	$mapCentersSql = "SELECT CityName, Latitude, Longitude FROM Cities WHERE CityName IN ('Liverpool', 'Cologne')";
    	$mapCentersResult = $conn->query($mapCentersSql);
    	$mapCenters = [];

	while($row = $mapCentersResult->fetch_assoc()) {
        	$mapCenters[$row['CityName']] = $row;
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

<!--
	 Debugging: Print the variables
	<div style="text-align: left; margin-top: 20px;">
		<h3>Debugging Information:</h3>
		<pre>Map Centers: <?php // echo htmlspecialchars(json_encode($mapCenters, JSON_PRETTY_PRINT)); ?></pre>
		<pre>Places: <?php // echo htmlspecialchars(json_encode($places, JSON_PRETTY_PRINT)); ?></pre>
	</div>
-->
<?php

// Function to fetch weather forecast data from the API
function getWeatherForecast($city, $appid) {
    $forecastUrl = "http://api.openweathermap.org/data/2.5/forecast?q={$city}&units=metric&appid={$appid}";
    $forecastJson = file_get_contents($forecastUrl);
    return json_decode($forecastJson, true); // Return as an associative array
}

// Function to update the Weather table with the latest weather forecast data
function updateWeatherData($conn, $city, $appid) {
    // Get the weather forecast data
    $forecastData = getWeatherForecast($city, $appid);

    // Check if the forecast data is available
    if (!$forecastData || !isset($forecastData['list'])) {
        return; // Forecast data not available, so return early
    }

    // Truncate the Weather table to remove old data
    $conn->query("DELETE FROM Weather WHERE CityID = (SELECT CityID FROM Cities WHERE CityName = '{$city}')");

    // Iterate over the forecasts and add them to the database
    foreach ($forecastData['list'] as $forecast) {
        $date = new DateTime($forecast['dt_txt']);
        $hour = $date->format('H');
	// Hourly forecast for todays date
	if ($date->format('Y-m-d') === date('Y-m-d') && $hour != '12' /* prevent duplicate*/) {
	    $stmt = $conn->prepare("INSERT INTO Weather (CityID, Temperature, Humidity, WindSpeed, `Condition`, Pressure, ForecastTime)
				   VALUES ((SELECT CityID FROM Cities WHERE CityName = ?), ?, ?, ?, ?, ?, ?)");
	    $stmt->bind_param("sdddsds",
		$city,
		$forecast['main']['temp'],
		$forecast['main']['humidity'],
		$forecast['wind']['speed'],
		$forecast['weather'][0]['main'],
		$forecast['main']['pressure'],
		$forecast['dt_txt']
	    );
	    $stmt->execute();
	    $stmt->close();
	}

        // Only insert data for midday and midnight forecasts for rest of the week
        if ($hour === '12' || $hour === '00') {
            $stmt = $conn->prepare("INSERT INTO Weather (CityID, Temperature, Humidity, WindSpeed, `Condition`, Pressure, ForecastTime)
                                     VALUES ((SELECT CityID FROM Cities WHERE CityName = ?), ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sdddsds",
                $city,
                $forecast['main']['temp'],
                $forecast['main']['humidity'],
                $forecast['wind']['speed'],
                $forecast['weather'][0]['main'],
                $forecast['main']['pressure'],
                $forecast['dt_txt']
            );
            $stmt->execute();
            $stmt->close();
        }
    }
}

// Function to display the weather forecasts
function displayWeatherForecasts($conn) {
    // Fetch all city names and their IDs
    $citiesResult = $conn->query("SELECT CityID, CityName FROM Cities ORDER BY CityID ASC");

    // Iterate over each city and display its weather forecast
    while ($city = $citiesResult->fetch_assoc()) {
        $weatherResult = $conn->query("SELECT Temperature, Humidity, WindSpeed, `Condition`, Pressure, ForecastTime
                                       FROM Weather WHERE CityID = {$city['CityID']}");

        echo "<h2>{$city['CityName']} Weather Forecast</h2>";
        echo "<table border='1'>";
        echo "<tr><th>Date/Time</th><th>Condition</th><th>Temperature</th><th>Wind</th><th>Humidity</th><th>Pressure</th></tr>";

        // Iterate over each forecast entry and display it
        while ($weatherRow = $weatherResult->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($weatherRow['ForecastTime']) . "</td>";
            echo "<td>" . htmlspecialchars($weatherRow['Condition']) . "</td>";
            echo "<td>" . htmlspecialchars($weatherRow['Temperature']) . "°C</td>";
            echo "<td>" . htmlspecialchars($weatherRow['WindSpeed']) . " m/s</td>";
            echo "<td>" . htmlspecialchars($weatherRow['Humidity']) . "%</td>";
            echo "<td>" . htmlspecialchars($weatherRow['Pressure']) . " hPa</td>";
            echo "</tr>";
        }
        echo "</table><br>"; // Close the table and add a break for spacing
    }
}

// Establish a new database connection
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update weather data for each city
updateWeatherData($conn, 'Liverpool', OPENWEATHERMAP_API_KEY);
updateWeatherData($conn, 'Cologne', OPENWEATHERMAP_API_KEY);

// Display the weather forecasts
displayWeatherForecasts($conn);

function fetchNewsForCity($city, $apiKey) {
    $yesterday = date('Y-m-d', strtotime('-1 day'));
    $twentyEightDaysAgo = date('Y-m-d', strtotime('-28 days'));
    $apiUrl = "https://newsapi.org/v2/everything?q=" . urlencode($city) . "&from={$twentyEightDaysAgo}&to={$yesterday}&apiKey={$apiKey}";

    // Initialize cURL session
    $curl = curl_init($apiUrl);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    // Set User-Agent header
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'User-Agent: Twin Cities Application' // requiring user agent - terms of API
    ]);

    // Execute cURL session
    $responseJson = curl_exec($curl);
    $err = curl_error($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    curl_close($curl);

    // Check if an error occurred
    if ($err) {
        echo "cURL Error #: " . $err;
        return [];
    } elseif ($httpcode != 200) {
        // Handle HTTP error (e.g., rate limits exceeded, bad request)
        echo "HTTP Error #: " . $httpcode;
        return [];
    } else {
        $response = json_decode($responseJson, true);
        return $response['articles'] ?? [];
    }
}

function updateNewsData($conn, $city, $newsItems) {
    // Clear previous news for the city
    $deleteStmt = $conn->prepare("DELETE FROM News WHERE CityName = ?");
    $deleteStmt->bind_param("s", $city);
    $deleteStmt->execute();
    $deleteStmt->close();

    foreach ($newsItems as $item) {
        // Convert date to MySQL datetime format
        $pubDate = new DateTime($item['publishedAt']);
        $formattedPubDate = $pubDate->format('Y-m-d H:i:s');

        $insertStmt = $conn->prepare("INSERT INTO News (CityName, Title, PubDate, Link) VALUES (?, ?, ?, ?)");
        $insertStmt->bind_param("ssss", $city, $item['title'], $formattedPubDate, $item['url']);
        $insertStmt->execute();
        $insertStmt->close();
    }
}

function displayNews($conn) {
    $cities = ['Liverpool', 'Cologne'];
    foreach ($cities as $city) {
        $newsResult = $conn->prepare("SELECT Title, PubDate, Link FROM News WHERE CityName = ? ORDER BY PubDate DESC");
        $newsResult->bind_param("s", $city);
        $newsResult->execute();
        $result = $newsResult->get_result();

        echo "<h2>{$city} News</h2>";
        echo "<table border='1'><tr><th>Title</th><th>Date</th><th>Link</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['Title']) . "</td>";
            echo "<td>" . htmlspecialchars($row['PubDate']) . "</td>";
            echo "<td><a href='" . htmlspecialchars($row['Link']) . "'>Read more</a></td>";
            echo "</tr>";
        }
        echo "</table><br>";
        $newsResult->close();
    }
}

// Fetch news for each city
$liverpoolNews = fetchNewsForCity('Liverpool', NEWSAPI_API_KEY);
$cologneNews = fetchNewsForCity('Cologne', NEWSAPI_API_KEY);

// Insert news data into the database
updateNewsData($conn, 'Liverpool', $liverpoolNews);
updateNewsData($conn, 'Cologne', $cologneNews);

// Display the news
displayNews($conn);


$conn->close();

?>


</body>
</html>
