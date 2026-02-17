const alertFailed = (message) => {
    Swal.fire({
        icon: "error",
        title: "Oops...",
        text: `${message}`
    });
}

const alertSuccess = (message, redirect = null) => {
    Swal.fire({
        title: "Success!",
        text: `${message}`,
        icon: "success"
    }).then(() => {
        if (redirect) {
            window.location.href = redirect
        }
    });
}

export default {
    alertFailed,
    alertSuccess
}
