const showCashRecords = (treasuryNo) => {
    const url = $("#url-api").data("api_list_cash");
    const token = $("#token").data("access_token");

    $.ajax({
        type: "GET",
        url: `${url}?treasury_no=${treasuryNo}`,
        headers: {
            'Authorization': `Bearer ${token}`
        },
        dataType: "json",
        success: function (response) {
            if (response.success) {

                $("#periode").text(`${new Date(response.month).toLocaleString('default', { month: 'long' })} ${response.year}`);

                const data = response.data;

                loadTable(data);

            } else {
                cashTable.loadTableFailed(response.message);
            }
        },
        error: function (xhr, status, error) {
            const responseError = JSON.parse(xhr.responseText);

            if (error === "Unauthorized") {
                window.location.href = "/login";
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: responseError.message
                });
            }

        }
    });
}


const loadTable = (data) => {
    const table = $("#tbody-cash-records");

    table.html("");

    if (data.length > 0) {

        data.forEach(element => {
            table.append(`
            <tr data-value="${element.treasury_detail_no}">
                <td>
                    <input class="form-check-input" id="${element.treasury_detail_no}" type="checkbox" value="" id="checkDefault">
                </td>
                <td>${element.treasury_detail_name}</td>
                <td>
                    ${new Date(element.month).toLocaleString('default', { month: 'long' })} ${element.year}
                </td>
                <td>${Number(element.income_value).toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 })}</td>
                <td class="expense-cell" data-value="${element.expense_value}">${Number(element.expense_value).toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 })}</td>
                <td class="text-center align-middle">
                    ${element.is_debt == 1 ? "Yes" : "No"}
                </td>
            </tr>
            `);
        });
    } else {
        loadTableFailed("No data found.");
    }

}

const loadTableFailed = (message) => {
    const table = $("#tbody-treasury-detail");
    table.html(`
    <tr>
        <td colspan="9" class="text-center">${message}</td>
    </tr>
    `);
}



export default {
    showCashRecords
}
