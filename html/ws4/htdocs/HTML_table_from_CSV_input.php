<!DOCTYPE html>
<html>
<head>
    <title>HTML Table Generated from CSV data</title>
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
        <h2>HTML Table Generated from CSV data</h2>

        <?php
        $file = fopen("quotes.csv", "r");

        if (!$file) {
            die("Unable to open file");
        }

        echo '<form method="get" action="" style="text-align: center">';
        echo '<label for="category">Filter by Category:</label>';
        echo '<select name="category" id="category">';
        echo '<option value="">All</option>';
        $categories = ["culture", "education", "humour", "love", "politics", "philosophy", "science"];

        foreach ($categories as $category) {
            $selected = (isset($_GET['category']) && $_GET['category'] == $category) ? 'selected' : '';
            echo '<option value="' . htmlspecialchars($category) . '" ' . $selected . '>' . ucfirst($category) . '</option>';
        }
        echo '</select>';
        echo '<input type="submit" value="Filter">';
        echo '</form>';

        echo '<table>';
        $line = 0;

        while (($cols = fgetcsv($file, 0, '|')) !== false) {
            if ($line === 0) {
                echo '<tr>';
                foreach ($cols as $col) {
                    echo '<th>' . htmlspecialchars($col) . '</th>';
                }
                echo '</tr>';
                $line++;
                continue;
            }

            if (isset($_GET['category']) && $_GET['category'] !== "" && trim($cols[5]) != $_GET['category']) {
                continue;
            }

            echo '<tr>';
            foreach ($cols as $index => $col) {
                if (strpos($col, 'upload.wikimedia.org') !== false) {
                    echo '<td><img src="' . htmlspecialchars($col) . '" alt="Image"></td>';
                } else if (strpos($col, 'http') !== false) {
                    $sourceName = htmlspecialchars($cols[1]);
                    echo '<td><a href="' . htmlspecialchars($col) . '" target="_blank">' . $sourceName . '</a></td>';
                } else {
                    echo '<td>' . htmlspecialchars($col) . '</td>';
                }
            }
            echo '</tr>';
        }
        echo '</table>';
        fclose($file);
        ?>

    </div>

    <a href="../../index.php">Back to home</a>
</body>
</html>
