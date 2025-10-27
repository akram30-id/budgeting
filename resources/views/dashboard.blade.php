@extends('layouts.app')

@section('title', 'Dashboard - Finance Hub')

@section('content')
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
@endsection

@section('scripts')
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
@endsection
