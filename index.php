<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Randomizer КиноПоиск</title>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet/less" type="text/css" href="less/styles.less" />
    <script src="libs/less.min.js" type="text/javascript"></script>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <script src="js/main.js" type="text/javascript"></script>
</head>
<body>
<div class="container-fluid h-100">
    <a class="close" href="#"></a>
    <div id="rowRand" class="row h-100 justify-content-md-center align-items-center">
        <div  class="col-md-auto text-center">

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
    </div>
    <div class="row" id="content">
        <div class="col-xl-4">
            <div id="first" class="block-content"></div>
        </div>
    </div>
</div>
</body>
</html>
<!--<script src="libs/select.js" type="text/javascript"></script>-->