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
    // Form is submitted
    $errorMessage = "";

    if (empty($quote) || empty($source) || empty($dob) || empty($dod) || empty($wplink) || empty($wpimg) || ($category == "Please select a category")) {
        $errorMessage = "Please fill in all fields and select a category";
    } else {
        $quote = str_replace('"', "'", $quote);
        $source = str_replace('"', "'", $source);
        $dob = str_replace('"', "'", $dob);
        $dod = str_replace('"', "'", $dod);
        $wplink = str_replace('"', "'", $wplink);
        $wpimg = str_replace('"', "'", $wpimg);

        $category = isset($_POST['category']) ? $_POST['category'] : "Please select a category";

        $csv = fopen('quotes.csv', 'a');

        if ($csv === false) {
            // Handle the case where fopen fails
            $errorMessage = "Failed to open quotes.csv for writing.";
        } else {
            $line = $quote . ', ' . $source . ', ' . $dob . ', ' . $dod . ', ' . $wplink . ', ' . $wpimg . ', ' . $category . "\n";

            // Check if fwrite was successful
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
    div {
        width: 50%;
        margin: 0 auto;
    }
    form {
        width: 100%;
    }
    label {
        display: block;
    }
    input, textarea, select {
        width: 100%;
    }
    input[type="submit"] {
        width: 20%;
        margin: 10px auto;
    }
    .error {
        color: red;
        margin-top: 10px;
    }
    .success {
        color: green;
        margin-top: 10px;
    }
</style>
</head>
<body>
    <h4 style="width: 55%; margin: 0 auto; color: red">Enter a new quote:</h4>
    <div>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="quote">Quote:</label>
        <textarea id="quote" name="quote" placeholder="Enter a quote..." maxlength="1200" rows="3" required="required"></textarea>
        <br>
        <label for="source">Source:</label>
        <input type="text" id="source" name="source" placeholder="Joe Bloggs" pattern=".{1,48}" required="required">
        <br>
        <label for="dob">Year of birth:</label>
        <input type="text" name="dob" id="dob" placeholder="253 BC" pattern="\d{1,16}" required="required">
        <br>
        <label for="dod">Year of death (if applicable):</label>
        <input type="text" name="dod" id="dod" placeholder=""  pattern="\d{1,16}">
        <br>
        <label for="wplink">Wikipedia link:</label>
        <input type="url" name="wplink" id="wplink" placeholder="https://en.wikipedia.org/wiki/" pattern="https:\/\/en\.wikipedia\.org\/wiki\/.+]+)" required="required">
        <br>
        <label for="wpimg">Wikipedia image link:</label>
        <input type="url" name="wpimg" id="wpimg" placeholder="https://.wikimedia.org/wikimedia/commons/" pattern="https:\/\/upload.wikimedia.org\/wikipedia\/commons\/.+)"required="required">
        <br>
        <label for="category">Category:</label>
        <select name="category" id="category">
            <option value="Please select a category">Please select a category</option>
            <option value="education">Education</option>
            <option value="history">History</option>
            <option value="humour">Humour</option>
            <option value="love">Love</option>
            <option value="philosophy">Philosophy</option>
            <option value="politics">Politics</option>
            <option value="science">Science</option>
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
	<h2></h2>
	<a href="../index.php">Back to home</a>
</body>
</html>
