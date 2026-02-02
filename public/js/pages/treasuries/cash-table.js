const loadTable = (data) => {
    const table = $("#tbody-treasury-detail");

    table.html("");

    if (data.length > 0) {

        data.forEach(element => {
            table.append(`
            <tr>
                <td>${new Date(element.created_at).toLocaleString('id-ID')}</td>
                <td>${element.treasury_detail_name}</td>
                <td>
                    ${new Date(element.month).toLocaleString('default', { month: 'long' })} ${element.year}
                </td>
                <td>${Number(element.income_value).toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 })}</td>
                <td>${Number(element.expense_value).toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 })}</td>
                <td class="text-center align-middle">
                    <input class="form-check-input checkbox-treasury-detail" type="checkbox" style="border-width:2px;" ${element.is_checked == 1 ? "checked" : ""} value="" id="checkDefault" data-treasury_detail_no="${element.treasury_detail_no}">
                </td>
                <td>${Number(element.estimate_value).toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 })}</td>
                <td class="actual-value-cell" data-treasury_detail_no="${element.treasury_detail_no}">${Number(element.actual_value).toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 })}</td>
                <td>
                    <button class="btn btn-sm btn-outline-primary mb-2 btn-update-cash" data-treasury_detail_no="${element.treasury_detail_no}">Edit</button>
                    <button class="btn btn-sm btn-outline-danger mb-2 btn-delete-cash" data-treasury_detail_no="${element.treasury_detail_no}" data-cash_detail="${element.treasury_detail_name}">Delete</button>
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
    loadTable,
    loadTableFailed
}
