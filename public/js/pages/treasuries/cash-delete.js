import toastComponent from "../../components/toast-component.js";
import { loadListDetailTreasury } from "./cash.js";

$(document).on("click", ".btn-delete-cash", function (e) {
    e.preventDefault();

    const treasuryDetailNo = $(this).data("treasury_detail_no");

    const detail = $(this).data("cash_detail");

    $("#cash-detail-delete").text(detail);

    $("#cash-no-delete").val(treasuryDetailNo);

    $("#modalDeleteCash").modal("show");
});

$("#btn-delete-cash").on("click", function () {

    const cashNo = $("#cash-no-delete").val();

    console.info(cashNo);

    const url = $("#url-api").data("api_delete_cash");
    const token = $("#token").data("access_token");

    $.ajax({
        type: "POST",
        url: url,
        headers: {
            "Authorization": `Bearer ${token}`
        },
        data: {
            treasury_detail_no: cashNo
        },
        dataType: "json",
        success: function (response) {
            if (response.success) {

                $("#modalDeleteCash").modal("hide");

                loadListDetailTreasury();

                toastComponent.toast(response.message);

            }
        },
        error: function (xhr, status, error) {
            const responseError = JSON.parse(xhr.responseText);

            $("#modalDeleteCash").modal("hide");

            toastComponent.toast(response.message, 'text-bg-danger');
        }
    });
})
