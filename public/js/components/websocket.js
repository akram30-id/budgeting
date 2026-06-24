$(document).ready(function () {
    const badgeNotif = $("#total-notification"); // ID elemen badge Anda [cite: 14]

    // --- 1. FUNGSI UPDATE UI BADGE ---
    // Memisahkan logika UI agar bisa digunakan bersama oleh AJAX dan WebSocket [cite: 59]
    function updateBadgeUI(count) {
        if (count > 0) {
            // Tampilkan angka (maksimal 99+) dan munculkan badge [cite: 27]
            badgeNotif.text(count > 99 ? "99+" : count).show();
        } else {
            // Sembunyikan jika tidak ada unread (menghindari titik merah kosong) [cite: 29, 63]
            badgeNotif.hide();
        }
    }

    // --- 2. FUNGSI AMBIL DATA AWAL (HIT API) ---
    // Memastikan pengguna melihat jumlah notifikasi terbaru saat refresh halaman [cite: 32, 62]
    function updateUnreadCount() {
        const url = $("#url").data("url_go");
        const token = $("#token").data("access_token");

        $.ajax({
            type: "GET",
            url: `${url}/api/notification/current`,
            headers: {
                "Authorization": `Bearer ${token}`
            },
            dataType: "json",
            success: function (response) {
                const totalUnread = response.data.Count; // Mengambil Count dari response API [cite: 23]
                updateBadgeUI(totalUnread);
            },
            error: function (err) {
                console.error("Gagal mengambil data notifikasi awal:", err);
            }
        });
    }

    // Jalankan hit API segera setelah document ready [cite: 31, 34]
    updateUnreadCount();

    // --- 3. LOGIKA WEBSOCKET ---
    const currentUserId = $('#user').data("id");
    let socket;

    function connectWebSocket() {
        const url = $("#url").data("url_go");
        const protocol = window.location.protocol === "https:" ? "wss" : "ws";
        let cleanURL = url.replace("http://", "").replace("https://", "");

        socket = new WebSocket(`${protocol}://${cleanURL}/ws/${currentUserId}`);

        socket.onopen = function () {
            console.log("Connected to Real-time Notification Server");
        };

        socket.onmessage = function (event) {
            const data = JSON.parse(event.data);

            // Filter berdasarkan tipe event dari Backend
            switch (data.type) {
                case "NEW_INVITATION":
                    // Logika notifikasi umum yang sudah ada
                    handleIncomingNotification(data);
                    break;

                case "INVITATION_ACCEPTED":
                    console.log("Real-time: Seseorang telah menerima undangan Anda!");
                    // Menjalankan Action Items dari PM
                    handleInvitationAccepted(data);
                    break;

                default:
                    console.log("Event type tidak dikenali:", data.type);
            }
        };

        socket.onclose = function () {
            console.log("WebSocket Disconnected. Reconnecting...");
            setTimeout(connectWebSocket, 5000);
        };

        socket.onerror = function (err) {
            console.error("WebSocket Error: ", err);
            socket.close();
        };
    }

    function handleIncomingNotification(data) {
        if (data.type === "NEW_INVITATION") {
            // Tampilkan Toast Bootstrap
            const toastId = 'toast-' + Date.now();
            const toastHTML = `
                <div id="${toastId}" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header bg-primary text-white">
                        <i class="fas fa-bell me-2"></i>
                        <strong class="me-auto">${data.title}</strong>
                        <small>Baru saja</small>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body bg-white solid">
                        ${data.message}
                    </div>
                </div>
            `;

            $('#toastPlacement').prepend(toastHTML);

            const toastElement = document.getElementById(toastId);
            const bsToast = new bootstrap.Toast(toastElement, {
                autohide: true,
                delay: 5000
            });
            bsToast.show();

            toastElement.addEventListener('hidden.bs.toast', function () {
                toastElement.remove();
            });

            // UPDATE BADGE SECARA REAL-TIME [cite: 58]
            // Ambil angka saat ini dari badge, tambah 1, lalu update UI
            let currentCount = parseInt(badgeNotif.text()) || 0;
            updateBadgeUI(currentCount + 1);
        }
    }

    /**
     * Logic khusus untuk T-003
     */
    const handleInvitationAccepted = (payload) => {
        // 1. Update Badge Count (Reuse fungsi dari notification.js)
        if (typeof updateUnreadCount === "function") {
            updateUnreadCount();
        }

        // 2. Append notifikasi baru ke dropdown secara real-time
        // Payload diasumsikan berisi pesan seperti: "User X telah menerima undangan di Treasury Y"
        const notificationHtml = `
            <li class="notification-item unread">
                <a class="dropdown-item" href="#">
                    <div class="d-flex align-items-center">
                        <div class="notif-icon bg-success text-white">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="ms-3">
                            <p class="mb-0 text-sm">${payload.message}</p>
                            <small class="text-muted">Baru saja</small>
                        </div>
                    </div>
                </a>
            </li>
        `;

        // Masukkan ke bagian paling atas list dropdown
        $("#notification-list").prepend(notificationHtml);

        // 3. Opsional: Trigger Toast untuk memberi tahu user secara visual jika dropdown tertutup
        showToast("Berhasil", payload.message);
    };

    connectWebSocket();
});
