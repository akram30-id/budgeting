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


const alertConfirmation = (question, message, btnText, act) => {
    Swal.fire({
        title: question,
        text: message,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: btnText
    }).then((result) => {
        if (result.isConfirmed) {
            act();
        }
    });
}

export default {
    alertFailed,
    alertSuccess,
    alertConfirmation
}
