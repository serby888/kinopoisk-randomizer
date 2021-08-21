<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>Randomizer КиноПоиск</title>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <link rel="stylesheet/less" type="text/css" href="less/styles.less"/>
    <script>
        less = {
            async: true,
        };
    </script>
    <script src="libs/less.min.js" type="text/javascript"></script>
    <link rel="shortcut icon" href="images/randomize.ico" type="image/x-icon">
    <script src="libs/particles.min.js"></script>
    <script src="libs/text-scramble.js"></script>
    <script src="js/main.js" type="text/javascript"></script>
    <script src="libs/select.js" type="text/javascript"></script>
</head>
<body>

<?php
    include('classes/Index.php');
    $block = new Index();
    $kinopoisk = @$block->getStatusDataBase();
    $info = $kinopoisk->getLastUpdateDate();
?>
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

    <div id="rowRand" class="wrapper-randomize-action">
        <div class="wrapper-title">
            <h1>rAndomizer <span>КиноПоиск</span></h1>
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
        <span class="additional-info">using <span>kinopoisk</span></span>
    </div>

    <div class="database-section">
        <div class="button-showing">DB info</div>

        <div class="status-connection <?= $kinopoisk->status['status'] ? 'success' : 'error' ?>">
            <object class="svg-wrapper" data="media/push-pin.svg" width="20" height="20"></object>
            <?= $kinopoisk->status['message'] ?>
        </div>
        <?php if($kinopoisk->status['status']): ?>
            <label class="container-checkbox">Use Database
                <input type="checkbox" id="use-db">
                <span class="checkmark"></span>
            </label>
            <div class="last-update">
                <span class="title">Last Update:</span>
                <span class="time"><?= $info['last-update'] ?></span>
                <span class="interval"><?= $info['interval'] ?></span>
            </div>
            <div class="qty-films">Quantity films: <?= $kinopoisk->getCountFilms() ?></div>

            <button class="button button-minimalistic" id="updateDatabase" type="button">Update DB</button>
        <?php else: ?>
            <div class="error-text"><?= $kinopoisk->status['error'] ?></div>
            <button class="button button-minimalistic" id="createDatabase" type="button">Create DB</button>
        <?php endif; ?>
    </div>

    <div id="content">
        <div id="first" class="block-content"></div>
    </div>

    <canvas class="background"></canvas>
</body>
</html>