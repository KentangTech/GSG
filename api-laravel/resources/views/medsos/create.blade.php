@extends('components.app')

@php
    $isEdit = isset($medsos);
    $pageTitle = $isEdit ? 'Edit Media Sosial' : 'Tambah Media Sosial';
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
                        <h2 class="fw-bold text-dark">{{ $pageTitle }}</h2>
                        <p class="text-muted">Kelola akun media sosial.</p>
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

                    <form action="{{ $isEdit ? route('medsos.update', $medsos->id) : route('medsos.store') }}"
                          method="POST" enctype="multipart/form-data">
                        @csrf
                        @if ($isEdit) @method('PUT') @endif

                        <div class="mb-4">
                            <label for="name" class="form-label fw-medium">Nama Perusahaan</label>
                            <input type="text" name="name" id="name" class="form-control form-control-lg" value="{{ old('name', $isEdit ? $medsos->name : '') }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="username" class="form-label fw-medium">Username</label>
                            <input type="text" name="username" id="username" class="form-control form-control-lg" value="{{ old('username', $isEdit ? $medsos->username : '') }}" required>
                        </div>

                        <!-- Upload Gambar dengan Crop -->
                        <div class="mb-4">
                            <label for="image" class="form-label fw-medium">Unggah Gambar (Opsional)</label>

                            <!-- Hidden File Input -->
                            <input type="file" name="image" id="image" class="d-none" accept="image/*">

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

                            @error('image')
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
                                             $isEdit && $medsos->image
                                                 ? asset('storage/' . $medsos->image)
                                                 : 'https://ui-avatars.com/api/?name=' .
                                                   urlencode(old('name', $isEdit ? $medsos->name : 'Medsos')) .
                                                   '&background=4361ee&color=fff&size=150'
                                         }}"
                                         alt="Pratinjau Gambar">
                                </div>
                            </div>
                        </div>

                        <!-- Platform Media Sosial -->
                        <div class="mb-4">
                            <label class="form-label fw-medium">Platform Media Sosial</label>
                            <div id="platforms-container">
                                @if ($isEdit && $medsos->platforms->count())
                                    @foreach($medsos->platforms as $p)
                                    <div class="row g-2 mb-2 platform-item">
                                        <div class="col-5">
                                            <select name="platforms[{{ $loop->index }}][name]" class="form-control platform-select" required>
                                                <option value="">-- Pilih Platform --</option>
                                                <option value="Instagram" {{ $p->name == 'Instagram' ? 'selected' : '' }}>Instagram</option>
                                                <option value="Facebook" {{ $p->name == 'Facebook' ? 'selected' : '' }}>Facebook</option>
                                                <option value="Twitter" {{ $p->name == 'Twitter' ? 'selected' : '' }}>Twitter / X</option>
                                                <option value="LinkedIn" {{ $p->name == 'LinkedIn' ? 'selected' : '' }}>LinkedIn</option>
                                                <option value="TikTok" {{ $p->name == 'TikTok' ? 'selected' : '' }}>TikTok</option>
                                                <option value="YouTube" {{ $p->name == 'YouTube' ? 'selected' : '' }}>YouTube</option>
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <input type="url" name="platforms[{{ $loop->index }}][url]" class="form-control" value="{{ $p->url }}" placeholder="https://..." required>
                                        </div>
                                        <div class="col-1">
                                            <button type="button" class="btn btn-danger btn-sm remove-platform">X</button>
                                        </div>
                                        <div class="col-12 mt-1">
                                            <span class="platform-icon-preview"></span>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="row g-2 mb-2 platform-item">
                                        <div class="col-5">
                                            <select name="platforms[0][name]" class="form-control platform-select" required>
                                                <option value="" selected>-- Pilih Platform --</option>
                                                <option value="Instagram">Instagram</option>
                                                <option value="Facebook">Facebook</option>
                                                <option value="Twitter">Twitter / X</option>
                                                <option value="LinkedIn">LinkedIn</option>
                                                <option value="TikTok">TikTok</option>
                                                <option value="YouTube">YouTube</option>
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <input type="url" name="platforms[0][url]" class="form-control" placeholder="https://..." required>
                                        </div>
                                        <div class="col-1">
                                            <button type="button" class="btn btn-danger btn-sm remove-platform">X</button>
                                        </div>
                                        <div class="col-12 mt-1">
                                            <span class="platform-icon-preview"></span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="add-platform">+ Tambah Platform</button>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="d-grid gap-3 d-sm-flex justify-content-center mt-5">
                            <button type="submit" class="btn btn-success px-4 py-2 fs-5 d-flex align-items-center gap-2">
                                <i class="fas fa-save"></i> {{ $buttonText }}
                            </button>
                            <a href="{{ route('medsos.index') }}" class="btn btn-secondary px-4 py-2 d-flex align-items-center gap-2">
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

<!-- JavaScript Utama -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('image');
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
                const file = new File([blob], "medsos_" + Date.now() + ".jpg", {
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

        // Tambah platform
        let platformIndex = {{ $isEdit ? $medsos->platforms->count() : 1 }};
        document.getElementById('add-platform').addEventListener('click', function () {
            const container = document.getElementById('platforms-container');
            const div = document.createElement('div');
            div.className = 'row g-2 mb-2 platform-item';
            div.innerHTML = `
                <div class="col-5">
                    <select name="platforms[${platformIndex}][name]" class="form-control platform-select" required>
                        <option value="" selected>-- Pilih Platform --</option>
                        <option value="Instagram">Instagram</option>
                        <option value="Facebook">Facebook</option>
                        <option value="Twitter">Twitter / X</option>
                        <option value="LinkedIn">LinkedIn</option>
                        <option value="TikTok">TikTok</option>
                        <option value="YouTube">YouTube</option>
                    </select>
                </div>
                <div class="col-6">
                    <input type="url" name="platforms[${platformIndex}][url]" class="form-control" placeholder="https://..." required>
                </div>
                <div class="col-1">
                    <button type="button" class="btn btn-danger btn-sm remove-platform">X</button>
                </div>
                <div class="col-12 mt-1">
                    <span class="platform-icon-preview"></span>
                </div>
            `;
            container.appendChild(div);
            platformIndex++;
            // Rebind event
            bindPlatformEvents();
        });

        // Hapus platform
        function removePlatform() {
            this.closest('.platform-item').remove();
        }

        // Icon mapping
        const iconMap = {
            'Instagram': '<i class="fab fa-instagram text-danger"></i>',
            'Facebook': '<i class="fab fa-facebook text-primary"></i>',
            'Twitter': '<i class="fab fa-twitter text-info"></i>',
            'LinkedIn': '<i class="fab fa-linkedin text-primary"></i>',
            'TikTok': '<i class="fab fa-tiktok text-black"></i>',
            'YouTube': '<i class="fab fa-youtube text-danger"></i>'
        };

        // Tampilkan icon
        function showIcon() {
            const select = this;
            const iconPreview = select.closest('.platform-item').querySelector('.platform-icon-preview');
            const platform = select.value;
            iconPreview.innerHTML = platform ? `${iconMap[platform] || ''} <small class="text-muted">${platform}</small>` : '';
        }

        // Bind event ke semua select
        function bindPlatformEvents() {
            document.querySelectorAll('.platform-select').forEach(select => {
                select.removeEventListener('change', showIcon);
                select.addEventListener('change', showIcon);
            });
            document.querySelectorAll('.remove-platform').forEach(btn => {
                btn.removeEventListener('click', removePlatform);
                btn.addEventListener('click', removePlatform);
            });
        }

        // Inisialisasi event
        bindPlatformEvents();
    });
</script>

@endsection
