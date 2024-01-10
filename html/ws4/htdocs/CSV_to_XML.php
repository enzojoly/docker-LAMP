<!DOCTYPE html>
<html>
<head>
    <title>HTML Table Generated from XML data</title>
    <style>
        body {
            text-align: center;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        select, input[type="submit"] {
            padding: 5px;
            margin-right: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
	}
	form {
            margin-bottom: 10px;
        }
        img {
            max-width: 100%;
            height: auto;
            max-height: 250px;
	}
	table a {
    	    padding: 0;
    	    background-color: transparent;
    	    color: #0066cc;
    	    text-decoration: underline;
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

    <div class="container">
        <a href="../directory.php">Back to WS4dir</a>
        <h2>HTML Table from Generated XML File</h2>

        <?php
        // Open the CSV file
        $csvFile = fopen("quotes.csv", "r");
        if (!$csvFile) {
            die("Unable to open file");
        }

        // Create a new XML document
        $xml = new SimpleXMLElement('<quotes/>');

        $firstRow = true;

        // Read each line of the CSV file
        while (($row = fgetcsv($csvFile, 0, '|')) !== FALSE) {
            // Skip the header row
            if ($firstRow) {
                $firstRow = false;
                continue;
            }
            // Create a new 'quote' element for each row
            $quoteElement = $xml->addChild('quote');
            $quoteElement->addChild('quote', htmlspecialchars($row[0]));
            $quoteElement->addChild('source', htmlspecialchars($row[1]));
            $quoteElement->addChild('dob-dod', htmlspecialchars($row[2]));
            $quoteElement->addChild('wplink', htmlspecialchars($row[3]));
            $quoteElement->addChild('wpimg', htmlspecialchars($row[4]));
            $quoteElement->addChild('category', htmlspecialchars($row[5]));
        }

        // Close the CSV file
        fclose($csvFile);

        // Save the XML to a file
        $xml->asXML('../quotes.xml');
        ?>

        <!-- Category Selection Form -->
        <form method="get" action="">
            <label for="category">Filter by Category:</label>
            <select name="category" id="category">
                <option value="">All</option>
                <?php
                $categories = ["culture", "education", "humour", "love", "politics", "philosophy", "science"];
                foreach ($categories as $category) {
                    $selected = (isset($_GET['category']) && $_GET['category'] == $category) ? 'selected' : '';
                    echo '<option value="' . htmlspecialchars($category) . '" ' . $selected . '>' . ucfirst($category) . '</option>';
                }
                ?>
            </select>
            <input type="submit" value="Filter">
        </form>

        <?php
        $xml = simplexml_load_file('../quotes.xml');
        echo '<table>';
        echo "<tr><th>quote</th><th>source</th><th>dob-dod</th><th>wplink</th><th>wpimg</th><th>category</th></tr>";
        foreach ($xml->quote as $quoteElement) {
            $quoteText = $quoteElement->quote;
            $source = $quoteElement->source;
            $dob_dod = $quoteElement->{'dob-dod'};
            $wplink = $quoteElement->wplink;
            $wpimg = $quoteElement->wpimg;
            $category = $quoteElement->category;

            if (isset($_GET['category']) && $_GET['category'] != "" && $_GET['category'] != $category) {
                continue;
            }
            echo "<tr><td>$quoteText</td><td>$source</td><td>$dob_dod</td><td><a href='$wplink' target='_blank'>$source</a></td><td><img src='$wpimg' alt='Image'></td><td>$category</td></tr>";
        }
        echo '</table>';
        ?>

    </div>

    <a href="../../index.php">Back to home</a>
</body>
</html>
