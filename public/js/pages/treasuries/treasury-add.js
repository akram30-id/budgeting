import alertComponent from "../../components/alert-component.js";
import toastComponent from "../../components/toast-component.js";
import { loadListTreasury } from "./index.js";
import treasuryCashDuplicate from "./treasury-cash-duplicate.js";

$("#load-modal-cash").on("click touchstart", function () {


    $("#treasuryModal").modal("show");

    const url = $("#url-api").data("api_list_treasure");

    const token = $("#token").data("access_token");

    $("#input-current-treasury").html("")

    $.ajax({
        type: "GET",
        url: `${url}?page=1&length=1000`,
        data: {},
        headers: {
            'Authorization': `Bearer ${token}`
        },
        dataType: "json",
        success: function (response) {
            if (response.success) {

                const data = response.data;

                if (data.length > 0) {
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
                $("#input-current-treasury").append(`<option value="" disabled>No data found</option>`)
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


});

$("#form-add-treasury").on("submit", function (e) {

    e.preventDefault();

    const url = $("#url-api").data("api_create_treasury");

    const token = $("#token").data("access_token");

    const data = {
        month: $("#input-treasury-month").val(),
        year: $("#input-treasury-year").val(),
    }

    const selectedCurrentTreasury = $("#input-current-treasury").find("option:selected");

    if (selectedCurrentTreasury.val() !== "") {

        $("#selected-treasury").text(selectedCurrentTreasury.val());

        treasuryCashDuplicate.showCashRecords(selectedCurrentTreasury.val());

        $("#modalDupplicateTreasury").modal("show");

        let listCashDuplicate = [];

        $(document).on("click", "#tbody-cash-records tr", function () {
            const cashNo = $(this).data("value");

            // $(this).toggleClass("table-active");

            if (listCashDuplicate.includes(cashNo)) {
                $(`#${cashNo}`).prop("checked", false);

                const idx = listCashDuplicate.indexOf(cashNo);
                if (idx !== -1) {
                    listCashDuplicate.splice(idx, 1);
                }

            } else {
                $(`#${cashNo}`).prop("checked", true);

                listCashDuplicate.push(cashNo);
            }

            console.info(listCashDuplicate);
        });

        $("#btn-submit-duplicate-cash").on("click", function () {

            Swal.fire({
                title: "Are you sure?",
                text: `We will dupplicate whole record from ${selectedCurrentTreasury.val()} to this treasury!`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, sure of it!"
            }).then((result) => {
                if (result.isConfirmed) {

                    // hit API duplicate treasury
                    const url = $("#url-api").data("api_duplicate_treasury");
                    const token = $("#token").data("access_token");

                    $.ajax({
                        type: "POST",
                        url: url,
                        headers: {
                            "Authorization": `Bearer ${token}`
                        },
                        data: JSON.stringify({
                            "treasury_no": selectedCurrentTreasury.val(),
                            "treasury_detail_no": listCashDuplicate,
                            "month": $("#input-treasury-month").val(),
                            "year": $("#input-treasury-year").val(),
                        }),
                        dataType: "json",
                        success: function (response) {
                            if (!response.success) {
                                alertComponent.alertFailed(response.message);
                                return;
                            }

                            const data = response.data;

                            if (!data.treasury_no) {
                                alertComponent.alertFailed("Failed to duplicate cash.");
                            }

                            alertComponent.alertSuccess(response.message, `/treasury/detail?treasury=${data.treasury_no}`);
                        },
                        error: function (xhr) {

                            const responseError = JSON.parse(xhr.responseText);

                            alertComponent.alertFailed(responseError.message);

                        }
                    });
                }
            });
        })

    } else {
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
    }

})
