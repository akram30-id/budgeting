import { loadListTreasury } from "./index.js";

const input = $("#input-search");
const btnSearch = $("#btn-search");

btnSearch.on("click touchstart", function (e) {

    e.preventDefault();

    const table = $("#tbody-treasury-cash");

    table.html(`
        <tr class="text-center">
            <td colspan="8">Loading . . .</td>
        </tr>
    `);

    const selectedLength = parseInt($("#select-length-treasury").val(), 10) || 15;
    loadListTreasury(1, selectedLength, input.val());

})
