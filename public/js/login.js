import alertComponent from "./components/alert-component.js";
import csrf_setup from "./csrf_setup.js";

const form = $("#form-sign-in");

form.on("submit", function (e) {
    e.preventDefault();

    const email = $("#inputEmail").val();
    const password = $("#inputPassword").val();

    if (!email || !password) {
        alert("Email dan password wajib diisi.");
        return;
    }

    $("#btn-sign-in").text("Loading...");

    submitLoginForm(email, password);
});

const submitLoginForm = (email, password) => {
    const url = $("#url").data("api_login");

    $.ajax({
        type: "POST",
        url: url,
        data: { email, password },
        dataType: "json",
        success: function (response) {
            if (response && response.success && response.token) {
                $("#btn-sign-in").text("Sign In");
                handleLoginSuccess(response.token);
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

const handleLoginSuccess = (token) => {
    const url = $("#url").data("save_token");

    $.ajax({
        type: "POST",
        url: url,
        headers: csrf_setup.headers,
        data: { access_token: token },
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
