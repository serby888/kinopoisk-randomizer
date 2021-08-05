<?php

include('../classes/Kinopoisk_Database.php');
$database = new Kinopoisk_Database();

switch ($_POST['mode']) {
    case 'create':
        header('Content-type: application/json');
        echo json_encode( $database->create() );
        break;
    case 'update':
        $database->update();
        break;
}

