<?php


class Kinopoisk_Database
{
    private $films, $conn, $db_name;

    public $status = [];


    public function __construct()
    {
        $configs = include_once($_SERVER['DOCUMENT_ROOT'] . "/randomizer-kinopoisk/config.php");
        $this->db_name = $configs['db_name'];

        $this->conn = new mysqli($configs['host'], $configs['username'], $configs['password'], $this->db_name);

        if ($this->conn->connect_error) {
            $this->status['status'] = false;
            $this->status['message'] = "Connection failed";
            $this->status['error'] = $this->conn->connect_error;
            $this->conn = new mysqli($configs['host'], $configs['username'], $configs['password']);
        } else {
            $this->status['status'] = true;
            $this->status['message'] = "Connected successfully";
        }
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
        $lastUpdate = new DateTime($this->conn->query("SELECT last_update FROM mysql.innodb_table_stats WHERE database_name = '".$this->db_name."' AND table_name = 'films'")->fetch_assoc()["last_update"]);
        $interval = $now->diff($lastUpdate);

        return [
            'last-update' => $lastUpdate->format('d-m-Y H:i:s'),
            'interval' => $this->_correctIntervalFormat($interval)
        ];
    }

    public function getCountFilms() {
        return $this->conn->query("SELECT COUNT(*) FROM films")->fetch_array()[0];
    }

    public function getGenresByFilmId($id) {
        $genres = $this->conn->query("SELECT genre FROM genres LEFT JOIN films ON id_film = id WHERE films.id =".$id)->fetch_all();

        $string = '';

        foreach ($genres as $key => $genre) {
            if ($key !== count($genres) - 1) {
                $string .= $genre[0] . ', ';
            } else {
                $string .= $genre[0];
            }
        }
        return '('.$string.')';
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


            $filmId = $this->conn->query("SELECT LAST_INSERT_ID()")->fetch_array()[0];

            $genres = explode(', ',str_replace('...', '', str_replace(')', '', str_replace('(', '', $film['genre']))));

            foreach ($genres as $genre) {
                $sql = 'INSERT 
                        INTO genres (id_film, genre)
                        VALUES (
                        "'.$filmId.'", 
                        "'.$genre.'")';

                if ($this->conn->query($sql) === TRUE) {
                    echo "New genre created successfully";
                } else {
                    echo "Error: " . $sql . "<br>" . $this->conn->error;
                }
            }
        }

        $this->conn->close();
    }

    public function create() {

        $status = [];

        if ($this->conn->query("CREATE DATABASE IF NOT EXISTS $this->db_name") === TRUE) {
            $status['database']['status'] = true;
            $status['database']['message'] = "Database $this->db_name created successfully";
        } else {
            $status['database']['status'] = false;
            $status['database']['message'] = "Error creating database: " . $this->conn->error;
        }

        $this->conn->select_db($this->db_name);

        $sql = "CREATE TABLE films (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            name_rus TEXT NOT NULL,
            name_eng VARCHAR(255) NOT NULL,
            release_year INT(11) NOT NULL,
            end_year INT(11) NULL,
            length_film INT(11) NOT NULL,
            rating_kp VARCHAR(255) NOT NULL,
            rating_kp_count INT(255) NULL,
            rating_imdb VARCHAR(255) NOT NULL,
            rating_imdb_count INT(255) NOT NULL,
            image_link TEXT NOT NULL,
            series BOOLEAN NOT NULL
            )";


        if ($this->conn->query($sql) === TRUE) {
            $status['tables']['films']['status'] = true;
            $status['tables']['films']['message'] = "Table films created successfully";
        } else {
            $status['tables']['films']['status'] = false;
            $status['tables']['films']['message'] = "Error creating table: " . $this->conn->error;
        }

        $sql = "CREATE TABLE genres (
            id_film INT(11),
            genre TEXT NOT NULL,
            INDEX id_film(id_film),
            CONSTRAINT FK_Genre FOREIGN KEY (id_film) 
            REFERENCES films(id) ON DELETE CASCADE
            )";

        if ($this->conn->query($sql) === TRUE) {
            $status['tables']['genres']['status'] = true;
            $status['tables']['genres']['message'] = "Table genres created successfully";
        } else {
            $status['tables']['genres']['status'] = false;
            $status['tables']['genres']['message'] = "Error creating table: " . $this->conn->error;
        }

        $this->conn->close();

        return $status;
    }
}