<!DOCTYPE html>
<html>

<head>
    <title>HTML Table Generated from CSV data</title>
    <style>
        body {
            text-align: center;
        }

        table {
            width: 80%;
            margin: 20px auto;
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

        select {
            padding: 5px;
            margin-right: 10px;
        }

        img {
            max-width: 100%;
            height: auto;
            max-height: 250px; /* Set a maximum height for images */
        }
    </style>
</head>

<body>

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
        // Check if the category is selected
        $selected = (isset($_GET['category']) && $_GET['category'] == $category) ? 'selected' : '';

        echo '<option value="' . htmlspecialchars($category) . '" ' . $selected . '>' . ucfirst($category) . '</option>';
    }
    echo '</select>';
    echo '<input type="submit" value="Filter">';
    echo '</form>';

    echo '<table>';
    $line = 0;

    while (($cols = fgetcsv($file, 0, '|')) !== false) {
        // Output table header for the first line
        if ($line === 0) {
            echo '<tr>';
            foreach ($cols as $col) {
                echo '<th>' . htmlspecialchars($col) . '</th>';
            }
            echo '</tr>';
            $line++;
            continue; // Skip the rest of the loop for the header line
        }

        // Check if category filter is applied
	if (isset($_GET['category']) && $_GET['category'] !== "") {
        if (isset($_GET['category']) && trim($cols[5]) != $_GET['category']) {
            continue; // Skip the row if category doesn't match
        }

    }
        // Output table row for data lines
	echo '<tr>';
	foreach ($cols as $index => $col) {
                if (strpos($col, 'upload.wikimedia.org') !== false) {
                    echo '<td><img src="' . htmlspecialchars($col) . '" alt="Image"></td>';
                } else if (strpos($col, 'http') !== false) {
                    // Use the source name as the anchor text
                    $sourceName = htmlspecialchars($cols[1]); // Assuming the source name is in the second column
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

    <a href="../../index.php">Back to home</a>
</body>

</html>
