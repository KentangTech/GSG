@extends('layout.master')

@section('title', 'Tambah Berita')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-white border-bottom-0 text-center">
                        <h4 class="mb-0 fw-semibold">📝 Tambah Berita Baru</h4>
                        <p class="text-muted mb-0">Silakan isi formulir untuk membuat berita baru.</p>
                    </div>

                    <div class="card-body p-4">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <form id="news-form" action="{{ route('news.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <!-- Judul -->
                            <div class="mb-3">
                                <label for="title" class="form-label fw-semibold">Judul Berita</label>
                                <input type="text" name="title" id="title"
                                    class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}"
                                    placeholder="Masukkan judul berita..." required>
                                @error('title')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Kategori -->
                            <div class="mb-3">
                                <label for="category" class="form-label fw-semibold">Kategori</label>
                                <input type="text" name="category" id="category"
                                    class="form-control @error('category') is-invalid @enderror"
                                    value="{{ old('category') }}" placeholder="Masukkan kategori (misal: Politik, Olahraga)"
                                    required>
                                @error('category')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Isi Berita -->
                            <div class="mb-3">
                                <label for="content" class="form-label fw-semibold">Isi Berita</label>
                                <textarea name="content" id="content" rows="8" class="form-control @error('content') is-invalid @enderror"
                                    placeholder="Tulis isi berita di sini..." required>{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Upload Gambar -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Upload Gambar</label>
                                <input type="file" name="image" id="upload-image" accept="image/*"
                                    class="form-control @error('image') is-invalid @enderror">
                                @error('image')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tombol Aksi -->
                            <div class="d-flex justify-content-end gap-3 mt-4">
                                <a href="{{ route('news.index') }}" class="btn btn-outline-secondary px-4">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-save me-1"></i> Simpan Berita
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cropper -->
    <div class="modal fade" id="cropperModal" tabindex="-1" aria-labelledby="cropperModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cropperModalLabel">Crop Gambar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="cropper-image" src="" class="img-fluid" style="max-height: 600px;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="crop-save" class="btn btn-success">Simpan Crop</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Cropper.js -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css " rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js "></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let cropper;

        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('upload-image');
            const image = document.getElementById('cropper-image');
            const modal = new bootstrap.Modal(document.getElementById('cropperModal'));

            document.getElementById('crop-save').addEventListener('click', function() {
                if (cropper) {
                    const base64Image = cropper.getCroppedCanvas().toDataURL();

                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'image_base64';
                    hiddenInput.value = base64Image;
                    document.getElementById('news-form').appendChild(hiddenInput);

                    // Submit form
                    document.getElementById('news-form').submit();
                }
            });

            if (input) {
                input.addEventListener('change', function() {
                    const file = this.files[0];

                    if (file) {
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            image.src = e.target.result;

                            // Tampilkan modal
                            modal.show();

                            // Inisialisasi Cropper setelah modal muncul
                            document.getElementById('cropperModal').addEventListener('shown.bs.modal',
                                function() {
                                    if (cropper) {
                                        cropper.destroy();
                                    }

                                    cropper = new Cropper(image, {
                                        aspectRatio: 16 / 9,
                                        viewMode: 2,
                                        dragMode: 'move',
                                        zoomOnWheel: true,
                                        zoomable: true,
                                        scalable: true,
                                        responsive: true,
                                        autoCropArea: 1,
                                        cropBoxResizable: true,
                                        cropBoxMovable: true,
                                        toggleDragModeOnDblclick: false,
                                        ready: function() {
                                            cropper.zoomTo(1);
                                        }
                                    });
                                });
                        };

                        reader.readAsDataURL(file);
                    }
                });
            }

            // Hapus cropper saat modal ditutup
            document.getElementById('cropperModal').addEventListener('hidden.bs.modal', function() {
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
            });
        });
    </script>
@endpush

@push('style')
    <style>
        .card {
            border-radius: 1rem;
        }

        .card-header {
            border-radius: 1rem 1rem 0 0;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #4e73df;
        }

        .btn-primary {
            background-color: #4e73df;
            border-color: #4e73df;
        }

        .btn-primary:hover {
            background-color: #3a5fcd;
            border-color: #3a5fcd;
        }

        .invalid-feedback {
            font-size: 0.875em;
        }

        .cropper-crop-box {
            max-height: 500px;
        }
    </style>
@endpush
