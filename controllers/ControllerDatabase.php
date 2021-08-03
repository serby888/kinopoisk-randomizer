<?php

include('../classes/Kinopoisk_Database.php');
$database = new Kinopoisk_Database();
$database->update();

header('Content-type: application/json');
echo json_encode( $database );