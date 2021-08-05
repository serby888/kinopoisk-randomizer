<?php


class Kinopoisk_Database
{
    private $films, $conn, $db_name;

    public $status = [
        'status' => false,
        'message' => 'Connection failed'
    ];


    function __construct()
    {
        $configs = include_once($_SERVER['DOCUMENT_ROOT'] . "/randomizer-kinopoisk/config.php");
        $this->db_name = $configs['db_name'];

        $this->conn = new mysqli($configs['host'], $configs['username'], $configs['password'], $this->db_name);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        $this->status['status'] = true;
        $this->status['message'] = "Connected successfully";
    }

    private function _preUpdate() {
        include('../classes/kinopoisk.php');
        $kinopoisk = new KinopoiskRandom(false);
        $kinopoisk->getData('all');
        $this->films = $kinopoisk->array_data;
    }

    private function _recordExist($name) {
        $result = $this->conn->query("SELECT * FROM films WHERE name_eng = '".$name."'");
        if($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    private function _correctIntervalFormat ($dateInterval) {
        $string = '%d days %h hours %i minutes %s seconds ago';

        if (!$dateInterval->d) {
            $string = '%h hours %i minutes %s seconds ago';
            if (!$dateInterval->h) {
                $string = '%i minutes %s seconds ago';
                if (!$dateInterval->i) {
                    $string = '%s seconds ago';
                }
            }
        }

        return $dateInterval->format($string);
    }

    public function getLastUpdateDate() {
        $now = new DateTime('now');
        $lastUpdate = new DateTime($this->conn->query("SELECT last_update FROM mysql.innodb_table_stats WHERE table_name = 'films'")->fetch_assoc()["last_update"]);
        $interval = $now->diff($lastUpdate);

        return [
            'last-update' => $lastUpdate->format('d-m-Y H:i:s'),
            'interval' => $this->_correctIntervalFormat($interval)
        ];
    }

    public function getCountFilms() {
        return $this->conn->query("SELECT COUNT(*) FROM films")->fetch_array()[0];
    }

    public function getFilmById($id) {
        return $this->conn->query("SELECT * FROM films WHERE id=".$id)->fetch_array();
    }

    public function clearTable() {
        $this->conn->query("TRUNCATE TABLE films");
    }

    public function update() {

        $this->_preUpdate();

        $this->clearTable();

        foreach ($this->films as $film) {

            $years = explode(') ', explode(' (', $film['name']['eng'])[1])[0];
            $name_eng = explode(' (', $film['name']['eng'])[0];
            if (strpos($years, ' – ') !== false) {
                $releaseYear = explode(' – ', $years)[0];
                $endYear = (explode(' – ', $years)[1] === '...') ? '' : explode(' – ', $years)[1] ;
                $series = true;
            } else {
                $releaseYear = $years;
                $endYear = '';
                $series = false;
            }


/*
            if ($this->_recordExist($name_eng)) {
                $sql = "UPDATE films 
                        SET
                        name_rus = '".explode(' (', $film['name']['rus'])[0]."',
                        release_year = '".$releaseYear."',
                        end_year = '".$endYear."',
                        length_film = '".(int)explode(') ', $film['name']['eng'])[1]."',
                        rating_kp = '".$film['rating_kp']['ratingValue']."',
                        rating_kp_count = '".$film['rating_kp']['ratingCount']."',
                        rating_imdb = '".$film['rating_IMDb']['ratingValue']."',
                        rating_imdb_count = '".$film['rating_IMDb']['ratingCount']."',
                        image_link = '".$film['imageLink']."',
                        series = '".$series."'
                        WHERE name_eng = '".$name_eng."'";
            } else {*/
                $sql = 'INSERT 
                        INTO films (name_rus, name_eng, release_year, end_year, length_film, rating_kp, rating_kp_count, rating_imdb, rating_imdb_count, image_link, series)
                        VALUES (
                        "'.explode(' (', $film['name']['rus'])[0].'", 
                        "'.$name_eng.'", 
                        "'.$releaseYear.'", 
                        "'.$endYear.'", 
                        "'.(int)explode(') ', $film['name']['eng'])[1].'", 
                        "'.$film['rating_kp']['ratingValue'].'", 
                        "'.$film['rating_kp']['ratingCount'].'", 
                        "'.$film['rating_IMDb']['ratingValue'].'", 
                        "'.$film['rating_IMDb']['ratingCount'].'", 
                        "'.$film['imageLink'].'",
                        "'.$series.'")';
            /*}*/
            

            if ($this->conn->query($sql) === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $this->conn->error;
            }
        }

        $this->conn->close();
    }
}