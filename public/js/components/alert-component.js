const alertFailed = (message) => {
    const alertHTML = `<div class="alert alert-danger" role="alert">
                        ${message}
                        </div>`;
    $("#alert-container").html(alertHTML);
    setTimeout(() => {
        $("#alert-container").html("");
    }, 5000);
}

export default {
    alertFailed
}