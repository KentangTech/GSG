@extends('components.app')

@section('title', 'Manajemen Berita')

@section('page-title', 'Manajemen Berita')

@section('content')
    <div class="container-fluid py-5">
        <!-- Header -->
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-5 gap-3">
            <div>
                <h2 class="fw-bold text-dark mb-1">Daftar Berita</h2>
                <p class="text-muted mb-0">Kelola berita perusahaan.</p>
            </div>
            <a href="{{ route('news.create') }}" class="btn btn-primary px-4 py-2 d-flex align-items-center gap-2 shadow-sm">
                <i class="fas fa-plus-circle"></i> Tambah Berita
            </a>
        </div>

        <!-- Toast untuk Success -->
        @if (session('success'))
            <div class="position-fixed top-0 end-0 p-3" style="z-index: 1100; margin-top: 60px;">
                <div id="successToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true"
                    data-bs-delay="5000">
                    <div class="toast-header bg-success text-white rounded-top">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong class="me-auto">Berhasil</strong>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"
                            aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        {{ session('success') }}
                    </div>
                </div>
            </div>
        @endif

        <!-- Overlay & Toast Konfirmasi Hapus -->
        <div id="deleteOverlay" class="position-fixed w-100 h-100 bg-black bg-opacity-50 d-none" style="z-index: 1050;">
        </div>
        <div id="deleteToast" class="position-fixed top-50 start-50 translate-middle d-none"
            style="z-index: 1100; width: 90%; max-width: 480px;">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Konfirmasi Hapus</h5>
                </div>
                <div class="card-body text-center p-5">
                    <i class="fas fa-trash-alt text-danger" style="font-size: 4rem; opacity: 0.3;"></i>
                    <p class="mt-4 fs-5">Yakin ingin menghapus berita <strong id="delete-name"
                            class="text-danger">ini</strong>?</p>
                    <p class="text-muted">Data tidak bisa dikembalikan.</p>
                </div>
                <div class="card-footer bg-light d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-secondary px-4" id="cancel-delete">Batal</button>
                    <button type="button" class="btn btn-danger px-4" id="confirm-delete-btn">Hapus</button>
                </div>
            </div>
        </div>

        <!-- Grid Berita -->
        <div class="row g-4">
            @forelse($news as $n)
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm hover-lift transition-all rounded-4 overflow-hidden">
                        <div class="position-relative">
                            <img src="{{ $n->gambar_url }}" class="card-img-top" alt="{{ $n->judul }}"
                                style="height: 200px; object-fit: cover;">
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-primary bg-opacity-90 px-3 py-2 shadow-sm">
                                    {{ $n->update_time?->format('d M Y') }}
                                    {{ $n->update_time?->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <h5 class="card-title mb-1 text-dark fw-semibold">{{ Str::limit($n->judul, 40) }}</h5>
                            <p class="text-muted small mb-2">
                                <i class="fas fa-user me-1"></i> {{ $n->uploader->name ?? 'Admin' }}
                            </p>
                            <p class="text-muted mb-3 small">{{ Str::limit($n->deskripsi, 100) }}</p>
                            <div class="d-flex gap-2">
                                <a href="{{ route('news.edit', $n) }}"
                                    class="btn btn-sm btn-outline-primary px-3 d-flex align-items-center gap-1">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button type="button"
                                    class="btn btn-sm btn-outline-danger px-3 d-flex align-items-center gap-1 w-100 justify-content-center"
                                    onclick="confirmDelete('{{ $n->id }}', '{{ addslashes($n->judul) }}', '{{ route('news.destroy', $n) }}')">
                                    <i class="fas fa-trash-alt"></i> Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="fas fa-newspaper text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                    <h5 class="text-muted mt-3">Belum ada berita</h5>
                    <a href="{{ route('news.create') }}" class="btn btn-primary mt-2">Tambah Berita</a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-5">
            {{ $news->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <!-- JavaScript Toast -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const successToastEl = document.getElementById('successToast');
            if (successToastEl) {
                const toast = new bootstrap.Toast(successToastEl);
                toast.show();
                successToastEl.addEventListener('hidden.bs.toast', () => this.remove());
            }

            let deleteForm = null;
            const deleteToast = document.getElementById('deleteToast');
            const deleteOverlay = document.getElementById('deleteOverlay');
            const deleteNameEl = document.getElementById('delete-name');

            window.confirmDelete = function(id, name, action) {
                deleteNameEl.textContent = name;
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = action;

                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';

                const method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'DELETE';

                form.append(csrf, method);
                deleteForm = form;

                deleteOverlay.classList.remove('d-none');
                deleteToast.classList.remove('d-none');
            };

            document.getElementById('cancel-delete').addEventListener('click', () => {
                deleteOverlay.classList.add('d-none');
                deleteToast.classList.add('d-none');
            });

            document.getElementById('confirm-delete-btn').addEventListener('click', () => {
                if (deleteForm) {
                    document.body.appendChild(deleteForm);
                    deleteForm.submit();
                }
            });
        });
    </script>

@endsection
