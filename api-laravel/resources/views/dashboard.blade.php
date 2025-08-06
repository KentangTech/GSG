@extends('components.app')

@section('title', 'Riwayat Notifikasi')
@section('page-title', 'Riwayat Notifikasi')

@section('content')
    <div class="container-fluid py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <!-- Header -->
                    <div class="card-header bg-gradient-primary text-white py-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                            <div>
                                <h4 class="mb-0 fw-bold">
                                    <i class="fas fa-bell me-2"></i> Riwayat Notifikasi
                                </h4>
                                <p class="mb-0 text-white-50 small mt-1">Kelola dan pantau semua notifikasi Anda</p>
                            </div>
                            <form action="{{ route('dashboard.markAllAsRead') }}" method="POST" class="mt-3 mt-md-0">
                                @csrf
                                <button type="submit"
                                    class="btn btn-light btn-sm px-4 rounded-pill shadow-sm hover-scale"
                                    onclick="return confirm('Tandai semua notifikasi sebagai dibaca?')">
                                    <i class="fas fa-check-double me-1"></i> Tandai Semua Dibaca
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @forelse ($notifications as $notif)
                                <li class="list-group-item px-5 py-4 border-0 hover-bg-light transition-fast">
                                    <div class="d-flex align-items-start">
                                        <!-- Icon dengan badge -->
                                        <div class="position-relative me-4 flex-shrink-0">
                                            <div class="avatar bg-{{ $notif->bg }} bg-opacity-10 text-{{ $notif->bg }} rounded-circle p-3 shadow-sm">
                                                <i class="fas {{ $notif->icon }} fa-lg"></i>
                                            </div>
                                            @if (! $notif->is_read)
                                                <span class="position-absolute top-0 start-0 translate-middle p-2 bg-success border border-light rounded-circle"
                                                    style="width: 10px; height: 10px;" title="Belum dibaca"></span>
                                            @endif
                                        </div>

                                        <!-- Konten notifikasi -->
                                        <div class="flex-grow-1">
                                            <p class="mb-1 fw-semibold {{ $notif->is_read ? 'text-muted' : 'text-gray-800' }} line-clamp-1">
                                                {{ $notif->title }}
                                            </p>
                                            <small class="text-muted">
                                                {{ $notif->created_at->diffForHumans() }} •
                                                <span class="fw-medium">{{ $notif->created_at->format('d M Y, H:i') }}</span>
                                            </small>
                                        </div>

                                        <!-- Badge tipe notifikasi -->
                                        <span class="badge bg-soft-{{ $notif->bg }} text-{{ $notif->bg }} rounded-pill px-3 py-2 text-sm">
                                            {{ ucfirst($notif->type) }}
                                        </span>
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item text-center py-5">
                                    <i class="fas fa-bell-slash fa-3x text-muted mb-3 opacity-50"></i>
                                    <h5 class="text-muted mb-1">Tidak ada notifikasi</h5>
                                    <p class="text-muted small mb-0">Anda tidak memiliki notifikasi saat ini.</p>
                                </li>
                            @endforelse
                        </ul>
                    </div>

                    <!-- Footer (Pagination) -->
                    <div class="card-footer bg-light border-0 py-4">
                        <div class="d-flex justify-content-center">
                            {{ $notifications->links('vendor.pagination.bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
