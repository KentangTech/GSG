@extends('layout.master')

@section('title', 'Edit Berita')

@section('content')
    <div class="container py-5 mt-4 mb-5" data-aos="fade-up" data-aos-duration="800">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-white border-bottom-0 text-center">
                        <h4 class="mb-1 fw-semibold">✏️ Edit Berita</h4>
                        <p class="text-muted mb-0">Perbarui informasi berita di bawah ini.</p>
                    </div>

                    <div class="card-body p-4">
                        <form action="{{ route('news.update', $news->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Judul -->
                            <div class="mb-3">
                                <label for="title" class="form-label fw-semibold">Judul Berita</label>
                                <input type="text" name="title" id="title"
                                    class="form-control @error('title') is-invalid @enderror"
                                    value="{{ old('title', $news->title) }}" placeholder="Masukkan judul berita..."
                                    required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Isi -->
                            <div class="mb-3">
                                <label for="content" class="form-label fw-semibold">Isi Berita</label>
                                <textarea name="content" id="content" rows="6" class="form-control @error('content') is-invalid @enderror"
                                    placeholder="Tulis isi berita di sini...">{{ old('content', $news->content) }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Gambar -->
                            <div class="mb-3">
                                <label for="image" class="form-label fw-semibold">Ganti Gambar (Opsional)</label>
                                <input type="file" name="image" id="image"
                                    class="form-control @error('image') is-invalid @enderror" accept="image/*">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                @if ($news->image)
                                    <div class="mt-3">
                                        <p class="mb-1 fw-semibold">Gambar Saat Ini:</p>
                                        <img src="{{ asset('storage/' . $news->image) }}" alt="Gambar Berita"
                                            class="img-fluid rounded shadow-sm border" style="max-height: 200px;">
                                    </div>
                                @endif

                                <!-- Preview Gambar Baru -->
                                <div class="mt-3" id="preview-container" style="display: none;">
                                    <p class="mb-1 fw-semibold">Preview Gambar Baru:</p>
                                    <img id="image-preview" src="#" alt="Preview Gambar"
                                        class="img-fluid rounded shadow-sm border" style="max-height: 200px;">
                                </div>
                            </div>

                            <!-- Tombol Aksi -->
                            <div class="d-flex justify-content-end gap-3 mt-4">
                                <a href="{{ route('news.index') }}" class="btn btn-outline-secondary px-4">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-success px-4">
                                    <i class="fas fa-save me-1"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true
        });

        // Preview gambar baru
        document.getElementById('image').addEventListener('change', function(event) {
            const preview = document.getElementById('image-preview');
            const container = document.getElementById('preview-container');

            if (event.target.files && event.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    container.style.display = 'block';
                }
                reader.readAsDataURL(event.target.files[0]);
            }
        });
    </script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                confirmButtonColor: '#3085d6',
                timer: 2500,
                showConfirmButton: false
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonColor: '#d33'
            });
        @endif
    </script>
@endpush
