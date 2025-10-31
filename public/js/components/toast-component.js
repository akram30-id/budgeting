const show = (id, message, colorClass = 'text-bg-primary') => {

    const toastEl = document.getElementById(`${id}`);
    if (!toastEl) return;

    const bodyEl = toastEl.querySelector('.toast-body');
    if (bodyEl) {
        bodyEl.textContent = message;
    } else {
        console.error('toast-body tidak ditemukan, pesan toast:', message);
    }

    // hapus semua kelas warna text-bg-* yang ada, lalu tambahkan yang baru
    [...toastEl.classList]
        .filter(cls => cls.startsWith('text-bg-'))
        .forEach(cls => toastEl.classList.remove(cls));
    toastEl.classList.add(colorClass);

    if (window.bootstrap && window.bootstrap.Toast) {
        const instance = window.bootstrap.Toast.getOrCreateInstance(toastEl, { delay: 5000 });
        instance.show();
    } else {
        $(toastEl).show();
        setTimeout(() => {
            $(toastEl).hide();
        }, 5000);
    }
}

export default {
    show
}
