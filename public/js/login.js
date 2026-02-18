import alertComponent from "./components/alert-component.js";
import csrf_setup from "./csrf_setup.js";

const form = $("#form-sign-in");

form.on("submit", function (e) {
    e.preventDefault();

    const email = $("#inputEmail").val();
    const password = $("#inputPassword").val();
    const rememberMe = $("#remember-me").is(":checked");

    if (!email || !password) {
        alertComponent.alertFailed("Email dan password wajib diisi.")
        return;
    }

    $("#btn-sign-in").text("Loading...");

    submitLoginForm(email, password, rememberMe);
});

const submitLoginForm = (email, password, rememberMe = false) => {
    const url = $("#url").data("api_login");

    $.ajax({
        type: "POST",
        url: url,
        data: { email, password, remember: rememberMe },
        dataType: "json",
        success: function (response) {
            if (response && response.success && response.token) {
                $("#btn-sign-in").text("Sign In");
                handleLoginSuccess(response.token, rememberMe);
            } else {
                alertComponent.alertFailed(response.message || "Login gagal. Silakan coba lagi.");
            }
        },
        error: function (xhr, status, error) {
            $("#btn-sign-in").text("Sign In");
            const errorResponse = JSON.parse(xhr.responseText);

            alertComponent.alertFailed(errorResponse.message || "Login gagal. Silakan coba lagi.");
        },
    });
};

const handleLoginSuccess = (token, rememberMe = false) => {
    const url = $("#url").data("save_token");

    $.ajax({
        type: "POST",
        url: url,
        headers: csrf_setup.headers,
        data: { access_token: token, remember: rememberMe },
        dataType: "json",
        success: function (response) {
            if (response && response.success) {
                window.location.href = "/";
            } else {
                alertComponent.alertFailed("Gagal menyimpan token.");
            }
        },
        error: function () {
            alertComponent.alertFailed("Terjadi kesalahan saat menyimpan token.");
        },
    });
};

$("#btn-password-visible").on("click", function (e) {

    e.preventDefault();

    if ($("#inputPassword").attr('type') == "password") {
        $(this).html(`<i class="bi bi-eye-slash" style="color: #c71306;"></i>`);
        $("#inputPassword").attr('type', 'text');
    } else {
        $(this).html(`<i class="bi bi-eye"></i>`);
        $("#inputPassword").attr('type', 'password');
    }

});
