<!DOCTYPE html>
<html>
<head>
    <title>LAMP-web</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        h1, h2 {
            color: #333;
	}
	.a {
            margin-left: 20px;
            background-color: white;
            text-align: left;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-radius: 5px;
            width: fit-content;
            margin: 20px auto;
        }
    </style>
</head>
<body>
	<div style="text-align: center;">
    <h1>PHP Workshops</h1>

    <?php
        $message = "Hello, World!";
        echo "<p>$message</p>";
    ?>
	</div>

	<div class="a">
    <h2>Project directory</h2>
    <a href="assignment/twinCities.php">TWIN CITIES ASSIGNMENT</a><br>
    <a href="ws1/test.php">Test</a><br>
    <a href="ws2/calc.php">Calculator</a><br>
    <a href="ws3/append_csv.php">Appending CSV file using HTML form</a><br>
    <a href="ws4/directory.php">HTML table from CSV input</a><br>
    <a href="ws5/Weather_CSV_to_HTML.php">Weather CSV to HTML</a><br>
    <a href="ws6/Twin_Cities.php">Weather API Example (Twin Cities)</a><br>
    <a href="ws7/7.php">Quilter ER Diagram</a><br>
    <a href="ws8/8.php">ER Diagrams cont.</a><br>
    <a href="ws9/9.php">Twin Cities ER Models</a><br>
    <a href="ws10/10.php">Data Normalization Help@Home</a><br>

	</div>
</body>
</html>
