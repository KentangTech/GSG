@extends('components.app')

@php
    // Cek apakah ini mode edit
    $isEdit = isset($bisnis) && $bisnis instanceof \App\Models\Bisnis;
    $pageTitle = $isEdit ? 'Edit Bisnis' : 'Tambah Bisnis';
    $buttonText = $isEdit ? 'Update' : 'Simpan';
@endphp

@section('title', $pageTitle)
@section('page-title', $pageTitle)

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <!-- Card -->
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-body p-4 p-md-5">

                    <!-- Header -->
                    <div class="text-center mb-5">
                        <h2 class="fw-bold text-dark mb-1">{{ $pageTitle }}</h2>
                        <p class="text-muted mb-0">Isi data lengkap dan unggah gambar jika diperlukan.</p>
                    </div>

                    <!-- Error Alert -->
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show rounded-4" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Terjadi kesalahan:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Form -->
                    <form action="{{ $isEdit ? route('bisnis.update', $bisnis->id) : route('bisnis.store') }}"
                          method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        @if ($isEdit)
                            @method('PUT')
                        @endif

                        <!-- Judul -->
                        <div class="mb-4">
                            <label for="judul" class="form-label fw-medium">Judul Bisnis</label>
                            <input type="text"
                                   name="judul"
                                   id="judul"
                                   class="form-control form-control-lg @error('judul') is-invalid @enderror"
                                   value="{{ old('judul', $isEdit ? $bisnis->judul : '') }}"
                                   placeholder="Masukkan judul bisnis"
                                   required>
                            @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tag -->
                        <div class="mb-4">
                            <label for="tag" class="form-label fw-medium">Tag / Penanggung Jawab</label>
                            <input type="text"
                                   name="tag"
                                   id="tag"
                                   class="form-control form-control-lg @error('tag') is-invalid @enderror"
                                   value="{{ old('tag', $isEdit ? $bisnis->tag : '') }}"
                                   placeholder="Contoh: Kadiv Pemasaran"
                                   required>
                            @error('tag')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <label for="deskripsi" class="form-label fw-medium">Deskripsi</label>
                            <textarea name="deskripsi"
                                      id="deskripsi"
                                      class="form-control @error('deskripsi') is-invalid @enderror"
                                      rows="5"
                                      placeholder="Jelaskan bisnis ini..."
                                      required>{{ old('deskripsi', $isEdit ? $bisnis->deskripsi : '') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Upload Gambar -->
                        <div class="mb-4">
                            <label for="gambar" class="form-label fw-medium">Unggah Gambar (Opsional)</label>

                            <!-- Hidden File Input -->
                            <input type="file"
                                   name="gambar"
                                   id="gambar"
                                   class="d-none"
                                   accept="image/*">

                            <!-- Drag & Drop Zone -->
                            <div id="dropzone"
                                 class="border border-dashed border-3 rounded-4 text-center py-5 px-4"
                                 style="cursor: pointer; transition: all 0.3s;"
                                 ondragover="dragOver(event)"
                                 ondragleave="dragLeave(event)"
                                 ondrop="dropFile(event)">
                                <i class="fas fa-cloud-upload-alt text-primary" style="font-size: 3rem;"></i>
                                <p class="mt-3 mb-0 text-muted">
                                    Seret & lepas file di sini, atau <strong>klik untuk memilih</strong>
                                </p>
                                <small class="text-muted">Maksimal 15MB, format: JPG, PNG, WEBP</small>
                            </div>

                            @error('gambar')
                                <div class="invalid-feedback d-block mt-2 text-center">{{ $message }}</div>
                            @enderror

                            <!-- Preview Gambar -->
                            <div class="mt-4 text-center">
                                <label class="d-block fw-medium">Pratinjau Gambar</label>
                                <div class="d-inline-block" style="width: 150px; height: 150px;">
                                    <img id="image-preview"
                                         class="rounded-3 border shadow-sm w-100 h-100"
                                         style="object-fit: cover;"
                                         src="{{
                                             $isEdit && $bisnis->gambar
                                                 ? asset('storage/' . $bisnis->gambar)
                                                 : 'https://ui-avatars.com/api/?name=' .
                                                   urlencode(old('judul', $isEdit ? $bisnis->judul : 'Bisnis')) .
                                                   '&background=4361ee&color=fff&size=150'
                                         }}"
                                         alt="Pratinjau Gambar">
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="d-grid gap-3 d-sm-flex justify-content-center mt-5">
                            <button type="submit"
                                    class="btn btn-success px-4 py-2 fs-5 d-flex align-items-center gap-2 shadow-sm">
                                <i class="fas fa-save"></i> {{ $buttonText }}
                            </button>
                            <a href="{{ route('bisnis.index') }}"
                               class="btn btn-secondary px-4 py-2 d-flex align-items-center gap-2">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cropper.js CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">

<!-- Modal Crop -->
<div class="modal fade" id="cropModal" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 shadow-sm">
            <div class="modal-header">
                <h5 class="modal-title" id="cropModalLabel">Crop Gambar (250×250)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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

<!-- Cropper.js JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>

<!-- JavaScript untuk Crop -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('gambar');
        const preview = document.getElementById('image-preview');
        const cropModal = document.getElementById('cropModal');
        const modal = bootstrap.Modal.getOrCreateInstance(cropModal);
        let cropper;

        // Klik dropzone → buka file picker
        dropzone.addEventListener('click', () => fileInput.click());

        // Handle klik upload
        fileInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) openCropModal(file);
        });

        // Drag over
        function dragOver(e) {
            e.preventDefault();
            dropzone.classList.add('border-primary', 'bg-light');
            dropzone.style.backgroundColor = 'rgba(67, 97, 238, 0.05)';
        }

        // Drag leave
        function dragLeave(e) {
            e.preventDefault();
            dropzone.classList.remove('border-primary', 'bg-light');
            dropzone.style.backgroundColor = '';
        }

        // Drop file
        function dropFile(e) {
            e.preventDefault();
            dragLeave(e);
            const file = e.dataTransfer.files[0];
            if (file && file.type.match('image.*')) {
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                fileInput.files = dataTransfer.files;
                fileInput.dispatchEvent(new Event('change'));
            } else {
                alert('Hanya file gambar yang diperbolehkan!');
            }
        }

        // Buka modal crop
        function openCropModal(file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const image = document.getElementById('image-crop');
                image.src = e.target.result;

                modal.show();

                cropModal.addEventListener('shown.bs.modal', function () {
                    if (cropper) cropper.destroy();
                    cropper = new Cropper(image, {
                        aspectRatio: 1,
                        viewMode: 1,
                        autoCropArea: 1,
                        responsive: true,
                        zoomable: true,
                        movable: true,
                        cropBoxResizable: true,
                        cropBoxMovable: true,
                        minCropBoxWidth: 250,
                        minCropBoxHeight: 250,
                        autoCrop: true
                    });
                }, { once: true });
            };
            reader.readAsDataURL(file);
        }

        // Crop & Simpan
        document.getElementById('cropAndSave').addEventListener('click', function () {
            if (!cropper) return;

            cropper.getCroppedCanvas({
                width: 250,
                height: 250,
                fillColor: '#fff',
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high'
            }).toBlob(function (blob) {
                const file = new File([blob], "bisnis_" + Date.now() + ".jpg", {
                    type: "image/jpeg"
                });

                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                fileInput.files = dataTransfer.files;

                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);

                modal.hide();
                cropper.destroy();
                cropper = null;
            }, 'image/jpeg', 0.9);
        });
    });
</script>

@endsection
