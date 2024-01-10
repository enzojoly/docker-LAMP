<!DOCTYPE html>
<html>
    <head>
        <title>ER models</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                color: #333;
                line-height: 1.6;
            }

            .container {
                width: 80%;
                margin: auto;
                overflow: hidden;
            }

            h2, h3 {
                text-align: center;
                margin-top: 20px;
            }

            img {
                height: auto;
                display: block;
                margin: 20px auto;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            }

            .conceptual-model {
                max-width: 100%;
                width: 400%;
            }

            .logical-model {
                max-width: 100%;
                width: 200%;
            }

            a {
                display: inline-block;
                text-align: center;
                margin: 20px auto;
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
            <h2>Conceptual -> Logical Entity Relationship</h2>

            <h3>Conceptual model</h3>
            <img src="c.png" alt="Conceptual Diagram" class="conceptual-model"> <br>

            <h3>Logical model</h3>
	    <img src="l.png" alt="Logical Diagram" class="logical-model"> <br>

            <h3>CSV Logical model</h3>
	    <img src="lcsvold.png" alt="Logical Diagram 2" class="logical-model"> <br>

            <h3>Refactored CSV Logical model</h3>
            <img src="lcsvnew.png" alt="Logical Diagram 3" class="logical-model"> <br>

            <a href="../index.php">Back to home</a>
        </div>

    </body>
</html>
