import cashTable from "./cash-table.js";

$(function () {

    let startIndex = null;

    $("#tbody-treasury-detail").sortable({
        items: "tr",
        axis: "y",

        update: function (event, ui) {
            let newIndex = ui.item.index();

            if (newIndex === startIndex) return;

            const treasuryDetailNo = ui.item.data("treasury_detail_no");

            const newSorts = newIndex + 1;

            const token = $("#token").data("access_token");
            const apiUpdateSort = $("#url-api").data("api_update_sort_cash");

            $.ajax({
                url: apiUpdateSort,
                method: "POST",
                headers: {
                    "Authorization": `Bearer ${token}`
                },
                contentType: "application/json",
                data: JSON.stringify({
                    treasury_detail_no: treasuryDetailNo,
                    sorts: newSorts
                }),
                success: function () {
                    $(".actual-value-cell").text("syncronizing...");
                    $(".estimate-value-cell").text("syncronizing...");

                    fetchTreasuryDetail(1, 1000, "");
                }
            });
        }

    })

})


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
                $(".estimate-value-cell").each(function () {
                    const detailNo = $(this).data("treasury_detail_no");
                    const found = response.data.find(d => d.treasury_detail_no === detailNo);
                    if (found) {
                        $(this).text(`${Number(found.estimate_value).toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 })}`)
                    }
                });

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
