const triggerLogout = $(".signout");

triggerLogout.on("click", function (e) {
    e.preventDefault();
    submitLogout();
});

const submitLogout = () => {
    const url = $("#url").data("api_logout");
    $.ajax({
        type: "POST",
        url: url,
        headers: {
            "Authorization": "Bearer " + $("#token").data("access_token")
        },
        dataType: "json",
        success: function (response) {
            if (response && response.success) {
                window.location.href = "/login";
            } else {
                alert("Logout gagal. Silakan coba lagi.");
            }
        },
        error: function (xhr) {
            
            const message = JSON.parse(xhr.responseText).message;

            alert(message || "Terjadi kesalahan saat proses logout.");
        },
    });
}
