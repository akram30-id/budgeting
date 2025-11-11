<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@yield('scripts')

<script type="module" src="{{ asset('js/logout.js') }}"></script>
<script src="{{ asset('js/components/sidebar.js') }}"></script>

@if (config('services.app_env') !== 'production')
    <script>
        (function() {
            const loadEruda = () => {
                if (!window.eruda) {
                    const script = document.createElement('script');
                    script.src = "https://cdn.jsdelivr.net/npm/eruda";
                    document.body.appendChild(script);
                    script.onload = function() {
                        eruda.init();

                        // Posisikan di kanan bawah
                        setTimeout(() => {
                            const erudaContainer = document.querySelector('.eruda');
                            if (erudaContainer) {
                                erudaContainer.style.left = 'auto';
                                erudaContainer.style.right = '10px';
                                erudaContainer.style.bottom = '10px';
                            }
                        }, 500);
                    };
                }
            };

            const destroyEruda = () => {
                if (window.eruda) {
                    try {
                        eruda.destroy();
                    } catch (e) {
                        console.warn('Eruda destroy error:', e);
                    }
                    // Hapus elemen dari DOM juga
                    const el = document.querySelector('.eruda');
                    if (el) el.remove();
                    delete window.eruda;
                }
            };

            const handleResize = () => {
                if (window.innerWidth < 768) {
                    loadEruda();
                } else {
                    destroyEruda();
                }
            };

            // Jalankan saat pertama kali load
            handleResize();

            // Dengarkan perubahan ukuran layar
            window.addEventListener('resize', handleResize);
        })();
    </script>
@endif
