<?php
// Database connection parameters
$dsn = 'mysql:host=localhost;dbname=Twin;charset=utf8';
$username = 'myUser';
$password = 'myPasswd';

try {
    // Connect to the database
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Execute SQL query for each set of data
    $POIquery = "SELECT CityID, Name, Type, OpeningHours, Description FROM Places_Of_Interest ORDER BY CityID DESC";
    $POIdata = $pdo->query($POIquery);

    $weatherquery = "SELECT ForecastTime, CityID, Temperature, Humidity, Windspeed, `Condition` FROM Weather ORDER BY ForecastTime DESC LIMIT 10";
    $weatherdata = $pdo->query($weatherquery);

    $newsquery = "SELECT CityName, Title, PubDate, Link FROM News ORDER BY PubDate DESC LIMIT 10";
    $newsdata = $pdo->query($newsquery);

    // Format results as RSS
    
    $rss_feed = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
    $rss_feed .= '<rss version="2.0">' . PHP_EOL;
    $rss_feed .= '<channel>' . PHP_EOL;
    $rss_feed .= '<title>My RSS Feed</title>';
    $rss_feed .= '<description>An RSS feed containing places of interest, weather data and news from liverpool and cologne.</description>';
    

    //display palces of interest data
    $rss_feed .= '<PLACES_OF_INTEREST>' . PHP_EOL;
    while ($row = $POIdata->fetch(PDO::FETCH_ASSOC)) {
        $rss_feed .= '<item>' . PHP_EOL;
        if($row['CityID'] == '1'){
            $rss_feed .= '<title>' . 'Liverpool' . '</title>' . PHP_EOL;
        } else {$rss_feed .= '<title>' . 'Cologne' . '</title>' . PHP_EOL;}
        $rss_feed .= '<name>' . htmlspecialchars($row['Name']) . '</name>' . PHP_EOL;
        $rss_feed .= '<type>' . htmlspecialchars($row['Type']) . '</type>' . PHP_EOL;
        $rss_feed .= '<openingHours>' . htmlspecialchars($row['OpeningHours']) . '</openingHours>' . PHP_EOL;
        $rss_feed .= '<description>' . htmlspecialchars($row['Description']) . '</description>' . PHP_EOL;
        $rss_feed .= '<link>' . '</link>' . PHP_EOL;
        $rss_feed .= '</item>' . PHP_EOL;
    }
    $rss_feed .= '</PLACES_OF_INTEREST>' . PHP_EOL;

    //display news data
    $rss_feed .= '<NEWS>' . PHP_EOL;
    while ($row = $newsdata->fetch(PDO::FETCH_ASSOC)) {
        $rss_feed .= '<item>' . PHP_EOL;
        $rss_feed .= '<title>' . htmlspecialchars($row['Title']) . '</title>' . PHP_EOL;
        $rss_feed .= '<cityName>' . htmlspecialchars($row['CityName']) . '</cityName>' . PHP_EOL;
        $rss_feed .= '<pubDate>' . htmlspecialchars($row['PubDate']) . '</pubDate>' . PHP_EOL;
        $rss_feed .= '<link>' . htmlspecialchars($row['Link']) . '</link>' . PHP_EOL;
        $rss_feed .= '</item>' . PHP_EOL;
    }
    $rss_feed .= '</NEWS>' . PHP_EOL;

    //display weather data 
    $rss_feed .= '<WEATHER>' . PHP_EOL;
    while ($row = $weatherdata->fetch(PDO::FETCH_ASSOC)) {
        $rss_feed .= '<item>' . PHP_EOL;
        if($row['CityID'] == '1'){
            $rss_feed .= '<Name>' . 'Liverpool' . '</Name>' . PHP_EOL;
        } else {$rss_feed .= '<Name>' . 'Cologne' . '</Name>' . PHP_EOL;}
        $rss_feed .= '<ForecastTime>' . htmlspecialchars($row['ForecastTime']) . '</ForecastTime>' . PHP_EOL;
        $rss_feed .= '<Temperature>' . htmlspecialchars($row['Temperature']) . 'C' . '</Temperature>' . PHP_EOL;
        $rss_feed .= '<Humidity>' . htmlspecialchars($row['Humidity']) . '%' . '</Humidity>' . PHP_EOL;
        $rss_feed .= '<Windspeed>' . htmlspecialchars($row['Windspeed']) . 'mph' . '</Windspeed>' . PHP_EOL;
        $rss_feed .= '<Condition>' . htmlspecialchars($row['Condition']) . '</Condition>' . PHP_EOL;
        $rss_feed .= '</item>' . PHP_EOL;
    }
    $rss_feed .= '</WEATHER>' . PHP_EOL;
    //close open tags
    $rss_feed .= '</channel>' . PHP_EOL;
    $rss_feed .= '</rss>' . PHP_EOL;

    // Output RSS feed
    header('Content-Type: application/rss+xml; charset=utf-8');
    echo $rss_feed;
} catch (PDOException $e) {
    // Handle database connection error
    echo "Connection failed: " . $e->getMessage();
}
?>

