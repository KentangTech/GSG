@extends('components.app')

@section('title', 'Manajemen Bisnis')

@section('page-title', 'Manajemen Bisnis')

@section('content')
    <div class="container-fluid py-5">
        <!-- Header -->
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-5 gap-3">
            <div>
                <h2 class="fw-bold text-dark mb-1">Daftar Bisnis</h2>
                <p class="text-muted mb-0">Kelola proyek atau unit bisnis perusahaan.</p>
            </div>
            <a href="{{ route('bisnis.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Bisnis
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

        <!-- Overlay untuk Konfirmasi Hapus -->
        <div id="deleteOverlay" class="position-fixed w-100 h-100 bg-black bg-opacity-50 d-none"
            style="z-index: 1050; top: 0; left: 0;"></div>

        <!-- Modal-Style Toast di Tengah Layar -->
        <div id="deleteToast" class="position-fixed top-50 start-50 translate-middle d-none"
            style="z-index: 1100; width: 90%; max-width: 480px;">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-danger text-white d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <h5 class="mb-0">Konfirmasi Hapus</h5>
                </div>
                <div class="card-body text-center p-5">
                    <i class="fas fa-trash-alt text-danger" style="font-size: 4rem; opacity: 0.3;"></i>
                    <p class="mt-4 fs-5">Yakin ingin menghapus <strong id="delete-name" class="text-danger">bisnis</strong>?
                    </p>
                    <p class="text-muted">Data yang dihapus tidak bisa dikembalikan.</p>
                </div>
                <div class="card-footer bg-light d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-secondary px-4" id="cancel-delete">Batal</button>
                    <button type="button" class="btn btn-danger px-4" id="confirm-delete-btn">Hapus</button>
                </div>
            </div>
        </div>

        <!-- Grid Bisnis -->
        <div class="row g-4">
            @forelse($bisnis as $b)
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm hover-lift transition-all duration-300 rounded-4 overflow-hidden"
                        style="box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);">
                        <!-- Gambar -->
                        <div class="position-relative">
                            <img src="{{ $b->gambar_url }}" class="card-img-top" alt="{{ $b->judul }}"
                                style="height: 200px; object-fit: cover; width: 100%;">
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-success bg-opacity-90 px-3 py-2 shadow-sm">
                                    {{ Str::limit($b->tag, 12) }}
                                </span>
                            </div>
                        </div>

                        <!-- Body -->
                        <div class="card-body p-4">
                            <h5 class="card-title mb-1 text-dark fw-semibold">{{ $b->judul }}</h5>
                            <p class="text-muted mb-3 small">
                                {{ Str::limit($b->deskripsi, 100) }}
                            </p>
                            <div class="d-flex gap-2">
                                <a href="{{ route('bisnis.edit', $b) }}"
                                    class="btn btn-sm btn-outline-primary px-3 d-flex align-items-center gap-1">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button type="button"
                                    class="btn btn-sm btn-outline-danger px-3 d-flex align-items-center gap-1 w-100 justify-content-center"
                                    onclick="confirmDelete(
                                    '{{ $b->id }}',
                                    '{{ addslashes(htmlspecialchars($b->judul, ENT_QUOTES)) }}',
                                    '{{ route('bisnis.destroy', $b) }}'
                                )">
                                    <i class="fas fa-trash-alt"></i> Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="fas fa-briefcase text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                    <h5 class="text-muted mt-3">Belum ada data bisnis</h5>
                    <p class="text-muted">Silakan tambahkan bisnis pertama Anda.</p>
                    <a href="{{ route('bisnis.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Bisnis
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-5">
            {{ $bisnis->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <!-- JavaScript untuk Toast -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toast Success
            const successToastEl = document.getElementById('successToast');
            if (successToastEl) {
                const successToast = new bootstrap.Toast(successToastEl);
                successToast.show();
                successToastEl.addEventListener('hidden.bs.toast', function() {
                    this.remove();
                });
            }

            // Variabel global
            let deleteForm = null;
            const deleteToast = document.getElementById('deleteToast');
            const deleteOverlay = document.getElementById('deleteOverlay');
            const deleteNameEl = document.getElementById('delete-name');
            const confirmBtn = document.getElementById('confirm-delete-btn');

            // Fungsi konfirmasi hapus (di-attach ke window agar bisa diakses dari onclick)
            window.confirmDelete = function(id, name, action) {
                console.log('confirmDelete called', {
                    id,
                    name,
                    action
                }); // Debug

                deleteNameEl.textContent = name;

                // Buat form sementara
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = action;

                // CSRF Token
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';

                // Method DELETE
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';

                form.appendChild(csrfInput);
                form.appendChild(methodInput);

                deleteForm = form;

                // Tampilkan overlay dan toast
                deleteOverlay.classList.remove('d-none');
                deleteToast.classList.remove('d-none');
            };

            // Tombol "Batal"
            document.getElementById('cancel-delete').addEventListener('click', function() {
                deleteOverlay.classList.add('d-none');
                deleteToast.classList.add('d-none');
            });

            // Tombol "Hapus"
            confirmBtn.addEventListener('click', function() {
                if (deleteForm) {
                    console.log('Submitting form:', deleteForm); // Debug
                    try {
                        document.body.appendChild(deleteForm);
                        deleteForm.submit(); // Submit langsung
                    } catch (error) {
                        console.error('Error submitting form:', error);
                        alert('Terjadi kesalahan saat menghapus data. Cek console.');
                    }
                } else {
                    console.warn('No form to submit');
                }
            });
        });
    </script>

@endsection
