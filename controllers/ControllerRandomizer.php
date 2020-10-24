<?php

include('../classes/kinopoisk.php');
$kinopoisk = new KinopoiskRandom();
$kinopoisk->main();

header('Content-type: application/json');
echo json_encode( $kinopoisk->response );