<?php

class KinopoiskRandom
{
    private $count_iteration;
    private $film, $count_all;
    private $array_numbers = [];
    public $array_data = [];
    private $array_numbers_two = [];
    public  $response = [];

    function __construct()
    {

        include('phpQuery-onefile.php');
        $html = file_get_contents('https://www.kinopoisk.ru/user/5679443/movies/list/type/3575/sort/default/vector/desc/vt/all/perpage/200/');
//        $html = file_get_contents('../kp.html'); //использовать локальный, если по ссылке забанили
        $html = str_replace("&nbsp;", ' ', $html);
        phpQuery::newDocumentHTML($html); 
        $count = (int)substr(pq('div.pagesFromTo:eq(0)')->text(), -3);

        $this->count_all = $count;
        $this->count_iteration = rand(5, 8);
    }

    public function main(){
        $this->_iteration($this->count_iteration, 'one');
        //var_dump($this->array_numbers);
        $this->_deleteDuplicate($this->array_numbers);
        //var_dump($this->array_numbers);
        $this->_createCorrectlyArray($this->array_numbers, 'one');
        //var_dump($this->array_numbers);

        $this->count_iteration = 3;

        $this->_iteration($this->count_iteration, 'two');
        //var_dump($this->array_numbers_two);
        $this->_deleteDuplicate($this->array_numbers_two);
        //var_dump($this->array_numbers_two);
        $this->_createCorrectlyArray($this->array_numbers_two, 'two');
        //var_dump($this->array_numbers_two);


        $array_keys = array_keys($this->array_numbers_two);
        $this->film = $this->array_numbers_two[$array_keys[rand(0, count($array_keys) - 1 )]];

        $this->getData($this->array_numbers);

        $this->response = [
            'stages' => [
                'one' => $this->array_numbers,
                'two' => $this->array_numbers_two,
                'three' => $this->film
            ],
            'data' => [
                'films' => $this->array_data,
                'count_all' => $this->count_all
            ]
        ];

//        echo '<pre>';
//        var_dump($this->response);
//        echo '</pre>';
//
//        $fp = fopen('results.json', 'w');
//        fwrite($fp, json_encode($this->response));
//        fclose($fp);
    }

    public function getData($numbersFilms) {
        $dataFilms = [];
        foreach (pq('ul#itemList li') as $film) {
            if ($numbersFilms == "all" || in_array((int)pq($film)->find('div.num')->text(), $numbersFilms)) {
                $link = 'https://www.kinopoisk.ru' . pq($film)->find('.images .poster .flap_img')->attr('title');
                $name = $this->_converterCyrillic(pq($film)->find('.info .name')->text());
                $nameString = $this->_converterCyrillic(pq($film)->find('.info span:eq(0)')->text());
                $genre = $this->_converterCyrillic(pq($film)->find('.info span:eq(1)')->text());
                $rating = pq($film)->find('.rating')->text();
                $ratingIMDb = pq($film)->find('.imdb')->text();

                $infoFilm = [
                    'id' => (int)pq($film)->find('div.num')->text(),
                    'name' => [
                        'rus' => $name,
                        'eng' => $nameString,
                        'for_torrent' => strtok($nameString, ')').')'
                    ],
                    'rating_kp' => $this->getRatingData($rating, false),
                    'rating_IMDb' => $this->getRatingData($ratingIMDb, true),
                    'imageLink' => $link,
                    'genre' => $genre
                ];
                array_push($dataFilms, $infoFilm);
            }
        }
        $this->array_data = $dataFilms;
    }

    private function getRatingData($string, $IMDb) {
        $ratingCount = str_replace(")", '', explode(' (', $string)[1]);
        $ratingValue = explode(' (', $string)[0];
        if ($IMDb) {
            $ratingValue = str_replace("IMDb: ", '', $ratingValue);
        }
        $ratingValue = number_format(round($ratingValue, 2), 2);

        return [
            'ratingValue' => $ratingValue,
            'ratingCount' => str_replace(' ', '', $ratingCount),
        ];
    }

    private function _converterCyrillic ($string) {
        return iconv('UTF-8', 'ISO-8859-1', $string);
    }

    private function _iteration($count_iteration, $mode){
        switch ($mode) {
            case 'one':
                for ($i=0; $i < $count_iteration; $i++) { 
                    array_push($this->array_numbers, rand(1, $this->count_all));
                }
                break;
            case 'two':
                $array_keys = array_keys($this->array_numbers);
                for ($i=0; $i < $count_iteration; $i++) { 
                    $film = rand(0, count($array_keys) - 1 );
                    array_push($this->array_numbers_two, $this->array_numbers[$array_keys[$film]]);
                }
                break;
        }
    }    

    private function _deleteDuplicate(&$array){
        $array = array_unique($array);
    }

    private function _checkArray($array) {
        if (count($array) == $this->count_iteration) {
            return true;
        } else {
            return false;
        }
    }

    private function _createCorrectlyArray(&$array, $mode)
    {
        // проверка на наличие дубликатов, их удаление, расчет разницы, запись в массив недостающих элементов
        while(!$this->_checkArray($array)){
            $difference = $this->count_iteration - count($array);
            $this->_iteration($difference, $mode);
            $this->_deleteDuplicate($array);
        }
    }    
}
