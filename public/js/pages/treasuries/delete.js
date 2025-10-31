import toastComponent from "../../components/toast-component.js";
import { loadListTreasury } from "./index.js";

// event delegation — bekerja untuk elemen yang ditambahkan dinamis
$(document).on('click', '.btn-delete-treasury', function () {
    $('#modalDelete').modal('show');

    const treasuryNo = $(this).data('treasury_no');
    $('#treasury-no-delete').text(treasuryNo);
});

$(document).on('click', '#btn-delete-treasury', function () {

    $("#tbody-treasury-cash").html(`
        <tr class="text-center">
            <td colspan="8">Loading . . .</td>
        </tr>
    `);

    const treasuryNo = $('#treasury-no-delete').text();

    deleteTreasury(treasuryNo);

    $('#modalDelete').modal('hide');
});


const deleteTreasury = (treasuryNo) => {

    const token = $('#token').data('access_token');

    $.ajax({
        url: '/api/delete-treasury',
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${token}`
        },
        data: { treasury_no: treasuryNo },
        success: function (response) {
            if (response.success) {
                toast(response.data || 'Treasury delete successfully', 'text-bg-success');
            } else {
                toast(response.message || 'Treasury delete failed', 'text-bg-danger');
            }
        },
        error: function (xhr, status, error) {
            const responseError = JSON.parse(xhr.responseText);
            toast(responseError.message || 'Treasury delete failed', 'text-bg-danger');
        },
        complete: function () {
            loadListTreasury();
        }
    });
}


const toast = (message, colorClass = 'text-bg-primary') => {

    const toastEl = document.getElementById('toast-treasury');
    if (!toastEl) return;

    const bodyEl = toastEl.querySelector('.toast-body');
    if (bodyEl) {
        bodyEl.textContent = message;
        console.log('Toast message diisi:', message);
    } else {
        console.error('toast-body tidak ditemukan, pesan toast:', message);
    }

    // hapus semua kelas warna text-bg-* yang ada, lalu tambahkan yang baru
    [...toastEl.classList]
        .filter(cls => cls.startsWith('text-bg-'))
        .forEach(cls => toastEl.classList.remove(cls));
    toastEl.classList.add(colorClass);

    if (window.bootstrap && window.bootstrap.Toast) {
        const instance = window.bootstrap.Toast.getOrCreateInstance(toastEl, { delay: 5000 });
        console.log('Menampilkan toast dengan Bootstrap API');
        instance.show();
    } else {
        $(toastEl).show();
        setTimeout(() => {
            $(toastEl).hide();
        }, 5000);
    }
}
