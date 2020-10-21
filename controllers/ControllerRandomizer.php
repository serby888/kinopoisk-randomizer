<?php

include('../classes/kinopoisk.php');

$array = [];
$kinopoisk = new KinopoiskRandom();
$kinopoisk->main();

array_push($array, (array)$kinopoisk->array_numbers);
array_push($array, (array)$kinopoisk->array_name);
array_push($array, (array)$kinopoisk->array_numbers_two);
array_push($array, (array)$kinopoisk->film);
array_push($array, (array)$kinopoisk->count_all);

header('Content-type: application/json');
echo json_encode( $array );