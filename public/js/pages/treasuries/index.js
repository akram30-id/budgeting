import mainTable from "./mainTable.js";

$(document).ready(function () {

    loadListTreasury();

    $("#select-length-treasury").on("change", function (e) {
        e.preventDefault();

        const selectedLength = parseInt($(this).val(), 10) || 15;
        const keywords = $("#input-search").val() || "";

        loadListTreasury(1, selectedLength, keywords);
    });

    $("#btn-previous-treasury").on("click touchstart", function (e) {
        e.preventDefault();

        const page = parseInt($("#btn-previous-treasury").data("page")) || initialPage;
        const length = parseInt($("#select-length-treasury").val()) || 15;
        const keywords = $("#input-search").val() || "";

        loadListTreasury(page, length, keywords);
    });

    $("#btn-next-treasury").on("click touchstart", function (e) {
        e.preventDefault();

        const page = parseInt($("#btn-next-treasury").data("page")) || initialPage;
        const length = parseInt($("#select-length-treasury").val()) || 15;
        const keywords = $("#input-search").val() || "";

        loadListTreasury(page, length, keywords);
    });
});

export const loadListTreasury = (page = 1, length = 15, keywords = "") => {

    const url = $("#url-api").data("api_list_treasure");

    const token = $("#token").data("access_token");

    if (page < 1) {
        page = 1;
    }

    const table = $("#tbody-treasury-cash");
    table.html(`
        <tr class="text-center">
            <td colspan="8">Loading . . .</td>
        </tr>
    `);

    $.ajax({
        type: "GET",
        url: `${url}?page=${page}&length=${length}&keywords=${keywords}`,
        data: {},
        headers: {
            'Authorization': `Bearer ${token}`
        },
        dataType: "json",
        success: function (response) {
            if (response.success) {
                mainTable.loadTable(response.data);

                const data = response.data;

                if (data.length === 0) {
                    mainTable.loadTableFailed("No data found.");
                } else {
                    $("#input-current-treasury").append(`<option value="">Choose Here</option>`)
                    data.forEach(element => {
                        $("#input-current-treasury").append(`
                            <option value="${element.treasury_no}">${element.treasury_no} - ${new Date(element.month).toLocaleString('default', { month: 'long' })} ${element.year}</option>
                        `)
                    });

                    $("#input-current-treasury").select2({
                        dropdownParent: $("#treasuryModal") // modal tempat select berada
                    });
                }
            } else {
                mainTable.loadTableFailed(response.message);
            }
        },
        error: function (xhr, status, error) {
            const responseError = JSON.parse(xhr.responseText);

            if (error === "Unauthorized") {
                window.location.href = "/login";
            } else {
                mainTable.loadTableFailed(responseError.message);
            }
        }
    });

}
