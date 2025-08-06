@extends('components.app')

@section('title', 'Riwayat Notifikasi')
@section('page-title', 'Riwayat Notifikasi')

@section('content')
    <div class="container-fluid py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <div class="card border-0 shadow-xl rounded-4">
                    <div class="card-header bg-gradient text-white py-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0 fw-bold"><i class="fas fa-history me-2"></i> Riwayat Notifikasi</h4>
                            <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-light rounded-pill">Tandai Semua Dibaca</button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @forelse ($notifications as $notif)
                                <li class="list-group-item px-5 py-4 border-0">
                                    <div class="d-flex align-items-start">
                                        <div class="position-relative me-4">
                                            <div class="avatar bg-{{ $notif->bg }} bg-opacity-10 text-{{ $notif->bg }} rounded-circle p-3">
                                                <i class="fas {{ $notif->icon }} fa-lg"></i>
                                            </div>
                                            @if (! $notif->is_read)
                                                <span class="position-absolute top-0 start-0 translate-middle p-1 bg-danger border border-light rounded-circle" style="width: 8px; height: 8px;"></span>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="mb-1 fw-semibold {{ $notif->is_read ? 'text-muted' : 'text-dark' }}">
                                                {{ $notif->title }}
                                            </p>
                                            <small class="text-muted">
                                                {{ $notif->created_at->format('d M Y, H:i') }}
                                            </small>
                                        </div>
                                        <span class="badge bg-{{ $notif->bg }} bg-opacity-10 text-{{ $notif->bg }} rounded-pill px-3">
                                            {{ $notif->type }}
                                        </span>
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item text-center py-5">
                                    <i class="fas fa-inbox fa-2x text-muted mb-3"></i>
                                    <p class="text-muted">Tidak ada notifikasi</p>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="card-footer bg-white border-0">
                        {{ $notifications->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
