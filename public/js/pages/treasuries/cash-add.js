import { loadListDetailTreasury } from "./cash.js";
import cashTable from "./cash-table.js";

$(document).ready(function () {

    $("#btn-add-cash").on("click touchstart", function () {

        $("#form-add-cash")[0].reset();

        $("#form-add-cash").find("input[type=text], input[type=number], textarea").val("");

        $("#btn-save-cash").show();

        $("#btn-save-updated-cash").hide();

    });

    $("#input-income-amount").on("input", function () {
        formatCurrencyInput($(this));
    });

    $("#input-expense-amount").on("input", function () {
        formatCurrencyInput($(this));
    });
});


$("#form-add-cash").on("submit", function (e) {

    e.preventDefault();

    const inputNotes = $("#input-notes").val();
    const isDebt = $("#input-is-debt").is(':checked') == true ? 1 : 0;
    const treasuryNo = $("#treasury-no").data("treasury_no");

    const detail = $("#input-cash-detail").val();
    let income = parseFloat($("#input-income-amount").val().replace(/,/g, ''));

    if (isNaN(income) || income == undefined) income = null;

    let expense = parseFloat($("#input-expense-amount").val().replace(/,/g, ''));

    if (isNaN(expense) || expense == undefined) expense = null;

    const url = $("#url-api").data("api_create_cash");
    const token = $("#token").data("access_token");

    $.ajax({
        type: "POST",
        url: url,
        data: {
            treasury_no: treasuryNo,
            detail: detail,
            notes: inputNotes,
            income: income,
            expense: expense,
            is_debt: isDebt
        },
        headers: {
            'Authorization': `Bearer ${token}`
        },
        dataType: "json",
        success: function (response) {

            if (response.success) {
                loadListDetailTreasury();
            } else {
                cashTable.loadTableFailed(response.message || "Something wrong when connecting to server");
            }
        }, error: function (xhr, status, error) {
            const responseError = JSON.parse(xhr.responseText);
            cashTable.loadTableFailed(responseError.message);
        },
        complete: function () {
            $("#addCashModal").modal("hide");
            $("#form-add-cash")[0].reset();
        }
    });

});

$("#form-debt-detail").hide();

$('#input-is-debt').change(function () {
    if ($(this).is(':checked')) {
        $('#form-debt-detail').show(); // Show the div if checked
    } else {
        $('#form-debt-detail').hide(); // Hide the div if unchecked
    }
});



/**
 * Format a jQuery input element as currency.
 * @param {jQuery} $input - jQuery input selector.
 */
export const formatCurrencyInput = ($input) => {
    let value = $input.val().replace(/[^0-9.]/g, ''); // remove non-numeric except dot

    const parts = value.split('.');
    let integerPart = parts[0];
    let decimalPart = parts[1] ? parts[1].slice(0, 2) : '';

    // Format integer with commas
    integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ',');

    const formatted = decimalPart ? `${integerPart}.${decimalPart}` : integerPart;
    $input.val(formatted);
}
