<!DOCTYPE html>
<html>
<head>
    <title>Weather Mashup consuming remote CSV + generating HTML using PHP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            text-align: center;
        }
        h1, h2 {
            color: #333;
        }
        form {
            margin: 20px 0;
        }
        select, input[type="submit"] {
            padding: 5px;
            margin-right: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        input[type="submit"] {
            background-color: #333;
            color: #fff;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #555;
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
    <h1>Weather CSV to HTML</h1>

    <form action="" method="post">
        <select id="site" name="site">
            <option value="" disabled selected>Select a weather station...</option>
            <option value="http://www.martynhicks.uk/weather/clientraw.txt">Horfield (Bristol)</option>
            <option value="http://www.thornburyweather.co.uk/weatherstation/clientraw.txt">Thornbury (Bristol)</option>
            <option value="https://www.glosweather.com/clientraw.txt">Gloucestershire</option>
            <option value="http://www.newquayweather.com/clientraw.txt">Newquay (Cornwall)</option>
            <option value="https://www.cotswoldgliding.co.uk/weather/clientraw.txt">Cotswold Gliding Club (Stroud)</option>
        </select>
        <input type="submit" value="View Data">
    </form>

    <?php
    // Define constants
    define('WINDSPEED', 1);
    define('WINDDIRECTION', 3);
    define('TEMPERATURE', 4);
    define('TIMEHH', 29);
    define('TIMEMM', 30);
    define('STATION', 32);
    define('SUMMARY', 49);

    // Function to convert degrees to compass point
    function degree_to_compass_point($d) {
        $dp = $d + 11.25;
        $dp = (int)$dp;
        $dp = $dp % 360;
        $dp = floor($dp / 22.5);
        $points = array("N", "NNE", "NE", "ENE", "E", "ESE", "SE", "SSE", "S", "SSW", "SW", "WSW", "W", "WNW", "NW", "NNW", "N");
        return $points[$dp];
    }

    // Check if form is submitted and process data
    if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['site'])) {
        $url = $_POST['site'];
        $csvData = file_get_contents($url);
        $data = explode(" ", $csvData); // Assuming space-delimited CSV

        // Display the data using the constants and function
        echo "<h2>Weather Data for " . htmlspecialchars($data[STATION]) . "</h2>";
        echo "<p>Time: " . htmlspecialchars($data[TIMEHH]) . ":" . htmlspecialchars($data[TIMEMM]) . "</p>";
        echo "<p>Temperature: " . htmlspecialchars($data[TEMPERATURE]) . " °C</p>";
        echo "<p>Wind Speed: " . htmlspecialchars($data[WINDSPEED]) . " km/h</p>";
        echo "<p>Wind Direction: " . degree_to_compass_point($data[WINDDIRECTION]) . "</p>";
        echo "<p>Summary: " . htmlspecialchars($data[SUMMARY]) . "</p>";
    }
    ?>

    <a href="../index.php">Back to home</a>
</body>
</html>
