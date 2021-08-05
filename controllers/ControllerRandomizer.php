<?php

include('../classes/kinopoisk.php');
$kinopoisk = new KinopoiskRandom($_POST['use-db']);
$kinopoisk->main();

header('Content-type: application/json');
echo json_encode( $kinopoisk->response );