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
        filmItem.innerHTML = '<div class="container"><div class="row"><div class="col-xl-2"><img class="film-item-poster" src="' + filmData.imageLink + '"></div><div class="col-xl-6"><div class="film-item-info"><div class="movie-title">' + filmData.name.rus + '</div><div class="movie-title">' + filmData.name.eng + '</div><div class="movie-genre">' + filmData.genre + '</div></div></div><div class="col-xl-4"><div>' + filmData.rating_kp + '</div><div>' + filmData.rating_IMDb + '</div></div></div></div>';
        return filmItem;
    }

    function startAnimation(stages) {
        let twoStep = stages['two'];

        toLeft($("#film-item-" + twoStep[0]), 630);
        toTop($("#film-item-" + twoStep[0]), 130);

        setTimeout(function () {
            toLeft($("#film-item-" + twoStep[1]), 630);
            toTop($("#film-item-" + twoStep[1]), 250);
        }, 1000);

        setTimeout(function () {
            toLeft($("#film-item-" + twoStep[2]), 630);
            toTop($("#film-item-" + twoStep[2]), 370);
        }, 2000);

        let threeStep = stages['three'];

        setTimeout(function () {
            toLeft($("#film-item-" + threeStep), 1260);
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
        }, 5000);
    }

    function getTorrentName(data, id) {
        return data.filter(
            function(data){ return data.id == id }
        );
    }
});