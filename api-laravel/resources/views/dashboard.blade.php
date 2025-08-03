@extends('components.app')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('content')
    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4 mb-4">
        @include('components.card', [
            'title' => 'Total Users',
            'value' => '1,234',
            'icon' => 'fas fa-users',
            'bg' => 'primary',
            'color' => 'primary',
            'trend' => 'up',
            'trendValue' => '12.5',
        ])

        @include('components.card', [
            'title' => 'Revenue',
            'value' => '$24,500',
            'icon' => 'fas fa-dollar-sign',
            'bg' => 'success',
            'color' => 'success',
            'trend' => 'up',
            'trendValue' => '8.3',
        ])

        @include('components.card', [
            'title' => 'Orders',
            'value' => '380',
            'icon' => 'fas fa-shopping-cart',
            'bg' => 'warning',
            'color' => 'warning',
            'trend' => 'down',
            'trendValue' => '3.1',
        ])

        @include('components.card', [
            'title' => 'Support Tickets',
            'value' => '42',
            'icon' => 'fas fa-ticket-alt',
            'bg' => 'danger',
            'color' => 'danger',
            'trend' => 'up',
            'trendValue' => '15.7',
        ])
    </div>

    <!-- Recent Activity -->
    <div class="card card-activity border-0">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0 text-dark fw-semibold">Recent Activity</h5>
                <a href="#" class="btn btn-outline-primary btn-sm px-3">View All</a>
            </div>

            <p class="text-muted mb-4">Analisis data dan aktivitas terbaru akan muncul di sini.</p>

            <div class="empty-chart p-5 rounded text-center">
                <i class="fas fa-chart-area fa-2x mb-3"></i>
                <h6 class="text-secondary fw-normal">📊 Tidak ada data saat ini</h6>
                <p class="text-muted small mb-0">Grafik akan muncul setelah integrasi data</p>
            </div>
        </div>
    </div>
@endsection
