<!DOCTYPE html>
<html>
<head>
    <title>Weather Forecast</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            text-align: center;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .forecast-container {
            display: flex;
            justify-content: space-around;
            padding: 20px;
            flex-wrap: wrap;
        }
        .forecast {
            border: 1px solid #ddd;
            padding: 10px;
            width: 45%;
            margin-bottom: 20px;
            background-color: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
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
    <h1>A weather mashup of Twin Cities using the OpenWeatherMap API</h1>
    <?php
        $apikey = "6aa908729d58145b17fd0f0b51caceb7"; // Your API key

        function getWeatherForecast($city, $apiKey) {
            $forecastUrl = "http://api.openweathermap.org/data/2.5/forecast?q={$city}&units=metric&appid={$apiKey}";
            $forecastJson = file_get_contents($forecastUrl);
            $forecastData = json_decode($forecastJson);

            $forecasts = [];
            foreach ($forecastData->list as $forecast) {
                $date = new DateTime($forecast->dt_txt);
                $hour = $date->format('H');

                if ($hour == '12' || $hour == '00') {
                    $dayOfWeek = $date->format('l');
                    $forecasts[$dayOfWeek][] = [
                        'date' => $date->format('Y-m-d H:i'),
                        'temp' => round($forecast->main->temp, 1) . '°C',
                        'condition' => $forecast->weather[0]->main,
                        'wind' => $forecast->wind->speed . ' m/s',
                        'humidity' => $forecast->main->humidity . '%',
                        'pressure' => $forecast->main->pressure . ' hPa'
                    ];
                }
            }

            return $forecasts;
        }

        $liverpoolWeather = getWeatherForecast('Liverpool', $apikey);
        $cologneWeather = getWeatherForecast('Cologne', $apikey);
    ?>

    <div class="forecast-container">
        <div class="forecast">
            <h2>Liverpool Weather Forecast</h2>
            <?php foreach ($liverpoolWeather as $day => $forecasts): ?>
                <div class="day-label"><?= $day ?></div>
                <table>
                    <tr>
                        <th>Date/Time</th>
                        <th>Condition</th>
                        <th>Temperature</th>
                        <th>Wind</th>
                        <th>Humidity</th>
                        <th>Pressure</th>
                    </tr>
                    <?php foreach ($forecasts as $forecast): ?>
                        <tr>
                            <td><?= $forecast['date'] ?></td>
                            <td><?= $forecast['condition'] ?></td>
                            <td><?= $forecast['temp'] ?></td>
                            <td><?= $forecast['wind'] ?></td>
                            <td><?= $forecast['humidity'] ?></td>
                            <td><?= $forecast['pressure'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endforeach; ?>
        </div>

        <div class="forecast">
            <h2>Cologne Weather Forecast</h2>
            <?php foreach ($cologneWeather as $day => $forecasts): ?>
                <div class="day-label"><?= $day ?></div>
                <table>
                    <tr>
                        <th>Date/Time</th>
                        <th>Condition</th>
                        <th>Temperature</th>
                        <th>Wind</th>
                        <th>Humidity</th>
                        <th>Pressure</th>
                    </tr>
                    <?php foreach ($forecasts as $forecast): ?>
                        <tr>
                            <td><?= $forecast['date'] ?></td>
                            <td><?= $forecast['condition'] ?></td>
                            <td><?= $forecast['temp'] ?></td>
                            <td><?= $forecast['wind'] ?></td>
                            <td><?= $forecast['humidity'] ?></td>
                            <td><?= $forecast['pressure'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endforeach; ?>
        </div>
    </div>

    <a href="../index.php">Back to home</a>
</body>
</html>
