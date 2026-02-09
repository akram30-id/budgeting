$("#cash-counter").hide();

$(document).on("dblclick", "#tbody-treasury-detail tr", function (e) {

    if ($(e.target).is("button", "input")) return;

    $(this).toggleClass("table-active");

    let totalExpense = 0;

    $("#tbody-treasury-detail tr.table-active").each(function () {
        totalExpense += Number($(this).find(".expense-cell").data("value")) || 0;
    });

    if (totalExpense > 0) {
        $("#cash-counter").show();
        $("#cash-counter-value").text(totalExpense.toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 }));
    } else {
        $("#cash-counter").hide();
    }

});
