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
</head>
<body>
<div class="container-fluid h-100">
    <div id="rowRand" class="row h-100 justify-content-md-center align-items-center">
        <div  class="col-md-auto text-center">
            <h1>Randomizer <span>КиноПоиск</span> </h1>
            <button class="button-black" id="buttonRandom" type="button">Go</button>
        </div>
    </div>
    <div class="row text-center" id="content" style="display: none">
        <div class="col-md">
            <div id="first" class="block-content"></div>
        </div>
    </div>
</div>
</body>
</html>

<script>
    $('#buttonRandom').on('click', function () {
        $.ajax({
            url: 'controllers/ControllerRandomizer.php',
            type: 'POST',
            data: {}
        }).done(function( result ){

            console.log(result[0]);
            console.log(result[1]);
            console.log(result[2]);
            console.log(result[3]);
            console.log(result[4]);
            $('#rowRand').addClass('top');
            $('#content').show();

            setTimeout(function () {
                $.each(result[0], function( index, value ) {
                    $( "#first" ).append( "<span class='item' data-item=" + value + ">" + result[1][index] + "</span><br><br>" );
                });
                let indexfirst;
                $('div#first span.item').each(function (index, value) {
                    var thisvalue = $(value);

                    setTimeout(function () {
                        //thisvalue.addClass('show-item')
                        thisvalue.animate({
                            height: 'show',
                            width: '350px'
                        })
                    }, index*500);

                    indexfirst = index;
                });

                var top = 0;
                setTimeout(function () {
                    $('div#first span.item').animate({
                        opacity: '0.5'
                    });
                }, indexfirst*800);

                let indexsecond;

                $.each(result[2], function( index, value ) {
                    setTimeout(function () {

                        $('div#first span.item[data-item = ' + value + ']').animate({
                            opacity: '1',
                            left: '420px'
                        });
                        $('div#first span.item[data-item = ' + value + ']').animate({
                            top: top + 'px'
                        });
                        top = top + 48;
                    }, (index + indexfirst)*800);
                    indexsecond = index;
                });

                setTimeout(function () {
                    $('div#first span.item').animate({
                        opacity: '0.5'
                    });
                }, (indexfirst + indexsecond)*1000);

                setTimeout(function () {

                    $('div#first span.item[data-item = ' + result[3] + ']').animate({
                        opacity: '1',
                        left: '840px'
                    });
                    $('div#first span.item[data-item = ' + result[3] + ']').animate({
                        top: '0px'
                    });
                }, (indexfirst + indexsecond)*1000);

                setTimeout(function () {
                    window.open('https://rutracker.org/forum/tracker.php?nm=' + $('div#first span.item[data-item = ' + result[3] + ']').text().split(' : ')[1].slice(0, -7), '_blank');
                    window.open('http://new-rutor.org/search/' + $('div#first span.item[data-item = ' + result[3] + ']').text().split(' : ')[1].slice(0, -7), '_blank');
                }, (indexfirst + indexsecond)*1250);

            }, 1200);
        });
    });
</script>