<!DOCTYPE html>
<html>
    <head>
        <title>PHP 9</title>
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

            h1, h2 {
                text-align: center;
                margin-top: 20px;
            }

            img {
                height: auto;
                display: block;
                margin: 20px auto;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            }

            .conceptual-model, .logical-model {
                max-width: 100%;
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
            <h1>Twin Cities Entity Relationship Diagrams</h1>

            <h2>Conceptual model</h2>
            <img src="condiagram.png" alt="concept" class="conceptual-model"> <br>

            <h2>Logical model</h2>
            <img src="logdiagram.png" alt="logical" class="logical-model">

            <a href="../index.php">Back to home</a>
        </div>

   </body>
</html>
