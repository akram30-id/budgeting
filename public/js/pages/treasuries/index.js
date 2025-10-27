import mainTable from "./mainTable.js";

$(document).ready(function () {

    loadListTreasury();

});

const loadListTreasury = (page = 1, length = 15) => {

    const url = $("#url-api").data("api_list_treasure");

    const token = $("#token").data("access_token");

    $.ajax({
        type: "GET",
        url: `${url}?page=${page}&length=${length}`,
        data: {},
        headers: {
            'Authorization': `Bearer ${token}`
        },
        dataType: "json",
        success: function (response) {
            if (response.success) {
                mainTable.loadTable(response.data);
            }
        },
        error: function (xhr, status, error) {
            const responseError = JSON.parse(xhr.responseText);

            mainTable.loadTableFailed(responseError.message);
        }
    });

}
