<?php
/* ======================================================
Calculator refactored
      x, y : numbers
      op : operator
      calc : Calculate button pressed
*/
function calculate($x, $y, $op) {
    switch ($op) {
        case '+':
            $prod = $x + $y;
            break;
        case '-':
            $prod = $x - $y;
            break;
        case '*':
            $prod = $x * $y;
            break;
        case '/':
            if ($y == 0) {
                $prod = "undefined";
            } else {
                $prod = $x / $y;
            }
    }
    return $prod;
}

//declare variables
$x = 0;
$y = 0;
$prod = 0;
$op = '';
//grab form values
extract($_GET);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>PHP Calculator Example</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                color: #333;
                line-height: 1.6;
                text-align: center;
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

            input[type="text"], select {
                padding: 5px;
                margin: 10px 0;
                border-radius: 5px;
                border: 1px solid #ddd;
            }

            input[type="submit"] {
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
            <h3>PHP Calculator (Refactored)</h3>
            <p>Calculate x and y with an operator and output the result</p>

            <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                x = <input type="text" name="x" size="5" value="<?php echo $x; ?>"/>
                <select name="op">
                    <option value="+" <?php if ($op == '+') echo 'selected="selected"'; ?>>+</option>
                    <option value="-" <?php if ($op == '-') echo 'selected="selected"'; ?>>-</option>
                    <option value="*" <?php if ($op == '*') echo 'selected="selected"'; ?>>*</option>
                    <option value="/" <?php if ($op == '/') echo 'selected="selected"'; ?>>/</option>
                </select>
                y = <input type="text" name="y" size="5" value="<?php echo $y; ?>"/>
                <input type="submit" name="calc" value="Calculate"/>
            </form>

            <?php
            if (isset($calc)) {
                if (is_numeric($x) && is_numeric($y)) {
                    $prod = calculate($x, $y, $op);
                    echo "<p>$x $op $y = $prod</p>";
                } else {
                    echo "<p>x and y values are required to be numeric ... please re-enter values</p>";
                }
            }
            ?>

        </div>
		<a href="../index.php" style="margin-top: 20px;">Back to home</a>
    </body>
</html>
