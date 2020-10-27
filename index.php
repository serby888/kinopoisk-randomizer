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

<script>
    $(function() {
        main();
        function main() {
            $('#buttonRandom').off().on('click', function () {
                $('#rowRand').fadeOut();
                $('.close').fadeIn();
                $.ajax({
                    dataType: "json",
                    url: 'controllers/ControllerRandomizer.php',
                    type: 'POST',
                    data: {}
                }).done(function( result ){
                    console.log(result);
                    viewItemsFilm(result.data.films);
                    setTimeout(function () {
                        startAnimation(result.stages);
                    }, 5000);
                });
            });
            $('.close').off().on('click', function () {
                closeResult();
            });
            $('body').on("keyup", function(e){
                if(e.key === "Escape") {
                    closeResult();
                }
            });
        }

        function closeResult() {
            $('#first').empty();
            $('#rowRand').fadeIn();
            $('.close').fadeOut();
        }

        function viewItemsFilm(films) {
            let container = document.getElementById("first");
            films.forEach((film) => {
                let elementFilm = createItemFilm(film);
                container.insertBefore(elementFilm, null);
            });
        }

        function createItemFilm( filmData ) {
            let filmItem = document.createElement("div");
            filmItem.classList.add("film-item");
            filmItem.id = 'film-item-' + filmData.id;
            filmItem.innerHTML = '<div class="container"><div class="row"><div class="col-xl-2"><img class="film-item-poster" src="' + filmData.imageLink + '"></div><div class="col-xl-6"><div class="film-item-info"><div>' + filmData.name.rus + '</div><div>' + filmData.name.eng + '</div><div>' + filmData.genre + '</div></div></div><div class="col-xl-4"><div>' + filmData.rating_kp + '</div><div>' + filmData.rating_IMDb + '</div></div></div></div>';
            return filmItem;
        }
        
        function startAnimation(stages) {
            let twoStep = stages['two'];

            aboveCenter(twoStep[0], 630, 0, 330);

            setTimeout(function () {
                aboveCenter(twoStep[1], 630, 110, 330);
            }, 1000);

            setTimeout(function () {
                aboveCenter(twoStep[2], 630, 110, 330);
            }, 2000);

            let threeStep = stages['three'];


            setTimeout(function () {
                aboveCenter(threeStep, 1260, 0, 660);
            }, 4000);
        }
        
        function aboveCenter(id, left, offset, sumHeightItems) {
            let startPoint = (screen.height - sumHeightItems) / 2;
            let itemPoint = document.getElementById("film-item-" + id).getBoundingClientRect().top;
            let element = $("#film-item-" + id);
            element.animate({
                left: left + 'px'
            });
            if ( startPoint > itemPoint ){
                element.animate({
                    top: startPoint - itemPoint + offset + 'px'
                });
            } else {
                element.animate({
                    bottom: itemPoint - startPoint + offset + 'px'
                });
            }
            console.log('id: ' + id);
            console.log('startPoint: ' + startPoint);
            console.log('itemPoint: ' + itemPoint);
        }
    });
    /*$('#buttonRandom').on('click', function () {
        $.ajax({
            dataType: "json",
            url: 'controllers/ControllerRandomizer.php',
            type: 'POST',
            data: {}
        }).done(function( result ){

            console.log(result);
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
    });*/
</script>