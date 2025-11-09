import toastComponent from "../../components/toast-component.js";
import { loadListDetailTreasury } from "./cash.js";

$(document).on("click", ".btn-update-cash", function () {

    $("#loader-cash").show();

    $("#form-add-cash").hide();

    $("#btn-save-cash").hide();

    $("#btn-save-updated-cash").show();

    $("#addCashModal").modal("show");

    $("#addCashModalLabel").text("Update Cash");

    const treasuryDetailNo = $(this).data("treasury_detail_no");

    const url = $("#url-api").data("api_detail_cash");

    const token = $("#token").data("access_token");

    $.ajax({
        type: "GET",
        url: `${url}?treasury_detail_no=${treasuryDetailNo}`,
        dataType: "json",
        headers: {
            "Authorization": `Bearer ${token}`
        },
        success: function (response) {

            if (response.success) {

                const data = response.data;

                $("#input-treasury-detail-no").val(data.treasury_detail_no);
                $("#input-cash-detail").val(data.treasury_detail_name);
                $("#input-income-amount").val(new Intl.NumberFormat('en-US').format(data.income_value));
                $("#input-expense-amount").val(new Intl.NumberFormat('en-US').format(data.expense_value));
                $("#input-notes").val(data.notes);

                if (data.is_debt == 1) {
                    $("#input-is-debt").attr("checked", true);

                    $('#form-debt-detail').show(); // Show the div if checked
                } else {
                    $("#input-is-debt").attr("checked", false);

                    $('#form-debt-detail').hide(); // Hide the div if unchecked
                }
            }

        },
        error: function (xhr, status, error) {
            const responseError = JSON.parse(xhr.responseText());

            $("#addCashModal").modal("hide");

            toastComponent.toast(responseError.message);
        },
        complete: function () {
            $("#form-add-cash").show();
            $("#loader-cash").hide();
        }
    });

})


$("#btn-save-updated-cash").on("click", function () {

    const url = $("#url-api").data("api_update_checked");

    const treasuryDetailNo = $("#input-treasury-detail-no").val();

    const token = $("#token").data("access_token");

    $.ajax({
        type: "POST",
        url: url,
        headers: {
            "Authorization": `Bearer ${token}`
        },
        data: {
            treasury_detail_no: treasuryDetailNo,
            treasury_detail_name: $("#input-cash-detail").val(),
            income_value: parseFloat($("#input-income-amount").val().replace(/[^\d]/g, '')),
            expense_value: parseFloat($("#input-expense-amount").val().replace(/[^\d]/g, '')),
            notes: $("#input-notes").val(),
            is_debt: $("#input-is-debt").is(':checked') == true ? 1 : 0
        },
        dataType: "json",
        success: function (response) {

            if (response.success) {

                loadListDetailTreasury();

                toastComponent.toast(response.message, 'text-bg-success');

            }

        },
        error: function (xhr) {
            const responseError = JSON.parse(xhr.responseText);

            toastComponent.toast(responseError.message, 'text-bg-danger');
        },
        complete: function () {
            $("#addCashModal").modal("hide");
        }
    });

})
