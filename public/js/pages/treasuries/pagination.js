import { loadListTreasury } from "./index.js";

const btnPrevious = $("#btn-previous-treasury");
const btnNext = $("#btn-next-treasury");
let currentPage = 1;

btnPrevious.on("click touchstart", function (e) {
    e.preventDefault();

    currentPage = currentPage - 1;

    if (currentPage <= 1) {
        $("#btn-previous-treasury").addClass("disabled");
    }

    if (currentPage > 1) {
        $("#btn-previous-treasury").removeClass("disabled");
    }

    changePage(currentPage);

});

btnNext.on("click touchstart", function (e) {
    e.preventDefault();

    currentPage = currentPage + 1;

    if (currentPage > 1) {
        $("#btn-previous-treasury").removeClass("disabled");
    }

    changePage(currentPage);
});

const changePage = (page) => {
    loadListTreasury(page);
};
