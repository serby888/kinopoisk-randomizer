$(function() {
    Particles.init({
        selector: '.background',
        connectParticles: true,
        color: '#eeeeee',
        speed: 0.3
    });

    main();
    function main() {

        const el = document.querySelector('.additional-info span')
        const fx = new TextScramble(el)

        $("#use-db").on('change', function() {
            if(this.checked) {
                fx.setText('local database');
            } else {
                fx.setText('kinopoisk');
            }
        });

        $('#updateDatabase').off().on('click', function () {
            $.ajax({
                dataType: "json",
                url: 'controllers/ControllerDatabase.php',
                type: 'POST',
                data: {
                    'mode': 'update'
                }
            }).done(function( result ){
                console.log(result);
            });
        });

        $('#createDatabase').off().on('click', function () {
            $.ajax({
                dataType: "json",
                url: 'controllers/ControllerDatabase.php',
                type: 'POST',
                data: {
                    'mode': 'create'
                }
            }).done(function( result ){
                console.log(result);
            //    not showing in console
            });
        });

        $('#buttonRandom').off().on('click', function () {
            $('#rowRand').fadeOut();
            $('.close').fadeIn();
            $.ajax({
                dataType: "json",
                url: 'controllers/ControllerRandomizer.php',
                type: 'POST',
                data: {
                    'use-db': $('#use-db').is(':checked'),
                }
            }).done(function( result ){
                console.log(result);
                viewItemsFilm(result.data.films);
                setTimeout(function () {
                    startAnimation(result.stages);
                    openTorrent(result);
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
        $('#content').empty();
        $('#rowRand').fadeIn();
        $('.close').fadeOut();
    }

    function viewItemsFilm(films) {
        let container = document.getElementById("content");
        films.forEach((film) => {
            let elementFilm = createItemFilm(film);
            container.insertBefore(elementFilm, null);
        });
    }

    function createItemFilm( filmData ) {
        let filmItem = document.createElement("div");
        filmItem.classList.add("film-item");
        filmItem.id = 'film-item-' + filmData.id;
        filmItem.innerHTML = '<div class="film-item-wrapper"><img class="film-item-poster" src="' + filmData.imageLink + '"><div class="film-item-info"><div class="movie-title">' + filmData.name.rus + '</div><div class="movie-title">' + filmData.name.eng + '</div><div class="movie-genre">' + filmData.genre + '</div></div><div class="film-item-rating"><span class="rating-value ' + getClassStatusFilm(filmData.rating_kp.ratingValue) + '">' + filmData.rating_kp.ratingValue + '</span><span class="rating-count">' + filmData.rating_kp.ratingCount + '</span><span class="rating-title">КиноПоиск</span></div><div class="film-item-rating"><span class="rating-value ' + getClassStatusFilm(filmData.rating_IMDb.ratingValue) + '">' + filmData.rating_IMDb.ratingValue + '</span><span class="rating-count">' + filmData.rating_IMDb.ratingCount + '</span><span class="rating-title">IMDb</span></div></div>';
        return filmItem;
    }

    function startAnimation(stages) {
        let twoStep = stages['two'];

        let widthElement = $('.film-item').first().outerWidth() + 50;
        let offset = (window.outerWidth - ( widthElement * 3 )) / 2 + widthElement;

        toLeft($("#film-item-" + twoStep[0]), offset);
        toTop($("#film-item-" + twoStep[0]), 130);

        setTimeout(function () {
            toLeft($("#film-item-" + twoStep[1]), offset);
            toTop($("#film-item-" + twoStep[1]), 250);
        }, 1000);

        setTimeout(function () {
            toLeft($("#film-item-" + twoStep[2]), offset);
            toTop($("#film-item-" + twoStep[2]), 370);
        }, 2000);

        let threeStep = stages['three'];

        setTimeout(function () {
            toLeft($("#film-item-" + threeStep), offset * 2);
        }, 4000);
    }

    function toTop(element, startPoint) {
        let offset = startPoint - element.position()['top'];
        if ( offset ){
            element.animate({
                top: offset + 'px'
            });
        }
    }

    function toLeft(element, left) {
        element.animate({
            left: left + 'px'
        });
    }

    function openTorrent(result) {
        setTimeout(function () {
            window.open('https://rutracker.org/forum/tracker.php?nm=' + getTorrentName(result.data.films, result.stages.three)[0].name.for_torrent, '_blank');
        }, 6000);
    }

    function getTorrentName(data, id) {
        return data.filter(
            function(data){ return data.id == id }
        );
    }
    function getClassStatusFilm(rating) {

        var status = '';

        if (rating < 5) {
            status = 'rating-value--negative';
        }
        if (5 > rating < 7) {
            status = 'rating-value--neutral';
        }
        if (rating > 7) {
            status = 'rating-value--positive'
        }
        return status;
    }
});