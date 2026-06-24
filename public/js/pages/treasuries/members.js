/**
 * members.js
 * Implementation for T-008: Member Access – FE-BE Integration
 */

import alertComponent from "../../components/alert-component.js";

let treasuryNo;

$(document).on("click", ".show-treasury-members", function (e) {
    e.preventDefault();

    treasuryNo = $(this).data("treasury_no");

    $("#members-of-treasury").text("");
    $("#members-of-treasury").text(treasuryNo);

    // show list members
    showListMembers(treasuryNo);
});

const showListMembers = (treasuryNo, page = 1) => {

    $("#member-list").html('<p class="fs-6 fst-italic">Loading . . . . .</p>');

    const url = $("#url-api").data("api_show_list_members");
    const token = $("#token").data("access_token");

    $.ajax({
        type: "GET",
        url: `${url}?treasury_no=${treasuryNo}&page=${page}`,
        headers: {
            "Authorization": `Bearer ${token}`
        },
        dataType: "json",
        success: function (response) {
            if (response.success) {
                const data = response.data.data

                if (data.length > 0) {
                    $("#member-list").html("");
                    data.forEach(element => {
                        // T-008: Logic for Permission Guard & Labeling
                        const isOwner = element.role === 'owner';
                        const canEdit = element.can_edit == 1;

                        $("#member-list").append(`
                            <div class="list-group-item d-flex align-items-center justify-content-between mb-1 p-1 treasury-member" data-id="${element.id}">
                                <div class="row">
                                    <div class="col-10">
                                        <p class="mb-0 fw-semibold" style="font-size: 9pt !important;">
                                            ${element.name} ${isOwner ? '<span class="badge bg-light text-dark border" style="font-size: 6pt;">Owner</span>' : ''}
                                        </p>
                                    </div>
                                    <div class="col-10">
                                        <p class="mb-0" style="font-size: 7pt !important;">${element.email}</p>
                                    </div>
                                </div>
                                ${element.is_accepted == 1
                                ? `<div class="d-flex align-items-center">
                                        <div class="dropdown me-1">
                                            <button class="btn btn-secondary dropdown-toggle btn-access-control"
                                                    type="button"
                                                    data-bs-toggle="dropdown"
                                                    aria-expanded="false"
                                                    style="font-size: 7pt !important; padding: 2px !important;"
                                                    ${isOwner ? 'disabled' : ''}>
                                                ${canEdit ? 'Bisa Ngedit' : 'Cuma Lihat'}
                                            </button>
                                            <ul class="dropdown-menu fs-6 shadow-sm">
                                                <li>
                                                    <a class="dropdown-item change-access" href="#"
                                                       data-member_id="${element.id}"
                                                       data-treasury_no="${treasuryNo}"
                                                       data-can_edit="0">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            Cuma Lihat
                                                        </div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item change-access" href="#"
                                                       data-member_id="${element.id}"
                                                       data-treasury_no="${treasuryNo}"
                                                       data-can_edit="1">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            Bisa Ngedit
                                                        </div>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <button class="btn btn-sm btn-outline-danger btn-list-item btn-delete-member" data-member_id="${element.id}" style="font-size: 7pt !important; padding: 2px !important;">
                                            Hapus
                                        </button>
                                    </div>`
                                : `<p class="mb-0" style="font-size: 7pt !important;"><i>Menunggu Konfirmasi</i></p>`}
                            </div>
                        `)
                    });
                } else {
                    $("#member-list").html('<p class="fs-6 fst-italic">Gak ada siapa-siapa</p>');
                }
            }
        }
    });


    $(document).on("click", ".btn-delete-member", function (e) {
        e.preventDefault();

        $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>...');

        const memberId = $(this).data("member_id");
        const token = $("#token").data("access_token");
        const url = $("#url").data("url_go");

        const actRemoveMember = (url, token, treasuryNo, memberId) => {
            $.ajax({
                type: "POST",
                url: `${url}/api/treasury/remove-member`,
                headers: {
                    "Content-Type": "application/json",
                    "Authorization": `Bearer ${token}`
                },
                data: JSON.stringify({
                    "treasury_no": treasuryNo,
                    "member_id": memberId
                }),
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        alertComponent.alertSuccess("Hapus Member Berhasil.");
                    } else {
                        alertComponent.alertFailed(response.message || "Ada Kesalahan Sistem, Coba Lagi Nanti.");
                    }

                    showListMembers(treasuryNo, 1);
                }
            });
        }

        alertComponent.alertConfirmation("Yakin ingin menghapus member?", "Member yang dihapus bisa diundang kembali.", "Ya, hapus!", () => actRemoveMember(url, token, treasuryNo, memberId));
    })
}

