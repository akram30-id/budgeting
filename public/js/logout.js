const triggerLogout = $(".signout");

triggerLogout.on("click touchstart", function (e) {
    e.preventDefault();
    submitLogout();
});

const submitLogout = () => {

    const isConfirmLogout = confirm("Apakah Anda yakin ingin logout?");

    if (!isConfirmLogout) {
        return;
    }

    window.location.href = $("#url").data("url_logout");
}
