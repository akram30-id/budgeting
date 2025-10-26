{{-- resources/views/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance Hub Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8fbfc;
            font-family: 'Inter', sans-serif;
        }

        .sidebar {
            background-color: #f8fbfc;
            border-right: 1px solid #e7f0f3;
            min-height: 100vh;
        }

        .sidebar .nav-link {
            color: #0d181b;
            font-weight: 500;
        }

        .sidebar .nav-link.active {
            background-color: #e7f0f3;
            border-radius: 8px;
        }

        .card-custom {
            background-color: #e7f0f3;
            border: none;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            {{-- Sidebar (Desktop) --}}
            <nav class="col-md-3 col-lg-2 d-none d-md-block sidebar p-3">
                <h5 class="mb-4">Finance Hub</h5>
                <ul class="nav flex-column gap-2">
                    <li class="nav-item">
                        <a href="#" class="nav-link active d-flex align-items-center gap-2">
                            <i class="bi bi-house-fill"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link d-flex align-items-center gap-2">
                            <i class="bi bi-folder"></i> Projects
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link d-flex align-items-center gap-2">
                            <i class="bi bi-bank"></i> Treasuries
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link d-flex align-items-center gap-2">
                            <i class="bi bi-cash-coin"></i> Debts
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link d-flex align-items-center gap-2">
                            <i class="bi bi-gear"></i> Settings
                        </a>
                    </li>
                </ul>
            </nav>

            {{-- Top Navbar (Mobile) --}}
            <nav class="navbar navbar-light bg-light d-md-none">
                <div class="container-fluid">
                    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
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
                                <i class="bi bi-folder"></i> Projects
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link d-flex align-items-center gap-2">
                                <i class="bi bi-bank"></i> Treasuries
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link d-flex align-items-center gap-2">
                                <i class="bi bi-cash-coin"></i> Debts
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link d-flex align-items-center gap-2">
                                <i class="bi bi-gear"></i> Settings
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Main Content --}}
            <main class="col-md-9 col-lg-10 p-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold">Dashboard</h2>
                </div>

                {{-- Stats Cards --}}
                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <div class="card card-custom p-3">
                            <p class="fw-medium mb-1">Total Projects</p>
                            <h4 class="fw-bold">25</h4>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card card-custom p-3">
                            <p class="fw-medium mb-1">Active Debts</p>
                            <h4 class="fw-bold">$150,000</h4>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card card-custom p-3">
                            <p class="fw-medium mb-1">Total Income</p>
                            <h4 class="fw-bold">$500,000</h4>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card card-custom p-3">
                            <p class="fw-medium mb-1">Total Expenses</p>
                            <h4 class="fw-bold">$350,000</h4>
                        </div>
                    </div>
                </div>

                {{-- Chart Section --}}
                <div class="mt-5">
                    <h4 class="fw-bold mb-3">Monthly Income vs. Expenses</h4>
                    <div class="card card-custom p-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between">
                            <div>
                                <p class="fw-medium mb-1">Income vs. Expenses</p>
                                <h3 class="fw-bold">$150,000</h3>
                                <p class="text-success fw-medium mb-0">+12% <span class="text-muted">Last 12 Months</span></p>
                            </div>
                            <canvas id="incomeChart" style="max-height: 180px;"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Projects Table --}}
                <div class="mt-5">
                    <h4 class="fw-bold mb-3">Top 5 Ongoing Projects</h4>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Project Name</th>
                                    <th>Status</th>
                                    <th>Budget</th>
                                    <th>Progress</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Project Alpha</td>
                                    <td><span class="badge bg-info text-dark">Ongoing</span></td>
                                    <td class="text-primary">$100,000</td>
                                    <td>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-success" style="width: 75%;"></div>
                                        </div>
                                        <small class="fw-medium">75%</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Project Beta</td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                    <td class="text-primary">$75,000</td>
                                    <td>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-success" style="width: 100%;"></div>
                                        </div>
                                        <small class="fw-medium">100%</small>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('incomeChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                    label: 'Income',
                    data: [10, 25, 40, 30, 50, 60, 80],
                    borderColor: '#4c869a',
                    backgroundColor: 'rgba(76,134,154,0.1)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        display: false
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>