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
                handleLoginSuccess(response.token);
            } else {
                alert(response.message || "Login gagal. Silakan coba lagi.");
            }
        },
        error: function (xhr, status, error) {
            const errorResponse = JSON.parse(xhr.responseText);

            alert(errorResponse.message || "Terjadi kesalahan saat proses login.");
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
                alert("Gagal menyimpan token.");
            }
        },
        error: function () {
            alert("Terjadi kesalahan saat menyimpan token.");
        },
    });
};
