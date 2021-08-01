<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>Randomizer КиноПоиск</title>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
            integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
            crossorigin="anonymous"></script>
    <link rel="stylesheet/less" type="text/css" href="less/styles.less"/>
    <script>
        less = {
            async: true,
        };
    </script>
    <script src="libs/less.min.js" type="text/javascript"></script>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <script src="libs/particles.min.js"></script>
    <script src="js/main.js" type="text/javascript"></script>
</head>
<body>
<div class="container-fluid h-100">
    <a class="close" href="#"></a>

    <div class="wrapper-filters">
        <div class="active-filters">
            <div data-id-genre="0" class="item-genre">Все жанры</div>
        </div>
        <div class="filters-genre">
            <div class="select-wrapper">
                <select>
                    <option value="0">Список жанров</option>
                    <option value="1">Аниме</option>
                    <option value="2">Биографии</option>
                    <option value="3">Боевики</option>
                    <option value="4">Вестерны</option>
                    <option value="5">Детективы</option>
                    <option value="6">Детские</option>
                    <option value="7">Документальные</option>
                    <option value="8">Драмы</option>
                    <option value="9">Игры</option>
                    <option value="10">Исторические</option>
                    <option value="11">Комедии</option>
                    <option value="12">Концерты</option>
                    <option value="13">Короткометражки</option>
                    <option value="14">Криминал</option>
                    <option value="15">Мелодрамы</option>
                    <option value="16">Музыкальные</option>
                    <option value="17">Мультфильмы</option>
                    <option value="18">Мюзиклы</option>
                    <option value="19">Новости</option>
                    <option value="20">Приключения</option>
                    <option value="21">Реальное ТВ</option>
                    <option value="22">Семейные</option>
                    <option value="23">Спортивные</option>
                    <option value="24">Ток-шоу</option>
                    <option value="25">Триллеры</option>
                    <option value="26">Ужасы</option>
                    <option value="27">Фантастика</option>
                    <option value="28">Фильмы-нуар</option>
                    <option value="29">Фэнтези</option>
                    <option value="30">Церемонии</option>
                </select>
            </div>
        </div>
        <div class="filters-type">
            <div class="select-wrapper">
                <select>
                    <option value="0">Все типы</option>
                    <option value="1">Фильмы</option>
                    <option value="2">Сериалы</option>
                </select>
            </div>
        </div>
    </div>

    <div id="rowRand" class="row h-100 justify-content-md-center align-items-center">
        <div class="col-md-auto text-center">

            <div class="wrapper-title">
                <h1>Randomizer <span>КиноПоиск</span></h1>
                <div class="scene">
                    <div class="cube">
                        <div class="cube__face cube__face--front">
                            <span class="dot"></span>
                        </div>
                        <div class="cube__face cube__face--back">
                            <div>
                                <span class="dot"></span>
                                <span class="dot"></span>
                                <span class="dot"></span>
                            </div>
                            <div>
                                <span class="dot"></span>
                                <span class="dot"></span>
                                <span class="dot"></span>
                            </div>
                            <div>
                                <span class="dot"></span>
                                <span class="dot"></span>
                                <span class="dot"></span>
                            </div>
                        </div>
                        <div class="cube__face cube__face--right"><span>КиноПоиск</span></div>
                        <div class="cube__face cube__face--left">IMDb</div>
                        <div class="cube__face cube__face--top">
                            <div>
                                <span class="dot"></span>
                                <span class="dot"></span>
                            </div>
                            <div>
                                <span class="dot"></span>
                            </div>
                            <div>
                                <span class="dot"></span>
                                <span class="dot"></span>
                            </div>
                        </div>
                        <div class="cube__face cube__face--bottom">
                            <span class="dot"></span>
                            <span class="dot"></span>
                        </div>
                    </div>
                </div>
            </div>

            <button class="button button-minimalistic" id="buttonRandom" type="button">Go</button>
        </div>

        <div class="database-section">
            <?php
            include('classes/Kinopoisk_Database.php');
            $kinopoisk = new Kinopoisk_Database();
            ?>
            <span class="status-connection <?= $kinopoisk->status['status'] ? 'success' : 'error' ?>"><?= $kinopoisk->status['message'] ?></span>

            <label class="container">use DB
                <input type="checkbox">
                <span class="checkmark"></span>
            </label>

            <button class="button button-minimalistic" id="updateDatabase" type="button">Update DB</button>
            <p>last Update <?= $kinopoisk->getLastUpdateDate() ?></p>
            <p>qty films <?= $kinopoisk->getCountFilms() ?></p>
        </div>

        <div class="row" id="content">
            <div class="col-xl-4">
                <div id="first" class="block-content"></div>
            </div>
        </div>
    </div>

    <canvas class="background"></canvas>
</body>
</html>
<!--<script src="libs/select.js" type="text/javascript"></script>-->