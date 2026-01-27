import cashTable from "./cash-table.js";

const treasuryNo = $("#treasury-no").data("treasury_no");

let currentPage = 1;
let currentLength = parseInt($("#select-length-treasury").val()) || 100;
let currentKeywords = "";

$(document).ready(function () {

    loadListDetailTreasury(1, 100, "");

});

export const loadListDetailTreasury = (page = 1, length = 100, keywords = "",) => {
    const url = $("#url-api").data("api_get_detail_treasury");
    const token = $("#token").data("access_token");

    $.ajax({
        type: "GET",
        url: `${url}?page=${page}&length=${length}&keywords=${keywords}&treasury_no=${treasuryNo}`,
        headers: {
            'Authorization': `Bearer ${token}`
        },
        dataType: "json",
        success: function (response) {
            if (response.success) {


                $("#periode").text(`${new Date(response.month).toLocaleString('default', { month: 'long' })} ${response.year}`);

                cashTable.loadTable(response.data);

                console.info(response.data.length);

                if (response.data.length === 0) {
                    cashTable.loadTableFailed("No data found.");
                }
            } else {
                cashTable.loadTableFailed(response.message);
            }
        },
        error: function (xhr, status, error) {
            const responseError = JSON.parse(xhr.responseText);
            cashTable.loadTableFailed(responseError.message);
        }
    });
}

$(document).on("click touchstart", ".checkbox-treasury-detail", function () {

    const treasuryDetailNo = $(this).data("treasury_detail_no");
    const isChecked = $(this).prop("checked");
    const token = $("#token").data("access_token");

    const url = $("#url-api").data("api_update_checked");

    console.log(treasuryDetailNo, isChecked);

    $.ajax({
        type: "POST",
        url: `${url}`,
        headers: {
            'Authorization': `Bearer ${token}`
        },
        dataType: "json",
        data: {
            treasury_detail_no: treasuryDetailNo,
            is_checked: (isChecked == true) ? 1 : 0
        },
        success: function (response) {

            if (response.success) {

                $(".actual-value-cell").text("syncronizing...");

                fetchTreasuryDetail(currentPage, currentLength, currentKeywords);
            }

        },
        error: function (xhr, status, error) {
            const responseError = JSON.parse(xhr.responseText);
            toastr.error(responseError.message);
        }
    });

});

const fetchTreasuryDetail = (page, length, keywords) => {

    const url = $("#url-api").data("api_get_detail_treasury");
    const token = $("#token").data("access_token");
    const treasuryNo = $("#treasury-no").data("treasury_no");

    $.ajax({
        type: "GET",
        url: `${url}?page=${page}&length=${length}&keywords=${keywords}&treasury_no=${treasuryNo}`,
        headers: {
            'Authorization': `Bearer ${token}`
        },
        dataType: "json",
        success: function (response) {
            if (response.success) {
                $(".actual-value-cell").each(function () {
                    const detailNo = $(this).data("treasury_detail_no");
                    const found = response.data.find(d => d.treasury_detail_no === detailNo);
                    if (found) {
                        $(this).text(`${Number(found.actual_value).toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 })}`)
                    }
                });
            } else {
                cashTable.loadTableFailed(response.message);
            }
        },
        error: function (xhr, status, error) {
            const responseError = JSON.parse(xhr.responseText);
            cashTable.loadTableFailed(responseError.message);
        }
    });

}
