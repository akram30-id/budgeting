import alertComponent from "../../components/alert-component.js";

$("#btn-new-password-visible").on("click", function (e) {

    e.preventDefault();

    if ($("#inputNewPassword").attr('type') == "password") {
        $(this).html(`<i class="bi bi-eye-slash" style="color: #c71306;"></i>`);
        $("#inputNewPassword").attr('type', 'text');
    } else {
        $(this).html(`<i class="bi bi-eye"></i>`);
        $("#inputNewPassword").attr('type', 'password');
    }

});

$("#btn-confirm-password-visible").on("click", function (e) {

    e.preventDefault();

    if ($("#inputConfirmPassword").attr('type') == "password") {
        $(this).html(`<i class="bi bi-eye-slash" style="color: #c71306;"></i>`);
        $("#inputConfirmPassword").attr('type', 'text');
    } else {
        $(this).html(`<i class="bi bi-eye"></i>`);
        $("#inputConfirmPassword").attr('type', 'password');
    }

});

let newPassword;
let confirmPassword;

$("#form-change-password").on("submit", function (e) {
    e.preventDefault();

    newPassword = $("#inputNewPassword").val();
    confirmPassword = $("#inputConfirmPassword").val();

    if (!newPassword) {
        $("#newpwd-invalid-feedback").html(
            `<p class="text-danger text-italic fs-6">Password baru gaboleh kosong.</p>`
        );

        setTimeout(() => {
            $("#newpwd-invalid-feedback").html("");
        }, 5000);
        return;
    }

    if (!confirmPassword) {
        $("#cnfpwd-invalid-feedback").html(
            `<p class="text-danger text-italic fs-6">Password barunya tulis ulang dulu dong.</p>`);
        setTimeout(() => {
            $("#cnfpwd-invalid-feedback").html("");
        }, 5000);
        return;
    }

    if (newPassword !== confirmPassword) {
        Swal.fire({
            title: "No no!",
            text: "Password baru dan konfirmasi password gak sama.",
            imageUrl: "/assets/img/bubu_bingung.gif",
            imageWidth: 128,
            // imageHeight: 250,
            imageAlt: "Custom image"
        });
        return;
    }

    $("#inputOldPassword").val("");

    $("#modalOldPassword").modal("show");
});

$("#btn-update-password").on("click", function (e) {
    e.preventDefault();

    const oldPassword = $("#inputOldPassword").val();

    const token = $("#token").data("access_token");
    const url = $("#url-settings").data("api_update_password");
    $.ajax({
        type: "POST",
        url: url,
        headers: {
            "Authorization": `Bearer ${token}`
        },
        data: JSON.stringify({
            old_password: oldPassword,
            new_password: newPassword,
            confirm_password: confirmPassword
        }),
        dataType: "json",
        success: function (response) {
            if (!response.success) {
                Swal.fire({
                    title: "Ups!",
                    text: response.message ||
                        "Ada yang salah saat request ke server. Coba lagi ya...",
                    imageUrl: "/assets/img/bubu_bingung.gif",
                    imageWidth: 128,
                    imageAlt: "Custom image"
                });
                return;
            } else {
                Swal.fire({
                    title: "Hore!",
                    text: response.data ||
                        "Update password berhasil",
                    imageUrl: "/assets/img/bubu_ok.gif",
                    imageWidth: 128,
                    imageAlt: "Custom image"
                }).then((result) => {
                    window.location.reload();
                });
            }
        },
        error: function (xhr, status, error) {
            const responseError = JSON.parse(xhr.responseText);

            if (error === "Unauthorized") {
                window.location.href = "/login";
            } else {
                Swal.fire({
                    title: "Ups!",
                    text: responseError.message ||
                        "Ada yang salah saat request ke server. Coba lagi ya...",
                    imageUrl: "/assets/img/bubu_bingung.gif",
                    imageWidth: 128,
                    imageAlt: "Custom image"
                });
                return;
            }
        }
    });
});
