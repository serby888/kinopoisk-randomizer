<?php

class Index
{
    public function __construct()
    {
        include('Kinopoisk_Database.php');
    }

    public function getStatusDataBase() {
        return new Kinopoisk_Database();
    }
}