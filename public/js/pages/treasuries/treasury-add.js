import toastComponent from "../../components/toast-component.js";
import { loadListTreasury } from "./index.js";

$("#load-modal-cash").on("click", function () {


    $("#treasuryModal").modal("show");


});

$("#form-add-treasury").on("submit", function (e) {

    e.preventDefault();

    const url = $("#url-api").data("api_create_treasury");

    const token = $("#token").data("access_token");

    const data = {
        month: $("#input-treasury-month").val(),
        year: $("#input-treasury-year").val(),
    }

    $.ajax({
        type: "POST",
        url: url,
        headers: {
            "Authorization": `Bearer ${token}`
        },
        data: data,
        dataType: "json",
        success: function (response) {
            console.info(response);

            if (response.success) {
                loadListTreasury();

                toastComponent.toast(response.message, 'text-bg-success');
            } else {
                toastComponent.toast(response.message, 'text-bg-danger');
            }
        },
        error: function (xhr) {

            const responseError = JSON.parse(xhr.responseText);

            toastComponent.toast(responseError.message, 'text-bg-danger');

        },
        complete: function () {
            $("#treasuryModal").modal("hide");
        }
    });

})
