import { loadListDetailTreasury } from "./cash.js";

const btnPrevious = $("#btn-previous-cash");
const btnNext = $("#btn-next-cash");
let currentPage = 1;

btnPrevious.on("click touchstart", function (e) {
    e.preventDefault();

    currentPage = currentPage - 1;

    if (currentPage <= 1) {
        $("#btn-previous-cash").addClass("disabled");
    }

    if (currentPage > 1) {
        $("#btn-previous-cash").removeClass("disabled");
    }

    changePage(currentPage);

});

btnNext.on("click touchstart", function (e) {
    e.preventDefault();

    currentPage = currentPage + 1;

    if (currentPage > 1) {
        $("#btn-previous-cash").removeClass("disabled");
    }

    changePage(currentPage);
});

const changePage = (page) => {
    loadListDetailTreasury(page)
};
