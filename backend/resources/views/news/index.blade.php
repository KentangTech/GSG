@extends('layout.master')

@section('title', 'CRUD News')

@section('content')
    <div class="container pt-5 pb-5">
        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
            <h2 class="fw-bold text-dark mb-0">
                <i class="fas fa-newspaper me-2 text-primary"></i> Manajemen Berita
            </h2>
            <a href="{{ route('news.create') }}" class="btn btn-primary d-inline-flex align-items-center shadow-sm">
                <i class="fas fa-plus me-2"></i> Tambah Berita
            </a>
        </div>

        <!-- Search & Filter -->
        <div class="row mb-4">
            <div class="col-md-6">
                <form action="{{ route('news.index') }}" method="GET" class="input-group">
                    <input type="text" name="search" class="form-control rounded-start" placeholder="Cari berita..." value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
            <div class="col-md-6">
                <form action="{{ route('news.index') }}" method="GET" class="d-flex gap-2">
                    <select name="category" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        @foreach (\App\Models\News::distinct()->pluck('category') as $category)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <!-- Daftar Berita -->
        <div class="row gy-4">
            @forelse ($news as $item)
                <div class="col-12">
                    <div class="card border-0 rounded-4 overflow-hidden d-flex flex-md-row shadow-sm hover-zoom">

                        <div class="position-relative" style="width: 220px; height: 140px; flex-shrink-0;">
                            @if ($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" alt="Gambar Berita"
                                    class="w-100 h-100 object-fit-cover">
                            @else
                                <div class="bg-light w-100 h-100 d-flex align-items-center justify-content-center">
                                    <i class="fas fa-image text-muted fs-4"></i>
                                </div>
                            @endif
                        </div>

                        <div class="card-body d-flex flex-column justify-content-between p-4">
                            <div>
                                <span class="badge bg-light text-dark border mb-2">{{ $item->category ?? 'Umum' }}</span>
                                <h5 class="card-title fw-bold text-dark mb-2" title="{{ $item->title }}">
                                    {{ Str::limit($item->title, 60) }}
                                </h5>
                                <p class="card-text text-muted mb-3">
                                    {{ Str::limit(strip_tags($item->content), 120) }}
                                </p>
                                <small class="text-muted">Dibuat: {{ $item->created_at->format('d M Y') }}</small>
                            </div>

                            <div class="d-flex gap-2 align-items-center">
                                <button type="button" class="btn btn-sm btn-outline-info rounded-pill px-3 py-1 d-flex align-items-center shadow-sm"
                                        data-bs-toggle="modal" data-bs-target="#previewModal{{ $item->id }}">
                                    <i class="fas fa-eye me-1"></i> Lihat
                                </button>
                                <a href="{{ route('news.edit', $item->id) }}"
                                    class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 d-flex align-items-center shadow-sm">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </a>
                                <form action="{{ route('news.destroy', $item->id) }}" method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus berita ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1 d-flex align-items-center shadow-sm">
                                        <i class="fas fa-trash-alt me-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Preview -->
                <div class="modal fade" id="previewModal{{ $item->id }}" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content rounded-4">
                            <div class="modal-header">
                                <h5 class="modal-title" id="previewModalLabel">{{ $item->title }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                @if ($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="Gambar Berita" class="img-fluid rounded mb-3">
                                @endif
                                <p>{!! nl2br(e($item->content)) !!}</p>
                                <small class="text-muted">Kategori: {{ $item->category ?? 'Umum' }} | Tanggal: {{ $item->created_at->format('d M Y') }}</small>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-light border rounded-4 text-center py-5">
                        <i class="fas fa-newspaper text-muted fs-2 mb-2"></i>
                        <p class="mb-0 text-muted">Belum ada berita tersedia.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-4 d-flex justify-content-center">
            {{ $news->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection

@push('style')
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .hover-zoom {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hover-zoom:hover {
            transform: scale(1.01);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
        }

        .card:hover {
            transform: translateY(-4px);
        }

        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .btn-sm {
            font-size: 0.85rem;
        }

        .rounded-pill {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .alert-light {
            background-color: #ffffff;
            border: 1px solid #e9ecef;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2 @11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap @5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                confirmButtonColor: '#3085d6',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            });
        @endif

        @if ($errors->any())
            let errorMessages = `{!! implode('<br>', $errors->all()) !!}`;
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                html: errorMessages,
                confirmButtonColor: '#d33',
                showClass: {
                    popup: 'animate__animated animate__shakeX'
                }
            });
        @endif
    </script>
@endpush
