{{-- Sidebar (Desktop) --}}
<nav class="col-md-3 col-lg-2 d-none d-md-block sidebar p-3 position-relative">
    <h5 class="mb-4">Finance Hub</h5>
    <ul class="nav flex-column gap-2">
        <li class="nav-item">
            <a href="/" data-module_name="dashboard" class="nav-link d-flex align-items-center gap-2">
                <i class="bi bi-house-fill"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a href="#" data-module_name="projects" class="nav-link d-flex align-items-center gap-2">
                <i class="bi bi-folder"></i> Projects
            </a>
        </li>
        <li class="nav-item">
            <a href="/treasury" data-module_name="treasuries" class="nav-link d-flex align-items-center gap-2">
                <i class="bi bi-bank"></i> Treasuries
            </a>
        </li>
        <li class="nav-item">
            <a href="/debt" data-module_name="debts" class="nav-link d-flex align-items-center gap-2">
                <i class="bi bi-cash-coin"></i> Debts
            </a>
        </li>
        <li class="nav-item">
            <a href="/settings" data-module_name="settings" class="nav-link d-flex align-items-center gap-2">
                <i class="bi bi-gear"></i> Settings
            </a>
        </li>
        <li class="nav-item">
            <a href="#" data-module_name="signout" class="nav-link d-flex align-items-center gap-2 signout">
                <i class="bi bi-box-arrow-right"></i> Sign Out
            </a>
        </li>
    </ul>

    <img src="{{ asset('assets/img/ngupil-bubu-dudu.gif') }}" alt="Mascot" class="sidebar-gif">
</nav>

{{-- Top Navbar (Mobile) --}}
<nav class="navbar navbar-light bg-light d-md-none">
    <div class="container-fluid">
        <button class="btn btn-outline-secondary" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
            <i class="bi bi-list fs-4"></i>
        </button>
        <span class="navbar-brand mb-0 h5">Finance Hub</span>
    </div>
</nav>

{{-- Offcanvas Sidebar (Mobile) --}}
<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel">
    <div class="offcanvas-header">
        <h5 id="mobileSidebarLabel" class="fw-bold">Finance Hub</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="nav flex-column gap-2">
            <li class="nav-item">
                <a href="#" class="nav-link active d-flex align-items-center gap-2">
                    <i class="bi bi-house-fill"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link d-flex align-items-center gap-2">
                    <i class="bi bi-folder"></i>
                    Projects
                </a>
            </li>
            <li class="nav-item">
                <a href="/treasury" class="nav-link d-flex align-items-center gap-2">
                    <i class="bi bi-bank"></i> Treasuries
                </a>
            </li>
            <li class="nav-item">
                <a href="/debt" class="nav-link d-flex align-items-center gap-2">
                    <i class="bi bi-cash-coin"></i> Debts
                </a>
            </li>
            <li class="/settingsav-item">
                <a href="#" class="nav-link d-flex align-items-center gap-2">
                    <i class="bi bi-gear"></i> Settings
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link d-flex align-items-center gap-2 signout">
                    <i class="bi bi-box-arrow-right"></i> Sign Out
                </a>
            </li>
        </ul>

        <img src="{{ asset('assets/img/ngupil-bubu-dudu.gif') }}" alt="" class="sidebar-gif">
    </div>
</div>
