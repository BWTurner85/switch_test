<!--
  This is an incredibly simple, plain, basic page just for the sake of giving a visual option as well.
-->
<html>
    <head>
        <title>Acme Inc movie recommendations</title>
        <style type="text/css">
            .movie {
                border:  1px solid #444444;
                width:   400px;
                padding: 9px;
                margin:  5px;
                background-color: #dddddd;
            }

            .movie-attr {
                display: block;
            }

            .label {
                display:     inline-block;
                font-weight: bold;
                width:       100px;
            }
        </style>
    </head>


    <body>
        <h1>Acme Inc movie recommendations</h1>

        <form method="GET">
            <label for="genre">Genre:</label>
            <input type="text" name="genre" id="genre" required value="<?php echo $_GET['genre'] ?>" />
            <label for="time">Time:</label>
            <input type="time" name="time" id="time" value="<?php echo $_GET['time'] ?>" />
            <input type="submit" value="Submit" />
        </form>

        <hr />

        <?php
            if ($_GET['genre']) {
                require_once '../app/bootstrap.php';

                $genre = urldecode($_GET['genre'] ?? 'test');
                $time  = isset($_GET['time']) ? $_GET['time'] : null;

                $movies    = (new \Acme\Client())->getRecommendations($genre, $time);
                $formatter = new \Acme\Formatter\HtmlFormatter();

                if (empty($movies)) {
                    echo "No movie recommendations\n";
                }

                foreach ($movies as $movie) {
                    echo $formatter->format($movie);
                }
            }
        ?>
    </body>
</html>