/**
 * T-008: Action Handler for Changing Member Access
 * Implements SAFE Approach (Loading -> API -> Update UI)
 */
$(document).on("click", ".change-access", function (e) {
    e.preventDefault();

    const $btn = $(this).closest('.dropdown').find('.btn-access-control');
    const memberId = $(this).data('member_id');
    const treasuryNo = $(this).data('treasury_no');
    const canEditValue = $(this).data('can_edit');
    const originalText = $btn.text().trim();
    const newText = canEditValue == 1 ? 'Bisa Ngedit' : 'Cuma Lihat';

    // 1. Loading State (Prevent Spam Clicking)
    $btn.prop('disabled', true).html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`);

    const url = $("#url").data("url_go");
    const token = $("#token").data("access_token");

    // 2. API Integration
    $.ajax({
        type: "POST",
        url: `${url}/api/treasury/member-access`,
        headers: {
            "Authorization": `Bearer ${token}`,
            "Content-Type": "application/json"
        },
        data: JSON.stringify({
            member_id: memberId,
            treasury_no: treasuryNo,
            can_edit: canEditValue
        }),
        dataType: "json",
        success: function (response) {
            if (response.success) {
                // 3. State Synchronization (Success)
                $btn.text(newText).prop('disabled', false);

                // Hapus tanda ceklis (ikon check) dari semua saudara (siblings) di dropdown yang sama
                $btn.closest('.dropdown-menu').find('.change-access').find('i.fa-check').remove();

                // Tambahkan tanda ceklis ke item yang baru saja diklik
                $btn.prepend('<i class="fas fa-check me-2 text-success"></i>');

                // Update text pada tombol utama dropdown agar mencerminkan status terbaru
                const newLabel = (canEditValue == 1) ? 'Bisa Ngedit' : 'Hanya Lihat';
                $btn.html(newLabel).removeClass('disabled');

                // Toast sukses
                alert("Akses berhasil diperbarui");
            } else {
                handleAccessError($btn, originalText, response.message || "Gagal mengubah akses.");
            }
        },
        error: function (xhr) {
            // 4. Error Handling & Revert
            let errorMsg = "Terjadi kesalahan jaringan.";
            if (xhr.status === 403) errorMsg = "Anda tidak memiliki izin (Unauthorized).";

            handleAccessError($btn, originalText, errorMsg);
        }
    });
});

// Helper for Error Revert
function handleAccessError(element, oldText, message) {
    alert(message);
    element.text(oldText).prop('disabled', false);
}

$(document).ready(function () {
    let timeout = null;

    $('#search-people').on('keyup', function () {
        let keywords = $(this).val();
        let listContainer = $('#suggestion-list');

        clearTimeout(timeout);

        const url = $("#url-api").data("api_find_users");
        const token = $("#token").data("access_token");

        if (keywords.length > 3) {
            timeout = setTimeout(function () {
                $.ajax({
                    url: url,
                    type: 'GET',
                    headers: {
                        "Authorization": `Bearer ${token}`
                    },
                    data: { keywords: keywords },
                    success: function (response) {
                        listContainer.empty().show();

                        if (!response.success) {
                            listContainer.append(`
                                <div class="list-group-item small text-danger">
                                    <i class="fas fa-exclamation-circle me-1"></i> ${response.message}
                                </div>
                            `);
                            return;
                        }

                        if (response.data && response.data.length > 0) {
                            response.data.forEach(function (user) {
                                listContainer.append(`
                                    <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center item-user-row"
                                        style="cursor: pointer;" data-name="${user.Name}" data-email="${user.Email}">

                                        <div class="text-truncate me-2">
                                            <div class="fw-bold mb-0" style="font-size: 0.85rem;">${user.Name}</div>
                                            <small class="text-muted d-block" style="font-size: 0.75rem;">${user.Email}</small>
                                        </div>

                                        <button type="button" class="btn btn-outline-primary btn-sm btn-invite" data-email="${user.Email}"
                                                style="font-size: 0.65rem; padding: 2px 8px;">
                                            INVITE
                                        </button>
                                    </div>
                                `);
                            });
                        } else {
                            listContainer.append('<div class="list-group-item small text-muted">Data tidak tersedia.</div>');
                        }
                    },
                    error: function (xhr) {
                        const responseError = JSON.parse(xhr.responseText);
                        $('#suggestion-list').empty().show().append(
                            `<div class="list-group-item small text-danger">${responseError.message}</div>`
                        );
                    }
                });
            }, 500);
        } else {
            listContainer.hide();
        }
    });

    $(document).on('click', '.item-user', function () {
        let name = $(this).data('name');
        let email = $(this).data('email');
        $('#search-people').val(name);
        $('#suggestion-list').hide();
        console.log("Selected:", name, email);
    });

    $(document).click(function (e) {
        if (!$(e.target).closest('.position-relative').length) {
            $('#suggestion-list').hide();
        }
    });


    // Menggunakan Event Delegation karena .btn-invite dibuat secara dinamis
    $(document).on('click', '.btn-invite', function (e) {
        e.preventDefault();
        e.stopPropagation(); // Mencegah event row diklik jika ada

        const $btn = $(this);
        const token = $("#token").data("access_token");
        const userEmail = $(this).data("email");

        // Fallback URL: sesuaikan jika ada data-attribute khusus di DOM untuk endpoint ini
        const url = $("#url").data("url_go");

        // 1. Loading State (Mencegah spam click & memberikan feedback visual)
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>...');

        $.ajax({
            url: `${url}/api/treasury/invite-member`,
            type: 'POST',
            headers: {
                "Authorization": `Bearer ${token}`,
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            data: JSON.stringify({
                "treasury_no": treasuryNo,
                "email": userEmail
            }),
            success: function (response) {
                // 2. Success Feedback (Safe Update)
                if (response.success || response.message === "Success") {
                    $btn.removeClass('btn-outline-primary')
                        .addClass('btn-success')
                        .text('INVITED')
                        .prop('disabled', true);

                    // Opsional: Sembunyikan suggestion list setelah sukses atau biarkan tetap terbuka
                    // $('#suggestion-list').hide();
                    // $('#search-people').val('');
                } else {
                    // Handle jika response 200 tapi ada flag error dari server
                    const message = response.message || "Gagal mengundang anggota.";
                    alertComponent.alertFailed(message);
                    resetInviteButton($btn);
                }
            },
            error: function (xhr) {
                // 3. Error Handling
                let errorMessage = "Terjadi kesalahan sistem saat mengundang member.";
                try {
                    const responseError = JSON.parse(xhr.responseText);
                    if (responseError && responseError.message) {
                        errorMessage = responseError.message;
                    }
                } catch (e) {
                    console.error("Gagal melakukan parse error response", e);
                }

                alertComponent.alertFailed(errorMessage);
                resetInviteButton($btn);
            }
        });
    });

    // Fungsi pembantu untuk mengembalikan state tombol jika gagal
    function resetInviteButton($button) {
        $button.prop('disabled', false)
            .removeClass('btn-success')
            .addClass('btn-outline-primary')
            .text('INVITE');
    }
});
