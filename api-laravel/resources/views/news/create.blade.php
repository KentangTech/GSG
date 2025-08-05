@extends('components.app')

@php
    $isEdit = isset($news) && $news instanceof \App\Models\News;
    $pageTitle = $isEdit ? 'Edit Berita' : 'Tambah Berita';
    $buttonText = $isEdit ? 'Update' : 'Simpan';
@endphp

@section('title', $pageTitle)
@section('page-title', $pageTitle)

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-5">
                        <h2 class="fw-bold text-dark mb-1">{{ $pageTitle }}</h2>
                        <p class="text-muted mb-0">Isi data berita dan unggah gambar jika diperlukan.</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show rounded-4" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Terjadi kesalahan:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ $isEdit ? route('news.update', $news->id) : route('news.store') }}"
                          method="POST" enctype="multipart/form-data">
                        @csrf
                        @if ($isEdit) @method('PUT') @endif

                        <div class="mb-4">
                            <label for="judul" class="form-label fw-medium">Judul Berita</label>
                            <input type="text" name="judul" class="form-control form-control-lg" value="{{ old('judul', $isEdit ? $news->judul : '') }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="deskripsi" class="form-label fw-medium">Deskripsi</label>
                            <textarea name="deskripsi" rows="6" class="form-control" required>{{ old('deskripsi', $isEdit ? $news->deskripsi : '') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="gambar" class="form-label fw-medium">Unggah Gambar (Opsional)</label>
                            <input type="file" name="gambar" id="gambar" class="d-none">
                            <div id="dropzone" class="border border-dashed border-3 rounded-4 text-center py-5 px-4" style="cursor: pointer;">
                                <i class="fas fa-cloud-upload-alt text-primary" style="font-size: 3rem;"></i>
                                <p class="mt-3 mb-0 text-muted">Seret & lepas atau <strong>klik untuk pilih</strong></p>
                            </div>
                            @error('gambar') <div class="invalid-feedback d-block mt-2">{{ $message }}</div> @enderror

                            <div class="mt-4 text-center">
                                <label class="d-block fw-medium">Pratinjau Gambar</label>
                                <div style="width: 150px; height: 150px; display: inline-block;">
                                    <img id="image-preview" class="w-100 h-100 rounded-3 border" style="object-fit: cover;"
                                         src="{{ $isEdit && $news->gambar ? asset('storage/' . $news->gambar) : 'https://ui-avatars.com/api/?name=News&background=4361ee&color=fff&size=150' }}">
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-3 d-sm-flex justify-content-center mt-5">
                            <button type="submit" class="btn btn-success px-4 py-2 fs-5 d-flex align-items-center gap-2">
                                <i class="fas fa-save"></i> {{ $buttonText }}
                            </button>
                            <a href="{{ route('news.index') }}" class="btn btn-secondary px-4 py-2 d-flex align-items-center gap-2">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cropper.js -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
<div class="modal fade" id="cropModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4">
            <div class="modal-header">
                <h5>Crop Gambar (250×250)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="image-crop" style="max-width: 100%;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="cropAndSave">Crop & Simpan</button>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('gambar');
        const preview = document.getElementById('image-preview');
        const modal = new bootstrap.Modal(document.getElementById('cropModal'));
        let cropper;

        dropzone.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) openCropModal(file);
        });

        function dragOver(e) { e.preventDefault(); dropzone.classList.add('border-primary'); }
        function dragLeave(e) { e.preventDefault(); dropzone.classList.remove('border-primary'); }

        dropzone.addEventListener('dragover', dragOver);
        dropzone.addEventListener('dragleave', dragLeave);
        dropzone.addEventListener('drop', function (e) {
            e.preventDefault();
            dragLeave(e);
            const file = e.dataTransfer.files[0];
            if (file && file.type.match('image.*')) {
                const dt = new DataTransfer();
                dt.items.add(file);
                fileInput.files = dt.files;
                fileInput.dispatchEvent(new Event('change'));
            }
        });

        function openCropModal(file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const img = document.getElementById('image-crop');
                img.src = e.target.result;
                modal.show();
                modal._element.addEventListener('shown.bs.modal', function () {
                    if (cropper) cropper.destroy();
                    cropper = new Cropper(img, {
                        aspectRatio: 1,
                        viewMode: 1,
                        minCropBoxWidth: 250,
                        minCropBoxHeight: 250,
                        autoCrop: true
                    });
                }, { once: true });
            };
            reader.readAsDataURL(file);
        }

        document.getElementById('cropAndSave').addEventListener('click', function () {
            if (!cropper) return;
            cropper.getCroppedCanvas({ width: 250, height: 250 }).toBlob(function (blob) {
                const file = new File([blob], "news_" + Date.now() + ".jpg", { type: "image/jpeg" });
                const dt = new DataTransfer();
                dt.items.add(file);
                fileInput.files = dt.files;
                const reader = new FileReader();
                reader.onload = e => preview.src = e.target.result;
                reader.readAsDataURL(file);
                modal.hide();
                cropper.destroy();
            }, 'image/jpeg', 0.9);
        });
    });
</script>
@endsection
