import { loadListDetailTreasury } from "./cash.js";

$("#btn-search-cash").on("click", function () {

    const searchInput = $("#search-cash");

    loadListDetailTreasury(1, 15, searchInput.val());

})
