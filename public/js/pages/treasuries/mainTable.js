const loadTable = (data) => {

    const table = $("#tbody-treasury-cash");

    table.html("");

    data.forEach(element => {
        table.append(`
        <tr class="text-center">
            <td><a class="text-decoration-none text-money fw-bold" href="/treasuries/detail?treasury=${element.treasury_no}">${element.treasury_no}</a></td>
            <td>${new Date(element.month).toLocaleString('default', { month: 'long' })}</td>
            <td>${element.year}</td>
            <td><a class="text-decoration-none text-money fw-bold" href="/treasuries/detail?treasury=${element.treasury_no}">${element.total_records}</a></td>
            <td><a class="text-decoration-none text-money fw-bold" href="/treasuries/member?treasury=${element.treasury_no}">${element.total_members}</a></td>
            <td>${element.owner_name}</td>
            <td>${formatDate(new Date(element.created_at))}</td>
            <td>
                <button class="btn-delete-treasury btn btn-xs btn-outline-danger" data-treasury_no="${element.treasury_no}">Delete</button>
            </td>
        </tr>`)
    });

}

const loadTableFailed = (message) => {
    const table = $("#tbody-treasury-cash");

    table.html("");

    table.html(`
        <tr class="text-center">
            <td colspan="8">${message}</td>
        </tr>
    `);

}

const formatDate = (date) => {
    const day = String(date.getDate()).padStart(2, '0'); // d: day of the month with leading zero
    const month = date.toLocaleString('default', { month: 'long' }); // F: full month name
    const year = date.getFullYear(); // Y: four-digit year

    const hours = String(date.getHours()).padStart(2, '0'); // h: hours with leading zero
    const minutes = String(date.getMinutes()).padStart(2, '0'); // i: minutes with leading zero
    const seconds = String(date.getSeconds()).padStart(2, '0'); // s: seconds with leading zero

    return `${day} ${month} ${year} ${hours}:${minutes}:${seconds}`;
}

export default {
    loadTable,
    loadTableFailed
}
