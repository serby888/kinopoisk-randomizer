<?php

class KinopoiskRandom
{
    private $count_iteration;
    public $film, $count_all;
    public $array_numbers = [];
    public $array_name = [];
    public $array_numbers_two = [];


    function __construct()
    {

        include('phpQuery-onefile.php');
        $html = file_get_contents('https://www.kinopoisk.ru/user/5679443/movies/list/type/3575/sort/default/vector/desc/vt/all/perpage/200/');
        //$html = file_get_contents('kp.html'); //использовать локальный, если по ссылке забанили
        phpQuery::newDocumentHTML($html); 
        $count = (int)substr(pq('div.pagesFromTo:eq(0)')->text(), -3);

        $this->count_all = $count;
        $this->count_iteration = rand(5, 10);
    }

    public function main(){

        $this->_iteration($this->count_iteration, 'one');
        //var_dump($this->array_numbers);
        $this->_delete_dublicate($this->array_numbers);
        //var_dump($this->array_numbers);
        $this->_create_correctly_array($this->array_numbers, 'one');
        //var_dump($this->array_numbers);

        $this->count_iteration = 3;

        $this->_iteration($this->count_iteration, 'two');
        //var_dump($this->array_numbers_two);
        $this->_delete_dublicate($this->array_numbers_two);
        //var_dump($this->array_numbers_two);
        $this->_create_correctly_array($this->array_numbers_two, 'two');
        //var_dump($this->array_numbers_two);


        $array_keys = array_keys($this->array_numbers_two);
        $this->film = $this->array_numbers_two[$array_keys[rand(0, count($array_keys) - 1 )]];

        foreach ($this->array_numbers as $key => $value) {
            $this->array_name[$key] = $value . ' : ' . $this->_get_name($value);
        }
    }

    private function _get_name ($number) {
        foreach (pq('div.num') as $value) {
            if ( $value->nodeValue == $number) {
                $name = pq($value)->parent('li')->find('span:eq(0)')->text();
                $name = strtok($name, ')').')';
                return $name;
            }
        }
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

    private function _delete_dublicate(&$array){
        $array = array_unique($array);
    }

    private function _check_array($array) {
        if (count($array) == $this->count_iteration) {
            return true;
        } else {
            return false;
        }
    }

    private function _create_correctly_array(&$array, $mode)
    {
        // проверка на наличие дубликатов, их удаление, расчет разницы, запись в массив недостоющих элементов
        while(!$this->_check_array($array)){                                          
            $difference = $this->count_iteration - count($array);
            $this->_iteration($difference, $mode);
            $this->_delete_dublicate($array); 
        }
    }    
}
