<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: text/html");

$mysqli = new mysqli(
    'db',
    'root',
    getenv('MYSQL_ROOT_PASSWORD'),
                     getenv('MYSQL_DATABASE')
);

if ($mysqli->connect_error) {
    die("Connection failed (AAAAAAAAAAAAAAAAAAAAAAH) : " . $mysqli->connect_error);
}

echo "Connection à la base de donnée OK";

echo "<a href='infinite_scroll.php'>Voir la page de test infinite_scroll</a>";
