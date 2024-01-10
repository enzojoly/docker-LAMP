<?php
session_start();

$quote = isset($_POST['quote']) ? $_POST['quote'] : "";
$source = isset($_POST['source']) ? $_POST['source'] : "";
$dob = isset($_POST['dob']) ? $_POST['dob'] : "";
$dod = isset($_POST['dod']) ? $_POST['dod'] : "";
$wplink = isset($_POST['wplink']) ? $_POST['wplink'] : "";
$wpimg = isset($_POST['wpimg']) ? $_POST['wpimg'] : "";
$category = isset($_POST['category']) ? $_POST['category'] : "Please select a category";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errorMessage = "";

    if (empty($quote) || empty($source) || empty($dob) || empty($dod) || empty($wplink) || empty($wpimg) || ($category == "Please select a category")) {
        $errorMessage = "Please fill in all fields and select a category";
    } else {
        // Sanitize input
        $quote = str_replace('"', "'", $quote);
        $source = str_replace('"', "'", $source);
        $dob = str_replace('"', "'", $dob);
        $dod = str_replace('"', "'", $dod);
        $wplink = str_replace('"', "'", $wplink);
        $wpimg = str_replace('"', "'", $wpimg);

        $csv = fopen('quotes.csv', 'a');

        if ($csv === false) {
            $errorMessage = "Failed to open quotes.csv for writing.";
        } else {
            $line = $quote . ', ' . $source . ', ' . $dob . ', ' . $dod . ', ' . $wplink . ', ' . $wpimg . ', ' . $category . "\n";

            if (fwrite($csv, $line) === false) {
                $errorMessage = "Failed to write to quotes.csv.";
            } else {
                fclose($csv);
                $_SESSION['successMessage'] = "Quote added successfully.";
                header('Location: /ws3/append_csv.php');
                exit();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
    <title>Append CSV</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            line-height: 1.6;
            text-align: center;
        }

	h4 {
	    color: red;
	}
        .container {
            width: 50%;
            margin: auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-radius: 5px;
        }

        form {
            margin: 20px 0;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input, textarea, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        input[type="submit"] {
            width: auto;
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #555;
        }

        .error, .success {
            width: 50%;
            margin: 10px auto;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }

        .error {
            background-color: #ffdddd;
            border: 1px solid #ffcccc;
        }

        .success {
            background-color: #ddffdd;
            border: 1px solid #ccffcc;
        }

        a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }

        a:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h4>Enter a new quote:</h4>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <label for="quote">Quote:</label>
            <textarea id="quote" name="quote" placeholder="Enter a quote..." maxlength="1200" rows="3" required="required"><?php echo $quote; ?></textarea>
            <br>
            <label for="source">Source:</label>
            <input type="text" id="source" name="source" placeholder="Joe Bloggs" pattern=".{1,48}" required="required" value="<?php echo $source; ?>">
            <br>
            <label for="dob">Year of birth:</label>
            <input type="text" name="dob" id="dob" placeholder="253 BC" pattern="\d{1,16}" required="required" value="<?php echo $dob; ?>">
            <br>
            <label for="dod">Year of death (if applicable):</label>
            <input type="text" name="dod" id="dod" placeholder=""  pattern="\d{1,16}" value="<?php echo $dod; ?>">
            <br>
            <label for="wplink">Wikipedia link:</label>
            <input type="url" name="wplink" id="wplink" placeholder="https://en.wikipedia.org/wiki/" pattern="https:\/\/en\.wikipedia\.org\/wiki\/.+]+" required="required" value="<?php echo $wplink; ?>">
            <br>
            <label for="wpimg">Wikipedia image link:</label>
            <input type="url" name="wpimg" id="wpimg" placeholder="https://.wikimedia.org/wikimedia/commons/" pattern="https:\/\/upload.wikimedia.org\/wikipedia\/commons\/.+)"required="required" value="<?php echo $wpimg; ?>">
            <br>
            <label for="category">Category:</label>
            <select name="category" id="category">
                <option value="Please select a category">Please select a category</option>
                <option value="education" <?php if ($category == 'education') echo 'selected="selected"'; ?>>Education</option>
                <option value="history" <?php if ($category == 'history') echo 'selected="selected"'; ?>>History</option>
                <option value="humour" <?php if ($category == 'humour') echo 'selected="selected"'; ?>>Humour</option>
                <option value="love" <?php if ($category == 'love') echo 'selected="selected"'; ?>>Love</option>
                <option value="philosophy" <?php if ($category == 'philosophy') echo 'selected="selected"'; ?>>Philosophy</option>
                <option value="politics" <?php if ($category == 'politics') echo 'selected="selected"'; ?>>Politics</option>
                <option value="science" <?php if ($category == 'science') echo 'selected="selected"'; ?>>Science</option>
            </select>
            <br>
            <input type="submit" value="Submit">
        </form>
        <?php if (!empty($errorMessage)) { ?>
            <p class="error"><?php echo $errorMessage; ?></p>
        <?php } ?>
        <?php if (!empty($_SESSION['successMessage'])){ ?>
            <p class="success"><?php echo $_SESSION['successMessage']; ?></p>
            <?php unset($_SESSION['successMessage']); ?>
        <?php } ?>
    </div>
	<a href="../index.php" style="margin-top: 20px;">Back to home</a>
</body>
</html>
