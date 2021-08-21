$(function() {
    var customSelectEle, i, j, selElmnt, divEle, divEleSelected, c;
    customSelectEle = document.querySelector(".select-wrapper");
    selElmnt = customSelectEle.getElementsByTagName("select")[0];
    divEle = document.createElement("div");
    divEle.setAttribute("class", "select select-minimalistic select-selected");
    divEle.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
    customSelectEle.appendChild(divEle);
    divEleSelected = document.createElement("div");
    divEleSelected.setAttribute("class", "select-items select-hide");

    Array.from(selElmnt).forEach((item, index) => {
        c = document.createElement("div");
        c.dataset.idGenre = item.value;
        c.innerHTML = selElmnt.options[index].innerHTML;

        c.addEventListener("click", function (e) {
            let itemGenre = document.createElement("div"),
                activeFiltersWrapper = document.querySelector(".active-filters"),
                allGenre = activeFiltersWrapper.children[0];

            if ( allGenre.dataset.idGenre == 0 ) {
                allGenre.remove();
            }

            itemGenre.dataset.idGenre = this.dataset.idGenre;
            itemGenre.setAttribute("class", "item-genre");
            itemGenre.innerText =  this.innerText;
            activeFiltersWrapper.appendChild(itemGenre);
            this.style.display = 'none';
            itemGenre.addEventListener("click", function (e) {
                var self = this;
                document.querySelector(".select-items").children.item(parseInt(self.dataset.idGenre) - 1).style.display = 'block';
                self.remove();
                if (!activeFiltersWrapper.children.length) {
                    itemGenre.dataset.idGenre = '0';
                    itemGenre.setAttribute("class", "item-genre");
                    itemGenre.innerText =  'Все жанры';
                    activeFiltersWrapper.appendChild(itemGenre);
                }
            });
        });
        divEleSelected.appendChild(c);
    });

    customSelectEle.appendChild(divEleSelected);
    divEle.addEventListener("click", function (e) {
        e.stopPropagation();
        closeSelect(this);
        let allGenre = document.querySelector(".select-items").children[0];
        if ( allGenre.dataset.idGenre == 0 ) {
            allGenre.remove();
        }
        this.nextSibling.classList.toggle("select-hide");
        this.classList.toggle("select-arrow-active");
    });

    function closeSelect(elmnt) {
        var customSelectEle,
            y,
            i,
            arrNo = [];
        customSelectEle = document.getElementsByClassName("select-items");
        y = document.getElementsByClassName("select-selected");
        for (i = 0; i < y.length; i++) {
            if (elmnt == y[i]) {
                arrNo.push(i);
            } else {
                y[i].classList.remove("select-arrow-active");
            }
        }
        for (i = 0; i < customSelectEle.length; i++) {
            if (arrNo.indexOf(i)) {
                customSelectEle[i].classList.add("select-hide");
            }
        }
    }

    document.addEventListener("click", closeSelect);
});