$(document).ready(function () {
    // Jalankan pertama kali saat halaman dimuat
    updateUnreadCount();
});


const btnNotification = $("#btn-notification");

$('.dropdown').on('show.bs.dropdown', function () {
    notifSwitchOn();
});

$('.dropdown').on('hidden.bs.dropdown', function () {
    notifSwitchOff();

    hideCurrentNotif();
});


$('.dropdown').on('shown.bs.dropdown', function () {
    showCurrentNotif();
});


const notifSwitchOn = () => {
    btnNotification.css({
        "background": "rgba(19, 125, 107, 0.15)",
        "color": "rgba(19, 125, 107, 1)"
    });
}

const notifSwitchOff = () => {
    btnNotification.css({
        "background": "rgba(0, 0, 0, 0.15)",
        "color": "rgba(0, 0, 0, 1)"
    })
}


const showCurrentNotif = () => {
    const url = $("#url").data("url_go");
    const token = $("#token").data("access_token");
    const badgeNotif = $("#total-notification"); // Ambil element badge
    const limit = 3;
    const page = 1;

    $(".notif-actions").html("");

    $.ajax({
        type: "GET",
        url: `${url}/api/notification/current?limit=${limit}&page=${page}`,
        headers: {
            "Authorization": `Bearer ${token}`
        },
        dataType: "json",
        success: function (response) {
            const data = response.data;
            const totalUnread = data.Count; // Asumsi Count ada di sini
            const notifList = data.NotificationList;

            // --- LOGIKA BADGE ---
            if (totalUnread > 0) {
                badgeNotif.text(totalUnread > 99 ? "99+" : totalUnread);
                badgeNotif.show(); // Tampilkan jika ada unread
            } else {
                badgeNotif.hide(); // Sembunyikan jika 0
            }

            // --- LOGIKA LIST NOTIFIKASI ---
            if (!notifList || notifList.length < 1) {
                $(".notif-actions").html(`<div class="text-center fs-5 fst-italic my-5">Tidak Ada Notifikasi</div>`);
            } else {
                notifList.forEach(element => {
                    $(".notif-actions").append(element.Message);
                });
            }
        }
    });
}

const hideCurrentNotif = () => {
    $(".notid-actions").html(`
        <div class="col-12 list-notif rounded-2 mb-1" style="height: 128px; width: 380px;">
            <div class="row align-items-center">
                <div class="col-2 mt-3">
                    <i class="bi bi-person-fill" style="font-size: 3rem;"></i>
                </div>
                <div class="col-10 placeholder-glow">
                    <span class="placeholder">Akram Ganzzz mengundang Anda ke Treasury #TRETEST000001</span>
                </div>
                <div class="col-2"></div>
                <div class="col-10 placeholder-glow">
                    <div class="d-flex align-items-center">
                        <button class="btn btn-sm btn-primary fs-6 disabled placeholder col-3" style="margin-right: 10px;" aria-disabled="true"></button>
                        <button class="btn btn-sm btn-danger fs-6 placeholder col-3 disabled" aria-disabled="true"></button>
                    </div>
                </div>
            </div>
        </div>
    `);
}


const updateUnreadCount = () => {
    const url = $("#url").data("url_go");
    const token = $("#token").data("access_token");
    const badgeNotif = $("#total-notification");

    $.ajax({
        type: "GET",
        url: `${url}/api/notification/current`,
        headers: {
            "Authorization": `Bearer ${token}`
        },
        dataType: "json",
        success: function (response) {
            const totalUnread = response.data.Count; // Mengambil Count dari response [cite: 12, 26]

            if (totalUnread > 0) {
                // Set angka (maksimal 99+) dan tampilkan badge [cite: 26]
                badgeNotif.text(totalUnread > 99 ? "99+" : totalUnread);
                badgeNotif.show();
            } else {
                // Sembunyikan jika tidak ada notifikasi baru [cite: 29]
                badgeNotif.hide();
            }
        }
    });
};



// Task T-001 & T-002: FE Trigger Accept Invite with UI Sync + Redirect Fix
// notification.js

$(document).on('click', '.btn-notif-accept', function (e) {
    e.preventDefault();

    const $btn = $(this);

    // ========== FIX #1: Parse JSON dari atribut 'data' ==========
    let parsedData;
    try {
        const rawData = $btn.attr('data');
        if (!rawData) {
            throw new Error('Data attribute kosong');
        }
        parsedData = JSON.parse(rawData);
    } catch (err) {
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan',
            text: 'Data notifikasi tidak valid. Silakan refresh halaman.'
        });
        return;
    }

    const notificationCode = parsedData.notification_code;
    const treasuryNo = parsedData.treasury_no;

    // ========== FIX #3a: Validasi treasury_no sebelum kirim ==========
    if (!notificationCode || !treasuryNo) {
        Swal.fire({
            icon: 'warning',
            title: 'Data Tidak Lengkap',
            text: 'Kode notifikasi atau Treasury tidak ditemukan.'
        });
        return;
    }

    // ========== FIX #3b: Additional validation ==========
    const trimmedTreasuryNo = String(treasuryNo).trim();
    if (!trimmedTreasuryNo || trimmedTreasuryNo === 'undefined' || trimmedTreasuryNo === 'null') {
        Swal.fire({
            icon: 'error',
            title: 'Data Treasury Tidak Valid',
            text: 'Nomor Treasury tidak valid. Silakan refresh halaman dan coba lagi.'
        });
        return;
    }

    // ========== SEPARASI URL: BE untuk API, FE untuk redirect ==========
    const apiBaseUrl = $("#url").data("url_go");      // ← Backend API base URL
    const token = $("#token").data("access_token");

    // Prevent double click + loading state
    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

    $.ajax({
        url: `${apiBaseUrl}/api/notification/accept-invite`,  // ← BE URL untuk API call
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        data: JSON.stringify({
            notification_code: notificationCode,
            treasury_no: trimmedTreasuryNo
        }),
        success: function (response) {
            if (response.success) {
                // ========== FIX #5: Redirect menggunakan RELATIVE PATH (FE domain) ==========
                // ❌ JANGAN: const redirectUrl = `${apiBaseUrl}/treasury/detail?treasury=...`;
                // ✅ GUNAKAN: relative path — browser otomatis resolve ke current FE domain

                const redirectPath = `/treasury/detail?treasury=${encodeURIComponent(trimmedTreasuryNo)}`;

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Undangan Treasury ' + trimmedTreasuryNo + ' telah diterima.',
                    timer: 1500,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then(() => {
                    // ========== FIX #4: Redirect di dalam .then() menggunakan relative path ==========
                    window.location.href = redirectPath;
                });

            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: response.message || 'Terjadi kesalahan yang tidak diketahui.'
                });
                $btn.prop('disabled', false).text('Accept');
            }
        },
        error: function (xhr) {
            let errorMessage = 'Gagal menerima undangan. Silakan coba lagi.';

            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }

            Swal.fire({
                'icon': 'error',
                'title': 'Gagal',
                'text': errorMessage,
                'confirmButtonText': 'OK'
            });

            $btn.prop('disabled', false).text('Accept');
        }
    });
});

// Helper untuk reset button jika gagal
const handleError = ($btn, message) => {
    $btn.prop('disabled', false).text('Accept');
    alert(message); // Sesuaikan dengan UI library (Toast/Swal) jika ada
};
