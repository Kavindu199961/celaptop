@extends('layouts.app')

@section('content')

   <div class="row mb-3">
    <!-- Total Repairs -->
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
        <a href="{{ route('user.laptop-repair.index') }}" style="text-decoration: none; color: inherit;">
            <div class="card p-3">
                <div class="row align-items-center">
                    <div class="col-6">
                        <h5 class="font-15">Total Repairs</h5>
                        <h2 class="mb-2 font-18">{{ $totalRepairs ?? 0 }}</h2>
                        <p class="mb-0"><span class="col-green">{{ rand(10, 25) }}%</span> Increase</p>
                    </div>
                    <div class="col-6 text-end">
                        <img src="/assets/img/banner/1.png" alt="Total Repairs">
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Completed Repairs -->
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
        <a href="{{ route('user.complete-repair.index') }}" style="text-decoration: none; color: inherit;">
            <div class="card p-3">
                <div class="row align-items-center">
                    <div class="col-6">
                        <h5 class="font-15">Completed Repairs</h5>
                        <h2 class="mb-2 font-18">{{ $completedRepairs ?? 0 }}</h2>
                        <p class="mb-0"><span class="col-orange">{{ rand(5, 15) }}%</span> Growth</p>
                    </div>
                    <div class="col-6 text-end">
                        <img src="/assets/img/banner/2.png" alt="Completed Repairs">
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Stock Items -->
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
        <a href="{{ route('user.stock.index') }}" style="text-decoration: none; color: inherit;">
            <div class="card p-3">
                <div class="row align-items-center">
                    <div class="col-6">
                        <h5 class="font-15">Stock Items</h5>
                        <h2 class="mb-2 font-18">{{ $totalStockItems ?? 0 }}</h2>
                        <p class="mb-0"><span class="col-green">{{ rand(15, 30) }}%</span> Increase</p>
                    </div>
                    <div class="col-6 text-end">
                        <img src="/assets/img/banner/3.png" alt="Stock Items">
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Total Shops -->
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
        <a href="{{ route('user.shop.index') }}" style="text-decoration: none; color: inherit;">
            <div class="card p-3">
                <div class="row align-items-center">
                    <div class="col-6">
                        <h5 class="font-15">Total Shops</h5>
                        <h2 class="mb-2 font-18">{{ $totalShops ?? 0 }}</h2>
                        <p class="mb-0"><span class="col-green">{{ rand(25, 45) }}%</span> Increase</p>
                    </div>
                    <div class="col-6 text-end">
                        <img src="/assets/img/banner/4.png" alt="Total Shops">
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

    <!-- Overview and Chart -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Repair Dashboard Overview</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Chart -->
                        <div class="col-lg-9">
                            <div id="repairChart"></div>
                            <div class="row text-center mt-3">
                                <div class="col-md-4">
                                    <i data-feather="settings" class="col-green"></i>
                                    <h5 class="mb-0">{{ $totalRepairs }}</h5>
                                    <p class="text-muted font-14">Total Repairs</p>
                                </div>
                                <div class="col-md-4">
                                    <i data-feather="check-circle" class="col-orange"></i>
                                    <h5 class="mb-0">{{ $completedRepairs }}</h5>
                                    <p class="text-muted font-14">Completed Repairs</p>
                                </div>
                                <div class="col-md-4">
                                    <i data-feather="box" class="col-blue"></i>
                                    <h5 class="mb-0">{{ $totalStockItems }}</h5>
                                    <p class="text-muted font-14">Stock Items</p>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Stats -->
                        <div class="col-lg-3">
                            <h6 class="mb-3">Quick Stats</h6>
                            <div class="d-flex align-items-center mb-3">
                                <i data-feather="trending-up" class="text-success me-2"></i>
                                <div>
                                    <h6 class="mb-0">{{ number_format($weeklyRepairs) }}</h6>
                                    <small class="text-muted">This Week</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <i data-feather="activity" class="text-info me-2"></i>
                                <div>
                                    <h6 class="mb-0">{{ number_format($monthlyRepairs) }}</h6>
                                    <small class="text-muted">This Month</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



<!-- Chart Script -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var options = {
            chart: {
                type: 'line',
                height: 350
            },
            series: [{
                name: 'Repairs',
                data: [30, 40, 35, 50, 49, 60, 70, 91, 125]
            }],
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep']
            }
        };

        var chart = new ApexCharts(document.querySelector("#repairChart"), options);
        chart.render();
    });
</script>
@endsection
